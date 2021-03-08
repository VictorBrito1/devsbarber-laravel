<?php

namespace App\Services;

use App\Models\Barber;
use Illuminate\Support\Facades\Validator;

class BarberService
{
    /**
     * @param $requestData
     * @return mixed
     */
    public function list($requestData)
    {
        $lat = $requestData['lat'] ?? null;
        $lng = $requestData['lng'] ?? null;
        $city = $requestData['city'] ?? null;
        $offset = $requestData['offset'] ?? 0;

        if ($city) {
            $res = $this->searchGeo($city);

            if ($res && count($res['results']) > 0) {
                $lat = $res['results'][0]['geometry']['location']['lat'];
                $lng = $res['results'][0]['geometry']['location']['lng'];
            }
        } elseif ($lat && $lng) {
            $res = $this->searchGeo("$lat,$lng");

            if ($res && count($res['results']) > 0) {
                $city = $res['results'][0]['formatted_address'];
            }
        } else {
            $lat = '-23.5630907';
            $lng = '-46.6682795';
            $city = 'São Paulo';
        }

        $barbers = Barber::select(Barber::raw("*, SQRT(
            POW(69.1 * (latitude - $lat), 2) +
            POW(69.1 * ($lng - longitude) * COS(latitude / 57.3), 2)) AS distance"))
            ->havingRaw('distance < ?', [10]) //Nearest
            ->orderBy('distance', 'ASC')
            ->offset($offset)
            ->limit(5)
            ->get();

        $data['barbers'] = $barbers;
        $data['loc'] = $city;

        return $data;
    }

    /**
     * @param $address
     * @return \Illuminate\Http\JsonResponse
     */
    private function searchGeo($address)
    {
        $key = env('MAPS_KEY', null);

        $address = urlencode($address);
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=$key";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($curl);
        curl_close($curl);

        return json_decode($res, true);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function read($id)
    {
        Validator::make(['id' => $id], [
            'id' => 'required|exists:barbers'
        ])->validate();

        $barber = Barber::with('photos', 'services', 'testimonials')
            ->where('id', $id)
            ->first();

        $barber['favorited'] = $barber->favorites()->find(auth()->user()->id) ? true : false;
        $barber = $this->populateBarberAvailability($barber);

        return $barber;
    }

    /**
     * @param $barber
     * @return mixed
     */
    private function populateBarberAvailability($barber)
    {
        $availWeekdays = [];

        foreach ($barber->availabilities as $item) {
            $availWeekdays[$item['weekday']] = explode(',', $item['hours']);
        }

        $checkAvailabilityDays = 20;

        $barberAppointments = $barber->appointments()
            ->whereBetween('appointment_at', [
                date('Y-m-d').' 00:00:00',
                date('Y-m-d', strtotime("+$checkAvailabilityDays days")).' 23:59:59' //Next 20 days
            ])
            ->get('appointment_at')
            ->map(function ($item) {
                return $item->appointment_at;
            })
            ->toArray();

        $availability = [];

        //Next 20 days
        for ($i = 0; $i < $checkAvailabilityDays; $i++) {
            $timeItem = strtotime("+$i days");
            $weekDay = date('w', $timeItem); //0 - Monday, 1 - Tuesday ...

            //If the week of the current loop is within the weeks that the barber has availability
            if (in_array($weekDay, array_keys($availWeekdays))) {
                $hours = [];
                $currentLoopDate = date('Y-m-d', $timeItem);

                foreach ($availWeekdays[$weekDay] as $hourItem) {
                    $currentLoopDateFormated = "$currentLoopDate $hourItem:00";

                    //If the date of the current loop is not scheduled for the barber
                    if (!in_array($currentLoopDateFormated, $barberAppointments)) {
                        $hours[] = $hourItem;
                    }
                }

                if ($hours) {
                    $availability[] = [
                        'date' => $currentLoopDate,
                        'hours' => $hours,
                    ];
                }
            }
        }

        $barber['availability'] = $availability;

        return $barber;
    }
}
