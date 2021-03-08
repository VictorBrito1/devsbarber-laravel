<?php

namespace App\Repositories;

use App\Models\Barber;
use Illuminate\Support\Facades\Validator;

class BarberRepository
{
    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws \Illuminate\Validation\ValidationException
     */
    public function findBarberWithRelationships($id)
    {
        Validator::make(['id' => $id], [
            'id' => 'required|exists:barbers'
        ])->validate();

        return Barber::with('photos', 'services', 'testimonials')
            ->where('id', $id)
            ->first();
    }
}
