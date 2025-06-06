<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Branch;
use App\Models\Order;
use App\Models\User;

use Illuminate\Http\Request;

class branchSalesController extends Controller
{
    public function branchSales(Request $request)
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
        $branchesQuery = Branch::withoutTrashed() //withTrashed() โหลดข้อมูลแม้แต่สาขาที่ถูก soft delete
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

        return view('viewBranchSales', compact('paginatedBranches', 'sort', 'province', 'totalPages', 'page', 'search'));
    }
}
