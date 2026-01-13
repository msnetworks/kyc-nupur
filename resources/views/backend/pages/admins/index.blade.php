@extends('backend.layouts.master')

@section('title')
Admins - Admin Panel
@endsection

@section('styles')
<!-- Start datatable css -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection


@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Admins</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><span>All Admins</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            @include('backend.layouts.partials.logout')
        </div>
    </div>
</div>
<!-- page title area end -->

<div class="main-content-inner">
    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title float-left">Admins List</h4>
                    <div class="float-right mb-2">
                        @if (Auth::guard('admin')->user()->can('admin.view'))
                        <a class="btn btn-success text-white mr-2" href="{{ route('admin.admins.export') }}">
                            <i class="fa fa-download"></i> Export to Excel
                        </a>
                        @endif
                        @if (Auth::guard('admin')->user()->can('admin.edit'))
                        <a class="btn btn-primary text-white" href="{{ route('admin.admins.create') }}">
                            <i class="fa fa-plus"></i> Create New Admin
                        </a>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                    <div class="data-tables">
                        @include('backend.layouts.partials.messages')
                        <table id="dataTable" class="text-center">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="5%">Sl</th>
                                    <th width="10%">Name</th>
                                    <th width="10%">UserName</th>
                                    <th width="10%">Email</th>
                                    <th width="10%">Mobile</th>
                                    <th width="20%">Password</th>
                                    <th width="20%">Roles</th>
                                    <th width="20%">Banks</th>
                                    <th width="15%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($admins as $admin)
                                @if($admin->id != 1)
                                <tr>
                                    <td>{{ $loop->index }}</td>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->username }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>{{ $admin->mobile }}</td>
                                    <td>{{ $admin->view_password }}</td>
                                    <td>
                                        @foreach ($admin->roles as $role)
                                        <span class="badge badge-info mr-1">
                                            {{ $role->name }}
                                        </span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @php
                                            $bankIds = explode(',', $admin->banks_assign);
                                            $banks = DB::table('banks')->whereIn('id',$bankIds)->get(); 
                                        @endphp
                                        @foreach ($banks as $bank)
                                            @if (isset($bank->name)) <!-- Check if the bank exists in the retrieved collection -->
                                                <span class="badge badge-info mr-1">
                                                    {{ $bank->name }} <!-- Display the bank name -->
                                                </span>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @if (Auth::guard('admin')->user()->can('admin.edit'))
                                        <a class="btn btn-success text-white" href="{{ route('admin.admins.edit', $admin->id) }}">Edit</a>
                                        @endif

                                        @if (Auth::guard('admin')->user()->can('admin.delete'))
                                        <a class="btn btn-danger text-white" href="{{ route('admin.admins.destroy', $admin->id) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $admin->id }}').submit();">
                                            Delete
                                        </a>
                                        <form id="delete-form-{{ $admin->id }}" action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" style="display: none;">
                                            @method('DELETE')
                                            @csrf
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                        @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- data table end -->

    </div>
</div>
@endsection


@section('scripts')
<!-- Start datatable js -->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

<script>
    /*================================
        datatable active
        ==================================*/
    if ($('#dataTable').length) {
        $('#dataTable').DataTable({
            responsive: true
        });
    }
</script>
@endsection