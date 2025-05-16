<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Department;
use App\Models\InvoiceCustomerName;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
   
        $studentCount    = Student::count();
        $teacherCount    = Teacher::count();
        $departmentCount = Department::count();
        $totalInvoices   = InvoiceCustomerName::count();


        $start  = Carbon::now()->subYear()->startOfMonth();
        $months = [];
        for ($i = 0; $i <= 12; $i++) {
            $months[] = $start->copy()->addMonths($i)->format('M Y');
        }

        $studentData = Student::select(
                DB::raw("DATE_FORMAT(created_at, '%b %Y') as month"),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', $start)
            ->groupBy('month')
            ->orderBy(DB::raw("MIN(created_at)"))
            ->pluck('total', 'month')
            ->toArray();

      
        $teacherData = Teacher::select(
                DB::raw("DATE_FORMAT(created_at, '%b %Y') as month"),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', $start)
            ->groupBy('month')
            ->orderBy(DB::raw("MIN(created_at)"))
            ->pluck('total', 'month')
            ->toArray();

        $studentsPerMonth = [];
        $teachersPerMonth = [];
        foreach ($months as $m) {
            $studentsPerMonth[] = $studentData[$m] ?? 0;
            $teachersPerMonth[] = $teacherData[$m] ?? 0;
        }

        return view('dashboard.home', compact(
            'studentCount',
            'teacherCount',
            'departmentCount',
            'totalInvoices',
            'months',
            'studentsPerMonth',
            'teachersPerMonth'
        ));
    }

    public function userProfile()
    {
        return view('dashboard.profile');
    }

    public function teacherDashboardIndex()
    {
        return redirect()->route('home');
    }

    public function studentDashboardIndex()
    {
        return redirect()->route('home');
    }
}
