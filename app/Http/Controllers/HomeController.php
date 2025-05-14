<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Department;
use App\Models\InvoiceCustomerName;
use Brian2694\Toastr\Facades\Toastr;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the dashboard with real‑time data (no revenue/payment).
     */
    public function index()
    {
        // Core entity counts
        $studentCount    = Student::count();
        $teacherCount    = Teacher::count();
        $departmentCount = Department::count();

        // Just total invoices—no payment/revenue details
        $totalInvoices = InvoiceCustomerName::count();

        return view('dashboard.home', compact(
            'studentCount',
            'teacherCount',
            'departmentCount',
            'totalInvoices'
        ));
    }

    public function userProfile()
    {
        return view('dashboard.profile');
    }

    public function teacherDashboardIndex()
    {
        return view('dashboard.teacher_dashboard');
    }

    public function studentDashboardIndex()
    {
        return view('dashboard.student_dashboard');
    }
}
