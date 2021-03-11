<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\CarsManager;
use App\OrderManager;
use App\User;
use App\Servicestatus;
use App\Serviceorder;
use App\RepairManager;
use Validator;
use Log;

class OrderController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Списък със заявки
    public function index(Request $request)
    {
        return view('orders.list', ['orders' => Serviceorder::viewClients()->get()])->withModel(RepairManager::class);
    }

    public function show(Request $request)
    {
        $request->merge(['user' => $request->order]);
        $validator = Validator::make($request->all(), [
            'user' => ['required', 'integer', 'exists:users,id'],
        ], [
            'user.exists' => 'Този клиент не съществува !',
            'user.required' => 'Не е посочен клиент !',
            'user.integer' => 'ID на клиента трябва да е число !',
        ]);

        if ($validator->fails()) {
            return ['message' => $validator->errors()->first()];
        }

        $cars = new CarsManager();
        $reserved = new OrderManager();
        return view('orders.form', [
            'user' => User::where('id', $request->user)->first(),
            'cars' => $cars->SetUser($request->user)->ListCars(),
            'status' => Servicestatus::all(),
            'reserved' => $reserved->schedule()
        ]);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'car_id' => ['required', 'integer', Rule::unique('serviceorder')
                ->whereNotIn('serviceorder.status', [3]), 'exists:cars,id'],
            'datepicker' => ['required', 'date_format:Y-m-d', 'after_or_equal:now', 'unique:serviceorder,schedule'],
            'reactiontime' => ['required'],
            'description' => ['required'],
            'status' => ['required', 'integer', 'exists:servicestatus,id'],
        ], [
            'car_id.required' => 'Не сте посочили автомобил !',
            'car_id.integer' => 'ID на автомобила трябва да е в числов вид !',
            'car_id.exists' => 'Този автомобил не съществува !',
            'car_id.unique' => 'Този автомобил вече е заявен !',
            'datepicker.required' => 'Не сте посочили дата !',
            'datepicker.date_format' => 'Невалидна дата !',
            'datepicker.unique' => 'Тази дата е заета !',
            'datepicker.after_or_equal' => 'Датата не може да бъде в минало време !',
            'reactiontime.required' => 'Не сте посочили време за реакция !',
            'description.required' => 'Не сте посочили описание на проблема !',
            'status.required' => 'Не сте посочили статус !',
            'status.integer' => 'Статуса трябва да е в числов вид !',
            'status.exists' => 'Невалиден статус !'
        ]);

        if ($validator->fails()) {
            return ['message' => $validator->errors()->first()];
        }

        $OrderManager = new OrderManager();
        $OrderManager->SetCar($request->car_id)
            ->SetDescription($request->description)
            ->SetState($request->status)
            ->SetDate($request->datepicker)
            ->add();

        return ['message' => 'Добавен успешно !'];

    }

    public function edit(Request $request)
    {
        $request->merge(['order' => $request->order]);
        $validator = Validator::make($request->all(), [
            'order' => ['required', 'integer', 'exists:serviceorder,id'],
        ], [
            'order.exists' => 'Тази заявка не съществува !',
            'order.required' => 'Не е посочена заявка !',
            'order.integer' => 'ID на заявка трябва да е число !',
        ]);

        if ($validator->fails()) {
            return ['message' => $validator->errors()->first()];
        }

        $cars = new CarsManager();
        $reserved = new OrderManager();

        $user_id = $reserved->SetOid($request->order)->UserByOrderId();

        return view('orders.form', [
            'oparams' => OrderManager::where('id', $request->order)->first(),
            'user' => User::where('id', $user_id)->first(),
            'cars' => $cars->SetUser($user_id)->ListCars(),
            'status' => Servicestatus::all(),
            'reserved' => $reserved->schedule()
        ]);
    }

    public function update(Request $request)
    {
        $request->merge(['order' => $request->order, 'schedule' => $request->datepicker ]);
        $validator = Validator::make($request->all(), [
            'order' => ['required', 'integer', 'exists:serviceorder,id'],
            'car_id' => ['required', 'integer', Rule::unique('serviceorder')
                ->whereNotIn('serviceorder.car_id', [$request->car_id])
                ->whereNotIn('serviceorder.status', [3]), 'exists:cars,id'],
            'schedule' => ['required', 'date_format:Y-m-d', 'after_or_equal:now', Rule::unique('serviceorder')
                ->whereNotIn('id',[$request->order])],
            'reactiontime' => ['required'],
            'description' => ['required'],
            'status' => ['required', 'integer', 'exists:servicestatus,id'],
        ], [
            'car_id.required' => 'Не сте посочили автомобил !',
            'car_id.integer' => 'ID на автомобила трябва да е в числов вид !',
            'car_id.exists' => 'Този автомобил не съществува !',
            'car_id.unique' => 'Този автомобил вече е заявен !',
            'schedule.required' => 'Не сте посочили дата !',
            'schedule.date_format' => 'Невалидна дата !',
            'schedule.unique' => 'Тази дата е заета !',
            'schedule.after_or_equal' => 'Датата не може да бъде в минало време !',
            'reactiontime.required' => 'Не сте посочили време за реакция !',
            'description.required' => 'Не сте посочили описание на проблема !',
            'status.required' => 'Не сте посочили статус !',
            'status.integer' => 'Статуса трябва да е в числов вид !',
            'status.exists' => 'Невалиден статус !',
            'order.exists' => 'Тази заявка не съществува !',
            'order.required' => 'Не е посочена заявка !',
            'order.integer' => 'ID на заявка трябва да е число !'
        ]);

        if ($validator->fails()) {
            return ['message' => $validator->errors()->first()];
        }


        $OrderManager = new OrderManager();
        $OrderManager->SetOid($request->order)
        ->SetCar($request->car_id)
            ->SetDescription($request->description)
            ->SetState($request->status)
            ->SetDate($request->schedule)
            ->upToDate();

        return ['message' => 'Записано !'];

    }
}
