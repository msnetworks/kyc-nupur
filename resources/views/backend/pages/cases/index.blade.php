@extends('backend.layouts.master')

@section('title')
Cases - Admin Panel
@endsection

@section('styles')
<!-- Start datatable css -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
<style>
    svg.w-5 {
        height: 15px;
    }
    .leading-5{
        margin: 15px;
    }
</style>
@endsection


@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Cases</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><span>All Cases</span></li>
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
                    <h4 class="header-title float-left">Cases List</h4>
                    @if(auth()->check())
                        @if(auth()->user()->can('case.create'))
                            <p class="float-right mb-2">
                                <a class="btn btn-primary text-white" href="{{ route('admin.case.create') }}">Create New Cases</a>
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
                                    <th width="10%">Refrence Number</th>
                                    <th width="10%">Name</th>
                                    <th width="10%">amount</th>
                                    <th width="15%">View</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cases as $user)
                                <tr>
                                    <td>{{ ($cases->firstItem() ?? 0) + $loop->index }}</td>
                                    <td>{{ $user->refrence_number }}</td>
                                    <td>{{ $user->applicant_name }}</td>
                                    <td>{{ $user->amount }}</td>
                                    <td>
                                        <a href="{{ route('admin.case.show', $user->id) }}"><img src="{{URL::asset('backend/assets/images/icons/user.png')}}" title="View"></img></a>
                                        {{-- <a href="{{ route('admin.case.edit', $user->id) }}"><img src="{{URL::asset('backend/assets/images/icons/edit.png')}}" title="Edit"></img></a> --}}
                                        {{-- <a href="{{ route('admin.case.edit', $user->id) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $user->id }}').submit();">
                                            <img src="{{URL::asset('backend/assets/images/icons/delete.png')}}" title="Edit"></img>
                                        </a> --}}

                                        {{-- <form id="delete-form-{{ $user->id }}" action="{{ route('admin.case.destroy', $user->id) }}" method="POST" style="display: none;">
                                            @method('DELETE')
                                            @csrf
                                        </form> --}}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 d-flex justify-content-center">
                        {{ $cases->appends(request()->query())->links('vendor.pagination.custom') }}
                    </div> 

                    <div class="col-md-12">
                        {{-- {{dd(session('failedRows')[0]['errors']) }} --}}
                        @if(Session::has('failedRows') && count(session('failedRows')) > 0)
                            <div class="alert alert-danger">
                                <strong>Some rows failed to process:</strong>
                                <ul>
                                    @foreach(session('failedRows') as $failedRow)
                                        <li>
                                            <strong>Row:</strong> {{ $failedRow['row'][0] }}<br>
                                            <strong>Errors:</strong>
                                            <ul>
                                                @foreach($failedRow['errors'] as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
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
            responsive: true,
            paging: false,
            info: false
        });
    }
</script>
@endsection