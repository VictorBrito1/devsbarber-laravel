<?php

namespace Database\Factories;

use App\Models\BarberService;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarberServiceFactory extends Factory
{
    protected $model = BarberService::class;

    /**
     * @return array
     */
    public function definition()
    {
        $servicesNames = ['Corte de cabelo', 'Pintura de unha', 'Progressiva', 'Limpeza de Pele', 'Corte Feminino', 'Barba'];

        return [
            'name' => $servicesNames[rand(0, count($servicesNames)-1)],
            'price' => $this->faker->numberBetween($min = 10, $max = 100),
        ];
    }
}
