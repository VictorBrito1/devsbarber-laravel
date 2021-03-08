<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barber extends Model
{
    use HasFactory;

    protected $table = 'barbers';
    public $timestamps = false;
    const BARBER_KEY = 'barber_id';

    /**
     * @param $value
     * @return string
     */
    public function getAvatarAttribute($value): string
    {
        return url("media/avatars/{$value}");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appointments()
    {
        return $this->hasMany(UserAppointment::class, 'barber_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos()
    {
        return $this->hasMany(BarberPhoto::class, self::BARBER_KEY);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(BarberReview::class, self::BARBER_KEY);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function services()
    {
        return $this->hasMany(BarberService::class, self::BARBER_KEY);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testimonials()
    {
        return $this->hasMany(BarberTestimonial::class, self::BARBER_KEY);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function availabilities()
    {
        return $this->hasMany(BarberAvailability::class, self::BARBER_KEY);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favorites()
    {
        return $this->belongsToMany(User::class, 'user_favorites', 'barber_id', 'user_id');
    }
}
