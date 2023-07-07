<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function bids()
    {
        return $this->hasMany(Bid::class);
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function urgency()
    {
        return $this->hasOne(Urgency::class);
    }

    public function location()
    {
        return $this->hasOne(LocationDeal::class);
    }
}
