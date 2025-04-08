<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\User;

class SalesTeamController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->query('role');

        $query = User::query();
        if ($role && $role !== 'all') {
            $query->where('us_role', $role);
        }

        $users = $query->paginate(10);

        // นับจำนวน role ต่าง ๆ
        $countAll = User::count();
        $countSales = User::where('us_role', 'Sales')->count();
        $countSupervisor = User::where('us_role', 'Sales Supervisor')->count();
        $countCEO = User::where('us_role', 'CEO')->count();

        return view('salesTeamMyMap', compact('users', 'countAll', 'countSales', 'countSupervisor', 'countCEO'));
    }

    public function detail($id)
    {
        $user = DB::table('users')->where('us_id', $id)->first();

        $branches = DB::table('branch')
            ->where('br_us_id', $id)
            ->paginate(5);

        return view('detailSalesTeamMyMap', compact('user', 'branches'));
    }
}
