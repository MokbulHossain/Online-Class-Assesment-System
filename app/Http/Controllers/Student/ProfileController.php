<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Student;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index(){
    	$student = Student::find(Auth::user()->id);
    	return view('student.profile.index',compact('student'));
    }


    public function profileUpdate(Request $request)
    {

        $this->validate($request,[
            'name'=>'required',
            'about'=>'required',
            'email'=>'required|email',
        ]);

        $image = $request->file('image');
        $slug = str_slug($request->name);
        $user = Student::find(Auth::user()->id);

        if (isset($image)) {

           $currentDate = Carbon::now()->toDateString();
           $imageName = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

           $request->file('image')->move(base_path() . '/public/studentprofile/', $imageName);


               // if (!Storage::disk('public')->exists('profile')) {

               //     Storage::disk('public')->makeDirectory('profile');
               // }

               // $profileImage = Image::make($image)->resize(263, 263)->save($imageName);

               // Storage::disk('public')->put('profile/'.$imageName, $profileImage);

       }

        $user->name = $request->name;
        $user->about = $request->about;
        $user->email = $request->email;
        $user->image = $imageName;
        $user->save();

        Toastr::success('Your Profile Updated Successfully.','success');
        return redirect()->back();
    } 

    public function passwordUpdate(Request $request)
    {
    	//return $request;
       $this->validate($request,[
            'old_password'=>'required',
            'password'=>'required|confirmed'
        ]);

        $hashedPassword = Auth::user()->password;

        if (Hash::check($request->old_password, $hashedPassword)) 
        {
            
            if (!Hash::check($request->password, $hashedPassword)) 
            {
                $student = Student::find(Auth::id());
                $student->password = Hash::make($request->password);
                $student->save();
                Toastr::success('Password Successfully Changed.', 'success');
                Auth::logout();
                return redirect()->back();
            }
            else
            {
                Toastr::error('You entered and old password.', 'error');
                return redirect()->back();
            }
        }
        else
        {
            Toastr::error('Please Enter a valid password.', 'error');
            return redirect()->back();
        }
    }
}
