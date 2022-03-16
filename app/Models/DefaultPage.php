<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultPage extends Model
{

    use HasFactory;

    protected $fillable = [
        "user_id","d_title","d_slug","d_is_public","d_body"
    ];

    protected $with = ["user"];


    public function user(){
        return $this->belongsTo(User::class);
    }
}
