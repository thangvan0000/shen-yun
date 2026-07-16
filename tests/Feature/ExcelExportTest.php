<?php

namespace Tests\Feature;

use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExcelExportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        session(['admin_authed' => true]);
    }

    /** @test */
    public function test_export_xls_works_with_all_sessions(): void
    {
        Registration::factory()->create();

        $response = $this->get('/admin/registrations/export.xls?session_id=all');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.ms-excel; charset=UTF-8');
    }

    /** @test */
    public function test_export_csv_works_with_all_sessions(): void
    {
        Registration::factory()->create();

        $response = $this->get('/admin/registrations/export.csv?session_id=all');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-16LE');
    }

    /** @test */
    public function test_export_xls_works_with_specific_session(): void
    {
        $reg = Registration::factory()->create();

        $response = $this->get('/admin/registrations/export.xls?session_id=' . $reg->event_session_id);

        $response->assertStatus(200);
    }
}
