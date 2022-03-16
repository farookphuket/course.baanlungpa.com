<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DB;
use App\Models\Blog;

class Category extends Model
{
    use HasFactory;

    protected static $cat_table = "categories";
    protected static $blog_link_table = "blog_category";

    protected $fillable = [
        "is_allow_edit",
        "user_id",
        "cat_name",
        "cat_slug"
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function blog(){
        return $this->belongsToMany(Blog::class);
    }


    public static function backupCategory($cat_id,$cmd=false){
        // file 
        $file = base_path("DB/category_list.sqlite");

        // table 
        $table = static::$cat_table;

        // data 
        $cat = Category::find($cat_id);

        $command = "";

        // switch case to specify wheather insert,update or delete
        switch($cmd):
        case"insert":
            $command = "
/* ======================= insert cat id {$cat_id} ===========================
 * on ".date("Y-m-d H:i:s a")."
 * */
INSERT INTO `{$table}`(`user_id`,`cat_name`,`cat_slug`,`is_allow_edit`,
`created_at`,`updated_at`)
VALUES(
    '{$cat->user_id}',
    '{$cat->cat_name}',
    '{$cat->cat_slug}',
    '{$cat->is_allow_edit}',
    '{$cat->created_at}',
    '{$cat->updated_at}');
";
            break;
        case"edit":
            $command = "
/* ======================= update cat id {$cat_id} ===========================
 * on ".date("Y-m-d H:i:s a")."
 * */
UPDATE `{$table}` SET cat_name='{$cat->cat_name}',
cat_slug='{$cat->cat_slug}',
is_allow_edit='{$cat->is_allow_edit}',
updated_at='{$cat->updated_at}' WHERE id='{$cat_id}';
"; 
            break;
            default:
            $command = "
/* ======================= Delete cat id {$cat_id} ===========================
 * on ".date("Y-m-d H:i:s a")."
 * */
/* DELETE FROM `{$table}` WHERE id='{$cat_id}'; */
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
        $file = base_path("DB/blog_category_link.sqlite");

        // command 
        $command = "";
        // data 
        $bl = DB::table($table)->where("blog_id",$blog_id)->get();
        if(count($bl) >= 2):
            $command = "
DELETE FROM `{$table}` WHERE blog_id='{$blog_id}';
";
            foreach($bl as $item):
                $command = "
/* ================= INSERT command for blog id {$blog_id} 
 * on ".date("Y-m-d H:i:s a")."
 * */
INSERT INTO `{$table}`(`blog_id`,`category_id`) VALUES('$item->blog_id',
'$item->category_id');
";
       
              //  write2text($file,$command);

            endforeach;
        else:

            foreach($bl as $item):
                $command = "
/* ================= INSERT command for blog id {$blog_id} 
 * on ".date("Y-m-d H:i:s a")."
 * */
INSERT INTO `{$table}`(`blog_id`,`category_id`) VALUES('$item->blog_id',
'$item->category_id');
";
       
               // write2text($file,$command);

            endforeach;
        endif;

                write2text($file,$command);

    }



}
