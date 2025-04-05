<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function branchGrowthRate()
    {
        $currentYear = Carbon::now()->year;

        $totalBranches = Branch::whereNull('deleted_at')->count();

        $branchGrowth = Branch::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $currentYear)
            ->whereNull('deleted_at')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month');

        $labels = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
        $growthRates = [];

        for ($i = 1; $i <= 12; $i++) {
            $growthRates[$labels[$i - 1]] = $branchGrowth[$i] ?? 0;
        }

        $growthPercentage = $totalBranches > 0
            ? round(array_sum($growthRates) / $totalBranches * 100, 2)
            : 0;

        return view('growthRate', compact('totalBranches', 'growthRates', 'growthPercentage'));
    }
}
