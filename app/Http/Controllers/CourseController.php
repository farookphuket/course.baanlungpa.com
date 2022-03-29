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


    // getFreeCourse
    public function getFreeCourse(){

        $perpage = request()->perpage;

        $free_pub_course = Course::where("course_is_free","!=",0)
            ->where("course_is_public","!=",0)
            ->latest()
            ->paginate($perpage);

        return response()->json([
            "course" => $free_pub_course
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
    public function setCourseCover($course_id=false){
        //$upload_file = request()->course_cover_file;
        $link_url = request()->course_cover_url;

        // set the default file if the user not upload or paste the image url
        $file_name = "/img/placeholders/1280x960.png";// default image

        // where upload file will go
        $upload_to_folder = public_path("user_upload_image/course");

        $old_pic = '';

        // insert or update
        if($course_id):
            $co = Course::find($course_id);
            $old_pic = $co->course_cover;
            $file_name = $old_pic;

            // on image url
            if(filter_var($link_url,FILTER_VALIDATE_URL)):
                $file_name = $link_url;
            endif;

            // on upload image 
            if(request()->hasFile('course_cover_file')):
                $new_name = Auth::user()->email."_";
                $new_name .= date("Y-m-d")."_";
                $new_name .= request()->file('course_cover_file')
                                    ->getClientOriginalName();

                // move upload file to upload folder 
                request()->file('course_cover_file')
                         ->move($upload_to_folder,$new_name);

                // set upload file name 
                $file_name = "/user_upload_image/course/".$new_name;

                // delete the old file 
                unlink(public_path($old_pic));

            endif;

        else:
            // no course id 
            
            // on image url
            if(filter_var($link_url,FILTER_VALIDATE_URL)):
                $file_name = $link_url;
            endif;

            // on upload image 
            if(request()->hasFile('course_cover_file')):
                $new_name = Auth::user()->email."_";
                $new_name .= date("Y-m-d")."_";
                $new_name .= request()->file('course_cover_file')
                                    ->getClientOriginalName();

                // move upload file to upload folder 
                request()->file('course_cover_file')
                         ->move($upload_to_folder,$new_name);

                // set upload file name 
                $file_name = "/user_upload_image/course/".$new_name;
            endif;
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
        $show_course = Course::where("id",$course->id)
                    ->with("classroom")
                    ->get();

        return response()->json([
            "course" => $show_course
        ]);
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
    public function update(Course $course)
    {
        //
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

        $cover = $this->setCourseCover($course->id);

        // prepare data 
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



        // remove the non concern keys from array
        unset($valid["classroom"]);

        // old course
        $old_course = Course::find($course->id);

        // create the new course 
        Course::where("id",$course->id)
            ->update($valid);


        // attach course to classroom 
        $old_course->classroom()->sync($classroom);

        $msg = "
<span class=\"has-text-success has-text-weight-bold \">
Success course id {$old_course->id} has been updated 
</span>
";
        return response()->json([
            "msg" => $msg
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        
        $del = Course::find($course->id);
        $del->delete();

        $msg = "
<span class=\"has-text-success has-text-weight-bold \">
Success course has deleted 
</span>
";
        return response()->json([
            "msg" => $msg,
            "course" => $course
        ]);
    }
}
