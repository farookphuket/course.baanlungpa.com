<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Blog;

use DB;


class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "co_title",
        "co_body",
    ];

    protected $with = [
        "user"
    ];

    protected static $comment_table = "comments";
    protected static $blog_comment_table = "blog_comment";


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function blog(){
        return $this->belongsToMany(Blog::class);

    }

    public function reply(){
        return $this->belongsToMany(Reply::class);
    }


    public static function backupComment($comment_id,$cmd=false){
        // table
        $table = static::$comment_table;

        // file 
        $file = base_path("DB/comment_list.sqlite");

        // data 
        $co = Comment::find($comment_id);

        // command 
        $command = "";

        // switch case 
        switch($cmd):
            case"insert":
            $command = "
/* ============================== INSERT Comment id  '{$comment_id}' ==========
 * on ".date("Y-m-d H:i:s a")."
 * */
INSERT INTO `{$table}`(`user_id`,`co_title`,`co_body`,`created_at`,
`updated_at`)VALUES(
    '{$co->user_id}',
    '{$co->co_title}',
    '{$co->co_body}',
    '{$co->created_at}',
    '{$co->updated_at}');
";
            break;
            case"edit":
    $command = "
/* ============================== UPDATE Comment id  '{$comment_id}' ==========
 * on ".date("Y-m-d H:i:s a")."
 * */
UPDATE `$table` SET co_title='{$co->co_title}',
co_body='{$co->co_body}',
updated_at='{$co->updated_at}' WHERE id='{$comment_id}';
";
            break;
            default:
    $command = "
/* ============================== DELETE Comment id  '{$comment_id}' ==========
 * on ".date("Y-m-d H:i:s a")."
/* DELETE FROM `{$table}` WHERE id='{$comment_id}'; */ 
";
            break;
        endswitch;

        // write to text
        write2text($file,$command);

    }

    public static function backupBlogLink($blog_id){
        // table 
        $table = static::$blog_comment_table;

        // file 
        $file = base_path("DB/blog_comment_link.sqlite");

        // blog 
        $bl = DB::table($table)->where("blog_id",$blog_id)->get();

        // command 
        $command = "";

        if(count($bl) >= 2):
            $command = "
/* ============================== Delete blog link id '{$blog_id}' ========
 * on ".date("Y-m-d H:i:s a")."
 * */
DELETE FROM `{$table}` WHERE blog_id='{$blog_id}';
";
        endif;

        foreach($bl as $it):
            $command = "
/* ========================== INSERT blog link '{$blog_id}' ==================
 * link comment id '{$it->comment_id}' to blog id '{$blog_id}' 
 * on ".date("Y-m-d H:i:s a")."
 * */
INSERT INTO `{$table}`(`blog_id`,`comment_id`) VALUES(
    '{$it->blog_id}',
    '{$it->comment_id}');
";
        endforeach;

        write2text($file,$command);
    }
}
