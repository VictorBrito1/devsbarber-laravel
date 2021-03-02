<?php

namespace Database\Factories;

use App\Models\Barber;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarberFactory extends Factory
{
    protected $model = Barber::class;

    /**
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'avatar' => rand(1, 4).'.png',
            'stars' => rand(2, 4).'.'.rand(0,9),
            'latitude' => '-23.5'.rand(0,9).'30907',
            'longitude' => '-46.6'.rand(0,9).'82795',
        ];
    }
}
