<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContactUsRequest;
use Notification;
use App\Notifications\ContactUsNotification;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function contactus(Request $request) {
      return view('contactus');
    }

    public function send(ContactUsRequest $request) {
        Notification::route('mail', config('app.notifiable'))
          ->notify(new ContactUsNotification($request));

        return redirect()
          ->back()
          ->with('status', 'Grazie per averci contattato!');
    }
}
