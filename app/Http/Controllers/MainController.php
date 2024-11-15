<?php

namespace App\Http\Controllers;

use Response;
use App\Models\City;
use App\Models\User;
use App\Models\State;
use App\Models\District;
use App\Models\UploadExcel;
use App\Models\Registration;
use App\Models\AssignedUser;
use App\Jobs\ExportAssignedUsersJob;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\IOFactory;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Milon\Barcode\Facades\DNS1D;
use Milon\Barcode\Facades\DNS2D;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Support\Facades\Storage;

use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;

class MainController extends Controller
{
    public function getDistricts($stateId)
    {
        $districts=District::where('stateId',$stateId)->get();
        return response()->json($districts);
    }

    public function getCities($districtId)
    {
        $cities=City::where('districtId',$districtId)->get();
        return response()->json($cities);
    }

    public function insertForm()
    {
        $stateDB=State::get();
        $cityDB=City::get();
        $districtDB=District::get();
        
        return view('insert',compact('stateDB','cityDB','districtDB'));
    }
    
    public function formSubmit(Request $request)
    {
        $registration = new Registration;
        $registration->name = $request->name;
        $registration->email = $request->email;
        $registration->phone = (int)$request->phone;
        $registration->password = ($request->password); 
        $registration->gender = $request->gender;
        $registration->state = $request->state;
        $registration->district = $request->district;
        $registration->city = $request->city;
        // $languages = [
        //     '2ndlanguage' => $request->languages,
        // ];
        // $registration->languages = $languages;
        $registration->language=$request->languages;

        // Process profile picture upload
        if($request->file('profilePicture')){
        $file1=$request->file('profilePicture');
        $filename1=time()."_".$file1->getClientOriginalName();
        $uploadlocation="./profilePicture";
        $file1->move($uploadlocation,$filename1);

        $registration->profile_picture = $uploadlocation.'/'.$filename1;
        }

        $imagePaths = [];

        if ($request->has('images')) {
            foreach ($request->images as $index => $image) {
                $decodedImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));
                $filename = time() . '_image_' . $index . '.png';
                $filePath = public_path('uploads/' . $filename);
                file_put_contents($filePath, $decodedImage);
                $imagePaths[] = 'uploads/' . $filename;
            }
        }

        $imgReg = json_encode($imagePaths);
        $registration->images = json_decode($imgReg);

        $registration->save();

        return response()->json(['status' => 'Success', 'message' => 'Inserted Data Successfully']);
    }

    public function displayall()
    {
        $user=Registration::OrderBy('created_at', 'desc')->paginate(10);
        $stateDB=State::get();
        $cityDB=City::get();
        $districtDB=District::get();
        return view('listing',compact('user','stateDB','cityDB','districtDB'));
    }

    public function pagination(Request $request)
    {
        $keyword = $request->keyword;

        $query = Registration::query();

        if (!empty($keyword)) {
            $query->where('name', 'like', '%' . $keyword . '%')
            ->orWhere('email', 'like', '%' . $keyword . '%');
        }

        $user = $query->OrderBy('created_at', 'desc')->paginate(10);
        
        $stateDB = State::get();
        $cityDB = City::get();
        $districtDB = District::get();

    return view('listing_search', compact('user', 'stateDB', 'cityDB', 'districtDB'))->render();
    }

    public function search(Request $request){

        $keyword = $request->keyword;

        $user = Registration::where('name', 'like', '%' . $keyword . '%')
                ->orWhere('email', 'like', '%' . $keyword . '%')
                ->OrderBy('created_at', 'desc')->paginate(10);
        $stateDB = State::get();
        $cityDB = City::get();
        $districtDB = District::get();

        return view('listing_search', compact('user', 'stateDB', 'cityDB', 'districtDB'))->render();
    }

    public function download()
    {
        $user = Registration::OrderBy('created_at', 'desc')->get();
        $stateDB = State::get();
        $cityDB = City::get();
        $districtDB = District::get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the header row
        $sheet->setCellValue('A1', 'SL.No.');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Phone');
        $sheet->setCellValue('E1', 'Password');
        $sheet->setCellValue('F1', 'Gender');
        $sheet->setCellValue('G1', 'State');
        $sheet->setCellValue('H1', 'District');
        $sheet->setCellValue('I1', 'City');
        $sheet->setCellValue('J1', 'Language');
        $sheet->setCellValue('K1', 'Profile Picture');
        // Determine the maximum number of images
        $maxImages = 0;
        foreach ($user as $all) {
            $maxImages = max($maxImages, count($all->images));
        }

        // Set dynamic headers for images
        for ($i = 1; $i <= $maxImages; $i++) {
            $sheet->setCellValue(chr(75 + $i) . '1', 'Image-' . $i); // K=75 is the ASCII code for 'K'
        }

        $row = 2;
        $i = 1;
        foreach ($user as $all) {
            $state = $stateDB->firstWhere('_id', $all->state);
            $district = $districtDB->firstWhere('_id', $all->district);
            $city = $cityDB->firstWhere('_id', $all->city);

            $sheet->setCellValue('A' . $row, $i++);
            $sheet->setCellValue('B' . $row, $all->name);
            $sheet->setCellValue('C' . $row, $all->email);
            $sheet->setCellValue('D' . $row, $all->phone);
            $sheet->setCellValue('E' . $row, $all->password);
            $sheet->setCellValue('F' . $row, $all->gender);
            $sheet->setCellValue('G' . $row, $state ? $state->name : 'N/A');
            $sheet->setCellValue('H' . $row, $district ? $district->name : 'N/A');
            $sheet->setCellValue('I' . $row, $city ? $city->name : 'N/A');
            $sheet->setCellValue('J' . $row, implode(', ', $all->language));

            // Add profile picture
            // if ($all->profile_picture) {
            //     $drawing = new Drawing();
            //     $drawing->setPath(public_path($all->profile_picture));
            //     $drawing->setCoordinates('K' . $row);
            //     $drawing->setHeight(50);
            //     $drawing->setWorksheet($sheet);
            // }

            $sheet->getRowDimension($row)->setRowHeight(50);

            $sheet->getColumnDimension('K')->setWidth(20);

            if ($all->profile_picture) {
                $drawing = new Drawing();
                $drawing->setPath(public_path($all->profile_picture));
                $drawing->setCoordinates('K' . $row);
                $drawing->setHeight(50);
                $drawing->setOffsetX(5); // Adjust the horizontal offset as needed
                $drawing->setOffsetY(5); // Adjust the vertical offset as needed
                $drawing->setWorksheet($sheet);
            }

            // Add images
            $col = 'L';
            foreach ($all->images as $image) {
                if ($image) {
                    $drawing = new Drawing();
                    $drawing->setPath(public_path($image));
                    $drawing->setCoordinates($col . $row);
                    $drawing->setHeight(25);
                    $drawing->setWorksheet($sheet);
                    $col++;
                }
            }

            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'data.xlsx';

        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);

        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }

    public function downloadCsv()
    {
        $user = Registration::orderBy('created_at', 'desc')->get();
        $stateDB = State::get();
        $cityDB = City::get();
        $districtDB = District::get();

        $html = '<table border="1">
                    <tr>
                        <th>SL.No.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>State</th>
                        <th>District</th>
                        <th>City</th>
                        <th>Language</th>
                        <th>Profile Picture</th>
                        <th>Images</th>
                    </tr>';

        $i = 1;
        foreach ($user as $all) {
            $state = $stateDB->firstWhere('_id', $all->state);
            $district = $districtDB->firstWhere('_id', $all->district);
            $city = $cityDB->firstWhere('_id', $all->city);

            $profilePicture = $all->profile_picture ? '<img src="' . url($all->profile_picture) . '" height="50" />' : 'N/A';
            
            // Prepare the images as HTML
            $imagesHtml = '';
            foreach ($all->images as $image) {
                if ($image) {
                    $imagesHtml .= '<img src="' . url($image) . '" height="25" style="margin: 0 2px;" />';
                }
            }

            $html .= '<tr>
                        <td>' . $i++ . '</td>
                        <td>' . $all->name . '</td>
                        <td>' . $all->email . '</td>
                        <td>' . $all->password . '</td>
                        <td>' . $all->phone . '</td>
                        <td>' . $all->gender . '</td>
                        <td>' . ($state ? $state->name : 'N/A') . '</td>
                        <td>' . ($district ? $district->name : 'N/A') . '</td>
                        <td>' . ($city ? $city->name : 'N/A') . '</td>
                        <td>' . implode(', ', $all->language) . '</td>
                        <td>' . $profilePicture . '</td>
                        <td>' . $imagesHtml . '</td>
                    </tr>';
        }

        $html .= '</table>';

        // Save the HTML content to a file
        $filename = 'data.html';
        file_put_contents($filename, $html);

        // Set headers for downloading the file as an Excel file
        return response()->download($filename, 'data.xls')->deleteFileAfterSend(true);
    }


    public function edit($ep)
    {
        $userid=$ep;
        $user=Registration::where(['_id' => $userid])->first();

        $state = State::where('_id', $user->state)->first();
        $district = District::where('_id', $user->district)->first();
        $city = City::where('_id', $user->city)->first();

        $stateDB=State::get();
        $cityDB=City::get();
        $districtDB=District::get();

        return view('edit',compact('user','stateDB','cityDB','districtDB','state','district','city'));
    }

    public function sendOtp(Request $request)
    {
        $email = $request->email;
        $otp = rand(100000, 999999);
        Session::put('otp', $otp);
        Mail::to($email)->send(new SendOtpMail($otp));
        return response()->json(['success' => true]);
    }

    public function verifyOtp(Request $request)
    {
        $otp = $request->otp;
        $storedOtp = Session::get('otp');
        if ($storedOtp == $otp) {
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Invalid OTP']);
    }

    public function sendPhoneOtp(Request $request)
    {
        $phone = $request->phone;

        // Generate OTP
        $otp = rand(100000, 999999);

        // Store OTP in session
        Session::put('phone_otp', $otp);

        // Send OTP via Twilio
        $this->sendSms($phone, "Your OTP code is: " . $otp);

        return response()->json(['success' => true]);
    }

    public function verifyPhoneOtp(Request $request)
    {
        $otp = $request->otp;
        $storedOtp = Session::get('phone_otp');
        if ($storedOtp == $otp) {

            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Invalid OTP']);
    }

    private function sendSms($phone, $message)
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $twilioPhoneNumber = env('TWILIO_PHONE_NUMBER');

        $client = new Client($sid, $token);

        $client->messages->create($phone, [
            'from' => $twilioPhoneNumber,
            'body' => $message,
        ]);
    }

    public function update(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        $userid=$request->input('uid');
        $user = Registration::where('_id', $userid)->first();

        // Process profile picture upload
        if($request->file('profilePicture')){
            // Delete old profile picture if exists
            if ($user->profile_picture) {
                $oldProfilePicturePath = public_path($user->profile_picture);
                if (file_exists($oldProfilePicturePath)) {
                    unlink($oldProfilePicturePath);
                }
            }


            $file1=$request->file('profilePicture');
            $filename1=time()."_".$file1->getClientOriginalName();
            $uploadlocation="./profilePicture";
            $file1->move($uploadlocation,$filename1);
        }

        // $imagePaths = $user->images ? $user->images : [];
        $imagePaths=[];

        if ($request->has('images')) {
            $oldImages=$user->images;
            // Delete old images from public/uploads folder
            foreach ($oldImages as $oldImage) {
                $oldImagePath = public_path($oldImage);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            foreach ($request->images as $index => $image) {
                $decodedImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));
                $filename = time() . '_image_' . $index . '.png';
                $filePath = public_path('uploads/' . $filename);
                file_put_contents($filePath, $decodedImage);
                $imagePaths[] = 'uploads/' . $filename;
            }
        }
        $imgReg = json_encode($imagePaths);

        

        $data=[
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password,
            'phone'=>$request->phone,
            'state'=>$request->state,
            'district'=>$request->district,
            'city'=>$request->city,
            'gender'=>$request->gender,
            'language'=>$request->languages,
            'profile_picture'=>$request->file('profilePicture') == "" ? "$user->profile_picture":$uploadlocation.'/'.$filename1,
            'images'=>$request->has('images') ? json_decode($imgReg) : $user->images,
            
        ];
        // print_r($data);
        // print_r($userid);
        Registration::where(['_id'=>$userid])->update($data);
        return response()->json(['status' => 'Success', 'message' => 'Updated Data Successfully']);
    }
    public function delete($del)
    {
        $userid=$del;
        Registration::where(['_id' => $userid])->delete();
        return redirect('/admin/listing')->with('message','User has been deleted');
    }

    public function codes($cd)
    {
        $userid=$cd;
        $user=Registration::where(['_id' => $userid])->first();
        // print_r($user);die;
        return view('codes',compact('user'));
    }

    public function uploadUser()
    {
        return view('admin/uploadUser');
    }
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $headerSkipped = false;

        foreach ($rows as $rowIndex => $row) {
            if (!$headerSkipped) {
                $headerSkipped = true;
                continue;
            }

            if (count($row) < 10) {
                continue;
            }

            $profilePicturePath = null;
            $imagePaths = [];

            $drawingCollection = $sheet->getDrawingCollection();
            foreach ($drawingCollection as $drawing) {
                // Profile picture in column K
                if ($drawing->getCoordinates() == 'K' . ($rowIndex + 1)) {
                    $profilePicturePath = $this->saveImage($drawing, 'profile_picture', 'uploadexcel');
                }

                foreach (['L', 'M', 'N'] as $index => $column) {
                    if ($drawing->getCoordinates() == $column . ($rowIndex + 1)) {
                        $imagePaths[] = $this->saveImage($drawing, 'image_' . $index, 'uploadexcelimage');
                    }
                }
            }

            $languages = array_map('trim', explode(',', $row[9]));

            $stateId = $this->getIdByName('states', $row[6]);
            $districtId = $this->getIdByName('districts', $row[7]);
            $cityId = $this->getIdByName('cities', $row[8]);

            UploadExcel::create([
                'name' => $row[1] ?? null,
                'email' => $row[2] ?? null,
                'phone' => $row[3] ?? null,
                'password' => $row[4] ?? null, 
                'gender' => $row[5] ?? null,
                'state' => $stateId ?? null,
                'district' => $districtId ?? null,
                'city' => $cityId ?? null,
                'language' => $languages ?? null,
                'profile_picture' => $profilePicturePath,
                'images' => $imagePaths,
            ]);
        }

        return back()->with('success', 'Data Imported Successfully');
    }

    private function saveImage(Drawing $drawing, $type, $directory)
    {
        $directoryPath = public_path($directory);
        
        if (!file_exists($directoryPath)) {
            mkdir($directoryPath, 0755, true);
        }

        $uniqueId = uniqid();
        $imageName = $uniqueId . '_' . $type . '.' . strtolower(pathinfo($drawing->getPath(), PATHINFO_EXTENSION));
        $imagePath = $directoryPath . '/' . $imageName;

        if ($drawing->getPath()) {
            $imageData = file_get_contents($drawing->getPath());
            if ($imageData) {
                file_put_contents($imagePath, $imageData);
                return './' . $directory . '/' . $imageName;
            }
        }

        return null; 
    }
    private function getIdByName($table, $name)
    {
        $record = DB::table($table)->where('name', $name)->first();
        return $record ? (string)$record['_id'] : null;
    }
    public function displayExcelfileUsers()
    {
        $user=UploadExcel::OrderBy('created_at', 'desc')->paginate(1);
        $stateDB=State::get();
        $cityDB=City::get();
        $districtDB=District::get();
        return view('admin.listExcelUsers',compact('user','stateDB','cityDB','districtDB'));
    }
    public function paginateUsers(Request $request)
    {
        $user = UploadExcel::orderBy('created_at', 'desc')->paginate(1);

        $stateDB = State::get();
        $cityDB = City::get();
        $districtDB = District::get();

        return view('admin.listExcelUsers_pagination', compact('user', 'stateDB', 'cityDB', 'districtDB'))->render();
    }
    public function searchUsers(Request $request)
    {
        $keyword = $request->input('keyword');
        $user = UploadExcel::where('name', 'LIKE', "%{$keyword}%")
            ->orWhere('email', 'LIKE', "%{$keyword}%")
            ->paginate(1);

        $stateDB = State::get();
        $cityDB = City::get();
        $districtDB = District::get();

        return view('admin.listExcelUsers_search', compact('user','stateDB','cityDB','districtDB'))->render();
    }
    public function dateSearch(Request $request)
    {
        $startDate =(int)date("Ymd", strtotime($request->start_date));
        $endDate = (int)date("Ymd", strtotime($request->end_date));
        
        $user = UploadExcel::whereBetween('date_of_registration', [$startDate, $endDate])->paginate(1);

        $stateDB = State::get();
        $cityDB = City::get();
        $districtDB = District::get();

        // $user=UploadExcel::whereBetween('date_of_registration', [$startDate, $endDate])->paginate(1);
        return view('admin.listExcelUsers_search', compact('user','stateDB','cityDB','districtDB'))->render();
    }
    //specified part for the job to download
    public function jobViewPage()
    {
        $user=AssignedUser::OrderBy('created_at', 'desc')->paginate(10);
        return view('job.jobViewPage',compact('user'));
    }

    public function downloadAssignedUser()
    {

        $filename = 'assigneduser-' . now()->format('Y-m-d') . '.xlsx';
        ExportAssignedUsersJob::dispatch($filename);

        session()->put('export_filename', $filename);

        return response()->json([
            'message' => 'The export job has been dispatched. You will be notified once the file is ready.',
            'download_link' => route('download.file', ['filename' => $filename])
        ]);
    }
    public function downloadAssignedUsers($filename)
    {
        $filePath = storage_path('app/exports/' . $filename);

        if (file_exists($filePath)) {
            return response()->download($filePath)->deleteFileAfterSend(true);
        } else {
            return response()->json(['error' => 'File not found!'], 404);
        }
    }
    //specified part for the job to download

    //Import function for state wise district

    public function importDataView(){
        return view('statedistrict_insert');
    }

    public function importStates()
    {
        $filePath = storage_path('app/data/states_districts.xlsx');

        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $insertedStates = [];

        foreach ($rows as $index => $row) {
            if ($index === 0) {
                continue;
            }

            $stateName = $row[1];
            $stateCode = $row[0];

            if (!in_array($stateName, $insertedStates)) {
                State::create([
                    'name' => $stateName,
                    'state_code' => $stateCode,
                ]);
                $insertedStates[] = $stateName;
            }
        }
        return redirect()->back()->with('success', 'States imported successfully.');
    }

    public function importDistricts()
    {
        $filePath = storage_path('app/data/states_districts.xlsx'); 
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $insertedDistricts = [];

        foreach ($rows as $index => $row) {

            if ($index === 0) {
                continue;
            }

            $stateName = $row[1];
            $districtName = $row[3];
            $districtCode = $row[2]; 

            $state = State::where('name', $stateName)->first();

            if (!in_array($districtName, $insertedDistricts)) {
                District::create(
                    [   'name' => $districtName,
                        'stateId' => $state->_id,
                        'district_code' => $districtCode,
                    ],
                );
                $insertedDistricts[] = $districtCode;
            }
        }
        return redirect()->back()->with('success', 'Districts imported successfully!');
    }

}
