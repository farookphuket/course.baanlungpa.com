<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Auth;
use DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $role_link_table = "role_user";

    public function index()
    {
        $user = null;
        if(Auth::check()):
            $user = Auth::user();
        endif;
        return response()->json([
            "user" => $user
        ]);

    }


    public function getUser(){
        $user = User::orderBy("created_at","DESC")
                    ->paginate(4)
                    ->onEachSide(1);

        $role = Role::all();

        return response()->json([
            "user" => $user,
            "role" => $role
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
    public function store()
    {


        $valid = request()->validate([
            "name" => ["required","unique:users,name"],
            "email" => ["required","email","unique:users,email"]
        ]);

        // check if the password field has value
        $pass = request()->password;
        $extra_msg = "";
        if(!isset($pass) && $pass == ''):
            $pass = Hash::make('1234');
            $extra_msg = " and use 1234 as password";
        else:
            $pass = Hash::make(request()->password);
            $extra_msg = " and use new password";
        endif;
        $valid["password"] = $pass;

        // role 
        $roles = request()->roles;


        // active user 
        $activeUser = !request()->activeUser?0:1;
        $active_at = null;
        if($activeUser):
            $active_at = date("Y-m-d H:i:s");
        endif;
        $valid["email_verified_at"] = $active_at;

        User::create($valid);
        $u = User::latest()->first();

        // update the role
        $u->role()->attach($roles);

        $msg = "<span class=\"tag is-medium is-success\">
            Success : user created {$extra_msg}! 
        </span>";

        return response()->json([
            "msg" => $msg
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $u_data = User::with('role')->find($user->id);

        return response()->json([
            "user" => $u_data
        ]);
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
        $uData = User::find($user->id);

        $valid = request()->validate([
            "name" => ["required","unique:users,name,".$user->id],
            "email" => ["required","email","unique:users,email,".$user->id]
        ]);

        // active user 
        $activeUser = !request()->activeUser?0:1;
        $active_at = null;

        if($activeUser):
            $active_at = date("Y-m-d H:i:s");
        endif;
        $valid["email_verified_at"] = $active_at;

        // check if the password field has value
        $pass = request()->password;
        $extra_msg = "";
        if(isset($pass) && $pass != ''):
            $pass = Hash::make(request()->password);
            $valid["password"] = $pass;
            $extra_msg = " and password";
        endif;

        // role 
        $roles = request()->roles;

        User::where("id",$user->id)->update($valid);

        $u = User::where("id",$user->id)
                    ->with('role')
                    ->first();

        // update the role
        $u->role()->sync($roles);

        $msg = "<span class=\"tag is-medium is-success\">
            Success : user id {$user->id}{$extra_msg} has been updated! 
        </span>";

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
        //

        $del = User::find($user->id);
        $del->role()->detach();

        $del->delete();

        $msg = "<span class=\"tag is-medium is-success\">
            Success : user id {$user->id} has been deleted! 
        </span>";

        return response()->json([
            "msg" => $msg
        ]);
    }
}
