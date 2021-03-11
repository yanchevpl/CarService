<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\User;
use App\Role;
use App\CarsManager;
use Validator;

class CarController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Списък с Клиенти
    public function index(Request $request)
    {
        $users = User::whereHas('getRole',function ($query){
            $query->where('role_user.role_id',3);
        })->get()->toArray();
        return view('cars.view',['users' => $users])->withModel(CarsManager::class);
    }

    // Създаване на нов Автомобил
    public function create(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'user' => ['required', 'integer'],
            'make' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string'],
            'make_year' => ['required', Rule::unique('cars')
            ->where('user_id', $request->user)
            ->where('make', $request->make)
            ->where('model',$request->model)
            ->where('make_year', $request->make_year)],
            'comment' => ['sometimes','nullable', 'string'],
        ], [
            'user.required' => 'не е посочен потребител !',
            'user.integer' => 'ID на потребител трябва да е число !',
            'make.required' => 'Не сте посочили марка на автомобила !',
            'make.string' => 'Марката на автомобила трябва да е стринг !',
            'model.required' => 'Не сте посочили модел на автомобила !',
            'model.string' => 'Модела на автомобила трябва да е стринг !',
            'password.required' => 'не сте въвели парола !',
            'make_year.required' => 'Не сте посочили година на автомобила !',
            'make_year.string' => 'Годината на автомобила трябва да е стринг !',
            'make_year.min' => 'Годината на автомобила трябва да минимум 4 символа !',
            'make_year.unique' => 'Този автомобил вече е регистриран !',
            'comment.string' => 'Коментарът трябва да е стринг !'
        ]);

        if($validator->fails())
        {
            return [ 'message' => $validator->errors()->first() ];
        }

        $car = new CarsManager();
        $car->SetUser($request->user)
            ->SetMake($request->make)
            ->SetModel($request->model)
            ->SetYear($request->make_year)
            ->SetComment($request->comment)
            ->add();

        return [ 'message' => 'Добавен успешно !' ];
    }
}
