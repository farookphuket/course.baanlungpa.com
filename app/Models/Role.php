<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Role extends Model
{
    use HasFactory;



    public function user(){
        return $this->belongsToMany(User::class);
    }

        /* ======================= Backup section =================================
     *
     * */
    public static function backupRole($role_id,$cmd=false){

        // table
        $table = static::$role_table;

        // file
        $file = base_path("DB/role_list.sqlite");

        // data
        $r = Role::find($role_id);

        // command
        $command = "";

        switch($cmd):
            case"insert":
            $command = "
/* ============================ INSERT COMMAND role id {$role_id} =============
 * on date ".date("Y-m-d H:i:s a")."
 * */
INSERT INTO `{$table}`(`role_name`,`created_at`,`updated_at`) VALUES(
    '{$r->role_name}',
    '{$r->created_at}',
    '{$r->updated_at}');
";
            break;
            case"edit":
                $command = "
/* ====================== Update Command for role id {$role_id} ===============
 * on date ".date("Y-m-d H:i:s a")."
 * */
UPDATE `{$table}` SET role_name='{$r->role_name}',
updated_at='{$r->updated_at}' WHERE id='{$role_id}';
";
                break;
            default:
    $command = "
/* =========================== Default Delete command for {$role_id} ==========
 * on  date ".date("Y-m-d H:i:s a")."
 * */
/* DELETE FROM `{$table}` WHERE id='{$rolw_id}'; */
";
            break;
    endswitch;

        write2text($file,$command);
    }

    /* ======================= End of Backup section ==========================
     *
     * */
}
