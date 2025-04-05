<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function order_detail($br_id)
    {
        $branch = Branch::with('order', 'manager')->where('br_id', $br_id)->first();

        if (!$branch) {
            return redirect()->back()->with('error', 'ไม่พบข้อมูลสาขานี้');
        }

        // รวมยอดขายทั้งปี
        $totalSales = $branch->order()
            ->whereYear('created_at', date('Y'))
            ->sum('od_amount');

        // ยอดขายรายเดือน
        $monthlySales = $branch->order()
            ->selectRaw('MONTH(created_at) as month, SUM(od_amount) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month')
            ->toArray();

        // ข้อมูล 12 เดือน
        $growthRates = [];
        for ($i = 1; $i <= 12; $i++) {
            $growthRates[Carbon::create()->month($i)->locale('th')->translatedFormat('F')] = $monthlySales[$i] ?? 0;
        }

        return view('orderDetail', compact('branch', 'totalSales', 'growthRates'));
    }
}


    
