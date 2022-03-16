<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

//use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;

// this line is very important
use Session;


use Auth;
use DB;

class LoginController extends Controller
{
    protected $user_token_table = "personal_access_tokens";
    protected $reset_password_table = "password_resets";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function checkUserPassport(){

        $user = null;

        if(Auth::check()):
            $user = Auth::user();
        endif;

        return response()->json([
            "user" => $user
        ]);

    }

    public function forgotPassword(){
        $valid = request()->validate([
            "email" => ["required","email"]
        ]);

        $msg = "";
        // check if this email exist
        $user = User::where("email",request()->email)
                        ->first();
        if($user == null || $user == '' || $user == NULL):
            $msg = "<span class=\"tag is-medium has-text-weight-bold 
                has-text-danger \">
                Error! we cannot find your account.
            </span>";
        else:

           $x = $this->sentResetLinkEmail($user); 
            $msg = "<span class=\"tag is-medium has-text-weight-bold 
                has-text-success \">
                Success! please check your email.
            </span>";
        endif;

        return response()->json([
            "msg" => $msg,
            "user" => $x,
        ]);
    }

    public function sentResetLinkEmail(User $user){

        $reset_token = Str::random(60);
        $reset_link = URL::to('/user-reset-password/'.$reset_token);
        $website = request()->getHttpHost();

        // insert data to reset table 
        DB::table($this->reset_password_table)
            ->insert([
                "email" => $user->email,
                "token" => $reset_token,
                "created_at" => now()
            ]);

        $user["website"] = $website;
        $user["token"] = $reset_token;


        $prepareSentData = array(
            "name" => $user->name,
            "title" => "reset user password DO NOT Reply",
            "reset_link" => $reset_link,
            "website" => $website
        );

        Mail::send("mail.reset-passwd-mail",$prepareSentData,
            function($message) use ($user){
                $message->from("no-reply@".$user["website"]);
                $message->to($user["email"],'please do not reply this message')
                        ->subject("dear {$user['name']} you have request to reset 
                    your password");
        });

        return $user;
    }

    
    public function userResetPassword($token){
        $url = '/user-reset-password';

        //dd($token);
        Session::put("user_reset_password_token",$token);

        $utk = Session::get("user_reset_password_token");
        return redirect($url)->with([
            "user_token" => $utk
        ]);
    }

    public function myTimeout(){
        // token age is 6 min.
        $token = Session::get("user_reset_password_token");
        
        $user_tk = DB::table($this->reset_password_table)
                ->where("token",$token)
                ->whereDate("created_at","=",date("Y-m-d"))
                ->first();

        $time_left = 0;

        if($user_tk != null):
            // user has 6 minute before time up
            $since = strtotime($user_tk->created_at)+(60*6);
            $end = $since-time();
            $time_left = round((int)$end/60);
        endif;
         
        //$user = Session::get("user_reset_password_token");

        return response()->json([
            "time_left" => $time_left
        ]);
    }
    public function userHasSetPassword(){

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
        //
        $valid = request()->validate([
            "email" => ["required","email"],
            "password" => ["required"],
        ]);

        $err = false;
        $url = "";
        $msg = "";
        $user_must_verify = true;
        $email = request()->email;
        $token = '';
        $user_enable = null;
        $isTeacher = false;

        if(!Auth::attempt($valid)):
            $err = 1;
            $msg = "<span class=\"tag is-medium is-danger\">
                Error! no account found!</span>";
            $url = '/login';
        else:
            // user is teacher 
            $teacher = $this->isTeacher();

            // user is student 
            $student = $this->isStudent();

            if($this->isUserVerified() && 
                $this->isUserVerified() != null):
                $user_must_verify = false;
                Session::put("user_must_verify",false);
                
            else:
                Session::put("user_must_verify",true);
            endif;

            $token = Auth::user()->createToken('auth_token')->plainTextToken;
            $user_enable = Auth::user();
            $user_url = $this->getUserUrl();
            $url = $user_url; 
            $msg = "<span class=\"tag is-medium is-success\">
                Welcome {$email}</span>";
        endif;


        return response()->json([
            "msg" => $msg,
            "url" => $url,
            "error" => $err,
            "user_must_verify" => $user_must_verify,
            "token_type" => "Bearer",
            "access_token" => $token,
            "user_enable" => $user_enable
        ]);
    }


    public function getUserUrl(){
        $user = User::find(Auth::user()->id);

        $url = '';
        $root_user = false;
        $member = false;

        if(!$this->isUserVerified() || 
        $this->isUserVerified() == null):
            $url = '/user-must-verify';
        else:
            
            if($user->is_admin != 0):
                $url = '/admin';
            else:
                $url = '/member';
            endif;
        endif;



        return $url;

    }

    public function isUserVerified(){
        $user = Auth::user();
        $get = User::where("email_verified_at","!=",null)
                    ->where("id",$user->id)
                    ->first();
        return $get;
    }

    public function isTeacher(){
        $u = User::find(Auth::user()->id);
        $teacher = 0;
        foreach($u->role as $r):
            if($r->role_name == 'teacher'):
                $teacher = true;
            endif;
        endforeach;


        if($teacher != 0):
            Session::put("user_is_teacher",$teacher);
            
        endif;

            
        $rData = [
            "teacher" => $teacher,
        ];
        return $rData;
    }


    public function isStudent(){
        $u = User::find(Auth::user()->id);
        $student = 0;
        foreach($u->role as $r):
            if($r->role_name == 'student'):
                $student = true;
            endif;
        endforeach;


        if($student != 0):
            Session::put("user_is_student",$student);
        endif;

            
        $rData = [
            "student" => $student,
        ];
        return $rData;
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
        //this method will reset the user password from
        // call from forgot password

        // get the token 
        $token = Session::get("user_reset_password_token");

        $get = DB::table($this->reset_password_table)
                        ->where("token",$token)
                        ->first();
        
        $email = $get->email;

        $valid = request()->validate([
            "password" => ["required_with:pass_conf","min:4","same:pass_conf"],
            "pass_conf" => ["min:4"]
        ],[
            "password.same" => "Error the confirmation not match!",
            "pass_conf.same" => "Error! not match",
            "pass_conf.min" => "Error! confirm too short"
        ]);

        $new_pass = Hash::make(request()->password);
        unset($valid["pass_conf"]);
        $valid["password"] = $new_pass;
        $valid["updated_at"] = now();

        //get user 
        $u = User::where("email",$email)
                    ->first();

        // update the password 
        User::where("email",$email)
                ->update($valid);

        // then delete the reset password token
        DB::table($this->reset_password_table)->truncate();

        Session::flush();

        $msg = "<span class=\"tag is-medium has-text-weight-bold 
            has-text-success\">Success you have reset your password</span>";

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
        // the token will be re-generate in every login 
        // make sure we clear all after user logout
        $u_token = DB::table($this->user_token_table)
            ->where('tokenable_id',Auth::user()->id)
            ->get();
        foreach($u_token as $arr):
            DB::table($this->user_token_table)
                ->delete($arr->id);
        endforeach;

        //also delete the current session
        request()->session()->flush();

        // then give the login url
        $url = '/login';

        return response()->json([
            'url' => $url
        ]);

    }
}
