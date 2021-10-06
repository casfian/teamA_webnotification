<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; //add this

class WebNotificationController extends Controller
{
    //3 functions
    //ini supaya bila guna akan minta login
    function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home');
    }

    public function storeToken(Request $request)
    {
        auth()->user()->update(['device_key'=>$request->token]);

        return response()->json(['Token Stored Successfully']);
    }

    //last function utk send notification
    public function sendWebNotification(Request $request)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $FcmToken = User::wherenotNull('device_key')->pluck('device_key')->all();

        $serverKey = 'AAAAMS24Aww:APA91bFUF-kCV3mpFpsmYiWisBvBVnMivGD-Zda8Nb990BsviF1BeDnSEQ5JCzBIDqyinzYCtHqdQx3-rMoauJHXlKbxSViq2wP5YqhyddftWVXiWLcAm22zZvh2DgqM80oJLv_KZQAx';

        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,  
            ]
        ];
        $encodedData = json_encode($data);
    
        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
      
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

        // Execute post
        $result = curl_exec($ch);

        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }        

        // Close connection
        curl_close($ch);

        // FCM response
        //dd($result);   //if you want to debug uncomment this!     
        return back();  

    }
}
