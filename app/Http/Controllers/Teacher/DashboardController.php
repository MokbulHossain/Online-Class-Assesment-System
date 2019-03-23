<?php

namespace App\Http\Controllers\Teacher;

use App\Attendance;
use App\Http\Controllers\Controller;
use App\Post;
use App\Student;
use App\Teacher;
use App\Course;
use App\Course_student;
use App\Mark;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
    	
    	$posts = Post::latest()->get();
    	return view('teacher.home', compact('posts'));
    	
    }

    public function unauthorize(){

    	return view('teacher.unauthorized');
    }

    public function attandance()
    {

        $now = now();
        $month = date('n', strtotime($now));
        $year = date('Y', strtotime($now));
        $days_in_current_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $courses = Teacher::find(Auth::user()->id)->courses;
        return view('teacher.attandance.index',compact('courses'));
    }

    public function todays()
    {
         $courses = Teacher::find(Auth::user()->id)->courses;

       return view('teacher.attandance.todays', compact('courses'));
    }
    public function select_student(Request $request){
         $student_id=array();$attand_student_id=array();
        $course_name =Course::find($request->c_id);
         $add ='teacher.attandance.add';
         $remove ='teacher.attandance.remove';
         $course_student = Course_student::where('course_id',$request->c_id)->get();
          foreach ($course_student as $value) {
             $student_id[]=$value->student_id;
         }
        $students = Student::whereIn('id',$student_id)->get();
           $student_ids = Attendance::where('teacher_id',Auth::user()->id)
                      ->where('course_name',$course_name->name)
                      ->where('date',date('d-m-Y'))
                      ->where('is_attend',1)->get();
         foreach ($student_ids as $value) {
             $attand_student_id[]=$value->student_id;
         }
      
        $output ='<tbody id="change_it">';
        if(count($students)>0){
          foreach($students as $student){
            $output =$output.'<tr> <td>'.$student->id.'</td><td>'.$student->name.'</td><td class="center">';
            if (in_array($student->id,$attand_student_id)) {
               $output=$output.'<input type="checkbox" name="attandance[]" value="'.$student->id.'" checked>';
            }
            else{
                $output=$output.'<input type="checkbox" name="attandance[]" value="'.$student->id.'">';
            }
            $output=$output.' </td></tr>';  
                            } 
            $output =$output.'</tbody> <input type="hidden" name="course_name" value="'.$course_name->name.'">';                          
                       }
      return $output;
    }

    public function submit_attandance(Request $request){
        $student_ids=array();
        $course_name= $request->input('course_name');
        $attandance= $request->input('attandance');
        if($attandance){
        foreach ($attandance as $value) {
            $att = Attendance::where('teacher_id',Auth::user()->id)
                      ->where('student_id',$value)
                      ->where('course_name',$course_name)
                      ->where('date',date('d-m-Y'))
                      ->first();
            if($att){
                if ($att->is_attend == 0) {
                    $att->is_attend = 1;$att->save();
                }
                
            }
            else{
                $attend = new Attendance();
                $attend->student_id = $value;
                $attend->teacher_id = Auth::user()->id;
                $attend->date = date('d-m-Y');
                $attend->course_name = $course_name;
                $attend->is_attend = 1;
                $attend->save();
            }
            $student_ids[]=$value;

            
        }
   if(count($student_ids) == 1){
     $un_att = Attendance::where('teacher_id',Auth::user()->id)
                      ->where('student_id','!=',$student_ids)
                      ->where('course_name',$course_name)
                      ->where('date',date('d-m-Y'))
                      ->get();
     }
     else{
        $un_att = Attendance::where('teacher_id',Auth::user()->id)
                      ->whereIn('student_id','!=',$student_ids)
                      ->where('course_name',$course_name)
                      ->where('date',date('d-m-Y'))
                      ->get();
     }
   }

    else{
        $un_att = Attendance::where('teacher_id',Auth::user()->id)
                      ->where('course_name',$course_name)
                      ->where('date',date('d-m-Y'))
                      ->get();
    }
    //return $un_att;
        
        if(count($un_att)>0){
            foreach ($un_att as $value) {
                $student = Attendance::where('teacher_id',Auth::user()->id)
                      ->where('student_id',$value->student_id)
                      ->where('course_name',$course_name)
                      ->where('date',date('d-m-Y'))
                      ->first();
                $student->is_attend =0;
                $student->save();
            }
        }
         Toastr::success('Attendance Given Successfully.', 'success');
        return redirect()->route('teacher.attandance');

    }

    public function attandance_add(Request $request, $id)
    {
        $now = now();
        $day = date('d', strtotime($now));
        $month = date('n', strtotime($now));
        $year = date('Y', strtotime($now));
        $date = date('d-m-Y', strtotime($now));

        $days_in_current_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        
        $teacher = Teacher::find(Auth::user()->id);

        $duplicate = Attendance::where('student_id', $id)->where('date', $date)->get();
        foreach ($duplicate as $dup) {
            if ($dup->student_id == $id and $dup->date == $date) {
               Toastr::info('Attendance Already Given.', 'info');
                return redirect()->route('teacher.todays'); 
            }
        }

        $attend = new Attendance();
        $attend->student_id = $id;
        $attend->teacher_id = $teacher->id;
        $attend->date = $date;
        $attend->is_attend = 1;
        $attend->save();

        Toastr::success('Attendance Given Successfully.', 'success');
        return redirect()->route('teacher.todays');
       
    }

    public function attandance_remove(Request $request, $id)
    {      
    return $id;  
        $attend = Attendance::where('student_id', $id)->get();
        foreach($attend as $at){
            $at->is_attend = 0;
            $at->save();
        }
        

        Toastr::success('Attendance Remove Successfully.', 'warning');
        return redirect()->route('teacher.todays');
    }

    public function marks_entry(){
        $courses = Teacher::find(Auth::user()->id)->courses;

       return view('teacher.marks.marks_entry', compact('courses'));
    }

    public function student_with_marks(Request $request){

        $student_id=array();$attand_student_id=array();
        $course_name =Course::find($request->c_id);
         $course_student = Course_student::where('course_id',$request->c_id)->get();
          foreach ($course_student as $value) {
             $student_id[]=$value->student_id;
         }
        $students = Student::whereIn('id',$student_id)->get();

          $output ='<tbody id="change_it">';
        if(count($students)>0){
          foreach($students as $student){
            $output .= '<tr><td>'.$student->id.'</td><td>'.$student->name.'</td>';

             if($student->mark){ 
            $output .='<td><input type="number" id="assignment'.$student->id.'" name="assignment" min="0" max="10" step="0.25" onkeyup="myFunction('.$student->id.')" value="'.$student->mark->assignment.'" /></td><td><input type="number" min="0" max="10" step="0.25" onkeyup="myFunction('.$student->id.')" name="presentation" id="presentation'.$student->id.'" value="'.$student->mark->presentation.'" /></td><td><input type="number" min="0" max="10" step="0.25" onkeyup="myFunction('.$student->id.')" name="class_test" value="'.$student->mark->class_test.'" id="class_test'.$student->id.'" /></td><td><input type="number" min="0" max="10" step="0.25" onkeyup="myFunction('.$student->id.')" name="attendance" id="attendance'.$student->id.'" value="'.$student->mark->attendance.'"/></td><td><input type="number" min="0" max="10" step="0.25" onkeyup="myFunction('.$student->id.')" name="mid" id="mid'.$student->id.'"  value="'.$student->mark->mid.'" /></td><td><input type="number" min="0" max="10" step="0.25" onkeyup="myFunction('.$student->id.')" name="final" id="final'.$student->id.'"  value="'.$student->mark->final.'"/></td><td id="GT'.$student->id.'">'.$student->mark->GT.'</td><td id="GRD'.$student->id.'">'.$student->mark->GRD.'</td><td id="GP'.$student->id.'">'.$student->mark->GP.'</td><td><input class="btn btn-primary" type="submit" value="update" onclick="update_marks('.$student->id.')"/></td>';
             }
             else{
                  $output .='<td><input type="number" name="assignment" id="assignment'.$student->id.'" min="0" max="10" step="0.25" value="0.00" onkeyup="myFunction('.$student->id.')" /></td><td><input type="number" min="0" max="10" step="0.25" value="0.00" onkeyup="myFunction('.$student->id.')"  name="presentation" id="presentation'.$student->id.'" /></td><td><input type="number" min="0" max="10" step="0.25" value="0.00" onkeyup="myFunction('.$student->id.')" name="class_test" id="class_test'.$student->id.'" /></td><td><input type="number" min="0" max="10" step="0.25" value="0.00" onkeyup="myFunction('.$student->id.')" name="attendance" id="attendance'.$student->id.'" /></td><td><input type="number" min="0" max="10" step="0.25" value="0.00" onkeyup="myFunction('.$student->id.')" name="mid" id="mid'.$student->id.'" /></td><td><input type="number" min="0" max="10" step="0.25" value="0.00" onkeyup="myFunction('.$student->id.')" name="final" id="final'.$student->id.'" /></td><td id="GT'.$student->id.'"></td><td id="GRD'.$student->id.'"></td><td id="GP'.$student->id.'"></td><td><input class="btn btn-primary" type="submit" value="update" onclick="update_marks('.$student->id.')"/></td>';
             }
             $output .='</tr>';
          }
      }
      $output .='</tbody><input type="hidden" value="'.$course_name->name.'" id="course_name">';

      return $output;
    }

    public function update_marks(Request $request){

        $data = $request->all();
        $already_exist = Mark::where('student_id',$data['s_id'])
                         ->where('teacher_id',Auth::user()->id)
                         ->where('course_name',$data['course_name'])
                         ->first();
        if(!empty($already_exist)){ 
            $already_exist->assignment =$data['assignment'];
            $already_exist->presentation =$data['presentation'];
            $already_exist->class_test =$data['class_test'];
            $already_exist->attendance =$data['attendance'];
            $already_exist->mid =$data['mid'];
            $already_exist->final =$data['final'];
            $already_exist->GT =$data['GT'];
            $already_exist->GRD =$data['GRD'];
            $already_exist->GP =$data['GP'];
            $already_exist->save();
            return 'Marks Upload Successfull';

        }
     
        else{  
            $already_exist = new Mark; 
             $already_exist->student_id =(int)$data['s_id'];
             $already_exist->teacher_id =(int)Auth::user()->id;
             $already_exist->course_name =$data['course_name'];
             $already_exist->assignment =(float)$data['assignment'];
            $already_exist->presentation =(float)$data['presentation'];
            $already_exist->class_test =(float)$data['class_test'];
            $already_exist->attendance =(float)$data['attendance'];
            $already_exist->mid =(float)$data['mid'];
            $already_exist->final =(float)$data['final'];
            $already_exist->GT =$data['GT'];
            $already_exist->GRD =$data['GRD'];
            $already_exist->GP =$data['GP'];
            $already_exist->save();
            return 'Marks Upload Successfull';
        }
        return 'There Have an Error !!';
    }
}

