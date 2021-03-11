<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\RoleUser;
use Log;
use Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $roles = Role::all();
        $users = User::with('getRole')->get()->toArray();
        //Log::debug($users);
        return view('users.list',['users' => $users, 'roles' => $roles]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:users,id'],
            'role' => ['required', 'integer', 'exists:roles,id'],
        ], [
            'id.exists' => 'Този клиент не съществува !',
            'id.required' => 'Не е посочен клиент !',
            'id.integer' => 'ID на клиента трябва да е число !',
            'role.exists' => 'Това ниво  не съществува !',
            'role.equired' => 'Не сте посочили ниво !',
            'role.integer' => 'Нивото трябва да е в числов вид !'
        ]);

        if ($validator->fails()) {
            return ['message' => $validator->errors()->first()];
        }

        RoleUser::where('user_id', $request->id)->update(['role_id' => $request->role]);

        return ['message' => 'Записано !'];
    }
}
