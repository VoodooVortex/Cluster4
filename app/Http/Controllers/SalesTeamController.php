<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class SalesTeamController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('salesTeamMyMap', compact('users'));
    }

    public function detail($id)
    {
        // ดึงข้อมูลผู้ใช้จาก us_id
        $user = DB::table('users')->where('us_id', $id)->first();

        // ดึงรายการสาขาที่ผู้ใช้นั้นดูแล จาก br_us_id
        $branches = DB::table('branch')
            ->where('br_us_id', $id)
            ->paginate(5); // หรือจะใช้ ->get() ถ้ายังไม่ใช้ paginate

        return view('detailSalesTeamMyMap', compact('user', 'branches'));
    }
    public function boot(): void
    {
        Paginator::useTailwind(); // ทำให้ paginate() ใช้ Tailwind CSS
    }


}
