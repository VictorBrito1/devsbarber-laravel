<?php

namespace Database\Seeders;

use App\Models\Barber;
use App\Models\BarberAvailability;
use App\Models\BarberPhoto;
use App\Models\BarberService;
use App\Models\BarberTestimonial;
use App\Models\User;
use Illuminate\Database\Seeder;

class BarberSeeder extends Seeder
{
    public function run()
    {
        $user1 = User::where('email', '=', 'test1@test.com')->first();
        $user2 = User::where('email', '=', 'test2@test.com')->first();

        Barber::factory(15)->create()->each(function (Barber $barber) use ($user1, $user2) {
            $barber->services()->saveMany(BarberService::factory(3)->make());

            for ($i = 1; $i <= 2; $i++) {
                $user = $i === 1 ? $user1 : $user2;

                $barberTestimonial = new BarberTestimonial();
                $barberTestimonial->barber()->associate($barber);
                $barberTestimonial->user()->associate($user);
                $barberTestimonial->body = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.';
                $barberTestimonial->rate = rand(2, 4) . '.' . rand(0, 9);
                $barberTestimonial->save();

                $barberPhoto = new BarberPhoto();
                $barberPhoto->barber()->associate($barber);
                $barberPhoto->url = rand(1, 5) . '.png';
                $barberPhoto->save();

                $rAdd = rand(7, 10);
                $hours = [];

                for($r = 0; $r < 8; $r++) {
                    $time = $r + $rAdd;

                    if ($time < 10) {
                        $time = '0'.$time;
                    }

                    $hours[] = $time . ':00';
                }

                $barberAvailability = new BarberAvailability();
                $barberAvailability->barber()->associate($barber);
                $barberAvailability->weekday = $i + rand(0, 3);
                $barberAvailability->hours = implode(',', $hours);
                $barberAvailability->save();
            }
        });
    }
}
