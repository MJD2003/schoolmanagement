<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Teacher;
use Brian2694\Toastr\Facades\Toastr;

class TeacherController extends Controller
{
    
    public function teacherAdd()
    {
        return view('teacher.add-teacher');
    }

 
    public function teacherList()
    {
        $listTeacher = Teacher::all();
        return view('teacher.list-teachers', compact('listTeacher'));
    }

    public function teacherGrid()
    {
        $teacherGrid = Teacher::all();
        return view('teacher.teachers-grid', compact('teacherGrid'));
    }

    public function saveRecord(Request $request)
    {
        $request->validate([
            'full_name'     => 'required|string|max:255',
            'gender'        => 'required|string|in:Female,Male,Other',
            'experience'    => 'required|string|max:100',
            'qualification' => 'required|string|max:255',
            'date_of_birth' => 'required|date_format:d-m-Y',
            'phone_number'  => 'nullable|string|max:20',
            'address'       => 'required|string|max:500',
            'city'          => 'required|string|max:100',
            'state'         => 'required|string|max:100',
            'zip_code'      => 'required|string|max:20',
            'country'       => 'required|string|max:100',
        ]);

        try {
            $teacher = new Teacher;
            $teacher->full_name     = $request->full_name;
            $teacher->gender        = $request->gender;
            $teacher->experience    = $request->experience;
            $teacher->qualification = $request->qualification;
            $teacher->date_of_birth = \Carbon\Carbon::createFromFormat('d-m-Y', $request->date_of_birth);
            $teacher->phone_number  = $request->phone_number;
            $teacher->address       = $request->address;
            $teacher->city          = $request->city;
            $teacher->state         = $request->state;
            $teacher->zip_code      = $request->zip_code;
            $teacher->country       = $request->country;
            $teacher->save();

            Toastr::success('Teacher has been added successfully.', 'Success');
            return redirect()->route('teacher/list/page');

        } catch (\Exception $e) {
            \Log::error('Error saving teacher: '.$e->getMessage());
            Toastr::error('Failed to add new teacher.', 'Error');
            return redirect()->back()->withInput();
        }
    }

   
    public function editRecord($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('teacher.edit-teacher', compact('teacher'));
    }

    public function updateRecordTeacher(Request $request)
    {
        $request->validate([
            'id'            => 'required|integer|exists:teachers,id',
            'full_name'     => 'required|string|max:255',
            'gender'        => 'required|string|in:Female,Male,Other',
            'experience'    => 'required|string|max:100',
            'qualification' => 'required|string|max:255',
            'date_of_birth' => 'required|date_format:d-m-Y',
            'phone_number'  => 'nullable|string|max:20',
            'address'       => 'required|string|max:500',
            'city'          => 'required|string|max:100',
            'state'         => 'required|string|max:100',
            'zip_code'      => 'required|string|max:20',
            'country'       => 'required|string|max:100',
        ]);

        DB::beginTransaction();
        try {
            $updateData = [
                'full_name'     => $request->full_name,
                'gender'        => $request->gender,
                'experience'    => $request->experience,
                'qualification' => $request->qualification,
                'date_of_birth' => \Carbon\Carbon::createFromFormat('d-m-Y', $request->date_of_birth),
                'phone_number'  => $request->phone_number,
                'address'       => $request->address,
                'city'          => $request->city,
                'state'         => $request->state,
                'zip_code'      => $request->zip_code,
                'country'       => $request->country,
            ];

            Teacher::where('id', $request->id)->update($updateData);

            DB::commit();
            Toastr::success('Teacher has been updated successfully.', 'Success');
            return redirect()->route('teacher/list/page');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating teacher: '.$e->getMessage());
            Toastr::error('Failed to update teacher.', 'Error');
            return redirect()->back()->withInput();
        }
    }

    public function teacherDelete(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:teachers,id'
        ]);

        DB::beginTransaction();
        try {
            Teacher::destroy($request->id);
            DB::commit();
            Toastr::success('Teacher has been deleted successfully.', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting teacher: '.$e->getMessage());
            Toastr::error('Failed to delete teacher.', 'Error');
            return redirect()->back();
        }
    }
}
