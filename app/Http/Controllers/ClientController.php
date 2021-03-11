<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cars;
use App\RepairManager;
use Auth;

class ClientController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $Cars = Cars::select('serviceorder.id as orderid',
            'cars.make as make',
            'cars.model as model',
            'cars.make_year as make_year',
            'serviceorder.schedule as schedule',
            'serviceorder.note as note',
            'servicestatus.title as title')->where('user_id', Auth::user()->id)
            ->join('serviceorder','cars.id','=','serviceorder.car_id')
            ->join('servicestatus','serviceorder.status','=','servicestatus.id')
            ->orderBy('serviceorder.id','DESC')
            ->get();

        return view('client.summary',['cars' => $Cars])->withModel(RepairManager::class);
    }
}
