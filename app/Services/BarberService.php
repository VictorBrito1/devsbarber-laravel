<?php

namespace App\Services;

use App\Models\Barber;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BarberService
{
    /**
     * @var ImageService
     */
    private $imageService;

    /**
     * BarberService constructor.
     * @param ImageService $imageService
     */
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * @param $data
     * @return Barber
     */
    public function create($data)
    {
        $barber = new Barber();
        $barber->name = $data['name'];
        $barber->longitude = $data['longitude'] ?? null;
        $barber->latitude = $data['latitude'] ?? null;
        $barber->save();

        return $barber;
    }

    /**
     * @param $avatar
     * @param $barberId
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function updateAvatar($avatar, $barberId)
    {
        $barber = Barber::find($barberId);

        if (!$barber) {
            throw new NotFoundHttpException(null, null, 0, ['errors' => 'Barber not found.']);
        }

        $filename = $this->imageService->save($avatar, '/media/avatars', 200, 200);

        $barber->avatar = $filename;
        $barber->save();

        return url("/media/avatars/{$filename}");
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $barber = Barber::find($id);

        if (!$barber) {
            throw new NotFoundHttpException(null, null, 0, ['errors' => 'Barber not found.']);
        }

        $barber->delete();

        return true;
    }
}
