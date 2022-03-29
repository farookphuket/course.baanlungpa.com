<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController as Login;
use App\Http\Controllers\RegisterController as Regis;
use App\Http\Controllers\WhatupController as Whatup;
use App\Http\Controllers\VisitorController as Visit;
use App\Http\Controllers\UserController as User;
use App\Http\Controllers\ProfileController as Profile;
use App\Http\Controllers\CategoryController as Cat;

use App\Http\Controllers\ClassRoomController as ClassRoom;
use App\Http\Controllers\CourseController as Course;
use App\Http\Controllers\TeacherController as Teacher;
use App\Http\Controllers\StudentController as Student;

use App\Http\Controllers\CommentController as Comment;
use App\Http\Controllers\ReplyController as Reply;
use App\Http\Controllers\BlogController as Blog;
use App\Http\Controllers\TagController as Tag;
use App\Http\Controllers\DefaultPageController as DefaultP;


// Member route 
use App\Http\Controllers\Member\DashboardController as Member;


// admin route 
use App\Http\Controllers\Admin\DashboardController as Admin;

use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// course 18 Mar 2022
Route::get('/free-course',[Course::class,'getFreeCourse'])
    ->name('getFreeCourse');

Route::get('/course/{course}',[Course::class,'show']);


// visitor
Route::get('/getvisitor',[Visit::class,'getVisitor'])
    ->name('getvisitor');
Route::post('/visitor',[Visit::class,'store']);

// comment 21 Feb 2022
Route::get("/get-blog-comment",[Comment::class,"getBlogComment"])
    ->name("getBlogComment");

// reply 21 Feb 2022
Route::get("/get-comment-reply",[Reply::class,"getCommentReply"])
    ->name("getCommentReply");

// static route that will follow the "/static-site/uri-segment" 
Route::get("/static-site",[DefaultP::class,"index"]);
Route::get('/static-site/{id:slug}',[DefaultP::class,"show"]);

Route::get('/about-us',[DefaultP::class,"about"])
    ->name("about");



Route::get("/blog",[Blog::class,"index"]);
Route::get("/blog/{id:slug}",[Blog::class,"show"]);

Route::get("/getcategory",[Cat::class,"getCategory"])
        ->name("getCategory");

Route::get("/gettag",[Tag::class,"index"])
        ->name("getTag");

// get whatup as public
Route::get('/getwhatup',[Whatup::class,'getWhatup'])
    ->name('getWhatup');


// register 
Route::post('/register',[Regis::class,'store'])
    ->name('register');

// login 
Route::post('/login',[Login::class,'store'])
    ->name('login');

// forgotpassword 
Route::post('/forgotpassword',[Login::class,"forgotPassword"])
    ->name("forgotPassword");

/* 6 Feb 2022
 * user has click on the reset password link via e-mail 
 * the end point for form submit will be hit this url to reset user password
 * */ 
Route::get('/my-timeout',[Login::class,"myTimeout"])
    ->name("myTimeout");
Route::put('/user-has-reset-password',[Login::class,"update"]);


// check if the user has passport
Route::get('/checkuserpassport',[Login::class,'checkUserPassport'])
    ->name('checkUserPassport');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $user = Auth::user();
    return $user;
});

// get blog by the category id
Route::get('/blog-by-category',[Blog::class,"getBlogByCategory"])
    ->name("getBlogByCategory");

/* #Member make a route prefix for member group */
Route::prefix("member")->name("member.")->middleware('auth:sanctum')
        ->group(function(){
    Route::resource('/dashboard',Member::class);


    // whatup 
    Route::resource("/whatup",Whatup::class);

    // blog 9-Feb-2022
    Route::resource("/blog",Blog::class);

    // comment 21 Feb 2022
    Route::resource("/comment",Comment::class);


    // reply 21 Feb 2022
    Route::resource("/reply",Reply::class);

    // profile
    Route::resource('/profile',Profile::class);
    Route::post('/confirm',[Profile::class,"userIsConfirmed"])
        ->name("userIsConfirmed");
//    // confirm password 
//    Route::post('/confirm-password',[USER::class,"userIsConfirmPassword"])
//        ->name("userIsConfirmPassword");

    // user 
    Route::resource("/user",User::class);

    /* Logout from member */
    Route::delete('/logout',[Login::class,'destroy'])->name('logout');

});

/*  #Teacher */
Route::prefix("teacher")->name("teacher.")->middleware('auth:sanctum')
                                        ->group(function(){

    Route::resource("/course",Course::class);

    Route::resource("/student",Student::class);

    Route::resource("/classroom",ClassRoom::class);

});


/* #Admin make a route prefix for Admin group */
Route::prefix("admin")->name("admin.")->middleware('auth:sanctum')
        ->group(function(){

    Route::resource('/dashboard',Admin::class);

    // defaultPage 
    Route::resource("/default",DefaultP::class);

    // what up 
    Route::resource('/whatup',Whatup::class);

    Route::resource('/profile',Profile::class);

    Route::get("/getcategory",[Cat::class,"getCategory"])
        ->name("getCategory");
    Route::resource("/category",Cat::class);

    // blog 9-Feb-2022
    Route::resource("/blog",Blog::class);

    // comment 21 Feb 2022
    Route::resource("/comment",Comment::class);
    Route::get('/blog-comment',[Comment::class,'getBlogCommentAs'])
        ->name('getBlogCommentAs');


    // reply 21 Feb 2022
    Route::resource("/reply",Reply::class);

    // tag 9-Feb-2022
    Route::resource("/tag",Tag::class);


    // Course 3-Mar-2022
    Route::resource("/course",Course::class);


    // Teacher 3-Mar-2022
    Route::resource("/teacher",Teacher::class);

    // whatup 
    Route::resource("/whatup",Whatup::class);

    // manage user route
    Route::resource("/user",User::class);
    Route::get('/getuser',[User::class,'getUser'])
        ->name('getUser');

});
