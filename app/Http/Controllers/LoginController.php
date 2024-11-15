<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function login()
    {
        return view('admin/login');
    }
    public function loginAction(Request $request)
    {
        $request->validate([
            'email' => 'required|regex:/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$/',
            'password' => 'required',
        ]);

        $email = $request->input('email');
        $password = ($request->input('password'));
        $admin = Registration::where('email','=', $email)->get();
        
        if (empty($admin[0])) {
            return response()->json(['status' => 'error', 'message' => 'Admin not found']);
        } 
        else if ($admin[0]['password']!=$password) {
            return response()->json(['status' => 'error', 'message' => 'Password is incorrect']);
        } 
        else {
            $otp = rand(100000, 999999);
            $var = ['otp' => $otp];
            Registration::where('email', '=', $email)->update($var);
            $getDataOtp = Registration::where('email', '=', $email)->get();
            
            Mail::to($email)->send(new SendOtpMail($otp));

            return response($getDataOtp);
        }
    }
    public function verifyAndLogin(Request $request){
        
        $email=$request->input('email');
        $password=md5($request->input('password'));
        $otp = $request->input('otp');
        $admin = Registration::where('email','=',$email)->get();
        // console.log($userDetails);
        if (empty($otp)) {
            return response()->json(['status' => 'error', 'message' => 'Please Enter Your One Time Password'], 404);
        }
        else if($admin[0]['otp']!=$otp)
        {
            return response()->json(['status' => 'error', 'message' => 'Wrong OTP'], 404);
        }
        else{
            // return response()->json(['status' => 'success', 'message' => 'Form submitted successfully']);
            Session::put('admin', $admin);
            session(['admin_id' => $admin[0]['_id']]);
                    if (Session::has('admin_id')) {
                        // Retrieve the value of the session variable
                        $userId = Session::get('admin_id');
                        // Use or display the value
                        // session(['admin_id' => $admin[0]->id]);
                        return response()->json(['status' => 'success', 'admin' => $admin]);
                        
                    } else {
                        echo "Admin ID session variable not set";
                    }
            
        }
    }
    public function logout(Request $request)
    {
        $request->session()->forget('admin_id');
        return redirect('admin/login')->with('message','Logout successfully');
    }
}
