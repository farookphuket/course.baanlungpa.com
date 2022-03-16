<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

use Auth;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $perpage = request()->perpage;
        
        if(isset(request()->perpage)):

            (int)$perpage;
            $tag = Tag::latest()
                    ->paginate($perpage);
        else:
            $tag = Tag::latest()->first();
        endif;


        return response()->json([
            "tag" => $tag
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
            "tag_name" => ["required","unique:tags,tag_name","max:18"]
        ],[
            "tag_name.unique" => "Error! this tag is existed,try again!",
            "tag_name.max" => "Error! your tag is too long",
        ]);

        $valid["tag_name"] = xx_clean(request()->tag_name);

        Tag::create($valid);

        // get the last row for backup
        $ta = Tag::latest()->first();

        // make a backup 
        Tag::backupTag($ta->id,"insert");

        $msg = "
<span class=\"tag is-medium has-text-success has-text-weight-bold\">
Success! tag has been created!</span>
";
        return response()->json([
            "msg" => $msg
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        $ta = Tag::find($tag->id);

        return response()->json([
            "tag" => $ta
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Tag $tag)
    {
        $ta = Tag::find($tag->id);
        $valid = request()->validate([
            "tag_name" => ["required","unique:tags,tag_name,".$tag->id,
            "max:21"]
        ],[
            "tag_name.unique" => "Error! cannot use this tag"
        ]);

        $valid["tag_name"] = xx_clean(request()->tag_name);

        Tag::where("id",$ta->id)
            ->update($valid);


        // make a backup 
        Tag::backupTag($ta->id,"edit");

        $msg = "
<span class=\"tag is-medium has-text-success has-text-weight-bold\">
Success! tag id {$ta->id} has been updated!</span>
";
        return response()->json([
            "msg" => $msg
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $ta = Tag::find($tag->id);

        $ta->delete();

        $msg = "
<span class=\"tag is-medium has-text-success has-text-weight-bold\">
Success! tag id {$ta->id} has been deleted!</span>
";
        return response()->json([
            "msg" => $msg
        ]);
    }
}
