<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\RoleUser;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;

    protected $redirectTo = '/client';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required','min:10'],
        ], [
            'name.required' => 'име е задължително поле !',
            'name.string' => 'невалидно име !',
            'name.max' => 'името трябва да е максимум 255 символа !',
            'email.required' => 'не сте въвели е-мейл адрес !',
            'email.string' => 'невалиден e-мейл адрес !',
            'email.max' => 'е-мейл адреса трябва да е максимум 255 символа !',
            'email.unique' => 'този е-мейл адрес е регистриран !',
            'password.required' => 'не сте въвели парола !',
            'password.string' => 'невалидна парола !',
            'password.min' => 'паролата трябва да е минимум 8 символа !',
            'password.confirmed' => 'паролата не съвпада !',
            'phone.required' => 'Не сте попълнили мобилен телефон !',
            'phone.min' => 'Телефонен номер трябва да е 10 цифри !'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        try {
            // Consistency insurance
            DB::beginTransaction();

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password'])
            ]);

            RoleUser::create(['role_id' => 3, 'user_id' => $user->id]);
            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
        }
    }
}
