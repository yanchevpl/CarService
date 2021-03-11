<?php

namespace App;

use App\Serviceorder;
use Mail;

class OrderManager extends Serviceorder
{
    public $oid;
    public $car;
    public $description;
    public $state;
    public $date;
    public $comment;

    public
    function SetClientComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    public
    function SetOid($oid)
    {
        $this->oid = $oid;
        return $this;
    }

    public
    function SetCar($car)
    {
        $this->car = $car;
        return $this;
    }

    public
    function SetDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public
    function SetState($state)
    {
        $this->state = $state;
        return $this;
    }

    public
    function SetDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function info()
    {
        return self::where('id', $this->oid)->first();
    }

    public function add()
    {
        return self::create([
            'car_id' => $this->car,
            'trouble' => $this->description,
            'status' => $this->state,
            'schedule' => $this->date,
            'note' => $this->client_comment
        ]);
    }

    public function ChangeStatus($status)
    {

        try {
            self::where('id', $this->oid)->update([
                'note' => $this->comment,
                'status' => $status
            ]);

            if ($status == 3) {
                self::email();
            }
        } catch (\Exception $exception)
        {
            return $exception;
        }

    }

    public function schedule()
    {
        return self::where('status', 3)->get()->pluck('schedule')->toJson();
    }

    public function UserByOrderId()
    {
        return self::where('serviceorder.id', $this->oid)
            ->join('cars','serviceorder.car_id','=','cars.id')->value('user_id');
    }

    public function email()
    {
        $recepient = self::join('cars', 'serviceorder.car_id', '=', 'cars.id')
            ->join('users', 'cars.user_id', '=', 'users.id')
            ->where('serviceorder.id', $this->oid)
            ->value('email');

        Mail::send(['text' => 'mail'], array('name' => 'Car Service'), function ($message) use ($recepient) {
            $message->to($recepient)->subject('Car Service');
            $message->from(env('MAIL_USERNAME'), env('MAIL_USERNAME'));
        });

        return;
    }

    public function upToDate()
    {
        self::where('id', $this->oid)
            ->update([
                'car_id' => $this->car,
                'trouble' => $this->description,
                'status' => $this->state,
                'schedule' => $this->date,
                'note' => $this->comment
                ]);
    }
}