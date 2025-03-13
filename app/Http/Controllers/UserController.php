<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //







































    function create(Request $req){
        $muser = new User();
        $muser->fname = $req->input('fname');
        $muser->lname = $req->input('lname');
        $muser->role = $req->role;
        $muser->email = $req->email;
        $muser->save();
        return redirect('/users');
    }


    function edit($id)
    {
        $user = User::find($id);
        $data['user'] = $user;
        return view('/user.edit', $data);
    }
    function edit_action(Request $req)
    {
        $muser = User::find($req->id);
        $muser->fname = $req->fname;
        $muser->lname = $req->lname;
        $muser->role = $req->role;
        $muser->saleSupervisor = $req->esaleSupervisor;
        $muser->email = $req->email;
        $muser->save();
        return redirect('/users');
    }
}
