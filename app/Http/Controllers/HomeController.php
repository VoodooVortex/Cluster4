<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // หาพนักงานที่เพิ่มสาขามากที่สุด
        $topUsers = User::withCount('branch')  // ดึงจำนวนสาขา
            ->orderByDesc('branch_count')  // เรียงลำดับตามจำนวนสาขา
            ->take(5)  // ดึง 5 คน
            ->get();

        return view('homePage', compact('topUsers'));
    }
}
