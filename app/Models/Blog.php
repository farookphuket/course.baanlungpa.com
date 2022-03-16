<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Tag;
use App\Models\Category;
use DB;

class Blog extends Model
{
    use HasFactory;

    // custom table 
    protected static $blog_table = "blogs";

    protected $fillable = [
        "user_id",
        "category_id",
        "bl_title",
        "bl_slug",
        "bl_excerpt",
        "bl_body",
        "bl_is_public",
    ];

    protected $with = [
        "category","tag","user","read",
        
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function category(){
        return $this->belongsToMany(Category::class);
    }

    public function tag(){
        return $this->belongsToMany(Tag::class);
    }


    public function comment(){
        return $this->belongsToMany(Comment::class);
    }

    public function reply(){
        return $this->belongsToMany(Reply::class);
    }

    public function read(){
        return $this->belongsToMany(Read::class);
    }

    public static function backupBlog($blog_id,$cmd=false){
        // table 
        $table = static::$blog_table;

        // file 
        $file = base_path("DB/blog_list.sqlite");

        // data 
        $bl_data = Blog::find($blog_id);

        // command
        $command = "";

        switch($cmd):
        case"insert":
            $command = "
/* ======================= insert Blog id {$blog_id} ========================
 * on ".date("Y-m-d H:i:s a")."
 *
 * */
INSERT INTO `{$table}`(`user_id`,`bl_title`,`bl_slug`,`bl_excerpt`,
`bl_is_public`,`bl_body`,`created_at`,`updated_at`) VALUES(
    '{$bl_data->user_id}',
    '{$bl_data->bl_title}',
    '{$bl_data->bl_slug}',
    '{$bl_data->bl_excerpt}',
    '{$bl_data->bl_is_public}',
    '{$bl_data->bl_body}',
    '{$bl_data->created_at}',
    '{$bl_data->updated_at}');
";
        // backup tag link to this post
        Tag::backupBlogLink($blog_id);


        // backup category link to this post
        Category::backupBlogLink($blog_id);

        break;
case"edit":
    $command = "
/* ======================= update Blog id {$blog_id} ========================
 * on ".date("Y-m-d H:i:s a")."
 *
 * */
UPDATE `{$table}` SET bl_title='{$bl_data->bl_title}',
bl_slug='{$bl_data->bl_slug}',
bl_excerpt='{$bl_data->bl_excerpt}',
bl_body='{$bl_data->bl_body}',
bl_is_public='{$bl_data->bl_is_public}',
updated_at='{$bl_data->updated_at}' WHERE id='{$blog_id}';
";
        
            // backup tag link to this post
            Tag::backupBlogLink($blog_id);
            
            // backup category link to this post
            Category::backupBlogLink($blog_id);
        break;
default:
    $command ="
/* ======================= DELETE Blog id {$blog_id} ========================
 * on ".date("Y-m-d H:i:s a")."
 *
 * */
/* DELETE FROM `{$table}` WHERE id='{$blog_id}'; */
";

    break;
    endswitch;

        
        write2text($file,$command);
    }

}
