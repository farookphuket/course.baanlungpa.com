<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "class_room_id",
        "studied_at",
        "successed_at"
    ];

    public function teacher(){
        return $this->belongsToMany(Teacher::class);
    }

    public function classroom(){
        return $this->belongsTo(ClassRoom::class);
    }
}
