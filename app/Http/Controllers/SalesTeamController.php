<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class SalesTeamController extends Controller
{
    function detail($id){
        $user = User::find($id);
        return view('detailSalesTeam', compact('user'));
    }
}
