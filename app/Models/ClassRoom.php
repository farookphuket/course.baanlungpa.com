<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "class_title",
        "class_description",
        "class_level",
        "class_year",
    ];

    protected $with = ["user"];



    public function user(){
        return $this->belongsTo(User::class);
    }

    public function course(){
        return $this->belongsToMany(Course::class);
    }

}
