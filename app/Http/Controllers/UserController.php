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

    function create(Request $req)
    {
        $muser = new User();
        $muser->fname = $req->input('fname');
        $muser->lname = $req->input('lname');
        $muser->role = $req->role;
        $muser->email = $req->email;
        $muser->save();
        return redirect('/users');
    }

    function edit_user($id)
    {
        $user = User::find($id);
        $data = $user;
        $allUser = User::all();
        return view('edit', ['users' => $data], compact('allUser'));
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

    function add_user()
    {
        return view('addUser');
    }

    /* --}}
    @title : ทำ Contorller ลบบัญชี
    @author : Yothin Sisaitham 66160088
    @create date : 04/04/2568
    --}} */
    public function delete_user(Request $req){
    // ตรวจสอบว่ามี ID ถูกส่งมาหรือไม่
    if ($req->has('id')) {
        $ids = $req->input('id'); // รับค่า ID

        // เช็คว่าเป็น array หรือไม่ ** checkbox ที่ให้กดได้หลายคน
        if (is_array($ids)) {
            // ลบแบบ batch ในกรณีที่มีหลาย ID
            $chunkSize = 500;  // กำหนดขนาด batch ที่ต้องการ
            foreach (array_chunk($ids, $chunkSize) as $chunk) {
                User::whereIn('id', $chunk)->delete();
            }
        } else {
            // ลบผู้ใช้คนเดียว
            $muser = User::find($ids);
            if ($muser) {
                $muser->delete();
            } else {
                return response()->noContent(); // ถ้าไม่พบผู้ใช้
            }
        }
        // เมื่อการลบเสร็จสิ้นให้ redirect ไปที่หน้ารายการผู้ใช้
        return redirect('/manageUser'); // ใช้เส้นทางที่เหมาะสม
    }

    // ถ้าไม่มี ID → แสดงรายการผู้ใช้ทั้งหมด
    $users = User::all();
    return view('manageUser', compact('users'));
    }
    /*
    // --------------- Not Use ---------------
    // Ver.1 - เลือกหลายรายการไม่ได้

     function delete_user(Request $req){
    if ($req->has('id')) {
        $muser = User::find($req->id); // ถ้าค้นหาด้วยค่า ID มา → ลบผู้ใช้นั้น
        if ($muser) {
            $muser->delete();
            return redirect('/manage-user'); // กลับไปหน้ารายการปกติ
        }
        return response()->noContent(); // ถ้าไม่เจอ ID
    }
    // ถ้าไม่มี ID → แสดงรายการผู้ใช้ทั้งหมด
    $users = User::all();
    return view('manage-user', compact('users'));
    } */
}
