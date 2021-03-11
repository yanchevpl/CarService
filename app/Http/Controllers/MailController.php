<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;


class MailController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }



}
