@extends('layouts.master')
@section('content')
<div class="page-wrapper">
  <div class="content container-fluid">
    <div class="page-header">
      <h3 class="page-title">Students</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('student/list') }}">Student</a></li>
        <li class="breadcrumb-item active">All Students</li>
      </ul>
    </div>

    {!! Toastr::message() !!}

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('student/list') }}">
      <div class="student-group-form row gx-2 mb-3">
        <div class="col-md-3">
          <input type="text" name="search_id" value="{{ request('search_id') }}" class="form-control" placeholder="Search by ID …">
        </div>
        <div class="col-md-3">
          <input type="text" name="search_name" value="{{ request('search_name') }}" class="form-control" placeholder="Search by Name …">
        </div>
        <div class="col-md-4">
          <input type="text" name="search_phone" value="{{ request('search_phone') }}" class="form-control" placeholder="Search by Phone …">
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-primary w-100">Search</button>
        </div>
      </div>
    </form>

  
    <div class="card card-table comman-shadow mt-4">
      <div class="card-body">
        <div class="page-header d-flex align-items-center mb-3">
          <h3 class="page-title mb-0">Students</h3>
          <div class="ms-auto">
            <button id="downloadPdfBtn" class="btn btn-outline-primary">
              <i class="fas fa-download"></i> Download
            </button>
            <a href="{{ route('student/add/page') }}" class="btn btn-primary ms-2">
              <i class="fas fa-plus"></i>
            </a>
          </div>
        </div>

        <div id="pdf-content">
          <div class="table-responsive">
            <table class="table table-hover table-center mb-0 datatable table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Class</th>
                  <th>DOB</th>
           
                  <th>Mobile Number</th>
                <th class="text-end">Action</th>
            
                </tr>
              </thead>
              <tbody>
                @forelse($studentList as $student)
                  <tr>
                    <td>{{ $student->id }}</td>
                    <td hidden class="avatar">{{ $student->upload }}</td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="student-details.html"class="avatar avatar-sm me-2">
                                                        <img class="avatar-img rounded-circle" src="{{ Storage::url('student-photos/'.$student->upload) }}" alt="">
                                                    </a>
                                                    <a href="student-details.html">{{ $student->first_name }} {{ $student->last_name }}</a>
                                                </h2>
                                    </td>
                    <td>{{ $student->class }} {{ $student->section }}</td>
                    <td>{{ $student->date_of_birth }}</td>
               
                    <td>{{ $student->phone_number }}</td>
                          <td>
                      <a href="{{ route('student/edit', $student->id) }}" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
                      <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#studentDeleteModal" data-id="{{ $student->id }}" data-avatar="{{ $student->upload }}"><i class="fas fa-trash-alt"></i></button>
                    </td>
                
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="text-center">No students found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        {{ $studentList->withQueryString()->links() }}
      </div>
    </div>
  </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="studentDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('student/delete') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Delete Student</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this student?
          <input type="hidden" name="id" id="delete-id">
          <input type="hidden" name="avatar" id="delete-avatar">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Delete</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
@section('script')
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const { jsPDF } = window.jspdf;

      document.getElementById('downloadPdfBtn').addEventListener('click', () => {
      
        const doc = new jsPDF({ unit: 'in', format: 'letter', orientation: 'landscape' });
        doc.text('Students List', 0.5, 0.5);

        
        doc.autoTable({
          html: '.datatable',        
          startY: 0.8,               
          margin: { left: 0.5, right: 0.5 },
          styles: { fontSize: 8 },   
          headStyles: { fillColor: [41, 128, 185] } 
        });

        doc.save('students_list.pdf');
      });
    });
  </script>

@endsection
@endsection
