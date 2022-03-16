<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Whatup extends Model
{
    use HasFactory;
    protected static $whatup_table = "whatups";

    protected $fillable = [
        "user_id",
        "wp_title",
        "wp_body",
        "is_public"
    ];

    protected $with = [
        "user",
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public static function backupWhatup($id,$cmd=false){
        // table 
        $table = static::$whatup_table;

        // file 
        $file = base_path("DB/whatup_list.sqlite");

        // data 
        $whatup = Whatup::find($id);

        // command
        $command = "";

        switch($cmd):
        case"insert":
            $command = "
/* ========================== Backup whatup id $id ==========================
 * on date ".date("Y-m-d H:i:s a")."
 * */
INSERT INTO `{$table}`(`user_id`,`wp_title`,`wp_body`,`is_public`,`created_at`,
`updated_at`) VALUES(
    '{$whatup->user_id}',
    '{$whatup->wp_title}',
    '{$whatup->wp_body}',
    '{$whatup->is_public}',
    '{$whatup->created_at}',
    '{$whatup->updated_at}');
";
        break;
case"edit":
    $command = "
/* ========================== update whatup id $id ==========================
 * on date ".date("Y-m-d H:i:s a")."
 * */
UPDATE `{$table}` SET wp_title='{$whatup->wp_title}',
wp_body='{$whatup->wp_body}',
is_public='{$whatup->is_public}',
updated_at='{$whatup->updated_at}' WHERE id='{$id}';";
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
