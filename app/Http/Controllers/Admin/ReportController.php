<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $filter = in_array($request->query('filter'), ['week', 'month']) ? $request->query('filter') : 'week';

        if ($filter === 'week') {
            $start = Carbon::now()->startOfWeek();
            $end   = Carbon::now()->endOfWeek();
        } else {
            $start = Carbon::now()->startOfMonth();
            $end   = Carbon::now()->endOfMonth();
        }

        $rows = Registration::query()
            ->whereBetween('created_at', [$start, $end])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(adult_count)   as adult_count'),
                DB::raw('SUM(ntl_count)     as ntl_count'),
                DB::raw('SUM(ntl_new_count) as ntl_new_count'),
                DB::raw('SUM(child_count)   as child_count'),
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Build a full date range so days with 0 registrations still appear
        $labels       = [];
        $adultData    = [];
        $ntlData      = [];
        $ntlNewData   = [];
        $childData    = [];

        $current = $start->copy();
        while ($current->lte($end)) {
            $dateKey = $current->toDateString();
            $labels[]     = $current->format($filter === 'week' ? 'D d/m' : 'd/m');
            $row          = $rows->get($dateKey);
            $adultData[]  = $row ? (int) $row->adult_count  : 0;
            $ntlData[]    = $row ? (int) $row->ntl_count    : 0;
            $ntlNewData[] = $row ? (int) $row->ntl_new_count: 0;
            $childData[]  = $row ? (int) $row->child_count  : 0;
            $current->addDay();
        }

        $totals = [
            'adult'   => array_sum($adultData),
            'ntl'     => array_sum($ntlData),
            'ntl_new' => array_sum($ntlNewData),
            'child'   => array_sum($childData),
        ];

        return view('admin.reports.index', compact(
            'filter', 'labels', 'adultData', 'ntlData', 'ntlNewData', 'childData', 'totals', 'start', 'end'
        ));
    }
}
