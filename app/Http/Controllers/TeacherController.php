<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Department;
use App\Models\Subject;
use Brian2694\Toastr\Facades\Toastr;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $totalTeachers    = Teacher::count();
        $totalDepartments = Department::count();
        $totalSubjects    = Subject::count();

        return view('teacher.dashboard', compact(
            'totalTeachers',
            'totalDepartments',
            'totalSubjects'
        ));
    }

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
        $data = $request->validate([
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
            $data['date_of_birth'] = \Carbon\Carbon::createFromFormat('d-m-Y', $data['date_of_birth']);
            Teacher::create($data);

            Toastr::success('Teacher added successfully.', 'Success');
            return redirect()->route('teacher.list');
        } catch (\Exception $e) {
            \Log::error('Error saving teacher: ' . $e->getMessage());
            Toastr::error('Failed to add teacher.', 'Error');
            return back()->withInput();
        }
    }

    public function editRecord($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('teacher.edit-teacher', compact('teacher'));
    }

    public function updateRecordTeacher(Request $request)
    {
        $data = $request->validate([
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

        try {
            $data['date_of_birth'] = \Carbon\Carbon::createFromFormat('d-m-Y', $data['date_of_birth']);
            Teacher::where('id', $data['id'])->update($data);

            Toastr::success('Teacher updated successfully.', 'Success');
            return redirect()->route('teacher.list');
        } catch (\Exception $e) {
            \Log::error('Error updating teacher: ' . $e->getMessage());
            Toastr::error('Failed to update teacher.', 'Error');
            return back()->withInput();
        }
    }

    public function teacherDelete(Request $request)
    {
        $request->validate(['id' => 'required|integer|exists:teachers,id']);

        try {
            Teacher::destroy($request->id);
            Toastr::success('Teacher deleted successfully.', 'Success');
            return back();
        } catch (\Exception $e) {
            \Log::error('Error deleting teacher: ' . $e->getMessage());
            Toastr::error('Failed to delete teacher.', 'Error');
            return back();
        }
    }
}