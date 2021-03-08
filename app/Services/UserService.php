<?php

namespace App\Services;

use App\Models\Barber;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class UserService
{
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    private $currentUser;

    /**
     * UserService constructor.
     */
    public function __construct()
    {
        $this->currentUser = auth()->user();
    }

    /**
     * @param $data
     * @return User
     */
    public function create($data)
    {
        $user = new User();
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->name = $data['name'];
        $user->save();

        return $user;
    }

    /**
     * @param $data
     * @return bool
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createAndLogin($data)
    {
        Validator::make($data, [
            'email' => ['string', 'email', 'required', 'unique:users'],
            'password' => ['string', 'required', 'min:6', 'confirmed'],
            'name' => ['string', 'required'],
        ])->validate();

        $this->create($data);

        return Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password']
        ]);
    }

    /**
     * @param $avatar
     * @param $currentUser
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\UrlGenerator|string
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateAvatar($avatar, $currentUser)
    {
        Validator::make(['avatar' => $avatar], [
            'avatar' => 'required|image|mimetypes:image/jpeg,image/jpg,image/png',
        ])->validate();

        $path = public_path('/media/avatars');
        $filename = md5(time().rand(0, 9999)) . '.jpg';

        Image::make($avatar->getRealPath())
            ->fit(300, 300)
            ->save("{$path}/{$filename}");

        $currentUser->avatar = $filename;
        $currentUser->save();

        return url("/media/avatars/{$filename}");
    }

    /**
     * @param $barberId
     * @return bool[]
     * @throws \Illuminate\Validation\ValidationException
     */
    public function favorite($barberId)
    {
        Validator::make(['barber_id' => $barberId], [
            'barber_id' => 'required|exists:barbers,id'
        ])->validate();

        $barber = Barber::find($barberId);

        if ($this->currentUser->favorites()->find($barberId)) {
            $this->currentUser->favorites()->detach($barber);
            $favorited = false;
        } else {
            $this->currentUser->favorites()->attach($barber);
            $favorited = true;
        }

        return ['favorited' => $favorited];
    }
}
