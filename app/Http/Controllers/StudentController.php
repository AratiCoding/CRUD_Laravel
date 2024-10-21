<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
class StudentController extends Controller
{
    public function index(){
        return view('student.index');
    }
    public function fetchstudent(){
        $students=Student::all();
        return response()->json([
            'students'=>$students,
        ]);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
           'name'=>'required | max:191',
           'email'=>'required|email|max:191',
           'phone'=>'required|max:191',
           'course'=>'required|max:191'
        ]);
        if($validator->fails()){
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages()    ,
            ]);
        }else{
            $student=new Student;
            $student->name=$request->input('name');
            $student->email=$request->input('email');
            $student->phone=$request->input('phone');
            $student->course=$request->input('course');
            $student->save();
            return response()->json([
                'status'=>200,
                'message'=>'Student added successfully'
            ]);

        }
        // $student=new Student;   
    }

    public function edit($id){
        $student=Student::find($id);
        if($student){
            return response()->json([
                'status'=>200,
                'message'=>$student,
            ]);
        }else{
            return response()->json([
                'status'=>404,
                'message'=>'Student Not Found',
            ]);
        }
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(),[
            'name'=>'required | max:191',
            'email'=>'required | email|max:191',
            'phone'=>'required | max:191',
            'course'=>'required | max:191'
         ]);
         if($validator->fails()){
             return response()->json([
                 'status'=>400,
                 'errors'=>$validator->messages()    ,
             ]);
         }else{

             $student=Student::find($id);
             if($student){
                $student->name=$request->input('name');
                $student->email=$request->input('email');
                $student->phone=$request->input('phone');
                $student->course=$request->input('course');
                $student->update();
                return response()->json([
                    'status'=>200,
                    'message'=>'Student Updated successfully'
                ]);
            }else{
                return response()->json([
                    'status'=>404,
                    'message'=>'Student Not Found',
                ]);
            }
             
 
         }
    }

    public function destroy($id){
        $student=Student::find($id);
        $student->delete();
        return response()->json([
            'status'=>200,
            'message'=>'Student Deleted Successfully',
        ]);
    }
}
