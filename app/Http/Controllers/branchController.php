<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class branchController extends Controller
{
    // Index view
    function index()
    {
        return view('branchMyMap');
    }
    // Show supervisor
    function showSupervisor($id)
    {
        $user = User::with('head')->find($id);
        return view('branchMyMap', ['branch' => $user]);
    }
   public function user()
{
    return $this->belongsTo(User::class, 'br_us_id', 'us_id');
}
}

