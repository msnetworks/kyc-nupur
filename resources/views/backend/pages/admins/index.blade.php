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
                    @if (Auth::guard('admin')->user()->can('admin.edit'))
                    <form id="bulkBlockForm" action="{{ route('admin.admins.bulkBlock') }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" id="bulkAction" value="block">
                        <div class="mb-3" id="bulkActions" style="display: none;">
                            <span id="selectedCount" class="mr-2 font-weight-bold">0 selected</span>
                            <button type="submit" class="btn btn-warning btn-sm mr-1" onclick="document.getElementById('bulkAction').value='block'">
                                <i class="fa fa-ban"></i> Block Selected
                            </button>
                            <button type="submit" class="btn btn-success btn-sm" onclick="document.getElementById('bulkAction').value='unblock'">
                                <i class="fa fa-unlock"></i> Unblock Selected
                            </button>
                        </div>
                    </form>
                    @endif
                    <div class="data-tables">
                        @include('backend.layouts.partials.messages')
                        <table id="dataTable" class="text-center">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    @if (Auth::guard('admin')->user()->can('admin.edit'))
                                    <th width="3%"><input type="checkbox" id="selectAll"></th>
                                    @endif
                                    <th width="5%">Sl</th>
                                    <th width="10%">Name</th>
                                    <th width="10%">UserName</th>
                                    <th width="10%">Email</th>
                                    <th width="10%">Mobile</th>
                                    <th width="20%">Password</th>
                                    <th width="20%">Roles</th>
                                    <th width="20%">Banks</th>
                                    <th width="20%">Assigned Users</th>
                                    <th width="15%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($admins as $admin)
                                @if($admin->id != 1)
                                <tr>
                                    @if (Auth::guard('admin')->user()->can('admin.edit'))
                                    <td><input type="checkbox" class="admin-checkbox" form="bulkBlockForm" name="admin_ids[]" value="{{ $admin->id }}"></td>
                                    @endif
                                    <td>{{ $loop->index }}</td>
                                    <td>{{ $admin->name }}
                                        @if($admin->is_blocked)
                                            <span class="badge badge-danger">Blocked</span>
                                        @endif
                                    </td>
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
                                        @php
                                            $assignedUserIds = explode(',', trim($admin->assigned_users ?? ''));
                                            $assignedUsers = !empty($assignedUserIds[0]) ? DB::table('users')->whereIn('id', $assignedUserIds)->get() : collect();
                                        @endphp
                                        @if($assignedUsers->isEmpty())
                                            <span class="badge badge-secondary">None</span>
                                        @else
                                            @foreach ($assignedUsers->take(2) as $user)
                                                <span class="badge badge-success mr-1">
                                                    {{ $user->name }}
                                                </span>
                                            @endforeach
                                            @if($assignedUsers->count() > 2)
                                                <button class="btn btn-sm btn-outline-primary m-2" data-toggle="modal" data-target="#viewAllUsersModal-{{ $admin->id }}">
                                                    View More ({{ $assignedUsers->count() - 2 }})
                                                </button>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton-{{ $admin->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-cog"></i> Actions
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-{{ $admin->id }}">
                                                @if (Auth::guard('admin')->user()->can('admin.edit'))
                                                <a class="dropdown-item" href="{{ route('admin.admins.edit', $admin->id) }}">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <a class="dropdown-item" href="#" data-assign-users-btn data-admin-id="{{ $admin->id }}" data-admin-name="{{ $admin->name }}" data-assigned-users="{{ $admin->assigned_users ?? '' }}" onclick="quickOpenModal({{ $admin->id }}, '{{ $admin->name }}', '{{ $admin->assigned_users ?? '' }}'); return false;">
                                                    <i class="fa fa-users"></i> Assign Users
                                                </a>
                                                @endif

                                                @if (Auth::guard('admin')->user()->can('admin.edit'))
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item {{ $admin->is_blocked ? 'text-success' : 'text-warning' }}" href="#" onclick="event.preventDefault(); document.getElementById('block-form-{{ $admin->id }}').submit();">
                                                    <i class="fa {{ $admin->is_blocked ? 'fa-unlock' : 'fa-ban' }}"></i> {{ $admin->is_blocked ? 'Unblock' : 'Block' }}
                                                </a>
                                                <form id="block-form-{{ $admin->id }}" action="{{ route('admin.admins.toggleBlock', $admin->id) }}" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                                @endif

                                                @if (Auth::guard('admin')->user()->can('admin.delete'))
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="{{ route('admin.admins.destroy', $admin->id) }}" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $admin->id }}').submit();">
                                                    <i class="fa fa-trash"></i> Delete
                                                </a>
                                                <form id="delete-form-{{ $admin->id }}" action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" style="display: none;">
                                                    @method('DELETE')
                                                    @csrf
                                                </form>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Store admin data for JavaScript -->
                        @foreach ($admins as $admin)
                            @if($admin->id != 1)
                            <div style="display:none;" data-admin-id="{{ $admin->id }}" data-admin-name="{{ $admin->name }}" data-assigned-users="{{ $admin->assigned_users ?? '' }}"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <!-- data table end -->

    </div>
