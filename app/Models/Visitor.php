<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Visitor extends Model
{
    use HasFactory;

    protected static $visitor_table = "visitors";

    protected $fillable = [
        'os','ip','browser','visited_at','device'
    ];


    
    public static function backupVisitor($id,$cmd=false){
        // table 
        $table = static::$visitor_table;

        // file 
        $file = base_path("DB/visitor_list.sqlite");

        // data 
        $visitor = Visitor::find($id);

        // command
        $command = "";

        switch($cmd):
        case"insert":
            $command = "
/* ========================== Backup visitor id $id ==========================
 * on date ".date("Y-m-d H:i:s a")."
 * */
INSERT INTO `{$table}`(`ip`,`os`,`browser`,`device`,`visited_at`,`created_at`,
`updated_at`) VALUES(
    '{$visitor->ip}',
    '{$visitor->os}',
    '{$visitor->browser}',
    '{$visitor->device}',
    '{$visitor->visited_at}',
    '{$visitor->created_at}',
    '{$visitor->updated_at}');
";
        break;
case"edit":
    $command = "
/* ========================== update visitor id $id ==========================
 * on date ".date("Y-m-d H:i:s a")."
 * */
UPDATE `{$table}` SET ip='{$visitor->ip}',
os='{$visitor->os}',
browser='{$visitor->browser}',
device='{$visitor->device}',
updated_at='{$visitor->updated_at}' WHERE id='{$id}';";
    break;
default:
    $command = "
/* ========================== DEFAULT DELETE visitor id $id ===================
 * on date ".date("Y-m-d H:i:s a")."
 * command not execute just to prevent from Error on seeding
 * */
/* DELETE FROM `{$table}` WHERE id='{$id}'; */
";
    break;
    endswitch;
        write2text($file,$command);
    }

}
