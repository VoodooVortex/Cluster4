<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    function index()
    {
        $currentYear = Carbon::now()->year + 543;  // Convert to Thai year
        $currentMonth = Carbon::now()->month;

        $months = [
            1 => 'มกราคม',
            2 => 'กุมภาพันธ์',
            3 => 'มีนาคม',
            4 => 'เมษายน',
            5 => 'พฤษภาคม',
            6 => 'มิถุนายน',
            7 => 'กรกฎาคม',
            8 => 'สิงหาคม',
            9 => 'กันยายน',
            10 => 'ตุลาคม',
            11 => 'พฤศจิกายน',
            12 => 'ธันวาคม',
        ];

        $currentMonthName = $months[$currentMonth];

        $topBranch = DB::table('users as u')
            ->join('branch as b', 'u.us_id', '=', 'b.br_us_id')
            ->join('order as o', 'b.br_id', '=', 'o.od_br_id')
            ->where('o.od_year', '=', $currentYear)
            ->where('o.od_month', '=', $currentMonthName)
            ->select('b.br_id', 'b.br_code', 'u.us_image', 'u.us_fname', 'o.od_amount', 'o.created_at')
            ->whereIn('o.od_id', function ($query) use ($currentYear, $currentMonthName) {
                // Subquery เพื่อเลือกข้อมูลที่มียอดขายล่าสุดจากแต่ละสาขา
                $query->selectRaw('MAX(o.od_id)')
                    ->from('order as o')
                    ->join('branch as b', 'o.od_br_id', '=', 'b.br_id')
                    ->where('o.od_year', '=', $currentYear)
                    ->where('o.od_month', '=', $currentMonthName)
                    ->groupBy('o.od_br_id');
            })
            ->orderByDesc('o.od_amount')  // เรียงตามยอดขายจากมากไปน้อย
            ->take(5)  // เลือกแค่ 5 อันดับแรก
            ->get();
        return view('homePage', compact('topBranch'));
    }
}
