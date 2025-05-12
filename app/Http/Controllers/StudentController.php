<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Brian2694\Toastr\Facades\Toastr;
use DB;

class StudentController extends Controller
{
    
    public function student(Request $request)
    {
        $query = Student::query();

        if ($request->filled('search_id')) {
            $query->where('id', 'like', '%'.$request->search_id.'%');
        }

        if ($request->filled('search_name')) {
            $name = $request->search_name;
            $query->where(function($q) use ($name) {
                $q->where('first_name', 'like', '%'.$name.'%')
                  ->orWhere('last_name',  'like', '%'.$name.'%');
            });
        }

        if ($request->filled('search_phone')) {
            $query->where('phone_number', 'like', '%'.$request->search_phone.'%');
        }

        $studentList = $query->orderBy('id', 'desc')->paginate(10);

        return view('student/student', compact('studentList'));
    }

    public function studentGrid()
    {
        $studentList = Student::orderBy('id', 'desc')->get();
        return view('student/student-grid', compact('studentList'));
    }

    public function studentAdd()
    {
        return view('student/add-student');
    }

    public function studentSave(Request $request)
    {
        $data = $request->validate([
            'first_name'    => 'required|string',
            'last_name'     => 'required|string',
            'gender'        => 'required|not_in:0',
            'date_of_birth' => 'required|date',
            'roll'          => 'required|string',
            'blood_group'   => 'required|string',
            'religion'      => 'required|string',
            'email'         => 'required|email|unique:students,email',
            'class'         => 'required|string',
            'section'       => 'required|string',
            'admission_id'  => 'required|string|unique:students,admission_id',
            'phone_number'  => 'required|string',
            'upload'        => 'required|image|max:2048',
        ]);

        DB::transaction(function() use ($request, $data) {
            $filename = uniqid().'.'.$request->upload->extension();
            $request->upload->move(
                storage_path('app/public/student-photos'),
                $filename
            );

            Student::create(array_merge($data, ['upload' => $filename]));
            Toastr::success('Student added successfully', 'Success');
        });

        return redirect()->route('student/list');
    }

    public function studentEdit($id)
    {
        $studentEdit = Student::where('id',$id)->first();
        return view('student.edit-student',compact('studentEdit'));
    }

    public function studentUpdate(Request $request)
    {
        DB::beginTransaction();
        try {

            if (!empty($request->upload)) {
                unlink(storage_path('app/public/student-photos/'.$request->image_hidden));
                $upload_file = rand() . '.' . $request->upload->extension();
                $request->upload->move(storage_path('app/public/student-photos/'), $upload_file);
            } else {
                $upload_file = $request->image_hidden;
            }
           
            $updateRecord = [
                'upload' => $upload_file,
            ];
            Student::where('id',$request->id)->update($updateRecord);
            
            Toastr::success('Has been update successfully :)','Success');
            DB::commit();
            return redirect()->back();
           
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('fail, update student  :)','Error');
            return redirect()->back();
        }
    }

    public function studentDelete(Request $request)
    {
        DB::beginTransaction();
        try {
           
            if (!empty($request->id)) {
                Student::destroy($request->id);
                unlink(storage_path('app/public/student-photos/'.$request->avatar));
                DB::commit();
                Toastr::success('Student deleted successfully :)','Success');
                return redirect()->back();
            }
    
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Student deleted fail :)','Error');
            return redirect()->back();
        }
    }



    public function studentProfile($id)
    {
        $studentProfile = Student::findOrFail($id);
        return view('student/student-profile', compact('studentProfile'));
    }
}
