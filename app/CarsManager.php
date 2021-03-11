<?php

namespace App;

use App\Cars;

class CarsManager extends Cars
{
    public $car_user;
    public $car_make;
    public $car_model;
    public $car_year;
    public $car_comment;

    public
    function SetUser($car_user)
    {
        $this->car_user = $car_user;
        return $this;
    }

    public
    function SetMake($car_make)
    {
        $this->car_make = $car_make;
        return $this;
    }

    public
    function SetModel($car_model)
    {
        $this->car_model = $car_model;
        return $this;
    }

    public
    function SetYear($car_year)
    {
        $this->car_year = $car_year;
        return $this;
    }

    public
    function SetComment($car_comment)
    {
        $this->car_comment = $car_comment;
        return $this;
    }

    public
    function add()
    {
      return self::create([
           'user_id' => $this->car_user,
           'make' => $this->car_make,
           'model' => $this->car_model,
           'make_year' => $this->car_year,
           'comment' => $this->car_comment
        ]);
    }

    public function ListCars()
    {
       return self::where('user_id', $this->car_user)->get()->toArray();
    }

    public static function CarCount($car_user)
    {
        return self::where('user_id', $car_user)->count();
    }


}

?>