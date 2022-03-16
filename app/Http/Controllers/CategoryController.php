<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }


    public function getCategory(){
        //$query = $this->getAs();
        $perpage = request()->perpage;
        if(isset($perpage)):
            (int)$perpage;
            $query = $this->getAs($perpage);
        else:
            $query = Category::latest()->get();
        endif;
        return response()->json([
            "category" => $query
        ]);
    }

    public function getAs($paging=false){
        if($paging):
            (int)$paging;
        endif;
        $user = Auth::user();
        $query = "";
        if($user->is_admin != 0):
            $query = Category::latest()
                        ->has('user')
                        ->with('user')
                        ->paginate($paging);
        else:
            $query = Category::where("is_allow_edit","!=",0)
                        ->orWhere("user_id",$user->id)
                        ->latest()
                        ->paginate($paging);
        endif;

        return $query;

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
            "cat_name" => ["required","min:4","unique:categories,cat_name"]
        ],[
            "cat_name.unique" => "Error! category existed",
        ]);

        $allow_edit = request()->is_allow_edit;
        $valid["user_id"] = Auth::user()->id;
        $valid["cat_name"] = xx_clean(request()->cat_name);
        $valid["cat_slug"] = request()->cat_slug;
        $valid["is_allow_edit"] = !$allow_edit?0:1;
        Category::create($valid);

        // get the last row 
        $cat = Category::latest()->first();

        // backup 
        Category::backupCategory($cat->id,"insert");

        $msg = "
<span class=\"tag is-medium has-text-success has-text-weight-bold\">
Success! category has been created</span>
";
        return response()->json([
            "msg" => $msg
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $cat = Category::find($category->id);
        return response()->json([
            "category" => $cat
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Category $category)
    {

        $cat = Category::find($category->id);
        $valid = request()->validate([
            "cat_name" => ["required","min:4","unique:categories,cat_name,".
            $cat->id]
        ],[
            "cat_name.unique" => "Error! category existed",
        ]);

        $allow_edit = request()->is_allow_edit;
        $valid["cat_name"] = xx_clean(request()->cat_name);
        $valid["cat_slug"] = request()->cat_slug;
        $valid["is_allow_edit"] = !$allow_edit?0:1;
        Category::where("id",$cat->id)
            ->update($valid);

        // backup 
        Category::backupCategory($cat->id,"edit");

        $msg = "
<span class=\"tag is-medium has-text-success has-text-weight-bold\">
Success! category id {$cat->id} has been updated</span>
";
        return response()->json([
            "msg" => $msg
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $cat = Category::find($category->id);
        $cat->delete();
        $msg = "
<span class=\"tag is-medium has-text-success has-text-weight-bold\">
Success! category id {$cat->id} has been updated</span>
";
        return response()->json([
            "msg" => $msg
        ]);
    }
}
