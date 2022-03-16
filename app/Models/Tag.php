<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Blog;

use DB;

class Tag extends Model
{

    // custom backup table 
    protected static $tag_table = "tags";
    protected static $blog_link_table = "blog_tag";
    use HasFactory;
    protected $fillable = [
        "tag_name",

    ];


    public function blog(){
        return $this->belongsToMany(Blog::class);
    }


    public static function backupTag($tag_id,$cmd=false){
        // file 
        $file = base_path("DB/tag_list.sqlite");

        // table 
        $table = static::$tag_table;

        // data 
        $tag = Tag::find($tag_id);

        $command = "";

        // switch case to specify wheather insert,update or delete
        switch($cmd):
        case"insert":
            $command = "
/* ======================= insert tag id {$tag_id} ===========================
 * on ".date("Y-m-d H:i:s a")."
 * */
INSERT INTO `{$table}`(`tag_name`,`created_at`,`updated_at`)
VALUES(
    '{$tag->tag_name}',
    '{$tag->created_at}',
    '{$tag->updated_at}');
";
            break;
        case"edit":
            $command = "
/* ======================= update tag id {$tag_id} ===========================
 * on ".date("Y-m-d H:i:s a")."
 * */
UPDATE `{$table}` SET tag_name='{$tag->tag_name}',
updated_at='{$tag->updated_at}' WHERE id='{$tag_id}';
"; 
            break;
            default:
            $command = "
/* ======================= Delete tag id {$tag_id} ===========================
 * on ".date("Y-m-d H:i:s a")."
 * */
/* DELETE FROM `{$table}` WHERE id='{$tag_id}'; */
";
            break;
        endswitch;
        // write to file
        write2text($file,$command);
    }

    public static function backupBlogLink($blog_id){
        //table 
        $table = static::$blog_link_table;

        // file 
        $file = base_path("DB/blog_tag_link.sqlite");

        // command 
        $command = "";
        // data 
        $bl = DB::table($table)->where("blog_id",$blog_id)->get();

        // there will be no 0 value
        if(count($bl) >= 2):
            $command = "
DELETE FROM `{$table}` WHERE blog_id='{$blog_id}';
";
            foreach($bl as $item):
                $command = "
/* ================= INSERT command for blog id {$blog_id} 
 * on ".date("Y-m-d H:i:s a")."
 * */
INSERT INTO `{$table}`(`blog_id`,`tag_id`) VALUES('$item->blog_id','$item->tag_id');
";
       
            //    write2text($file,$command);

            endforeach;
        else:

            foreach($bl as $item):
                $command = "
/* ================= INSERT command for blog id {$blog_id} 
 * on ".date("Y-m-d H:i:s a")."
 * */
INSERT INTO `{$table}`(`blog_id`,`tag_id`) VALUES('$item->blog_id','$item->tag_id');
";
       
             //   write2text($file,$command);

            endforeach;
        endif;

        write2text($file,$command);

    }

}
