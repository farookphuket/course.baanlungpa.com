<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;

use Auth;

class CommentController extends Controller
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


    public function getBlogComment(){
        $blog_id = request()->blog_id;
        $perpage = request()->perpage;

        // get the comments related to this blog id
        $comments = Blog::find($blog_id)
                        ->comment()
                        ->with("reply")
                        ->latest()
                        ->paginate($perpage);
        return response()->json([
            "comments" => $comments
        ]);
    }


    public function getBlogCommentAs(){
        $admin = Auth::user()->is_admin;
        $login = Auth::user();
        $perpage = request()->perpage;
        $comments = "";
        if($login):
            if($admin):
                $comments = Comment::latest()
                                ->with('blog')
                                ->with("reply")
                                ->paginate($perpage);
            else:
                $comments = Comment::where("user_id",Auth::user()->id)
                                ->latest()
                                ->with('blog')
                                ->paginate($perpage);
            endif;
        else:

            $comments = Comment::latest()
                            ->with('blog')
                            ->with('reply')
                            ->paginate($perpage);
        endif;

        return response()->json([
            "comments" => $comments
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
            "co_title" => ["required","max:80"],
            "co_body" => ["required","max:15000"]
        ],[
            "co_title.required" => "Error! title is required",
            "co_body.required" => "Error! content is required",
            "co_body.max" => "Error! your comment is too long",
        ]);


        $valid["user_id"] = Auth::user()->id;
        $valid["co_title"] = xx_clean(request()->co_title);
        $valid["co_body"] = xx_clean(request()->co_body);

        $blog = Blog::find(request()->blog_id);

        // create comment
        Comment::create($valid);

        // get the last comment
        $co = Comment::latest()->first();

        // link blog to this comment
        $blog->comment()->attach($co);

        // backup comment 
        Comment::backupComment($co->id,"insert");

        // backup comment link to blog 
        Comment::backupBlogLink($blog->id);

        $msg = "
<span class=\"tag is-medium has-text-success has-text-weight-bold \">
Success : comment has been created
</span>
";
        return response()->json([
            "msg" => $msg,

        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
