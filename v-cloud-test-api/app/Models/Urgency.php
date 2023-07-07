<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Urgency extends Model
{
    protected $table = 'urgency';
    public function deal()
    {
        return $this->belongsTo(Deal::class);

    }
}
