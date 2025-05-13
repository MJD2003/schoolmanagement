@extends('layouts.master')

@section('content')
    {{-- flash messages --}}
    {!! Toastr::message() !!}

    <div class="page-wrapper">
        <div class="content container-fluid">

         
            @php
                // total invoices
                $totalCount   = $invoiceList->count();
                $totalAmount  = $invoiceList->sum('total_amount');

                // by status
                $paidCount    = $invoiceList->where('status', 'Paid')->count();
                $paidAmount   = $invoiceList->where('status', 'Paid')->sum('total_amount');

                $overdueCount = $invoiceList->where('status', 'Overdue')->count();
                $overdueAmt   = $invoiceList->where('status', 'Overdue')->sum('total_amount');

                $cancelCount  = $invoiceList->where('status', 'Cancelled')->count();
                $cancelAmt    = $invoiceList->where('status', 'Cancelled')->sum('total_amount');

                $unpaidList   = $invoiceList->filter(fn($i) => !in_array($i->status, ['Paid','Cancelled']));
                $unpaidCount  = $unpaidList->count();
                $unpaidAmt    = $unpaidList->sum('total_amount');
            @endphp

            <style>
          
            .filter-bar {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                gap: 1rem;
                margin-bottom: 1.5rem;
            }
            .filter-bar .form-control {
                min-width: 150px;
                transition: box-shadow .2s;
            }
            .filter-bar .form-control:focus {
                box-shadow: 0 0 .25rem rgba(0,123,255,.5);
            }
            .filter-bar .btn {
                padding: .5rem 1.25rem;
                transition: background-color .2s, transform .1s;
            }
            .filter-bar .btn:hover {
                transform: translateY(-1px);
            }
            </style>

            <div class="page-header d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="page-title mb-0">Invoices</h3>
                    <ul class="breadcrumb p-0 mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Invoices</li>
                    </ul>
                </div>
                <div>
                    <a href="{{ route('invoice/list/page') }}" class="invoices-links active me-2"><i class="fa fa-list"></i></a>
                    <a href="{{ route('invoice/grid/page') }}" class="invoices-links"><i class="fa fa-th"></i></a>
                </div>
            </div>

            <div class="card report-card mb-4">
                <div class="card-body">
                    <div class="filter-bar">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" id="customerName" class="form-control" placeholder="Customer Name">
                        </div>

                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            <input type="date" id="dateFrom" class="form-control" placeholder="From">
                            <input type="date" id="dateTo" class="form-control" placeholder="To">
                        </div>

                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-book-open"></i></span>
                            <select id="statusSelect" class="form-select">
                                <option value="">All Statuses</option>
                                <option>Paid</option>
                                <option>Overdue</option>
                                <option>Draft</option>
                                <option>Recurring</option>
                                <option>Cancelled</option>
                            </select>
                        </div>

                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-bookmark"></i></span>
                            <input type="text" id="categoryName" class="form-control" placeholder="Category">
                        </div>

                        <button id="btnSearch" class="btn btn-primary"><i class="fas fa-search me-1"></i>Search</button>
                        <button id="btnClear" class="btn btn-outline-secondary"><i class="fas fa-eraser me-1"></i>Clear</button>
                    </div>
                </div>
            </div>


            <div class="d-flex justify-content-center align-items-center mb-4">
                <a href="{{ route('invoice/add/page') }}" class="btn btn-success">
                    <i class="feather feather-plus-circle me-1"></i>New Invoice
                </a>
            </div>

         
            <div class="row mb-4 text-center">
                @foreach([
                    ['icon1.svg','All Invoices',$totalAmount,$totalCount],
                    ['icon2.svg','Paid',$paidAmount,$paidCount],
                    ['icon3.svg','Unpaid',$unpaidAmt,$unpaidCount],
                    ['icon4.svg','Cancelled',$cancelAmt,$cancelCount],
                ] as [$icon,$label,$amount,$count])
                    <div class="col-lg-3 col-sm-6 mb-3">
                        <div class="card inovices-card h-100 shadow-sm">
                            <div class="card-body">
                                <img src="{{ URL::to("assets/img/icons/{$icon}") }}" class="mb-2" alt="">
                                <h5 class="mb-1">${{ number_format($amount, 2) }}</h5>
                                <p class="mb-0 small text-muted">{{ $label }} ({{ $count }})</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- invoices table --}}
            <div class="card card-table shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="invoicesTable" class="table table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th><th>Category</th><th>Created</th>
                                    <th>Customer</th><th>Amount</th><th>Due</th><th>Status</th><th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoiceList as $i)
                                <tr>
                                    <td><a href="{{ url('invoice/edit/'.$i->invoice_id) }}">{{ $i->invoice_id }}</a></td>
                                    <td class="cell-category">{{ $i->category }}</td>
                                    <td class="cell-created">{{ \Carbon\Carbon::parse($i->created_at)->format('Y-m-d') }}</td>
                                    <td class="cell-customer">{{ $i->customer_name }}</td>
                                    <td>${{ number_format($i->total_amount,2) }}</td>
                                    <td class="cell-due">{{ \Carbon\Carbon::parse($i->due_date)->format('Y-m-d') }}</td>
                                    <td class="cell-status">{{ $i->status }}</td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <a href="#" class="action-icon" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="{{ url('invoice/edit/'.$i->invoice_id) }}">
                                                    <i class="far fa-edit me-1"></i>Edit</a>
                                                </li>
                                                <li><a class="dropdown-item" href="{{ url('invoice/view/'.$i->invoice_id) }}">
                                                    <i class="far fa-eye me-1"></i>View</a>
                                                </li>
                                                <li><a class="dropdown-item text-danger" href="#">
                                                    <i class="far fa-trash-alt me-1"></i>Delete</a>
                                                </li>
                                            </ul>
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
@endsection

@section('script')
<script>
document.getElementById('btnSearch').addEventListener('click', filterTable);
document.getElementById('btnClear').addEventListener('click', clearFilters);

function filterTable() {
    const name = document.getElementById('customerName').value.toLowerCase(),
          cat  = document.getElementById('categoryName').value.toLowerCase(),
          from = document.getElementById('dateFrom').value,
          to   = document.getElementById('dateTo').value,
          stat = document.getElementById('statusSelect').value;

    document.querySelectorAll('#invoicesTable tbody tr').forEach(r => {
        const cName    = r.querySelector('.cell-customer').textContent.toLowerCase(),
              cCat     = r.querySelector('.cell-category').textContent.toLowerCase(),
              cCreated = r.querySelector('.cell-created').textContent,
              cStat    = r.querySelector('.cell-status').textContent;

        let visible = true;
        if (name && !cName.includes(name))          visible = false;
        if (cat  && !cCat.includes(cat))            visible = false;
        if (from && cCreated < from)                visible = false;
        if (to   && cCreated > to)                  visible = false;
        if (stat && stat !== '' && cStat !== stat)  visible = false;

        r.style.display = visible ? '' : 'none';
    });
}

function clearFilters() {
    ['customerName','categoryName','dateFrom','dateTo'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('statusSelect').value = '';
    document.querySelectorAll('#invoicesTable tbody tr').forEach(r => r.style.display = '');
}
</script>
@endsection
