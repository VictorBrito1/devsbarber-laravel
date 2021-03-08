<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarberPhoto extends Model
{
    use HasFactory;

    protected $table = 'barber_photos';
    public $timestamps = false;

    /**
     * @param $value
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getUrlAttribute($value)
    {
        return url("media/avatars/{$value}");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function barber()
    {
        return $this->belongsTo(Barber::class, 'barber_id');
    }
}
