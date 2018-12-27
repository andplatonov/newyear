<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\DemoEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public static function send($request)
    {
        $objDemo = new \stdClass();
        $objDemo->number = $request;
        Mail::to("info@andplatonov.com")->send(new DemoEmail($objDemo));
    }
}
