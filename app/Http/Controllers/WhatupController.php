<?php

namespace App\Http\Controllers;

use App\Models\Whatup;
use Illuminate\Http\Request;

use Auth;

class WhatupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perpage = request()->perpage;
        if(Auth::check()):
            $wp = $this->getMemberWhatup($perpage);
        else:
            $wp = Whatup::where("is_public","!=",0)
                    ->latest()
                    ->paginate($perpage);
        endif;

        return response()->json([
            "whatup" => $wp
        ]);
    }

    public function getWhatup(){
        $wp = "";
        $perpage = request()->perpage;

        if(Auth::check()):
            $wp = $this->getMemberWhatup($perpage);
        else:
            $wp = Whatup::where("is_public","!=",0)
                        ->has('user')
                        ->with('user')
                        ->orderBy("created_at","DESC")
                        ->paginate(4);
        endif;

        return response()->json([
            "whatup" => $wp
        ]);
    }

    public function getMemberWhatup($perpage = false){

        $user = Auth::user();

        if($perpage):
            (int)$perpage;
        endif;
        if($user->is_admin != 0):
            $wp = Whatup::latest()
                ->paginate($perpage)
                ->onEachSide(1);
                    
        else:
            $wp = Whatup::where("is_public","!=",0)
                        ->orWhere("user_id",Auth::user()->id)
                        ->orderBy("created_at","DESC")
                        ->paginate($perpage);
        endif;
        return $wp;

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
            "wp_title" => ["required","max:80"],
            "wp_body" => ["required","max:5000"]
        ],[
            "wp_title.required" => "Error title cannot be empty!",
            "wp_title.max" => "Error title too long!",
            "wp_body.max" => "Error your post is too long!",
            "wp_body.required" => "Error your post cannot be empty!",
        ]);

        $is_public = !request()->is_public?0:1;

        $user_id = Auth::user()->id;

        $valid["wp_title"] = xx_clean(request()->wp_title);
        $valid["wp_body"] = xx_clean(request()->wp_body);
        $valid["is_public"] = $is_public;
        $valid["user_id"] = $user_id;

        Whatup::create($valid);

        // get the last row
        $wp = Whatup::latest()->first();

        // backup to file 
        Whatup::backupWhatup($wp->id,"insert");

        $msg = "<span class=\"has-text-success has-text-justify 
            has-text-weight-bold is-size-1-mobile\">
            Success : your post has been created</span>";
        return response()->json([
            "msg" => $msg
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Whatup  $whatup
     * @return \Illuminate\Http\Response
     */
    public function show(Whatup $whatup)
    {
        $wp = Whatup::find($whatup->id);
        return response()->json([
            "whatup" => $wp
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Whatup  $whatup
     * @return \Illuminate\Http\Response
     */
    public function edit(Whatup $whatup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Whatup  $whatup
     * @return \Illuminate\Http\Response
     */
    public function update(Whatup $whatup)
    {
        
        $valid = request()->validate([
            "wp_title" => ["required","max:80"],
            "wp_body" => ["required","max:5000"]
        ],[
            "wp_title.required" => "Error title cannot be empty!",
            "wp_title.max" => "Error title too long!",
            "wp_body.max" => "Error your post is too long!",
            "wp_body.required" => "Error your post cannot be empty!",
        ]);

        $is_public = !request()->is_public?0:1;


        $valid["wp_title"] = xx_clean(request()->wp_title);
        $valid["wp_body"] = xx_clean(request()->wp_body);
        $valid["is_public"] = $is_public;
        $valid["updated_at"] = now();

        Whatup::where("id",$whatup->id)->update($valid);

        // save to backup file 
        Whatup::backupWhatup($whatup->id,"edit");

        $msg = "<span class=\"has-text-success has-text-justify 
            has-text-weight-bold is-size-1-mobile \">
            Success : your post has been updated</span>";
        return response()->json([
            "msg" => $msg
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Whatup  $whatup
     * @return \Illuminate\Http\Response
     */
    public function destroy(Whatup $whatup)
    {
        $del = Whatup::find($whatup->id);
        $del->delete();

        $msg = "<span class=\"has-text-success has-text-justify 
            has-text-weight-bold is-size-1-mobile\">
            Success : your post has been deleted</span>";
        return response()->json([
            "msg" => $msg
        ]);
    }
}
