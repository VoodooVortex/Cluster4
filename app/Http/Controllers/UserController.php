<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all(); // ดึงข้อมูลผู้ใช้ทั้งหมด
        return view('manageUser', compact('users'));
    }
}
