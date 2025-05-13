@extends('layouts.master')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Subjects</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Subjects</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="student-group-form mb-3">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-2">
                        <div class="form-group">
                            <input type="text" class="form-control" id="filter-subj-id" placeholder="Search by ID ...">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-2">
                        <div class="form-group">
                            <input type="text" class="form-control" id="filter-subj-name" placeholder="Search by Name ...">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-2">
                        <div class="form-group">
                            <input type="text" class="form-control" id="filter-subj-class" placeholder="Search by Class ...">
                        </div>
                    </div>
                    <div class="col-lg-2 mb-2">
                        <div class="search-student-btn">
                            <button type="button" class="btn btn-secondary" id="btn-clear-subj-filters">Clear</button>
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
                                        <h3 class="page-title">Subjects</h3>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <button id="btn-download-subj-pdf" class="btn btn-outline-primary me-2">
                                            <i class="fas fa-download"></i> Download PDF
                                        </button>
                                        <a href="{{ route('subject/add/page') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table
                                    class="table border-0 star-student table-hover table-center mb-0 datatable table-striped"
                                    id="subjectsTable">
                                    <thead class="student-thread">
                                        <tr>
                                            <th>
                                                <div class="form-check check-tables">
                                                    <input class="form-check-input" type="checkbox" value="all">
                                                </div>
                                            </th>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Class</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($subjectList as $value)
                                        <tr>
                                            <td>
                                                <div class="form-check check-tables">
                                                    <input class="form-check-input" type="checkbox" value="{{ $value->subject_id }}">
                                                </div>
                                            </td>
                                            <td class="col-subj-id">{{ $value->subject_id }}</td>
                                            <td class="col-subj-name">
                                                <h2><a>{{ $value->subject_name }}</a></h2>
                                            </td>
                                            <td class="col-subj-class">{{ $value->class }}</td>
                                            <td class="text-end">
                                                <div class="actions">
                                                    <a href="{{ url('subject/edit/'.$value->subject_id) }}"
                                                       class="btn btn-sm bg-info-light me-1">
                                                        <i class="far fa-edit"></i>
                                                    </a>
                                                    <button type="button"
                                                            class="btn btn-sm bg-danger-light subj-delete"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#delete"
                                                            data-id="{{ $value->subject_id }}">
                                                        <i class="fe fe-trash-2"></i>
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

    {{-- delete modal --}}
    <div class="modal custom-modal fade" id="delete" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Delete Subject</h3>
                        <p>Are you sure want to delete?</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <form action="{{ route('subject/delete') }}" method="POST">
                            @csrf
                            <input type="hidden" name="subject_id" class="e_subject_id" value="">
                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-danger continue-btn w-100">
                                        Delete
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@section('script')
    {{-- jquery delete handler --}}
    <script>
        $(document).on('click','.subj-delete',function() {
            const id = $(this).data('id');
            $('.e_subject_id').val(id);
        });
    </script>

    {{-- jsPDF & AutoTable CDN --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const idFilter     = document.getElementById('filter-subj-id');
        const nameFilter   = document.getElementById('filter-subj-name');
        const classFilter  = document.getElementById('filter-subj-class');
        const clearBtn     = document.getElementById('btn-clear-subj-filters');
        const downloadBtn  = document.getElementById('btn-download-subj-pdf');
        const table        = document.getElementById('subjectsTable');

        function applyFilters() {
            const idVal    = idFilter.value.trim().toLowerCase();
            const nameVal  = nameFilter.value.trim().toLowerCase();
            const classVal = classFilter.value.trim().toLowerCase();

            Array.from(table.tBodies[0].rows).forEach(row => {
                const cells      = row.cells;
                const matchId     = !idVal    || cells[1].textContent.toLowerCase().includes(idVal);
                const matchName   = !nameVal  || cells[2].textContent.toLowerCase().includes(nameVal);
                const matchClass  = !classVal || cells[3].textContent.toLowerCase().includes(classVal);

                row.style.display = (matchId && matchName && matchClass) ? '' : 'none';
            });
        }

        [idFilter, nameFilter, classFilter].forEach(input =>
            input.addEventListener('input', applyFilters)
        );

        clearBtn.addEventListener('click', () => {
            idFilter.value = nameFilter.value = classFilter.value = '';
            applyFilters();
        });

        const { jsPDF } = window.jspdf;
        downloadBtn.addEventListener('click', () => {
            const doc = new jsPDF({ unit: 'pt', format: 'a4' });
            doc.text('Subjects List', 40, 50);
            doc.autoTable({
                html: '#subjectsTable',
                startY: 70,
                theme: 'striped'
            });
            doc.save('subjects_list.pdf');
        });
    });
    </script>
@endsection

@endsection
