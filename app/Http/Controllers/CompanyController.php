<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Company;
use Intervention\Image\Facades\Image;
use NoCaptcha\Facades\NoCaptcha;
use Illuminate\Support\Facades\Session;

class CompanyController extends Controller
{
    public function company(){
        return view('company.create');
    }
    public function companySubmit(Request $request)
    {
        if ($request->captcha != session('captcha_text')) {
            return back()->with('message', 'Invalid CAPTCHA, please try again.');
        }
        
        $registration = new Company;
        $registration->name = $request->name;
        $registration->email = $request->email;
        $registration->phone = (int)$request->phone;
        $registration->save();

        return back()->with('message', 'Company created Successfully');
    }
    public function captcha()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
        $captchaText = '';
        for ($i = 0; $i < 6; $i++) {
            $captchaText .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        Session::put('captcha_text', $captchaText); 
        $width = 180; 
        $height = 60; 
        $image = imagecreatetruecolor($width, $height);

        $bgColor = imagecolorallocate($image, 255, 253, 208); 
        imagefill($image, 0, 0, $bgColor);

        $noiseColor = imagecolorallocate($image, 180, 180, 180); 
        for ($i = 0; $i < 1000; $i++) { 
            imagesetpixel($image, rand(0, $width), rand(0, $height), $noiseColor);
        }

        for ($i = 0; $i < 20; $i++) { 
            $lineColor = imagecolorallocate($image, rand(150, 200), rand(150, 200), rand(150, 200)); 
            imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $lineColor);
        }

        for ($i = 0; $i < 10; $i++) { 
            $arcColor = imagecolorallocate($image, rand(100, 150), rand(100, 150), rand(100, 150)); 
            imagearc($image, rand(0, $width), rand(0, $height), rand(20, 60), rand(20, 60), rand(0, 360), rand(0, 360), $arcColor);
        }

        $font = public_path('fonts/arial.ttf'); 
        if (!file_exists($font)) {
            die('Font file not found.');
        }

        $x = 10;
        for ($i = 0; $i < strlen($captchaText); $i++) {
            $fontSize = rand(16, 22); 
            $angle = rand(-20, 20); 
            $y = rand(30, 50); 
            
            $textColor = imagecolorallocate($image, rand(0, 80), rand(0, 80), rand(0, 80)); 
            imagettftext($image, $fontSize, $angle, $x, $y, $textColor, $font, $captchaText[$i]);
            
            $x += rand(22, 28);
        }

        for ($i = 0; $i < 3; $i++) {
            $cutColor = imagecolorallocate($image, rand(0, 100), rand(0, 100), rand(0, 100)); 
            $yPosition = rand(20, 40); 
            imageline($image, 0, $yPosition, $width, $yPosition, $cutColor);
        }

        header('Content-Type: image/png');
        imagepng($image);

        imagedestroy($image);
    }




}
