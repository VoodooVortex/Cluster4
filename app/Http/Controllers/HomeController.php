<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class HomeController extends Controller
{
    //
    public function index() {
        $monthMap = [
            'มกราคม' => 1,
            'กุมภาพันธ์' => 2,
            'มีนาคม' => 3,
            'เมษายน' => 4,
            'พฤษภาคม' => 5,
            'มิถุนายน' => 6,
            'กรกฎาคม' => 7,
            'สิงหาคม' => 8,
            'กันยายน' => 9,
            'ตุลาคม' => 10,
            'พฤศจิกายน' => 11,
            'ธันวาคม' => 12
        ];



        // ยอดขายทั้งหมด
        $totalSales = Order::sum('od_amount');

        // ยอดขายเดือนก่อนหน้า
        $previousMonthSales = Order::where('od_month' , date('m') - 1)->sum('od_amount');

        //หา % การเติบโต (ยอดขายเดือนปัจจุบัน - ยอดขายเดือนก่อน / ยอดขายเดือนก่อน )* 100
        $growthPercentage = $previousMonthSales > 0 ? (($totalSales - $previousMonthSales) / $previousMonthSales) * 100 : 0;

        //ค่าเฉลี่ย
        $averageSales = Order::avg('od_amount');

// ดึงข้อมูลยอดขายรายเดือน
        $salesData = Order::where('od_year', 2568) // ปีล่าสุด
        ->selectRaw('od_month, SUM(od_amount) as total_sales')
        ->groupBy('od_month')
        ->orderByRaw("FIELD(od_month, 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม')")
        ->get();

        // เตรียมข้อมูลยอดขายรายเดือน
        $monthlySales = [];
        foreach ($salesData as $sale) {
        $monthNumber = $monthMap[$sale->od_month]; // แปลงชื่อเดือนเป็นหมายเลขเดือน
        $monthlySales[$monthNumber] = $sale->total_sales;
        }

        // กรณีที่บางเดือนไม่มีข้อมูล ยอดขายจะเป็น 0
        $monthlySales = array_replace(array_flip(range(1, 12)), $monthlySales);





        // ชื่อเดือน
        $labels = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];


        return view('homePage' , compact ('totalSales' , 'averageSales' , 'growthPercentage' , 'labels' , 'monthlySales'  ));
    }
}
