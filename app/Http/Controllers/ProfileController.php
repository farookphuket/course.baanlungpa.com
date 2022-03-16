<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function userIsConfirmed(){

        $valid = request()->validate([
            "pass_conf" => ["required"],
        ],
        [
            "pass_conf.required" => "Error! you cannot leave form empty"
        ]);

        $msg = "<span class=\"tag is-medium has-text-weight-bold 
            has-text-danger\">Error! wrong password</span>";

        $pass_conf = request()->pass_conf;
        $confirmed = false;
        
        if(Hash::check($pass_conf,Auth::user()->password)):
            $msg = "<span class=\"tag is-medium has-text-weight-bold 
            has-text-success\">Success! you have confirmed </span>";
            $confirmed = true;
        endif;

        return response()->json([
            "user_is_confirmed" => $confirmed,
            "msg" => $msg
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(User $user)
    {
        $valid = request()->validate([
            "name" => ["required","unique:users,name,".Auth::user()->id],
            "email" => ["required","email","unique:users,email,".Auth::user()->id],
            "password" => ["min:4","nullable"],
        ]);
        $pass = request()->password;

        $extra_msg = "";
        if(isset($pass) && $pass != null):
            $pass = Hash::make($pass);
            $extra_msg = "and your password ";
            $valid["password"] = $pass;
        else:
            // password field cannot be null
            $valid["password"] = Auth::user()->password;
        endif;

        $valid["updated_at"] = now();
        // update user info
        User::where("id",Auth::user()->id)
                ->update($valid); 

        $msg = "<span class=\"tag has-text-success has-text-weight-bold 
            is-medium\">Success your profile {$extra_msg}has been updated!</span>";
        return response()->json([
            "msg" => $msg
        ]);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $del = User::find($user->id);
        $del->delete();

        $msg = "<span class=\"tag has-text-success has-text-weight-bold 
            is-medium\">Success your account has been deleted!</span>";
        return response()->json([
            "msg" => $msg
        ]);
    }
}
