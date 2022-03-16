<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Reply;
use App\Models\Comment;
use Illuminate\Http\Request;

use Auth;

class ReplyController extends Controller
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


    public function getCommentReply(){
        $comment_id = request()->comment_id;


        $reply = Comment::find($comment_id)->reply()
                        ->latest()
                        ->paginate(4);

        return response()->json([
            "reply" => $reply
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
        $comment_id = request()->comment_id;

        $co = Comment::find($comment_id);

        // get the blog 
        $bo = Blog::find(request()->blog_id);

        $valid = request()->validate([
            "re_title" => ["required","max:80"],
            "re_body" => ["required","max:7000"]
        ],[
            "re_title.required" => "Error! title cannot be empty!",
            "re_title.max" => "Error! title is too long!",
            "re_body.required" => "Error! comment cannot be empty!",
            "re_body.max" => "Error! comment too long!",
        ]);

        $valid["user_id"] = Auth::user()->id;
        $valid["re_title"] = xx_clean(request()->re_title);
        $valid["re_body"] = xx_clean(request()->re_body);

        Reply::create($valid);

        // get the last record
        $re = Reply::latest()->first();

        // link reply to comment
        $co->reply()->attach($re);

        // link reply to blog
        $bo->reply()->attach($re);

        // backup reply 
        Reply::backupReply($re->id,"insert");

        // backup blog link 
        Reply::backupBlogLink($bo->id);

        // backup comment link 
        Reply::backupCommentLink($co->id);

        $msg = "
<span class=\"tag has-text-weight-bold has-text-success is-medium\">
Success : your reply has created!
</span>
";
        return response()->json([
            "msg" => $msg
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function show(Reply $reply)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function edit(Reply $reply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reply $reply)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reply $reply)
    {
        //
    }
}
