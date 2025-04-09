<?php



namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


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

    public $thaiMonths = [
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


    public function index(Request $request)
    {
        $userRole = Auth::user()->us_role;

        if (Auth::check() && Auth::user()->us_role === 'CEO') {
            $currentMonth = now()->month;  // กำหนดเดือน ปี ปัจจุบัน
            $currentYear = now()->year;

            $branches = Branch::with('users')->get();
            $sort = $request->get('sort', 'desc');
            $search = $request->input('search');
            $province = $request->get('province');
            $role = $request->get('role');

            $branchesQuery = Branch::withTrashed()
                ->with([
                    'manager:us_id,us_fname,us_lname,us_email,us_role',
                    'order' => function ($query) use ($currentYear) {
                        $query->whereYear('created_at', $currentYear)
                            ->latest() // ดึงข้อมูลล่าสุดก่อน
                            ->limit(1) // เอาแค่รายการเดียว
                            ->select('od_id', 'od_br_id', 'od_month', 'od_amount');
                    },
                    'users'
                ])
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
                ->when($province, fn($query) => $query->where('br_province', $province)) // กรองจังหวัด
                ->when($role && $role !== 'ทั้งหมด', function ($query) use ($role) {
                    $query->whereHas('manager', function ($mq) use ($role) {
                        $mq->where('us_role', $role);
                    });
                })
                ->when($role === 'ทั้งหมด', function ($query) {});

            $branches = $branchesQuery->get();
            $branchesWithSales = [];
            $missingBranches = [];

            foreach ($branches as $branch) {
                $sales = $this->getMonthlySales($branch->br_id, $currentYear);  // ดึงยอดขายของเดือนนั้น
                $user = $branch->users->first();


                $branchCreatedMonth = $branch->created_at->month;   // เก็บเดือน ปีที่สาขาเปิดใช้งาน
                $branchCreatedYear = $branch->created_at->year;

                foreach ($this->thaiMonths as $monthNum => $monthName) {  // วนลูปเดือนตั้งเเต่เดือนแรกจนถึงปัจจุบัน
                    if ($monthNum > $currentMonth) {   // ข้ามเดือนได้
                        continue;
                    }

                    if ($branchCreatedYear == $currentYear && $monthNum < $branchCreatedMonth) {
                        continue;    // ข้ามปีที่สาขายังไม่เปิด
                    }

                    // ค้นหา order od_month ตรงกับเดือนที่กำลังตรวจสอบ และมียอดขาย > 0
                    $hasSale = $sales->first(function ($sale) use ($monthName) {
                        $monthNumber = array_search($sale->od_month, $this->thaiMonths);
                        return $monthNumber == array_search($monthName, $this->thaiMonths) || $sale->od_amount > 0;
                    });


                    if (is_null($hasSale)) {
                        $missingBranches[] = (object) [
                            'br_id' => $branch->br_id,
                            'br_code' => $branch->br_code,
                            'br_name' => $branch->br_name,
                            'us_email' => optional($user)->us_email ?? '',
                            'us_image' => optional($user)->us_image ?? '',
                            'missing_month' => $monthName . ' ' . $currentYear,
                            'missing_month_number' => $monthNum,
                            'updated_at' => $branch->updated_at,
                            'od_month' => $this->thaiMonths[$monthNum],
                        ];
                        // dd($missingBranches);
                    } else {
                        $branchesWithSales[] = (object) [
                            'br_id' => $branch->br_id,
                            'br_code' => $branch->br_code,
                            'br_name' => $branch->br_name,
                            'us_email' => optional($user)->us_email ?? '',
                            'us_image' => optional($user)->us_image ?? '',
                            'total_sales' => optional($hasSale)->od_amount,
                            'latest_updated_at' => optional($hasSale)->updated_at,
                        ];
                    }
                }
            }

            return view(
                'order',
                [
                    'branchesWithSales' => collect($branchesWithSales),
                    'branchesWithoutSales' => collect($missingBranches),
                    'search' => $search,          // ส่งค่า search ไปยัง view
                    'sort' => $sort,              // ส่งค่า sort ไปยัง view
                    'province' => $province,      // ส่งค่า province ไปยัง view
                ]
            );
        } elseif (Auth::check() && Auth::user()->us_role === 'Sales Supervisor') {

            $currentUserId = Auth::user()->us_id;

            // ดึงรายชื่อ user ที่มี us_head = คนที่ login
            $userIdsOrder = User::where('us_head', $currentUserId)
                ->whereNull('deleted_at')
                ->pluck('us_id');

            $branchIds = Branch::whereIn('br_us_id', $userIdsOrder)
                ->whereNull('deleted_at')
                ->pluck('br_id');

            $currentMonth = now()->month;  // กำหนดเดือน ปี ปัจจุบัน
            $currentYear = now()->year;

            $branches = Branch::with('users')->get();
            $sort = $request->get('sort', 'desc');
            $search = $request->input('search');
            $province = $request->get('province');
            $role = $request->get('role');

            $branchesQuery = Branch::withTrashed()
                ->with([
                    'manager:us_id,us_fname,us_lname,us_email,us_role',
                    'order' => function ($query) use ($currentYear) {
                        $query->whereYear('created_at', $currentYear)
                            ->latest() // ดึงข้อมูลล่าสุดก่อน
                            ->limit(1) // เอาแค่รายการเดียว
                            ->select('od_id', 'od_br_id', 'od_month', 'od_amount');
                    },
                    'users'
                ])
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
                ->when($province, fn($query) => $query->where('br_province', $province)) // กรองจังหวัด
                ->when($role && $role !== 'ทั้งหมด', function ($query) use ($role) {
                    $query->whereHas('manager', function ($mq) use ($role) {
                        $mq->where('us_role', $role);
                    });
                })
                ->when($role === 'ทั้งหมด', function ($query) {});

            $branches = $branchesQuery->whereIn('br_id', $branchIds)->get();
            $branchesWithSales = [];
            $missingBranches = [];

            foreach ($branches as $branch) {
                $sales = $this->getMonthlySales($branch->br_id, $currentYear);  // ดึงยอดขายของเดือนนั้น
                $user = $branch->users->first();


                $branchCreatedMonth = $branch->created_at->month;   // เก็บเดือน ปีที่สาขาเปิดใช้งาน
                $branchCreatedYear = $branch->created_at->year;

                foreach ($this->thaiMonths as $monthNum => $monthName) {  // วนลูปเดือนตั้งเเต่เดือนแรกจนถึงปัจจุบัน
                    if ($monthNum > $currentMonth) {   // ข้ามเดือนได้
                        continue;
                    }

                    if ($branchCreatedYear == $currentYear && $monthNum < $branchCreatedMonth) {
                        continue;    // ข้ามปีที่สาขายังไม่เปิด
                    }

                    // ค้นหา order od_month ตรงกับเดือนที่กำลังตรวจสอบ และมียอดขาย > 0
                    $hasSale = $sales->first(function ($sale) use ($monthName) {
                        $monthNumber = array_search($sale->od_month, $this->thaiMonths);
                        return $monthNumber == array_search($monthName, $this->thaiMonths) || $sale->od_amount > 0;
                    });


                    if (is_null($hasSale)) {
                        $missingBranches[] = (object) [
                            'br_id' => $branch->br_id,
                            'br_code' => $branch->br_code,
                            'br_name' => $branch->br_name,
                            'us_email' => optional($user)->us_email ?? '',
                            'us_image' => optional($user)->us_image ?? '',
                            'missing_month' => $monthName . ' ' . $currentYear,
                            'missing_month_number' => $monthNum,
                            'updated_at' => $branch->updated_at,
                            'od_month' => $this->thaiMonths[$monthNum],
                        ];
                        // dd($missingBranches);
                    } else {
                        $branchesWithSales[] = (object) [
                            'br_id' => $branch->br_id,
                            'br_code' => $branch->br_code,
                            'br_name' => $branch->br_name,
                            'us_email' => optional($user)->us_email ?? '',
                            'us_image' => optional($user)->us_image ?? '',
                            'total_sales' => optional($hasSale)->od_amount,
                            'latest_updated_at' => optional($hasSale)->updated_at,
                        ];
                    }
                }
            }

            return view(
                'order',
                [
                    'branchesWithSales' => collect($branchesWithSales),
                    'branchesWithoutSales' => collect($missingBranches),
                    'search' => $search,          // ส่งค่า search ไปยัง view
                    'sort' => $sort,              // ส่งค่า sort ไปยัง view
                    'province' => $province,      // ส่งค่า province ไปยัง view
                ]
            );
        } else {
            $currentUserId = Auth::user()->us_id;

            // ดึงรายชื่อ user ที่มี us_head = คนที่ login
            $branchIds = Branch::where('br_us_id', $currentUserId)
                ->whereNull('deleted_at')
                ->pluck('br_id');

            $currentMonth = now()->month;  // กำหนดเดือน ปี ปัจจุบัน
            $currentYear = now()->year;

            $branches = Branch::with('users')->get();
            $sort = $request->get('sort', 'desc');
            $search = $request->input('search');
            $province = $request->get('province');
            $role = $request->get('role');

            $branchesQuery = Branch::withTrashed()
                ->with([
                    'manager:us_id,us_fname,us_lname,us_email,us_role',
                    'order' => function ($query) use ($currentYear) {
                        $query->whereYear('created_at', $currentYear)
                            ->latest() // ดึงข้อมูลล่าสุดก่อน
                            ->limit(1) // เอาแค่รายการเดียว
                            ->select('od_id', 'od_br_id', 'od_month', 'od_amount');
                    },
                    'users'
                ])
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
                ->when($province, fn($query) => $query->where('br_province', $province)) // กรองจังหวัด
                ->when($role && $role !== 'ทั้งหมด', function ($query) use ($role) {
                    $query->whereHas('manager', function ($mq) use ($role) {
                        $mq->where('us_role', $role);
                    });
                })
                ->when($role === 'ทั้งหมด', function ($query) {});

            $branches = $branchesQuery->whereIn('br_id', $branchIds)->get();
            $branchesWithSales = [];
            $missingBranches = [];

            foreach ($branches as $branch) {
                $sales = $this->getMonthlySales($branch->br_id, $currentYear);  // ดึงยอดขายของเดือนนั้น
                $user = $branch->users->first();


                $branchCreatedMonth = $branch->created_at->month;   // เก็บเดือน ปีที่สาขาเปิดใช้งาน
                $branchCreatedYear = $branch->created_at->year;

                foreach ($this->thaiMonths as $monthNum => $monthName) {  // วนลูปเดือนตั้งเเต่เดือนแรกจนถึงปัจจุบัน
                    if ($monthNum > $currentMonth) {   // ข้ามเดือนได้
                        continue;
                    }

                    if ($branchCreatedYear == $currentYear && $monthNum < $branchCreatedMonth) {
                        continue;    // ข้ามปีที่สาขายังไม่เปิด
                    }

                    // ค้นหา order od_month ตรงกับเดือนที่กำลังตรวจสอบ และมียอดขาย > 0
                    $hasSale = $sales->first(function ($sale) use ($monthName) {
                        $monthNumber = array_search($sale->od_month, $this->thaiMonths);
                        return $monthNumber == array_search($monthName, $this->thaiMonths) || $sale->od_amount > 0;
                    });


                    if (is_null($hasSale)) {
                        $missingBranches[] = (object) [
                            'br_id' => $branch->br_id,
                            'br_code' => $branch->br_code,
                            'br_name' => $branch->br_name,
                            'us_email' => optional($user)->us_email ?? '',
                            'us_image' => optional($user)->us_image ?? '',
                            'missing_month' => $monthName . ' ' . $currentYear,
                            'missing_month_number' => $monthNum,
                            'updated_at' => $branch->updated_at,
                            'od_month' => $this->thaiMonths[$monthNum],
                        ];
                        // dd($missingBranches);
                    } else {
                        $branchesWithSales[] = (object) [
                            'br_id' => $branch->br_id,
                            'br_code' => $branch->br_code,
                            'br_name' => $branch->br_name,
                            'us_email' => optional($user)->us_email ?? '',
                            'us_image' => optional($user)->us_image ?? '',
                            'total_sales' => optional($hasSale)->od_amount,
                            'latest_updated_at' => optional($hasSale)->updated_at,
                        ];
                    }
                }
            }

            return view(
                'order',
                [
                    'branchesWithSales' => collect($branchesWithSales),
                    'branchesWithoutSales' => collect($missingBranches),
                    'search' => $search,          // ส่งค่า search ไปยัง view
                    'sort' => $sort,              // ส่งค่า sort ไปยัง view
                    'province' => $province,      // ส่งค่า province ไปยัง view
                ]
            );
        }
    }

    private function getMonthlySales(int $branchId, int $year)
    {
        $sales = DB::table('users as u')
            ->join('branch as b', 'u.us_id', '=', 'b.br_us_id')
            ->leftJoin('order as o', function ($join) use ($year) {
                $join->on('b.br_id', '=', 'o.od_br_id');
            })
            ->select('b.br_id', 'b.br_code', 'b.br_name', 'u.us_email', 'o.od_month', 'o.od_amount')
            ->where('b.br_id', $branchId)
            ->groupBy('b.br_id', 'b.br_code', 'b.br_name', 'u.us_email', 'o.od_month', 'o.od_amount')
            ->get();
        return $sales;
    }


    public function add_order($br_id, $od_month)
    {
        $branch = Branch::findOrFail($br_id);

        // ตรวจสอบว่ามี order สำหรับสาขาและเดือนนี้แล้วหรือยัง
        $addAmount = Order::where('od_br_id', $br_id)
            ->where('od_month', $od_month)
            ->where('od_year', now()->year)
            ->first();

        // แปลงชื่อเดือนเป็นภาษาไทย
        $thaiMonthName = \Carbon\Carbon::createFromDate(null, $od_month, 1)
            ->locale('th')
            ->translatedFormat('F');

        // ปี พ.ศ.
        $thaiYearName = \Carbon\Carbon::now()->format('Y') + 543;

        return view('addOrder', [
            'branch' => $branch,
            'selectedMonth' => $od_month,
            'thaiMonthName' => $thaiMonthName,
            'thaiYearName' => $thaiYearName,
            'addAmount' => $addAmount,
        ]);
    }

    public function storeOrder(Request $req)
    {
        $order = new Order();
        $order->od_br_id = $req->input('br_id');
        $order->od_amount = $req->input('amount');
        $order->od_month = $req->input('month');
        $order->od_year = $req->input('year');
        $order->od_us_id = $req->input('users');
        $order->save();

        return redirect()->route('order')->with('success', 'เพิ่มยอดขายเรียบร้อยแล้ว');
    }
}
