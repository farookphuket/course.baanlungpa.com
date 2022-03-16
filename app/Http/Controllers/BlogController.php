<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Tag;
use App\Models\Read;
use App\Models\Category;

use Illuminate\Http\Request;

use Auth;
use DB;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $query = "";
        $tag = Tag::orderBy("created_at","DESC")
                        ->get();
        $category = Category::all();

        $tag_has_blog = Tag::with('blog')
                            ->latest()
                            ->get();

        $cat_has_blog = Category::with('blog')
                            ->latest()
                            ->get();

        $perpage = request()->perpage;

        if($user):
            $query = $this->getAs($perpage);
        else:
            $query = Blog::latest()
                            ->where("bl_is_public","!=",0)
                            ->paginate(4);
        endif;
        return response()->json([
            "blog" => $query,
            "category" => $category,
            "tag" => $tag,
            "tag_has_blog" => $tag_has_blog,
            "cat_has_blog" => $cat_has_blog
        ]);
    }

    public function getAs($perpage = false){
        $query = "";
        if($perpage):
            (int)$perpage;
        endif;

        if(Auth::user()->is_admin != 0):
            $query = Blog::latest()
                            ->with("comment")
                            ->paginate($perpage);
        else:
            $query = Blog::where("bl_is_public","!=",0)
                        ->orWhere("user_id",Auth::user()->id)
                        ->latest()
                        ->paginate($perpage);
        endif;
        return $query;
    }

    public function getBlogByCategory(){


        $cat_id = request()->cat_id;
        $perpage = request()->perpage;

        $blog = Category::find($cat_id)
                        ->blog()
                        ->paginate($perpage);



            return response()->json([
                "blog" => $blog
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
            "bl_title" => ["required","max:80","unique:blogs,bl_title"],
            "bl_excerpt" => ["required","min:50","max:1000"],
            "bl_body" => ["required","min:50","max:6000"],
        ],
        [
            "bl_excerpt.required" => "Error! the Excerpt is required!",
            "bl_excerpt.min" => "Error! the Excerpt is too short!",
            "bl_excerpt.max" => "Error! the Excerpt are too long!",
            "bl_body.required" => "Error! the Content cannot be empty!",
            "bl_body.min" => "Error! the Content is too short!",
            "bl_body.max" => "Error! the Content are too Long!",
        ]);

        // tags 
        $tags = request()->tag;
        // user has create his tag 
        // so no need to re-create 
        // prepare data for blog
        $public = !request()->bl_is_public?0:1;
        $blog_data = [
            "user_id" => Auth::user()->id,
            "bl_title" => xx_clean(request()->bl_title),
            "bl_excerpt" => xx_clean(request()->bl_excerpt),
            "bl_body" => xx_clean(request()->bl_body),
            "bl_is_public" => $public,
            "bl_slug" => rtrim(request()->bl_slug,"-")
        ];

        // create blog 
        Blog::create($blog_data);

        // get the last insert 
        $bl = Blog::latest()->first();



        // attach the category 
        $bl->category()->attach(request()->category);

        // attach blog to tag 
        $bl->tag()->attach($tags);
        

        // make a backup to file
        Blog::backupBlog($bl->id,"insert");

        //make backup tag
        Tag::backupBlogLink($bl->id);

        //make backup category
        Category::backupBlogLink($bl->id);

        $msg = "
<span class=\"tag is-medium has-text-success has-text-weight-bold\">
Success : your blog has been created
</span>
";
        return response()->json([
            "msg" => $msg
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $blog = Blog::where("bl_slug",$slug)
                    ->with("comment")
                    ->with("reply")
                    ->first();

        // set read
        Read::isBlogHasRead($blog->id);


        return response()->json([
            "blog" => $blog,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        //
        $bl = Blog::find($blog->id);

        return response()->json([
            "blog" => $bl
        ]);
    }

    public function isHasTag($blog_id){
        $b = Blog::find($blog_id)->tag();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Blog $blog)
    {
        $bo = Blog::find($blog->id);

        $valid = request()->validate([
            "bl_title" => ["required","max:80","unique:blogs,bl_title,".$bo->id],
            "bl_excerpt" => ["required","min:50","max:1000"],
            "bl_body" => ["required","min:50"],
        ],
        [
            "bl_title" => "Error! this title has taken, try the unique one",
            "bl_excerpt.required" => "Error! the Excerpt is required!",
            "bl_excerpt.min" => "Error! the Excerpt is too short!",
            "bl_excerpt.max" => "Error! the Excerpt are too long!",
            "bl_body.required" => "Error! the Content cannot be empty!",
            "bl_body.min" => "Error! the Content is too short!",
            "bl_body.max" => "Error! the Content are too Long!",
        ]);

        // tags 
        $tags = request()->tag;
        // user has create his tag 

        // so no need to re-create 
        // prepare data for blog
        $public = !request()->bl_is_public?0:1;
        $blog_data = [
            "bl_title" => xx_clean(request()->bl_title),
            "bl_excerpt" => xx_clean(request()->bl_excerpt),
            "bl_body" => xx_clean(request()->bl_body),
            "bl_is_public" => $public,
            "bl_slug" => rtrim(request()->bl_slug,"-")
        ];

        // create blog 
        Blog::where("id",$blog->id)
            ->update($blog_data);




        // sync the category 
        $bo->category()->sync(request()->category);

        // sync blog to tag 
        $bo->tag()->sync($tags);

        // backup 
        Blog::backupBlog($blog->id,"edit");
        

        //make backup tag
        Tag::backupBlogLink($blog->id);

        //make backup category
        Category::backupBlogLink($blog->id);


        $msg = "
<span class=\"tag is-medium has-text-success has-text-weight-bold\">
Success : blog id {$blog->id} has been updated
</span>
";
        return response()->json([
            "msg" => $msg
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        //
        $del = Blog::find($blog->id);
        $del->delete();

        $msg = "
<span class=\"tag is-medium has-text-success has-text-weight-bold\">
Success : blog id {$blog->id} has been deleted
</span>
";
        return response()->json([
            "msg" => $msg
        ]);
    }
}
