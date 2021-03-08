<?php

namespace App\Http\Controllers;

use App\Services\BarberService;
use Illuminate\Http\Request;

class BarberController extends Controller
{
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    private $currentUser;

    /**
     * @var BarberService
     */
    private $barberService;

    /**
     * BarberController constructor.
     * @param BarberService $barberService
     */
    public function __construct(BarberService $barberService)
    {
        $this->middleware('auth:api');

        $this->currentUser = Auth()->user();
        $this->barberService = $barberService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        return response()->json($this->barberService->list($request->all()));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function read($id)
    {
        return response()->json($this->barberService->read($id));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function setAppointment(Request $request, $id)
    {
        return response()->json($this->barberService->setAppointment($id, $request->all()));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function search(Request $request)
    {
        return response()->json($this->barberService->search($request->input('q')));
    }
}
