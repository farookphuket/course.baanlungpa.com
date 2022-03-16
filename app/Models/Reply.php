<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DB;

class Reply extends Model
{
    use HasFactory;

    protected static $reply_table = "replies";
    protected static $blog_reply_table = "blog_reply";
    protected static $comment_link_table = "comment_reply";

    protected $with = [
        "user","comment"
    ];

    protected $fillable = [
        "user_id","comment_id","re_title","re_body"
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comment(){
        return $this->belongsToMany(Comment::class);
    }

    public function blog(){
        return $this->belongsToMany(Blog::class);
    }






    public static function backupReply($reply_id,$cmd=false){
        // table
        $table = static::$reply_table;

        // file 
        $file = base_path("DB/reply_list.sqlite");

        // data 
        $re = Reply::find($reply_id);

        // command 
        $command = "";

        // switch case 
        switch($cmd):
            case"insert":
            $command = "
/* ============================== INSERT Reply id  '{$reply_id}' ==========
 * on ".date("Y-m-d H:i:s a")."
 * */
INSERT INTO `{$table}`(`user_id`,`re_title`,`re_body`,`created_at`,
`updated_at`)VALUES(
    '{$re->user_id}',
    '{$re->re_title}',
    '{$re->re_body}',
    '{$re->created_at}',
    '{$re->updated_at}');
";
            break;
            case"edit":
    $command = "
/* ============================== UPDATE Reply id  '{$reply_id}' ==========
 * on ".date("Y-m-d H:i:s a")."
 * */
UPDATE `$table` SET co_title='{$re->re_title}',
re_body='{$re->re_body}',
updated_at='{$re->updated_at}' WHERE id='{$reply_id}';
";
            break;
            default:
    $command = "
/* ============================== DELETE Comment id  '{$reply_id}' ==========
 * on ".date("Y-m-d H:i:s a")."
/* DELETE FROM `{$table}` WHERE id='{$reply_id}'; */ 
";
            break;
        endswitch;

        // write to text
        write2text($file,$command);

    }

    public static function backupBlogLink($blog_id){
        // table 
        $table = static::$blog_reply_table;

        // file 
        $file = base_path("DB/blog_reply_link.sqlite");

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
 * link reply id '{$it->reply_id}' to blog id '{$blog_id}' 
 * on ".date("Y-m-d H:i:s a")."
 * */
INSERT INTO `{$table}`(`blog_id`,`reply_id`) VALUES(
    '{$it->blog_id}',
    '{$it->reply_id}');
";
        endforeach;

        write2text($file,$command);
    }


    public static function backupCommentLink($comment_id){
        // table 
        $table = static::$comment_link_table;

        // file 
        $file = base_path("DB/reply_comment_link.sqlite");

        // comment 
        $bl = DB::table($table)->where("comment_id",$comment_id)->get();

        // command 
        $command = "";

        if(count($bl) >= 2):
            $command = "
/* ============================== Delete comment link id '{$comment_id}' ========
 * on ".date("Y-m-d H:i:s a")."
 * */
DELETE FROM `{$table}` WHERE comment_id='{$comment_id}';
";
        endif;

        foreach($bl as $it):
            $command = "
/* ========================== INSERT comment link '{$comment_id}' ==================
 * link reply id '{$it->reply_id}' to comment id '{$comment_id}' 
 * on ".date("Y-m-d H:i:s a")."
 * */
INSERT INTO `{$table}`(`comment_id`,`reply_id`) VALUES(
    '{$it->comment_id}',
    '{$it->reply_id}');
";
        endforeach;

        write2text($file,$command);
    }

}
