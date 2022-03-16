<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected static $course_table = 'courses';

    protected $fillable = [
        "user_id",
        "course_year",
        "course_term",
        "course_id",
        "course_credit",
        "course_name",
        "course_cover",
        "course_excerpt",
        "course_body",
        "course_hours",
        "course_is_free",
        "course_is_public",
        "released_at",
    ];

    protected $with = ["user"];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function teacher(){
        return $this->belongsToMany(Teacher::class);
    }

    public function classroom(){
        return $this->belongsToMany(ClassRoom::class);
    }

}
