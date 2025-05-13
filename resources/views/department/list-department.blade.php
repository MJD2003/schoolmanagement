@extends('layouts.master')
@section('content')
{{-- message --}}
{!! Toastr::message() !!}
<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Departments</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Departments</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="student-group-form mb-3">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-2">
                    <div class="form-group">
                        <input type="text" class="form-control" id="filter-dept-id" placeholder="Search by ID ...">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-2">
                    <div class="form-group">
                        <input type="text" class="form-control" id="filter-dept-name" placeholder="Search by Name ...">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-2">
                    <div class="form-group">
                        <input type="text" class="form-control" id="filter-dept-year" placeholder="Search by Year ...">
                    </div>
                </div>
                <div class="col-lg-2 mb-2">
                    <div class="search-student-btn">
                        <button type="button" class="btn btn-primary" id="btn-clear-filters">Clear</button>
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
                                    <h3 class="page-title">Departments</h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    <button id="btn-download-pdf" class="btn btn-outline-primary me-2">
                                        <i class="fas fa-download"></i> Download PDF
                                    </button>
                                 
                                    <a href="{{ route('department/add/page') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                          <table class="table table-striped table-hover table-center mb-0" id="dataList">
                              <thead class="student-thread">
                                  <tr>
                                      <th>ID</th>
                                      <th>Name</th>
                                      <th>HOD</th>
                                      <th>Started Year</th>
                                      <th>No of Students</th>
                                      <th class="text-end">Action</th>
                                  </tr>
                              </thead>
                              <tbody>
                            
                              </tbody>
                          </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- delete modal --}}
<div class="modal custom-modal fade" id="delete" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>Delete Department</h3>
                    <p>Are you sure want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <form action="{{ route('department/delete') }}" method="POST">
                            @csrf
                            <input type="hidden" name="department_id" class="e_department_id" value="">
                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary paid-continue-btn" style="width: 100%;">Delete</button>
                                </div>
                                <div class="col-6">
                                    <a data-bs-dismiss="modal" class="btn btn-primary paid-cancel-btn">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#dataList').DataTable({
                processing: true,
                serverSide: true,
                ordering: true,
                searching: true,
                ajax: { url: "{{ route('get-data-list') }}" },
                columns: [
                    { data: 'department_id', name: 'department_id' },
                    { data: 'department_name', name: 'department_name' },
                    { data: 'head_of_department', name: 'head_of_department' },
                    { data: 'department_start_date', name: 'department_start_date' },
                    { data: 'no_of_students', name: 'no_of_students' },
                    { data: 'modify', name: 'modify' },
                ]
            });
        });
    </script>

    <script>
        $(document).on('click', '.delete', function() {
            var _this = $(this).closest('tr');
            $('.e_department_id').val(_this.find('.department_id').data('department_id'));
        });
    </script>

 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const idFilter     = document.getElementById('filter-dept-id');
        const nameFilter   = document.getElementById('filter-dept-name');
        const yearFilter   = document.getElementById('filter-dept-year');
        const clearBtn     = document.getElementById('btn-clear-filters');
        const downloadBtn  = document.getElementById('btn-download-pdf');
        const table        = document.getElementById('dataList');

        function applyFilters() {
            const idVal   = idFilter.value.trim().toLowerCase();
            const nameVal = nameFilter.value.trim().toLowerCase();
            const yearVal = yearFilter.value.trim().toLowerCase();

            Array.from(table.tBodies[0].rows).forEach(row => {
                const cells     = row.cells;
                const matchId    = !idVal   || cells[0].textContent.toLowerCase().includes(idVal);
                const matchName  = !nameVal || cells[1].textContent.toLowerCase().includes(nameVal);
                const matchYear  = !yearVal || cells[3].textContent.toLowerCase().includes(yearVal);

                row.style.display = (matchId && matchName && matchYear) ? '' : 'none';
            });
        }

        [idFilter, nameFilter, yearFilter].forEach(input =>
            input.addEventListener('input', applyFilters)
        );

        clearBtn.addEventListener('click', () => {
            idFilter.value = nameFilter.value = yearFilter.value = '';
            applyFilters();
        });

        const { jsPDF } = window.jspdf;
        downloadBtn.addEventListener('click', () => {
            const doc = new jsPDF({ unit: 'pt', format: 'a4' });
            doc.text('Departments List', 40, 50);
            doc.autoTable({
                html: '#dataList',
                startY: 70,
                theme: 'striped'
            });
            doc.save('departments_list.pdf');
        });
    });
    </script>
@endsection
@endsection
