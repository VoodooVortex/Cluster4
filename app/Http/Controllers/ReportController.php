<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function report_CEO(Request $request)
    {

        $selectedYear = (int) $request->input('year');
        // เริ่มต้น query
        $query = DB::table('order')
            ->join('branch', 'order.od_br_id', '=', 'branch.br_id') // เชื่อมโยงตาราง order กับ branch
            ->select('order.od_month', 'branch.br_province', DB::raw('SUM(order.od_amount) as total_sales'))
            ->where('order.od_year', $selectedYear);

        $thaiMonthMap = [
            'มกราคม' => 'ม.ค.',
            'กุมภาพันธ์' => 'ก.พ.',
            'มีนาคม' => 'มี.ค.',
            'เมษายน' => 'เม.ย.',
            'พฤษภาคม' => 'พ.ค.',
            'มิถุนายน' => 'มิ.ย.',
            'กรกฎาคม' => 'ก.ค.',
            'สิงหาคม' => 'ส.ค.',
            'กันยายน' => 'ก.ย.',
            'ตุลาคม' => 'ต.ค.',
            'พฤศจิกายน' => 'พ.ย.',
            'ธันวาคม' => 'ธ.ค.',
        ];

        $provinceToRegionMap = [
            // ภาคเหนือ
            'เชียงใหม่' => 'ภาคเหนือ',
            'เชียงราย' => 'ภาคเหนือ',
            'ลำพูน' => 'ภาคเหนือ',
            'ลำปาง' => 'ภาคเหนือ',
            'แพร่' => 'ภาคเหนือ',
            'น่าน' => 'ภาคเหนือ',
            'พะเยา' => 'ภาคเหนือ',
            'แม่ฮ่องสอน' => 'ภาคเหนือ',
            'ตาก' => 'ภาคเหนือ',
            'สุโขทัย' => 'ภาคเหนือ',
            'พิษณุโลก' => 'ภาคเหนือ',
            'พิจิตร' => 'ภาคเหนือ',
            'กำแพงเพชร' => 'ภาคเหนือ',
            'อุตรดิตถ์' => 'ภาคเหนือ',

            // ภาคกลาง
            'กรุงเทพมหานคร' => 'ภาคกลาง',
            'นนทบุรี' => 'ภาคกลาง',
            'ปทุมธานี' => 'ภาคกลาง',
            'พระนครศรีอยุธยา' => 'ภาคกลาง',
            'อ่างทอง' => 'ภาคกลาง',
            'ลพบุรี' => 'ภาคกลาง',
            'สิงห์บุรี' => 'ภาคกลาง',
            'สระบุรี' => 'ภาคกลาง',
            'นครปฐม' => 'ภาคกลาง',
            'สมุทรสาคร' => 'ภาคกลาง',
            'สมุทรสงคราม' => 'ภาคกลาง',
            'สุพรรณบุรี' => 'ภาคกลาง',
            'ชัยนาท' => 'ภาคกลาง',
            'เพชรบูรณ์' => 'ภาคกลาง',

            // ภาคตะวันออก
            'ชลบุรี' => 'ภาคตะวันออก',
            'ระยอง' => 'ภาคตะวันออก',
            'จันทบุรี' => 'ภาคตะวันออก',
            'ตราด' => 'ภาคตะวันออก',
            'ฉะเชิงเทรา' => 'ภาคตะวันออก',
            'ปราจีนบุรี' => 'ภาคตะวันออก',
            'สระแก้ว' => 'ภาคตะวันออก',

            // ภาคตะวันตก
            'ราชบุรี' => 'ภาคตะวันตก',
            'กาญจนบุรี' => 'ภาคตะวันตก',
            'เพชรบุรี' => 'ภาคตะวันตก',
            'ประจวบคีรีขันธ์' => 'ภาคตะวันตก',

            // ภาคตะวันออกเฉียงเหนือ
            'นครราชสีมา' => 'ภาคอีสาน',
            'บุรีรัมย์' => 'ภาคอีสาน',
            'สุรินทร์' => 'ภาคอีสาน',
            'ศรีสะเกษ' => 'ภาคอีสาน',
            'อุบลราชธานี' => 'ภาคอีสาน',
            'ยโสธร' => 'ภาคอีสาน',
            'อำนาจเจริญ' => 'ภาคอีสาน',
            'ชัยภูมิ' => 'ภาคอีสาน',
            'ขอนแก่น' => 'ภาคอีสาน',
            'มหาสารคาม' => 'ภาคอีสาน',
            'ร้อยเอ็ด' => 'ภาคอีสาน',
            'กาฬสินธุ์' => 'ภาคอีสาน',
            'หนองบัวลำภู' => 'ภาคอีสาน',
            'เลย' => 'ภาคอีสาน',
            'หนองคาย' => 'ภาคอีสาน',
            'บึงกาฬ' => 'ภาคอีสาน',
            'สกลนคร' => 'ภาคอีสาน',
            'นครพนม' => 'ภาคอีสาน',
            'มุกดาหาร' => 'ภาคอีสาน',

            // ภาคใต้
            'นครศรีธรรมราช' => 'ภาคใต้',
            'กระบี่' => 'ภาคใต้',
            'พังงา' => 'ภาคใต้',
            'ภูเก็ต' => 'ภาคใต้',
            'สุราษฎร์ธานี' => 'ภาคใต้',
            'ระนอง' => 'ภาคใต้',
            'ชุมพร' => 'ภาคใต้',
            'สงขลา' => 'ภาคใต้',
            'สตูล' => 'ภาคใต้',
            'ตรัง' => 'ภาคใต้',
            'พัทลุง' => 'ภาคใต้',
            'ปัตตานี' => 'ภาคใต้',
            'ยะลา' => 'ภาคใต้',
            'นราธิวาส' => 'ภาคใต้',
        ];


        // ดึงข้อมูลยอดขาย
        $sales = DB::table('order')
            ->select('od_month', DB::raw('SUM(od_amount) as total_sales'))
            ->where('od_year', $selectedYear)
            ->groupBy('od_month')
            ->orderByRaw("FIELD(od_month, 'มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม')")
            ->get()
            ->map(function ($sale) use ($thaiMonthMap) {
                $sale->od_month = $thaiMonthMap[$sale->od_month] ?? $sale->od_month;
                return $sale;
            });


        // ดึงปีทั้งหมด
        $allYears = DB::table('order')
            ->select('od_year')
            ->distinct()
            ->orderBy('od_year', 'desc')
            ->pluck('od_year');

        // ดึงข้อมูลยอดขายปีที่แล้ว
        $previousYearSales = DB::table('order')
            ->join('branch', 'order.od_br_id', '=', 'branch.br_id') // เชื่อมโยงตาราง order กับ branch
            ->select(DB::raw('SUM(order.od_amount) as total_sales'))
            ->where('order.od_year', $selectedYear - 1)  // ปีที่แล้ว
            ->groupBy('branch.br_province')
            ->get();

        // คำนวณยอดขายรวม
        $totalAmount = $sales->sum('total_sales');

        // คำนวณเปอร์เซ็นต์การเติบโต
        $previousYearTotal = $previousYearSales->sum('total_sales');
        $growthPercentage = 0;
        if ($previousYearTotal > 0) {
            $growthPercentage = (($totalAmount - $previousYearTotal) / $previousYearTotal) * 100;
        }

        // คำนวณค่าเฉลี่ย
        $monthsCount = $sales->count();
        $average = $monthsCount > 0 ? $totalAmount / $monthsCount : 0;



        $currentYearBranches = DB::table('branch')
            ->whereYear('created_at', $selectedYear - 543) // ลบ 543 เพื่อให้ match กับ ค.ศ.
            ->count();

        // ดึงจำนวนสาขาทั้งหมดในปีที่แล้ว
        $previousYearBranches = DB::table('branch')
            ->whereYear('created_at', $selectedYear - 1 - 543)
            ->count();

        // คำนวณเปอร์เซ็นต์การเติบโตของจำนวนสาขา
        $growthPercentageBranches = 0;
        if ($previousYearBranches > 0) {
            $growthPercentageBranches = (($currentYearBranches - $previousYearBranches) / $previousYearBranches) * 100;
        }


        // รายชื่อเดือนแบบย่อเรียงลำดับ
        $thaiShortMonths = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];

        // เติมยอดขายที่ไม่มีให้เป็น 0
        $completeSales = collect($thaiShortMonths)->map(function ($month) use ($sales) {
            $matched = $sales->firstWhere('od_month', $month);
            return (object)[
                'od_month' => $month,
                'total_sales' => $matched ? $matched->total_sales : 0
            ];
        });


        // ดึงจำนวนสาขาที่เพิ่มในแต่ละเดือน
        $branchesByMonth = DB::table('branch')
            ->select(
                DB::raw("MONTH(created_at) as month"),
                DB::raw("COUNT(*) as count")
            )
            ->whereYear('created_at', $selectedYear - 543)
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->pluck('count', 'month');

        // รายชื่อเดือนแบบย่อ
        $thaiMonths = [
            1 => 'ม.ค.',
            2 => 'ก.พ.',
            3 => 'มี.ค.',
            4 => 'เม.ย.',
            5 => 'พ.ค.',
            6 => 'มิ.ย.',
            7 => 'ก.ค.',
            8 => 'ส.ค.',
            9 => 'ก.ย.',
            10 => 'ต.ค.',
            11 => 'พ.ย.',
            12 => 'ธ.ค.',
        ];

        // คำนวณยอดสะสม และหาว่าเดือนไหนเป็นเดือนสุดท้ายที่มีการเพิ่มสาขา
        $cumulativeBranches = [];
        $total = 0;
        $lastMonthWithNewBranch = 0;

        for ($i = 1; $i <= 12; $i++) {
            $count = $branchesByMonth[$i] ?? 0;
            $total += $count;
            $cumulativeBranches[] = [
                'month' => $thaiMonths[$i],
                'total_branches' => $total
            ];
            if ($count > 0) {
                $lastMonthWithNewBranch = $i;
            }
        }

        // User
        // ดึงข้อมูลจำนวนพนักงานทั้งหมดในปีนี้
        $currentYearEmployeeCount = DB::table('users')
            ->whereYear('created_at', $selectedYear - 543) // ลบ 543 เพื่อให้ตรงกับปี พ.ศ.
            ->count();

        // ดึงข้อมูลจำนวนพนักงานทั้งหมดในปีที่แล้ว
        $previousYearEmployeeCount = DB::table('users')
            ->whereYear('created_at', $selectedYear - 1 - 543) // ปีที่แล้ว
            ->count();

        // คำนวณเปอร์เซ็นต์การเติบโตของพนักงานทั้งหมด
        $growthPercentage = 0;
        if ($previousYearEmployeeCount > 0) {
            $growthPercentage = (($currentYearEmployeeCount - $previousYearEmployeeCount) / $previousYearEmployeeCount) * 100;
        }

        $currentYearRoleCounts = [
            'Sales' => DB::table('users')->where('us_role', 'Sales')->whereYear('created_at', $selectedYear)->count(),
            'Sales Supervisor' => DB::table('users')->where('us_role', 'Sales Supervisor')->whereYear('created_at', $selectedYear)->count(),
            'CEO' => DB::table('users')->where('us_role', 'CEO')->whereYear('created_at', $selectedYear)->count(),
        ];


        // ส่งข้อมูลไปยัง View
        return view('reportCEO', [
            'selectedYear' => $selectedYear,
            'allYears' => $allYears,
            'sales' => $completeSales,
            'totalAmount' => $totalAmount,
            'average' => round($average),
            'growthPercentage' => round($growthPercentage, 2),
            'currentYearBranches' => $currentYearBranches,
            'growthPercentageBranches' => round($growthPercentageBranches, 2),
            'cumulativeBranches' => $cumulativeBranches,
            'lastMonthWithNewBranch' => $lastMonthWithNewBranch,
            'currentYearEmployeeCount' => $currentYearEmployeeCount,
            'previousYearEmployeeCount' => $previousYearEmployeeCount,
            'growthPercentage' => round($growthPercentage, 2),
            'currentYearRoleCounts' => $currentYearRoleCounts,
        ]);
    }
}
