<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;

class FollowsController extends Controller
{
    public function __construct()
    //_construct is constructor
    {
        $this->middleware('auth');
    }


    public function store(User $user)
    {
        return auth()->user()->following()->toggle($user->profile);
        //toggle()-> toggle b/w connected or not connected

    }
}
