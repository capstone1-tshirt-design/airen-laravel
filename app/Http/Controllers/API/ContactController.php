<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ContactMessageSubmitted;
use App\Http\Requests\Contact\Store;

class ContactController extends Controller
{
    public function sendInquiry(Store $request)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ];
        $user = $data;
        $user['user'] = true;

        Notification::route('mail', env('MAIL_USERNAME'))->notify(new ContactMessageSubmitted($data));
        Notification::route('mail', $request->email)->notify(new ContactMessageSubmitted($user));

        return response(null, 201);
    }
}
