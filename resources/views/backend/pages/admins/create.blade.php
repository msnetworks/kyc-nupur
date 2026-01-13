
@extends('backend.layouts.master')

@section('title')
Admin Create - Admin Panel
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .form-check-label {
        text-transform: capitalize;
    }
</style>
@endsection


@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Admin Create</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.admins.index') }}">All Admins</a></li>
                    <li><span>Create Admin</span></li>
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
                    <h4 class="header-title">Create New Role</h4>
                    @include('backend.layouts.partials.messages')
                    
                    <form action="{{ route('admin.admins.store') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-4 col-sm-12">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                            </div>
                            <div class="form-group col-md-4 col-sm-12">
                                <label for="mobile">Mobile Number</label>
                                <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter Mobile Number" pattern="\d{10}">
                            </div>
                            <div class="form-group col-md-4 col-sm-12">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Enter Password">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="password">Assign Roles</label>
                                <select name="roles[]" id="roles" class="form-control select2" multiple>
                                    <option value="">--Select Roles--</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="bank">Assign Bank</label>
                                <select name="bank[]" id="bank" class="form-control select2" multiple>
                                    <option value="">--Select Banks--</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div id="agent-dropdown-container" class="form-group col-md-6 col-sm-6 d-none">
                                <label for="userSelect">Assign Default Agent</label>
                                <select name="default_agent_assign" id="userSelect" class="form-control">
                                </select>
                            </div>
                            <div id="branch-dropdown-container" class="form-group col-md-6 col-sm-6 d-none">
                                <label for="branchSelect">Assign Branch</label>
                                <select name="branch_assign" id="branchSelect" class="form-control">
                                </select>
                            </div>
                            
                        </div>
                        <!-- Container for the dynamically added agent dropdown -->
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-6">
                                <label for="username">Admin Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" required>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save Admin</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- data table end -->
        
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('#roles').on('change', function () {
            let selectedRoles = $(this).val();
            // Check if "bank" is among the selected roles
            if (selectedRoles && selectedRoles.includes('Bank')) {
                var customGetPath = "{{ route('admin.users.agent','1')}}";
                $('#agent-dropdown-container').removeClass('d-none');
                // Make an AJAX request to fetch agents
                $.ajax({
                    url: customGetPath,
                    type: 'GET',
                    success: function(response) {
                        var select = $('#userSelect');
                        select.empty(); // Clear any existing options
                        select.append('<option value="">--Select Option--</option>'); // Add default option
                        $.each(response, function(key, users) {
                            $.each(users, function(index, user) {
                                console.log(user);
                                var option = $('<option></option>')
                                    .attr('value', user.id)
                                    .text(user.name);
                                select.append(option);
                            });

                            $(".case_fi_type_id").val(selectedIdsJson);
                            $('#exampleModal').modal('show');
                        });
                    },
                    error: function() {
                        alert('Request failed');
                    }
                });
            } else {
                $('#agent-dropdown-container').addClass('d-none')
            }
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('#roles, #bank').on('change', function () {
            let selectedRoles = $('#roles').val();
            // Check if "bank" is among the selected roles
            if (selectedRoles && selectedRoles.includes('Bank')) {
                var customGetPath = "{{ route('get.branches','__bankId__')}}";
                $('#branch-dropdown-container').removeClass('d-none');
                // Make an AJAX request to fetch agents
                let bankId = $('#bank').val()
                if (bankId) {
                // Clear existing options and add a loading message
                $('#branchSelect').html('<option value="">Loading...</option>');

                // Make AJAX call to fetch branches
                $.ajax({
                    url: '{{ route('get.branches', ':bankId') }}'.replace(':bankId', bankId),
                    type: 'GET',
                    success: function (data) {
                        console.log(data);
                        // Clear and populate branch dropdown
                        $('#branchSelect').empty().append('<option value="">--Select Option--</option>');
                        $.each(data, function (key, branch) {
                            $('#branchSelect').append('<option value="' + branch.id + '">' + branch.branch_code + '</option>');
                        });
                    },
                    error: function () {
                        alert('Unable to fetch branch codes. Please try again.');
                    }
                });
            } else {
                // Reset branch dropdown if no bank selected
                $('#branchSelect').html('<option value="">--Select Option--</option>');
            }
            } else {
                $('#branch-dropdown-container').addClass('d-none')
            }
        });
    })
</script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    })
</script>
@endsection