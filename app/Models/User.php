<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'email_verified_at'
    ];

    protected $with = ["role"];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /* custom */
    protected static $user_table = "users";
    protected static $user_role_table = "role_user";

    public function whatup(){
        return $this->HasMany(Whatup::class);
    }

    public function defaultpage(){
        return $this->hasMany(DefaultPage::class);
    }

    public function role(){
        return $this->belongsToMany(Role::class);
    }


    public function category(){
        return $this->hasMany(Category::class);
    }

    public function blog(){
        return $this->hasMany(Blog::class);
    }

    public function classroom(){
        return $this->hasMany(ClassRoom::class);
    }

    public function teacher(){
        return $this->hasMany(Teacher::class);
    }


    public static function backupUser($user_id,$cmd=false){
        // table 
        $table = static::$user_table;

        // file 
        $file = base_path("DB/user_list.sqlite");

        // data 
        $u = User::find($user_id);

        // command 
        $command = '';

        // default to delete command
        switch($cmd):
        case"insert":
            $command = " 
/* =========================== Insert new id {$user_id} ======================
 * on ".date("Y-m-d H:i:s a")." START 
 * */
INSERT INTO `{$table}`(`name`,`email`,`password`,`email_verified_at`,`is_admin`,
`created_at`,`updated_at`)VALUES(
    '{$u->name}',
    '{$u->email}',
    '$u->password',
    '{$u->email_verified_at}',
    '{$u->is_admin}',
    '{$u->created_at}',
    '{$u->updated_at}');
/* ============================================================================
 *                  END OF INSERT COMMAND FOR user id {$user_id}
 * ============================================================================
 * */
";
            break;
        case"edit":
            $command = "
/* =========================== Update id {$user_id} ===========================
 * on ".date("Y-m-d H:i:s a")."
 * ============================================================================
 * */
UPDATE `{$table}` SET name='{$u->name}',
password='{$u->password}', 
email='{$u->email}',
email_verified_at='{$u->email_verified_at}',
updated_at='{$u->updated_at}' WHERE id='{$user_id}';
/* ============================================================================
 *                      END UPDATE COMMAND FOR user id {$user_id}
 * ============================================================================
 * */
";
            break;
        default:
            $command = "
/* ========================== delete user {$user_id} =========================
 * on ".date("Y-m-d H:i:s a")."
 * will be comment delete command just to prevent from having Error
 * */
/* DELETE FROM {$table} WHERE id={$user_id}; */
/* ============================================================================
 *                  END OF DELETE COMMAND FOR user id {$user_id}
 * ============================================================================
 * */
";
            break;
            endswitch;
        write2text($file,$command);

        // backup role for this user 
        static::backupRoleUser($user_id);
    }

    public static function backupRoleUser($user_id){
        /*
         * the user's role will not be edit as user can have more than one 
         * role.
         * so if the first query find some row of the particular user id 
         * will be delete then re-insert again
         * */

        // table 
        $table = static::$user_role_table;

        // file 
        $file = base_path("DB/user_role_link.sqlite");

        // data  
        $r = DB::table($table)->where('user_id',$user_id)->get();

        // command
        $command = "";

        if(count($r) != 0):
            // delete this row 
            $command = "
/* =============================== Delete user id {$user_id} ==================
 * the user id {$user_id} will delete for re-create on ".date("Y-m-d H:i:s a")." 
 * START
 * */
DELETE FROM `{$table}` WHERE user_id='{$user_id}';
/* ============================================================================
 *                  Delete Command END
 * ============================================================================
 * */
";    
        endif;
        // create role for user as user can have more than one roles
        foreach($r as $item):
            $command = "
/* ============================= INSERT COMMAND for {$user_id} ================
 * on date ".date("Y-m-d H:i:s a")."
 * START
 * ============================================================================
 * */
INSERT INTO `{$table}` (`user_id`,`role_id`)VALUES(
    '{$item->user_id}',
    '{$item->role_id}');
/* ============================================================================
*                   INSERT COMMAND for user id {$user_id} END
* =============================================================================
* */
";
        endforeach;

        write2text($file,$command);
    }


}
