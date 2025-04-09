<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Branch;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // รับค่าจากผู้ใช้ใช้งาน
        $sort = $request->get('sort', 'desc'); // sort เรียงยอดขายจากมากไปน้อยหรือน้อยไปมาก โดยที่ค่าเริ่มต้นคือมากไปน้อย
        $search = $request->input('search'); // ค้นหาจากที่ผู้ใช้งานกรอก
        $province = $request->get('province'); // จังหวัดที่ใช้กรอก
        $perPage = 5; // ต้องการโชว์แค่ 5 สาขาต่อหน้า
        $page = $request->input('page', 1); // หน้าปัจจุบันที่แสดงผล

        // ตรวจสอบว่ามีการระบุรหัสสาขา
        if ($request->has('br_id')) {
            $branch = Branch::with('branch', 'manager')->where('br_id', $request->get('br_id'))->first();

            if (!$branch) {
                return redirect()->back()->with('error', 'ไม่พบข้อมูลสาขานี้');
            }
        }

        // ดึงข้อมูลสาขา
        $branchesQuery = Branch::withTrashed() //withTrashed() โหลดข้อมูลแม้แต่สาขาที่ถูก soft delete
            ->with([
                'manager',
                'order',
                'image' => fn($query) => $query->latest()->limit(1)
            ])
            // สามารค้นหาจากรหัสสาขา ชื่อสาขา หรือจังหวัด
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('br_code', 'LIKE', "{$search}%")
                        ->orWhere('br_name', 'LIKE', "{$search}%")
                        ->orWhere('br_province', 'LIKE', "{$search}%")
                        ->orWhereHas('manager', function ($mq) use ($search) {
                            $mq->where('us_fname', 'LIKE', "{$search}%")
                                ->orWhere('us_lname', 'LIKE', "{$search}%")
                                ->orWhereRaw("CONCAT(us_fname, ' ', us_lname) LIKE ?", ["{$search}%"]);
                        });
                });
            })
            //ถ้าผู้ใช้งานเลือกจังหวัดที่ต้องการดู ระบบจะแสดงแค่จังหวัดนั้น
            ->when($province, fn($query) => $query->where('br_province', $province));

        // คำนวณยอดขายและดึงรูปล่าสุดของผู้ใช้งาน
        $branches = $branchesQuery->get()->map(function ($branch) {
            $branch->total_sales = $branch->order()
                ->whereYear('created_at', now()->year)
                ->sum('od_amount');

            $branch->latest_image = $branch->image->first();
            return $branch;
        });

        // เรียงลำดับยอดขาย
        $branches = $sort === 'asc'
            ? $branches->sortBy('total_sales')->values()
            : $branches->sortByDesc('total_sales')->values();

        // นับจำนวนสาขาทั้งหมดจากการค้นหาหรือตัวกรองที่ผู้ใช้งานเลือก
        $totalBranches = $branches->count();
        $totalPages = ceil($totalBranches / $perPage);

        // ตรวจสอบความถูกต้องของหน้า
        if ($page < 1) {
            $page = 1;
        } else if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
        }

        // แสดงเฉพาะข้อมูลในหน้าปัจจุบัน
        $offset = ($page - 1) * $perPage;
        $paginatedBranches = $branches->slice($offset, $perPage);

        // กำหนดลำดับเลขหน้าของแต่ละสาขา
        foreach ($paginatedBranches as $index => $branch) {
            $branch->branch_number = $offset + $index + 1;
        }

        return view('branchMyMap', compact('paginatedBranches', 'sort', 'province', 'totalPages', 'page', 'search'));
    }
    public function sales_supervisor(Request $request)
    {
        // รับค่าจากผู้ใช้งาน
        $sort = $request->get('sort', 'desc'); // sort เรียงยอดขายจากมากไปน้อยหรือน้อยไปมาก โดยที่ค่าเริ่มต้นคือมากไปน้อย
        $search = $request->input('search'); // ค้นหาจากที่ผู้ใช้งานกรอก
        $province = $request->get('province'); // จังหวัดที่ใช้กรอก
        $perPage = 5; // ต้องการโชว์แค่ 5 สาขาต่อหน้า
        $page = $request->input('page', 1); // หน้าปัจจุบันที่แสดงผล

        // ดึง id ของผู้ใช้งานที่เข้าถึง
        $currentUserId = auth()->user()->us_id;

        // ตรวจสอบว่ามีการระบุรหัสสาขา
        if ($request->has('br_id')) {
            $branch = Branch::with('branch', 'manager')
                ->where('br_id', $request->get('br_id'))
                ->where('br_us_id', $currentUserId) // เพิ่มเงื่อนไขให้เป็นสาขาที่ผู้ใช้ปัจจุบันเป็นคนสร้าง
                ->first();

            if (!$branch) {
                return redirect()->back()->with('error', 'ไม่พบข้อมูลสาขานี้');
            }
        }

        // ดึงข้อมูลสาขา
        $branchesQuery = Branch::withTrashed() //withTrashed() โหลดข้อมูลแม้แต่สาขาที่ถูก soft delete
            ->with([
                'manager',
                'order',
                'image' => fn($query) => $query->latest()->limit(1)
            ])
            ->where('br_us_id', $currentUserId)
            // สามารค้นหาจากรหัสสาขา ชื่อสาขา หรือจังหวัด
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('br_code', 'LIKE', "{$search}%")
                        ->orWhere('br_name', 'LIKE', "{$search}%")
                        ->orWhere('br_province', 'LIKE', "{$search}%")
                        ->orWhereHas('manager', function ($mq) use ($search) {
                            $mq->where('us_fname', 'LIKE', "{$search}%")
                                ->orWhere('us_lname', 'LIKE', "{$search}%")
                                ->orWhereRaw("CONCAT(us_fname, ' ', us_lname) LIKE ?", ["{$search}%"]);
                        });
                });
            })
            //ถ้าผู้ใช้งานเลือกจังหวัดที่ต้องการดู ระบบจะแสดงแค่จังหวัดนั้น
            ->when($province, fn($query) => $query->where('br_province', $province));

        // คำนวณยอดขายและดึงรูปล่าสุดของผู้ใช้งาน
        $branches = $branchesQuery->get()->map(function ($branch) {
            $branch->total_sales = $branch->order()
                ->whereYear('created_at', now()->year)
                ->sum('od_amount');

            $branch->latest_image = $branch->image->first();
            return $branch;
        });

        // เรียงลำดับยอดขาย
        $branches = $sort === 'asc'
            ? $branches->sortBy('total_sales')->values()
            : $branches->sortByDesc('total_sales')->values();

        // นับจำนวนสาขาทั้งหมดจากการค้นหาหรือตัวกรองที่ผู้ใช้งานเลือก
        $totalBranches = $branches->count();
        $totalPages = ceil($totalBranches / $perPage);

        // ตรวจสอบความถูกต้องของหน้า
        if ($page < 1) {
            $page = 1;
        } else if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
        }

        // แสดงเฉพาะข้อมูลในหน้าปัจจุบัน
        $offset = ($page - 1) * $perPage;
        $paginatedBranches = $branches->slice($offset, $perPage);

        // กำหนดลำดับเลขหน้าของแต่ละสาขา
        foreach ($paginatedBranches as $index => $branch) {
            $branch->branch_number = $offset + $index + 1;
        }

        return view('reportSalesSupervisor', compact('paginatedBranches', 'sort', 'province', 'totalPages', 'page', 'search'));
    }
    public function reportSaleTeam(Request $request)
    {

        // รับค่าจากผู้ใช้งาน
        $sort = $request->get('sort', 'desc'); // sort เรียงยอดขายจากมากไปน้อยหรือน้อยไปมาก โดยที่ค่าเริ่มต้นคือมากไปน้อย
        $search = $request->input('search'); // ค้นหาจากที่ผู้ใช้งานกรอก
        $province = $request->get('province'); // จังหวัดที่ใช้กรอก
        $perPage = 5; // ต้องการโชว์แค่ 5 สาขาต่อหน้า
        $page = $request->input('page', 1); // หน้าปัจจุบันที่แสดงผล

        // ดึง id ของผู้ใช้งานที่เข้าถึง
        $currentUserId = auth()->user()->us_id;

        // ตรวจสอบว่ามีการระบุรหัสสาขา
        if ($request->has('br_id')) {
            $branch = Branch::with('branch', 'manager')
                ->where('br_id', $request->get('br_id'))
                ->where('br_us_id', $currentUserId) // เพิ่มเงื่อนไขให้เป็นสาขาที่ผู้ใช้ปัจจุบันเป็นคนสร้าง
                ->first();

            if (!$branch) {
                return redirect()->back()->with('error', 'ไม่พบข้อมูลสาขานี้');
            }
        }

        // ดึงข้อมูลสาขา
        $branchesQuery = Branch::withTrashed()
            ->with([
                'manager',
                'order',
                'image' => fn($query) => $query->latest()->limit(1)
            ])
            ->whereHas('manager', function ($query) use ($currentUserId) {
                $query->where('us_head', $currentUserId);
            })
            // สามารค้นหาจากรหัสสาขา ชื่อสาขา หรือจังหวัด
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('br_code', 'LIKE', "{$search}%")
                        ->orWhere('br_name', 'LIKE', "{$search}%")
                        ->orWhere('br_province', 'LIKE', "{$search}%")
                        ->orWhereHas('manager', function ($mq) use ($search) {
                            $mq->where('us_fname', 'LIKE', "{$search}%")
                                ->orWhere('us_lname', 'LIKE', "{$search}%")
                                ->orWhereRaw("CONCAT(us_fname, ' ', us_lname) LIKE ?", ["{$search}%"]);
                        });
                });
            })
            //ถ้าผู้ใช้งานเลือกจังหวัดที่ต้องการดู ระบบจะแสดงแค่จังหวัดนั้น
            ->when($province, fn($query) => $query->where('br_province', $province));

        // คำนวณยอดขายและดึงรูปล่าสุดของผู้ใช้งาน
        $branches = $branchesQuery->get()->map(function ($branch) {
            $branch->total_sales = $branch->order()
                ->whereYear('created_at', now()->year)
                ->sum('od_amount');

            $branch->latest_image = $branch->image->first();
            return $branch;
        });

        // เรียงลำดับยอดขาย
        $branches = $sort === 'asc'
            ? $branches->sortBy('total_sales')->values()
            : $branches->sortByDesc('total_sales')->values();

        // นับจำนวนสาขาทั้งหมดจากการค้นหาหรือตัวกรองที่ผู้ใช้งานเลือก
        $totalBranches = $branches->count();
        $totalPages = ceil($totalBranches / $perPage);

        // ตรวจสอบความถูกต้องของหน้า
        if ($page < 1) {
            $page = 1;
        } else if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
        }

        // แสดงเฉพาะข้อมูลในหน้าปัจจุบัน
        $offset = ($page - 1) * $perPage;
        $paginatedBranches = $branches->slice($offset, $perPage);

        // กำหนดลำดับเลขหน้าของแต่ละสาขา
        foreach ($paginatedBranches as $index => $branch) {
            $branch->branch_number = $offset + $index + 1;
        }

        return view('reportSalesSupervisor', compact('paginatedBranches', 'sort', 'province', 'totalPages', 'page', 'search'));
    }


    public function report_CEO(Request $request)
    {
        $selectedYear = (int) $request->input('year', Carbon::now()->year + 543);
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






        $monthMap = [
            'มกราคม',
            'กุมภาพันธ์',
            'มีนาคม',
            'เมษายน',
            'พฤษภาคม',
            'มิถุนายน',
            'กรกฎาคม',
            'สิงหาคม',
            'กันยายน',
            'ตุลาคม',
            'พฤศจิกายน',
            'ธันวาคม',
        ];

        $months = $monthMap;

        $orders = DB::table('order')
            ->select('od_month', 'od_amount')
            ->where('od_year', $selectedYear)
            ->whereIn('od_month', $months)
            ->orderByRaw("FIELD(od_month, '" . implode("','", $months) . "')")
            ->get();

        $monthlyData = [];

        // จัดกลุ่มข้อมูลตามเดือน
        foreach ($orders as $order) {
            $month = $order->od_month;
            if (!isset($monthlyData[$month])) {
                $monthlyData[$month] = [];
            }
            $monthlyData[$month][] = $order->od_amount;
        }

        $monthlyTotal = [];

        foreach ($months as $month) {
            if (!empty($monthlyData[$month])) {
                $amounts = $monthlyData[$month];
                $total = array_sum($amounts);
                $monthlyTotal[$month] = $total;
            } else {
                $monthlyTotal[$month] = 0;
            }
        }


        $monthlyMedian = [];
        foreach ($months as $month) {
            if (!empty($monthlyData[$month])) {
                $amounts = $monthlyData[$month];
                sort($amounts);
                $count = count($amounts);
                $middle = floor($count / 2);

                if ($count % 2) {
                    $median = $amounts[$middle];
                } else {
                    $median = ($amounts[$middle - 1] + $amounts[$middle]) / 2;
                }

                $monthlyMedian[$month] = $median;
            } else {
                $monthlyMedian[$month] = 0;
            }
        }



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



        // ดึงข้อมูลยอดขายตามภาค
        $salesRegion = DB::table('order')
            ->join('branch', 'order.od_br_id', '=', 'branch.br_id')
            ->select('branch.br_province', DB::raw('SUM(order.od_amount) as total_sales'))
            ->where('order.od_year', $selectedYear)
            ->groupBy('branch.br_province')
            ->get()
            ->groupBy(function ($item) use ($provinceToRegionMap) {
                // ตรวจสอบชื่อคอลัมน์ br_province จากตาราง branch
                return $provinceToRegionMap[$item->br_province] ?? 'ไม่ทราบภาค';
            })
            ->map(function ($items, $region) {
                return [
                    'region' => $region,
                    'total_sales' => $items->sum('total_sales')
                ];
            })
            ->values();



        $regionLabels = $salesRegion->pluck('region'); // ['ภาคเหนือ', 'ภาคกลาง', ...]
        $salesData = $salesRegion->pluck('total_sales'); // [5000, 7000, ...]



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
        $growthPercentagemployee = 0;
        if ($previousYearEmployeeCount > 0) {
            $growthPercentageemployee = (($currentYearEmployeeCount - $previousYearEmployeeCount) / $previousYearEmployeeCount) * 100;
        }

        $currentYearRoleCounts = [
            'Sales' => DB::table('users')
                ->where('us_role', 'Sales')
                ->whereYear('created_at', $selectedYear - 543)
                ->count(),

            'Sales Supervisor' => DB::table('users')
                ->where('us_role', 'Sales Supervisor')
                ->whereYear('created_at', $selectedYear - 543)
                ->count(),

            'CEO' => DB::table('users')
                ->where('us_role', 'CEO')
                ->whereYear('created_at', $selectedYear - 543)
                ->count(),
        ];

        $employeeByMonth = DB::table('users')
            ->select(
                DB::raw("MONTH(created_at) as month"),
                DB::raw("COUNT(*) as count")
            )
            ->whereYear('created_at', $selectedYear - 543)
            ->groupBy(DB::raw("MONTH(created_at)"))
            ->pluck('count', 'month');

        $cumulativeemployee = [];
        $totalemployee = 0;
        $lastMonthWithNewemployee = 0;

        for ($i = 1; $i <= 12; $i++) {
            $count = $employeeByMonth[$i] ?? 0;
            $totalemployee += $count;
            $cumulativeemployee[] = [
                'month' => $thaiMonths[$i],
                'total_employee' => $totalemployee
            ];
            if ($count > 0) {
                $lastMonthWithNewemployee = $i;
            }
        }

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
            'currentYearRoleCounts' => $currentYearRoleCounts,
            'lastMonthWithNewemployee' => $lastMonthWithNewemployee,
            'cumulativeemployee' => $cumulativeemployee,
            'regionLabels' => $regionLabels,
            'salesData' =>  $salesData,
            'growthPercentagemployee' => $growthPercentagemployee,
            'monthlyMedian' => $monthlyMedian,
            'monthlyTotal' => $monthlyTotal,

        ]);
    }
}
