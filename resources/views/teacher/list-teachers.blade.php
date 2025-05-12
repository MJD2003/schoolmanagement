@extends('layouts.master')
@section('content')
{{-- message --}}
{!! Toastr::message() !!}
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Teachers</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Teachers</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="student-group-form mb-3">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-2">
                    <div class="form-group">
                        <input id="filter-id" type="text" class="form-control" placeholder="Search by ID ...">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-2">
                    <div class="form-group">
                        <input id="filter-name" type="text" class="form-control" placeholder="Search by Name ...">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-2">
                    <div class="form-group">
                        <input id="filter-phone" type="text" class="form-control" placeholder="Search by Phone ...">
                    </div>
                </div>
                <div class="col-lg-2 mb-2">
                    <div class="search-student-btn">
                        <button id="btn-clear-filters" type="button" class="btn btn-secondary">Clear</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="page-header mb-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="page-title">Teachers</h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    <button id="btn-download-pdf" class="btn btn-outline-primary me-2">
                                        <i class="fas fa-download"></i> Download PDF
                                    </button>
                                    <a href="{{ route('teacher/grid/page') }}" class="btn btn-outline-gray me-2">
                                        <i class="fa fa-th" aria-hidden="true"></i>
                                    </a>
                                    <a href="{{ route('teacher/add/page') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="DataList" class="table border-0 star-student table-hover table-center mb-0 table-striped">
                                <thead class="student-thread">
                                    <tr>
                                        <th>
                                            <div class="form-check check-tables">
                                                <input class="form-check-input" type="checkbox" value="all" id="check-all">
                                            </div>
                                        </th>
                                        <th>ID</th>
                                        <th>Name</th>
                                    
                                        <th>Gender</th>
                                       
                                     
                                        <th>Mobile Number</th>
                                        <th>Address</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($listTeacher as $list)
                                    <tr>
                                        <td>
                                            <div class="form-check check-tables">
                                                <input class="form-check-input" type="checkbox" value="{{ $list->id }}">
                                            </div>
                                        </td>
                                        <td class="col-id">{{ $list->id }}</td>
                                        <td class="col-name">
                                            <h2 class="table-avatar">
                                                <a href="#" class="avatar avatar-sm me-2">
                                                    @if (!empty($list->avatar))
                                                        <img class="avatar-img rounded-circle" src="{{ URL::to('images/'.$list->avatar) }}" alt="{{ $list->full_name }}">
                                                    @else
                                                        <img class="avatar-img rounded-circle" src="{{ URL::to('images/photo_defaults.jpg') }}" alt="{{ $list->full_name }}">
                                                    @endif
                                                </a>
                                                <a href="#">{{ $list->full_name }}</a>
                                            </h2>
                                        </td>
                                      
                                        <td class="col-gender">{{ $list->gender }}</td>
                                 
                                        <td class="col-phone">{{ $list->phone_number }}</td>
                                        <td class="col-address">{{ $list->address }}</td>
                                        <td class="text-end">
                                            <div class="actions">
                                                <a href="{{ url('teacher/edit/'.$list->id) }}" class="btn btn-sm bg-info-light me-1">
                                                    <i class="far fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm bg-danger-light teacher_delete" data-bs-toggle="modal" data-bs-target="#teacherDelete" data-id="{{ $list->id }}">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal custom-modal fade" id="teacherDelete" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>Delete Teacher</h3>
                    <p>Are you sure you want to delete this teacher?</p>
                </div>
                <div class="modal-btn delete-action">
                    <form action="{{ route('teacher/delete') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" class="e_teacher_id">
                        <div class="row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-danger continue-btn">Delete</button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-secondary paid-cancel-btn" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('script')
  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterId = document.getElementById('filter-id');
            const filterName = document.getElementById('filter-name');
            const filterPhone = document.getElementById('filter-phone');
            const clearBtn = document.getElementById('btn-clear-filters');
            const table = document.getElementById('DataList');

            function applyFilters() {
                const idVal = filterId.value.trim().toLowerCase();
                const nameVal = filterName.value.trim().toLowerCase();
                const phoneVal = filterPhone.value.trim().toLowerCase();

                Array.from(table.tBodies[0].rows).forEach(row => {
                    const idText = row.querySelector('.col-id').textContent.toLowerCase();
                    const nameText = row.querySelector('.col-name').textContent.toLowerCase();
                    const phoneText = row.querySelector('.col-phone').textContent.toLowerCase();

                    const matches =
                        (idVal === '' || idText.includes(idVal)) &&
                        (nameVal === '' || nameText.includes(nameVal)) &&
                        (phoneVal === '' || phoneText.includes(phoneVal));

                    row.style.display = matches ? '' : 'none';
                });
            }

            [filterId, filterName, filterPhone].forEach(input => {
                input.addEventListener('input', applyFilters);
            });

            clearBtn.addEventListener('click', function() {
                filterId.value = '';
                filterName.value = '';
                filterPhone.value = '';
                applyFilters();
            });

         
            document.querySelectorAll('.teacher_delete').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    document.querySelector('.e_teacher_id').value = id;
                });
            });

        
            const { jsPDF } = window.jspdf;
            document.getElementById('btn-download-pdf').addEventListener('click', () => {
                const doc = new jsPDF();
                doc.text('Teachers List', 14, 20);
                doc.autoTable({ html: '#DataList', startY: 30 });
                doc.save('teachers_list.pdf');
            });
        });
    </script>
@endsection
@endsection
