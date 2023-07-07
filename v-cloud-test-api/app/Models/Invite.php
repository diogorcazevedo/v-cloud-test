<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    protected $table = 'invites';


    public function host()
    {
        return $this->belongsTo(User::class,'user_invited');
    }

    public function guest()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
