@extends('layouts.master')

@section('content')
    {!! Toastr::message() !!}

    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- Page header --}}
            <div class="page-header mb-4">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Welcome, {{ Session::get('name') }}!</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Summary widgets --}}
            <div class="row mb-4">
                {{-- Students --}}
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Students</h6>
                                    <h3>{{ $studentCount }}</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ asset('assets/img/icons/dash-icon-01.svg') }}" alt="Students Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Teachers --}}
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Teachers</h6>
                                    <h3>{{ $teacherCount }}</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ asset('assets/img/icons/dash-icon-02.svg') }}" alt="Teachers Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Departments --}}
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Departments</h6>
                                    <h3>{{ $departmentCount }}</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="{{ asset('assets/img/icons/dash-icon-03.svg') }}" alt="Departments Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Invoices --}}
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Total Invoices</h6>
                                    <h3>{{ $totalInvoices }}</h3>
                                </div>
                                <div class="db-icon">
                                  <img src="{{ asset('assets/img/icons/dash-icon-03.svg') }}" alt="Departments Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Single full‑width chart --}}
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h5 class="card-title">Teacher vs Student Growth</h5>
                        </div>
                        <div class="card-body">
                            <div id="apexcharts-area"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
