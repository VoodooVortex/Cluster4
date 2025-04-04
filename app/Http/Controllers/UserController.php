<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function index()
    {
        $users = User::all(); // ดึงข้อมูลผู้ใช้ทั้งหมด
        return view('manageUser', compact('users'));
    }

    // Aninthita Prasoetsang 66160381
    public function create(Request $request)
    {
    // ตรวจสอบค่าที่รับมา
    $request->validate([
        'username' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'role' => 'required|string',
        'email' => 'nullable|email|max:255',
        'head' => 'nullable|exists:users,us_id', // ต้องมีเฉพาะบางตำแหน่ง
    ]);
       // สร้างผู้ใช้ใหม่ในฐานข้อมูล
       User::create([
        'us_fname' => $request->username,
        'us_lname' => $request->lastname,
        'us_email' => $request->email,
        'us_role' => $request->role,
        'us_head' => $request->head ?? null, // ถ้าไม่มีหัวหน้างาน ให้เป็น null
    ]);
        return redirect('/manage-user')->with('success', 'เพิ่มผู้ใช้งานสำเร็จ');
    }


    function edit_user($id)
    {
        $user = User::find($id);
        $data = $user;
        $allUser = User::all();
        return view('editUser', ['users' => $data], compact('allUser'));
    }

    function edit_action(Request $req)
    {
        $muser = User::find($req->id);
        $muser->us_fname = $req->fname;
        $muser->us_lname = $req->lname;
        $muser->us_role = $req->role;
        $muser->us_head = $req->head;
        $muser->us_email = $req->email;
        $muser->save();
        return redirect('/manage-user');
    }


    public function add_user()
    {
        $allUser = User::where('us_role', 'Sales Supervisor')->get();
        return view('addUser', compact('allUser'));
    }
    
}
