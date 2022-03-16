<?php

namespace App\Http\Controllers;

use App\Models\DefaultPage;
use Illuminate\Http\Request;

use Auth;

class DefaultPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_list = DefaultPage::latest()
                        ->paginate(6);
        return response()->json([
            "static_page" => $page_list
        ]);
    }


    public function about(){

        $about = DefaultPage::where("d_slug","about-our-website")->first();

        return response()->json([
            "about" => $about
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
            "d_title" => ["required","unique:default_pages,d_title"]
        ]);

        $is_public = !request()->d_is_public?0:1;
        // prepare data 
        $d_data = [
            "user_id" => Auth::user()->id,
            "d_title" => xx_clean(request()->d_title),
            "d_slug" => request()->d_slug,
            "d_body" => xx_clean(request()->d_body),
            "d_is_public" => $is_public
        ];

        DefaultPage::create($d_data);

        $msg = "
<span class=\"tag is-medium has-text-success has-text-weight-bold\">
Success: data has been created!
</span>
";
        return response()->json([
            "msg" => $msg
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DefaultPage  $defaultPage
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $show = DefaultPage::where("d_slug",$slug)
                        ->where("d_is_public","!=",0)
                        ->firstOrFail();

        return response()->json([
            "page" => $show
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DefaultPage  $defaultPage
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        

        $show = DefaultPage::find($id);

        return response()->json([
            "page" => $show
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DefaultPage  $defaultPage
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {

        $df = DefaultPage::find($id);

        
        $valid = request()->validate([
            "d_title" => ["required","unique:default_pages,d_title,".
            $df->id]
        ]);

        $is_public = !request()->d_is_public?0:1;
        // prepare data 
        $d_data = [
            "d_title" => xx_clean(request()->d_title),
            "d_slug" => request()->d_slug,
            "d_body" => xx_clean(request()->d_body),
            "d_is_public" => $is_public
        ];

        DefaultPage::where("id",$df->id)
            ->update($d_data);
         

        $msg = "
<span class=\"tag is-medium has-text-success has-text-weight-bold\">
Success: data id {$df->id} has been updated!
</span>
";
        return response()->json([
            "msg" => $msg
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DefaultPage  $defaultPage
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $df = DefaultPage::find($id);
        $df->delete();

        $msg = "
<span class=\"tag is-medium has-text-success has-text-weight-bold\">
Success: data id {$df->id} has been deleted!
</span>
";
        return response()->json([
            "msg" => $msg
        ]);
    }
}
