<?php

namespace App\Services;

use App\Models\Barber;

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
}
