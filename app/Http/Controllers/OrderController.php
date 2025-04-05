<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderController extends Controller
{

    function index()
    {
        $orders = User::join('branch as b', 'users.us_id', '=', 'b.br_us_id') // ดึงข้อมูลผู้ใช้ทั้งหมด
        ->join('order as o', 'b.br_id', '=', 'o.od_br_id')
        ->select('b.br_id', 'b.br_code', 'users.us_image', 'users.us_email', 'o.od_amount')
        ->get();
        return view('order', compact('orders'));
    }

    function add_order()
    {
        return view('addOrder');
    }

}
