<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendMailJob;



class SendMailController extends Controller
{
    public function sendMail()
    {
        $data = array('name' => 'Ahamed', 'email' => 'ahamedhakulmubeen@gmail.com');

        SendMailJob::dispatch($data);
        return view('send-mail');
    }
}
