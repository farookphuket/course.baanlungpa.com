<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Models\ClassRoom;
use App\Models\Student;

use Illuminate\Http\Request;

use Auth;
use Session;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $perpage = request()->perpage;


        $classroom = ClassRoom::latest()->get();

        $user = User::where("is_admin","!=",1)
            ->whereHas("role",fn($q) => 
            $q->where("role_name","student")
            )
            ->latest()
            ->get();

        return response()->json([
            "user" => $user,
            "classroom" => $classroom
        ]);

    }

    public function getAs(){
        $perpage = request()->perpage;
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
    public function store(Request $request)
    {

        $valid = request()->validate([
            "classroom_id" => ["required"]
        ]);

        unset($valid["student_ids"]);


        $class_id = request()->classroom_id;
        $students = request()->student_ids;

        // prepare data 
        $valid["class_room_id"] = $class_id;
        $valid["studied_at"] = request()->studied_at;
        $valid["successed_at"] = request()->successed_at;
        $valid["user_id"] = Auth::user()->id;

        // get the class 
        $class = ClassRoom::find($class_id);

        $class->student()->attach($students);



        $msg = "<span class=\"has-text-success has-text-weight-bold\">
            Successs set student </span>";

        return response()->json([
            "msg" => $msg
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }
}
