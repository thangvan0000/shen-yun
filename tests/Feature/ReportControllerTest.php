<?php

namespace Tests\Feature;

use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Authenticate as admin
        session(['admin_authed' => true]);
    }

    /** @test */
    public function test_report_page_loads_with_default_week_filter(): void
    {
        $response = $this->get('/admin/reports');

        $response->assertStatus(200);
        $response->assertViewIs('admin.reports.index');
        $response->assertViewHas('filter', 'week');
    }

    /** @test */
    public function test_report_page_loads_with_month_filter(): void
    {
        $response = $this->get('/admin/reports?filter=month');

        $response->assertStatus(200);
        $response->assertViewHas('filter', 'month');
    }

    /** @test */
    public function test_start_and_end_are_start_and_end_of_current_week_by_default(): void
    {
        $response = $this->get('/admin/reports?filter=week');

        $response->assertStatus(200);

        $start = $response->viewData('start');
        $end   = $response->viewData('end');

        $this->assertTrue(
            $start->isSameDay(Carbon::now()->startOfWeek()),
            'start should be the beginning of the current week'
        );
        $this->assertTrue(
            $end->isSameDay(Carbon::now()->endOfWeek()),
            'end should be the end of the current week'
        );
    }

    /** @test */
    public function test_start_and_end_are_start_and_end_of_current_month_by_default(): void
    {
        $response = $this->get('/admin/reports?filter=month');

        $response->assertStatus(200);

        $start = $response->viewData('start');
        $end   = $response->viewData('end');

        $this->assertTrue($start->isSameDay(Carbon::now()->startOfMonth()));
        $this->assertTrue($end->isSameDay(Carbon::now()->endOfMonth()));
    }

    /** @test */
    public function test_date_parameter_anchors_week_period(): void
    {
        // Request a specific past week (anchor = 2026-04-14, which is a Tuesday)
        $response = $this->get('/admin/reports?filter=week&date=2026-04-14');

        $response->assertStatus(200);

        $start = $response->viewData('start');
        $end   = $response->viewData('end');

        // Mon 13 Apr 2026 – Sun 19 Apr 2026
        $this->assertEquals('2026-04-13', $start->toDateString());
        $this->assertEquals('2026-04-19', $end->toDateString());
    }

    /** @test */
    public function test_date_parameter_anchors_month_period(): void
    {
        $response = $this->get('/admin/reports?filter=month&date=2026-03-15');

        $response->assertStatus(200);

        $start = $response->viewData('start');
        $end   = $response->viewData('end');

        $this->assertEquals('2026-03-01', $start->toDateString());
        $this->assertEquals('2026-03-31', $end->toDateString());
    }

    /** @test */
    public function test_prev_url_points_to_previous_week(): void
    {
        $response = $this->get('/admin/reports?filter=week&date=2026-04-14');

        $response->assertStatus(200);

        $prevUrl = $response->viewData('prevUrl');

        $this->assertStringContainsString('filter=week', $prevUrl);
        $this->assertStringContainsString('date=2026-04-07', $prevUrl);
    }

    /** @test */
    public function test_prev_url_points_to_previous_month(): void
    {
        $response = $this->get('/admin/reports?filter=month&date=2026-04-15');

        $response->assertStatus(200);

        $prevUrl = $response->viewData('prevUrl');

        $this->assertStringContainsString('filter=month', $prevUrl);
        $this->assertStringContainsString('date=2026-03-', $prevUrl);
    }

    /** @test */
    public function test_next_url_is_null_when_at_current_week(): void
    {
        // No date given => defaults to current week => nextUrl should be null
        $response = $this->get('/admin/reports?filter=week');

        $response->assertStatus(200);

        $nextUrl = $response->viewData('nextUrl');
        $this->assertNull($nextUrl, 'nextUrl should be null when already at the current period');
    }

    /** @test */
    public function test_next_url_is_null_when_at_current_month(): void
    {
        $response = $this->get('/admin/reports?filter=month');

        $response->assertStatus(200);

        $nextUrl = $response->viewData('nextUrl');
        $this->assertNull($nextUrl);
    }

    /** @test */
    public function test_next_url_is_provided_for_past_week(): void
    {
        $response = $this->get('/admin/reports?filter=week&date=2026-04-14');

        $response->assertStatus(200);

        $nextUrl = $response->viewData('nextUrl');

        $this->assertNotNull($nextUrl, 'nextUrl should be set for a past period');
        $this->assertStringContainsString('filter=week', $nextUrl);
        $this->assertStringContainsString('date=2026-04-21', $nextUrl);
    }

    /** @test */
    public function test_totals_aggregate_registrations_within_period(): void
    {
        Carbon::setTestNow('2026-04-14'); // a Tuesday

        // 2 registrations within week (Mon 13 – Sun 19 Apr 2026)
        Registration::factory()->create([
            'adult_count'   => 3,
            'ntl_count'     => 1,
            'ntl_new_count' => 0,
            'child_count'   => 2,
            'created_at'    => '2026-04-14 10:00:00',
        ]);
        Registration::factory()->create([
            'adult_count'   => 2,
            'ntl_count'     => 0,
            'ntl_new_count' => 1,
            'child_count'   => 0,
            'created_at'    => '2026-04-15 10:00:00',
        ]);
        // 1 registration outside the week
        Registration::factory()->create([
            'adult_count'   => 10,
            'created_at'    => '2026-04-07 10:00:00',
        ]);

        $response = $this->get('/admin/reports?filter=week&date=2026-04-14');

        $response->assertStatus(200);

        $totals = $response->viewData('totals');

        $this->assertEquals(5, $totals['adult']);    // 3 + 2
        $this->assertEquals(1, $totals['ntl']);
        $this->assertEquals(1, $totals['ntl_new']);
        $this->assertEquals(2, $totals['child']);

        Carbon::setTestNow(); // reset
    }

    /** @test */
    public function test_invalid_filter_defaults_to_week(): void
    {
        $response = $this->get('/admin/reports?filter=invalid_value');

        $response->assertStatus(200);
        $response->assertViewHas('filter', 'week');
    }

    /** @test */
    public function test_labels_count_matches_days_in_week(): void
    {
        $response = $this->get('/admin/reports?filter=week');

        $response->assertStatus(200);

        $labels = $response->viewData('labels');
        $this->assertCount(7, $labels);
    }

    /** @test */
    public function test_labels_count_matches_days_in_month(): void
    {
        // April 2026 has 30 days
        $response = $this->get('/admin/reports?filter=month&date=2026-04-15');

        $response->assertStatus(200);

        $labels = $response->viewData('labels');
        $this->assertCount(30, $labels);
    }
}
