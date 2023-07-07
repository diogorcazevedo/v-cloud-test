<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'photos';
    protected $fillable = [
        'src',
        'deal_id',
    ];
    public function deal()
    {
        return $this->belongsTo(Deal::class);

    }
}
