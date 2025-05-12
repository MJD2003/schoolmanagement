@extends('layouts.master')

@section('content')
    {{-- Toastr notifications --}}
    {!! Toastr::message() !!}

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Add Teacher</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('teacher/list/page') }}">Teachers</a></li>
                            <li class="breadcrumb-item active">Add Teacher</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('teacher/save') }}" method="POST">
                                @csrf
                                <div class="row">
                                    {{-- Basic Details --}}
                                    <div class="col-12">
                                        <h5 class="form-title"><span>Basic Details</span></h5>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Teacher's Name <span class="login-danger">*</span></label>
                                            <input
                                                type="text"
                                                class="form-control @error('full_name') is-invalid @enderror"
                                                name="full_name"
                                                placeholder="Full name"
                                                value="{{ old('full_name') }}"
                                                required
                                            >
                                            @error('full_name')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Gender <span class="login-danger">*</span></label>
                                            <select
                                                class="form-control select @error('gender') is-invalid @enderror"
                                                name="gender"
                                                required
                                            >
                                                <option disabled {{ old('gender') ? '' : 'selected' }}>Select Gender</option>
                                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                                <option value="Male"   {{ old('gender') == 'Male'   ? 'selected' : '' }}>Male</option>
                                                <option value="Other"  {{ old('gender') == 'Other'  ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('gender')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Experience <span class="login-danger">*</span></label>
                                            <input
                                                type="text"
                                                class="form-control @error('experience') is-invalid @enderror"
                                                name="experience"
                                                placeholder="e.g. 5 years"
                                                value="{{ old('experience') }}"
                                                required
                                            >
                                            @error('experience')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Qualification <span class="login-danger">*</span></label>
                                            <input
                                                type="text"
                                                class="form-control @error('qualification') is-invalid @enderror"
                                                name="qualification"
                                                placeholder="e.g. M.Sc in Education"
                                                value="{{ old('qualification') }}"
                                                required
                                            >
                                            @error('qualification')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms calendar-icon">
                                            <label>Date Of Birth <span class="login-danger">*</span></label>
                                            <input
                                                type="text"
                                                class="form-control datetimepicker @error('date_of_birth') is-invalid @enderror"
                                                name="date_of_birth"
                                                placeholder="DD-MM-YYYY"
                                                value="{{ old('date_of_birth') }}"
                                                required
                                            >
                                            @error('date_of_birth')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Address --}}
                                    <div class="col-12">
                                        <h5 class="form-title"><span>Address</span></h5>
                                    </div>

                                    <div class="col-12 col-sm-6">
                                        <div class="form-group local-forms">
                                            <label>Address <span class="login-danger">*</span></label>
                                            <input
                                                type="text"
                                                class="form-control @error('address') is-invalid @enderror"
                                                name="address"
                                                placeholder="Enter address"
                                                value="{{ old('address') }}"
                                                required
                                            >
                                            @error('address')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6">
                                        <div class="form-group local-forms">
                                            <label>Phone</label>
                                            <input
                                                type="text"
                                                class="form-control @error('phone_number') is-invalid @enderror"
                                                name="phone_number"
                                                placeholder="Enter Phone Number"
                                                value="{{ old('phone_number') }}"
                                            >
                                            @error('phone_number')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>City <span class="login-danger">*</span></label>
                                            <input
                                                type="text"
                                                class="form-control @error('city') is-invalid @enderror"
                                                name="city"
                                                placeholder="Enter City"
                                                value="{{ old('city') }}"
                                                required
                                            >
                                            @error('city')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>State <span class="login-danger">*</span></label>
                                            <input
                                                type="text"
                                                class="form-control @error('state') is-invalid @enderror"
                                                name="state"
                                                placeholder="Enter State"
                                                value="{{ old('state') }}"
                                                required
                                            >
                                            @error('state')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Zip Code <span class="login-danger">*</span></label>
                                            <input
                                                type="text"
                                                class="form-control @error('zip_code') is-invalid @enderror"
                                                name="zip_code"
                                                placeholder="Enter Zip"
                                                value="{{ old('zip_code') }}"
                                                required
                                            >
                                            @error('zip_code')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Country <span class="login-danger">*</span></label>
                                            <input
                                                type="text"
                                                class="form-control @error('country') is-invalid @enderror"
                                                name="country"
                                                placeholder="Enter Country"
                                                value="{{ old('country') }}"
                                                required
                                            >
                                            @error('country')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="student-submit">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        // Initialize any datetimepickers
        $('.datetimepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    </script>
@endsection
