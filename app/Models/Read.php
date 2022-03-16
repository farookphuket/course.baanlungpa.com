<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Blog;

use DB;

class Read extends Model
{
    use HasFactory;

    protected static $blog_read_table = "blog_read";
    protected static $read_table = "reads";

    protected $fillable = [
        "ip","os","browser","device"
    ];



    public function blog(){
        return $this->belongsToMany(Blog::class);
    }

    public static function isBlogHasRead($blog_id){

        $blog_read = DB::table("blog_read")
                        ->where("blog_id",$blog_id)
                        ->where("ip",getUserIp())
                        ->whereDate("readed_at","=",date("Y-m-d"))
                        ->get();

        $get = Read::where("ip",getUserIp())
                    ->whereDate("created_at","=",date("Y-m-d"))
                    ->get();

        if(count($get) == 0 || count($blog_read) == 0):

            // prepare read data
            $r_data = [
                "os" => getUserOs(),
                "ip" => getUserIp(),
                "browser" => getUserBrowser(),
                "device" => getUserDevice()
            ];

            // save read data
            Read::create($r_data);
            // get the last record
            $read = Read::latest()->first();

            // backup item to file
            static::backupRead($read->id);


            // prepare read blog link data
            $b_link = [
                "ip" => getUserIp(),
                "readed_at" => now(),
                "blog_id" => $blog_id,
                "read_id" => $read->id
            ];

            // save read link
            DB::table("blog_read")
                    ->insert($b_link);


            // backup blog read link to file 
            static::backupBlogLink($blog_id);

        endif;

    }

    public static function backupRead($read_id){

        // table 
        $table = static::$read_table;

        // file 
        $file = base_path("DB/read_list.sqlite");

        $r_data = Read::find($read_id);

        $command = "
/* ======================= insert read id {$read_id}
 * on ".date("Y-m-d H:i:s a")."
 * */
INSERT INTO `{$table}` (`ip`,`os`,`device`,`browser`,`created_at`,
`updated_at`) VALUES(
    '{$r_data->ip}',
    '{$r_data->os}',
    '{$r_data->device}',
    '{$r_data->browser}',
    '{$r_data->created_at}',
    '{$r_data->updated_at}');
";
    
        write2text($file,$command);
    }

    public static function backupBlogLink($blog_id){
        // table 
        $table = static::$blog_read_table;

        // file 
        $file = base_path("DB/blog_read_link.sqlite");

        // command
        $command = "";

        // data 
        $b_data = DB::table($table)->where("blog_id",$blog_id)->get();

        if(count($b_data) >= 2):
            $command = "
/*  =========== Delete blog_id {$blog_id}
 *  as just to prevent from having the redundant blog_id */
DELETE FROM `{$table}` WHERE blog_id='{$blog_id}';
";

        foreach($b_data as $item):
            $command = "
/* ================ created link the read id '{$item->read_id}' =============
 * to blog_id '{$item->blog_id}' on ".date("Y-m-d H:i:s a")."
 * */
INSERT INTO `{$table}`(`blog_id`,`read_id`,`ip`,`readed_at`)VALUES(
'{$item->blog_id}',
'{$item->read_id}',
'{$item->ip}',
'{$item->readed_at}');
";

            //write2text($file,$command);
        endforeach;

        else:

        foreach($b_data as $item):
            $command = "
/* ================ created link the read id '{$item->read_id}' =============
 * to blog_id '{$item->blog_id}' on ".date("Y-m-d H:i:s a")."
 * */
INSERT INTO `{$table}`(`blog_id`,`read_id`,`ip`,`readed_at`)VALUES(
'{$item->blog_id}',
'{$item->read_id}',
'{$item->ip}',
'{$item->readed_at}');
";

            //write2text($file,$command);
        endforeach;

        endif;

            write2text($file,$command);

    }

}