</div>

<!-- Single Reusable Modal for Assigning Users -->
<div class="modal fade" id="assignUsersModal" tabindex="-1" role="dialog" aria-labelledby="assignUsersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignUsersModalLabel">Assign Users</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="assignUsersForm" action="" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label><strong>Select Users:</strong></label>
                        <input type="text" class="form-control mb-3" id="userSearch" placeholder="Search users...">
                        <div class="user-list" id="userList" style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 4px;">
                            <!-- Users will be loaded here via JavaScript -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View All Assigned Users Modals -->
@foreach ($admins as $admin)
    @if($admin->id != 1)
        @php
            $assignedUserIds = explode(',', trim($admin->assigned_users ?? ''));
            $assignedUsers = !empty($assignedUserIds[0]) ? DB::table('users')->whereIn('id', $assignedUserIds)->get() : collect();
        @endphp
        @if($assignedUsers->count() > 2)
        <div class="modal fade" id="viewAllUsersModal-{{ $admin->id }}" tabindex="-1" role="dialog" aria-labelledby="viewAllUsersModalLabel-{{ $admin->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewAllUsersModalLabel-{{ $admin->id }}">Assigned Users - {{ $admin->name }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="list-group">
                            @foreach ($assignedUsers as $user)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">{{ $user->name }}</h6>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                        <span class="badge badge-success">Active</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif
@endforeach
@endsection
<!-- jQuery (required for Bootstrap and DataTables) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Start datatable js -->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

<script>
    // Global variables for modal
    let currentAdminId = null;
    let allUsersData = [];
    let adminData = {}; // Store admin info by ID

    // Load users on page load
    $(document).ready(function() {
        /*================================
            datatable active
            ==================================*/
        if ($('#dataTable').length) {
            $('#dataTable').DataTable();
        }

        loadAllUsers();
        loadAdminData();
        setupEventHandlers();
    });

    // Load all users once from controller
    function loadAllUsers() {
        allUsersData = {!! json_encode($users) !!};
        console.log('Users loaded:', allUsersData.length);
    }

    // Load admin data from hidden divs
    function loadAdminData() {
        $('[data-admin-id]').each(function() {
            const adminId = $(this).data('admin-id');
            adminData[adminId] = {
                name: $(this).data('admin-name'),
                assignedUsers: $(this).data('assigned-users') || ''
            };
        });
        console.log('Admin data loaded:', adminData);
    }

    // Setup event handlers
    function setupEventHandlers() {
        // Handle modal open from dropdown
        $(document).on('click', '[data-assign-users-btn]', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const adminId = $(this).data('admin-id');
            console.log('Opening modal for admin:', adminId);
            console.log('Admin data:', adminData[adminId]);
            
            if (allUsersData.length === 0) {
                console.error('No users loaded!');
                alert('Error: Users data not loaded');
                return;
            }
            
            openAssignUsersModal(adminId);
            
            // Show modal using Bootstrap 4
            try {
                $('#assignUsersModal').modal('show');
                console.log('Modal should now be visible');
            } catch(err) {
                console.error('Error showing modal:', err);
                alert('Error opening modal. Please check browser console.');
            }
        });

        // Search users
        $(document).on('keyup', '#userSearch', function() {
            searchUsers();
        });
    }

    // Open assign users modal
    function openAssignUsersModal(adminId) {
        currentAdminId = adminId;
        const admin = adminData[adminId] || {};
        
        $('#assignUsersModalLabel').text('Assign Users to ' + (admin.name || 'Admin'));
        $('#assignUsersForm').attr('action', '/admin/admins/' + adminId + '/assign-users');
        
        // Get currently assigned users for this admin
        const assignedUserIds = admin.assignedUsers ? admin.assignedUsers.split(',').map(Number) : [];
        
        // Render user list
        renderUserList(allUsersData, assignedUserIds);
    }

    // Render user list
    function renderUserList(users, assignedIds = []) {
        let html = '';
        users.forEach(user => {
            const isChecked = assignedIds.includes(user.id) ? 'checked' : '';
            html += `
                <div class="form-check mt-2">
                    <input class="form-check-input user-checkbox" type="checkbox" name="assigned_users[]" value="${user.id}" 
                        id="user-${currentAdminId}-${user.id}" ${isChecked}>
                    <label class="form-check-label" for="user-${currentAdminId}-${user.id}">
                        ${user.name} (${user.email})
                    </label>
                </div>
            `;
        });
        $('#userList').html(html);
    }

    // Search users
    function searchUsers() {
        const searchTerm = $('#userSearch').val().toLowerCase();
        const filteredUsers = allUsersData.filter(user => 
            user.name.toLowerCase().includes(searchTerm) || 
            user.email.toLowerCase().includes(searchTerm)
        );
        
        const admin = adminData[currentAdminId] || {};
        const assignedUserIds = admin.assignedUsers ? admin.assignedUsers.split(',').map(Number) : [];
        
        renderUserList(filteredUsers, assignedUserIds);
    }

    // Quick open modal function
    function quickOpenModal(adminId, adminName, assignedUsersStr) {
        console.log('Quick opening modal for admin:', adminId, adminName);
        currentAdminId = adminId;
        
        $('#assignUsersModalLabel').text('Assign Users to ' + adminName);
        $('#assignUsersForm').attr('action', '/admin/admins/' + adminId + '/assign-users');
        
        const assignedIds = assignedUsersStr ? assignedUsersStr.split(',').map(Number) : [];
        renderUserList(allUsersData, assignedIds);
        
        $('#assignUsersModal').modal('show');
    }

    // Clear search when modal closes
    $('#assignUsersModal').on('hidden.bs.modal', function() {
        $('#userSearch').val('');
        currentAdminId = null;
    });

    // Bulk select functionality
    function updateBulkActions() {
        const checked = $('.admin-checkbox:checked').length;
        $('#selectedCount').text(checked + ' selected');
        if (checked > 0) {
            $('#bulkActions').show();
        } else {
            $('#bulkActions').hide();
        }
    }

    $('#selectAll').on('change', function() {
        $('.admin-checkbox').prop('checked', this.checked);
        updateBulkActions();
    });

    $(document).on('change', '.admin-checkbox', function() {
        if (!this.checked) {
            $('#selectAll').prop('checked', false);
        } else if ($('.admin-checkbox:checked').length === $('.admin-checkbox').length) {
            $('#selectAll').prop('checked', true);
        }
        updateBulkActions();
    });
</script>