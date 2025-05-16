<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        return view('student.student', compact('studentList'));
    }

   
    public function studentGrid()
    {
        $studentList = Student::orderBy('id', 'desc')->get();
        return view('student.student-grid', compact('studentList'));
    }

 
    public function studentAdd()
    {
        return view('student.add-student');
    }

   
    public function studentSave(Request $request)
    {
        $data = $request->validate([
            'first_name'    => 'required|string',
            'last_name'     => 'required|string',
            'gender'        => 'required|in:Female,Male,Others',
            'date_of_birth' => 'required|date',
            'roll'          => 'nullable|string',
            'blood_group'   => 'required|in:A+,B+,O+',
            'religion'      => 'required|in:Hindu,Christian,Others',
            'email'         => 'required|email|unique:students,email',
            'class'         => 'required|in:10,11,12',
            'section'       => 'required|in:A,B,C',
            'admission_id'  => 'nullable|string|unique:students,admission_id',
            'phone_number'  => 'nullable|string',
            'upload'        => 'required|image|max:2048',
        ]);

        DB::transaction(function() use ($request, $data) {
            $filename = uniqid().'_'.$request->upload->getClientOriginalName();
            $request->upload->storeAs('public/student-photos', $filename);

            Student::create(array_merge($data, ['upload' => $filename]));
            Toastr::success('Student added successfully', 'Success');
        });

        return redirect()->route('student/list');
    }

    public function studentEdit($id)
    {
        $studentEdit = Student::findOrFail($id);
        return view('student.edit-student', compact('studentEdit'));
    }

  
    public function studentUpdate(Request $request)
    {
        $data = $request->validate([
            'id'             => 'required|exists:students,id',
            'first_name'     => 'required|string',
            'last_name'      => 'required|string',
            'gender'         => 'required|in:Female,Male,Others',
            'date_of_birth'  => 'required|date',
            'roll'           => 'nullable|string',
            'blood_group'    => 'required|in:A+,B+,O+',
            'religion'       => 'required|in:Hindu,Christian,Others',
            'email'          => 'required|email|unique:students,email,' . $request->id,
            'class'          => 'required|in:10,11,12',
            'section'        => 'required|in:A,B,C',
            'admission_id'   => 'nullable|string|unique:students,admission_id,' . $request->id,
            'phone_number'   => 'nullable|string',
            'upload'         => 'nullable|image|max:2048',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('upload')) {
                if ($request->image_hidden && Storage::exists('public/student-photos/'.$request->image_hidden)) {
                    Storage::delete('public/student-photos/'.$request->image_hidden);
                }
                $filename = uniqid().'_'.$request->upload->getClientOriginalName();
                $request->upload->storeAs('public/student-photos', $filename);
            } else {
                $filename = $request->image_hidden;
            }

           
            $updateData = [
                'first_name'    => $data['first_name'],
                'last_name'     => $data['last_name'],
                'gender'        => $data['gender'],
                'date_of_birth' => $data['date_of_birth'],
                'roll'          => $data['roll'],
                'blood_group'   => $data['blood_group'],
                'religion'      => $data['religion'],
                'email'         => $data['email'],
                'class'         => $data['class'],
                'section'       => $data['section'],
                'admission_id'  => $data['admission_id'],
                'phone_number'  => $data['phone_number'],
                'upload'        => $filename,
            ];

        
            Student::where('id', $data['id'])->update($updateData);
            DB::commit();

            Toastr::success('Student updated successfully', 'Success');
            return redirect()->route('student/list');

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Update failed: '.$e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }

    
    public function studentDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            Student::destroy($request->id);
            if ($request->avatar && Storage::exists('public/student-photos/'.$request->avatar)) {
                Storage::delete('public/student-photos/'.$request->avatar);
            }
            DB::commit();

            Toastr::success('Student deleted successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Deletion failed', 'Error');
            return redirect()->back();
        }
    }


    public function studentProfile($id)
    {
        $studentProfile = Student::findOrFail($id);
        return view('student.student-profile', compact('studentProfile'));
    }
}
