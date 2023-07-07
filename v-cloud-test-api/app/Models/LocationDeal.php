<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationDeal extends Model
{
    protected $table = 'location_deal';
    public function deal()
    {
        return $this->belongsTo(Deal::class);

    }
}
