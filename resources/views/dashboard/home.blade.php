@extends('layouts.master')

@section('content')
    {!! Toastr::message() !!}

    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- Page header --}}
            <div class="page-header mb-4">
                <div class="page-sub-header">
                    <h3 class="page-title">Welcome, {{ Session::get('name') }}!</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ul>
                </div>
            </div>

            <div class="row mb-4">
                @foreach ([
                    ['label'=>'Students','count'=>$studentCount,'icon'=>'dash-icon-01.svg'],
                    ['label'=>'Teachers','count'=>$teacherCount,'icon'=>'dash-icon-02.svg'],
                    ['label'=>'Departments','count'=>$departmentCount,'icon'=>'dash-icon-03.svg'],
                    ['label'=>'Invoices','count'=>$totalInvoices,'icon'=>'dash-icon-04.svg'],
                ] as $w)
                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-comman w-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6>{{ $w['label'] }}</h6>
                                    <h3>{{ $w['count'] }}</h3>
                                </div>
                                <img src="{{ asset('assets/img/icons/' . $w['icon']) }}"
                                     alt="{{ $w['label'] }} Icon">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Growth chart --}}
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card card-chart">
                        <div class="card-header">
                            <h5 class="card-title">Teacher vs Student Growth</h5>
                        </div>
                        <div class="card-body">
                            {{-- Explicit height prevents collapse --}}
                            <div id="apexcharts-area" style="height: 350px;"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const options = {
            chart: {
                type: 'area',
                height: 350,
                toolbar: { show: false }
            },
            series: [
                { name: 'Students', data: @json($studentsPerMonth) },
                { name: 'Teachers', data: @json($teachersPerMonth) }
            ],
            xaxis: {
                categories: @json($months),
                title: { text: 'Month' }
            },
            yaxis: {
                title: { text: 'Count' },
                min: 0
            },
            stroke: { curve: 'smooth' },
            markers: { size: 4 },
            fill: { opacity: 0.3 },
            tooltip: { shared: true, intersect: false }
        };

        const chart = new ApexCharts(
            document.querySelector('#apexcharts-area'),
            options
        );
        chart.render();
    });
</script>
@endsection
