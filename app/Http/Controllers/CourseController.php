<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

use Auth;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $course = "";
        $classroom = ClassRoom::latest()->get();
        $perpage = request()->perpage;
        if(Auth::check()):
            $course = $this->getAs();
        else:
            $course = Course::where("course_is_free","!=",0)
                                ->paginate($perpage);
        endif;

        return response()->json([
            "course" => $course,
            "classroom" => $classroom
        ]);
    }

    public function getAs(){

        $cData = "";
        $perpage = request()->perpage;

        if(Auth::user()->is_admin != 0):
            $cData = Course::latest()
                            ->paginate($perpage);
        else:
        $cData = Course::where("course_is_public","!=",0)
                            ->orWhere("user_id",Auth::user()->id)
                            ->with("classroom")
                            ->latest()
                            ->paginate($perpage);
        endif;
        
        return $cData;
    }

    public function whoIs(){
        $u = '';
        $role = '';
        $rData = '';
        if(Auth::check() == true):
            $u = User::find(Auth::user()->id);
            $role = $u->role;
        endif;

            $rData = [
                "user" => $u,
                "role" => $role
            ];
        return $rData;
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
            "classroom" => ["required"],
            "course_name" => ["required","max:80"],
            "course_hours" => ["numeric","min:2","max:100"]
        ],[
            "classroom.required" => "Error! please select the classroom"
        ]);

        $c_room_id = request()->classroom;
        $classroom = ClassRoom::find($c_room_id);


        $released = "";
        if(request()->course_is_public != 0 || 
            request()->course_is_public != ''):
            $released = now();
        endif;

        $cover = $this->setCourseCover();

        // prepare data 
        $valid["user_id"] = Auth::user()->id;
        $valid["course_name"] = xx_clean(request()->course_name);
        $valid["course_cover"] = $cover;
        $valid["course_excerpt"] = xx_clean(request()->course_excerpt);
        $valid["course_body"] = xx_clean(request()->course_body);

        $valid["course_is_free"] = !request()->course_is_free?0:1;
        $valid["course_is_public"] = !request()->course_is_public?0:1;
        $valid["released_at"] = $released;
        $valid["course_term"] = request()->course_term;
        $valid["course_credit"] = request()->course_credit;
        $valid["course_hours"] = request()->course_hours;
        $valid["course_year"] = request()->course_year;
        $valid["course_credit"] = request()->course_credit;

        $valid["course_id"] = request()->course_id;


        // remove the non concern keys from array
        unset($valid["classroom"]);

        // create the new course 
        Course::create($valid);

        // get the last insert row 
        $co = Course::latest()->first();

        // attach course to classroom 
        $co->classroom()->attach($classroom);

        $msg = "
<span class=\"has-text-success has-text-weight-bold \">
Success course has created 
</span>
";
        return response()->json([
            "msg" => $msg
        ]);
    }


    /** 
     * setCourseCover will return the string of cover image
     * */
    public function setCourseCover(){
        //$upload_file = request()->course_cover_file;
        $link_url = request()->course_cover_url;

        // set the default file if the user not upload or paste the image url
        $file_name = "/img/placeholders/1280x960.png";// default image


        if(request()->hasFile("course_cover_file")):
            // folder to upload file 
            $upload_folder = public_path("user_upload_image/course");

            // create new image name include user email and upload date 
            $new_name = Auth::user()->email."_"; 
            $new_name .= date("Y-m-d")."_";
            $new_name .= request()->file("course_cover_file")
                                  ->getClientOriginalName();
            // move file to the public folder 
            request()->file('course_cover_file')
                     ->move($upload_folder,$new_name);
            $file_name = $upload_folder."/".$new_name;
        endif;

        if(filter_var($link_url,FILTER_VALIDATE_URL)):
            $file_name = $link_url;
        endif;

        return $file_name;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        //
    }
}
