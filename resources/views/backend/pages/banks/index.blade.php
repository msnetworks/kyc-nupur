@extends('backend.layouts.master')

@section('title')
banks - Admin Panel
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
                <h4 class="page-title pull-left">Banks</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><span>All Banks</span></li>
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
                    <h4 class="header-title float-left">Banks List</h4>
                    @if(auth()->check())
                        @if(auth()->user()->can('bank.create'))
                            <p class="float-right mb-2">
                                <a class="btn btn-primary text-white" href="{{ route('admin.banks.create') }}">Create New banks</a>
                            </p>
                        @endif
                    @endif
                    <div class="clearfix"></div>
                    <div class="data-tables">
                        @include('backend.layouts.partials.messages')
                        <table id="dataTable" class="text-center">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="5%">Sl</th>
                                    <th width="10%">Name</th>
                                    <th width="10%">Code</th>
                                    {{-- @if(auth()->check())
                                        @if(auth()->user()->can('bank.edit') || auth()->user()->can('bank.delete') || auth()->user()->role == 'superadmin') --}}
                                            <th width="15%">Action</th>
                                        {{-- @endif
                                    @endif --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($banks as $bank)
                                <tr>
                                    <td class="text-left">{{ $loop->index+1 }}</td>
                                    <td class="text-left">{{ $bank->name }}</td>
                                    <td class="text-left">{{ $bank->branch_code }}</td>
                                    {{-- @if(auth()->check())
                                        @if(auth()->user()->can('bank.edit') || auth()->user()->can('bank.delete') || auth()->user()->role == 'superadmin') --}}
                                    <td>
                                        {{-- @if(auth()->check())
                                            @if(auth()->user()->can('bank.edit')  || auth()->user()->role == 'superadmin') --}}
                                                <a class="btn btn-success text-white" href="{{ route('admin.banks.edit', $bank->id) }}">Edit</a>

                                                {{-- <a class="btn btn-danger text-white" href="{{ route('admin.banks.edit', $bank->id) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $bank->id }}').submit();">
                                                    Delete
                                                </a> --}}
                                            {{-- @endif
                                        @endif --}}
                                        @if(auth()->check())
                                            @if(auth()->user()->can('bank.delete'))
                                                {{-- <form id="delete-form-{{ $bank->id }}" action="{{ route('admin.banks.destroy', $bank->id) }}" method="POST" style="display: none;">
                                                    @method('DELETE')
                                                    @csrf
                                                </form> --}}
                                            @endif
                                        @endif
                                    </td>
                                    {{-- @endif
                                @endif --}}
                                </tr>
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