 <!-- sidebar menu area start -->
 @php
 $usr = Auth::guard('admin')->user();
 @endphp
 <style>
    /* Toggle Button */
    .toggle-button {
        position: fixed;
        top: 15px;
        left: 15px;
        background-color: #2c3e50;
        color: white;
        border: none;
        font-size: 24px;
        cursor: pointer; 
        z-index: 1100;
        padding: 10px 15px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .toggle-button:hover {
        background-color: #34495e;
    }

    .metismenu {
        display: block !important;
        visibility: visible !important;
        z-index: 1000 !important;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .toggle-button {
            font-size: 20px;
            padding: 8px 12px;
        }
        .sidebar-menu {
            transition: left 0.3s ease-in-out;
        }
    }
 </style>
 <div class="sidebar-menu">
     <div class="sidebar-header">
         <div class="logo">
             <a href="{{ route('admin.dashboard') }}">
                 <h2 class="text-white">Admin</h2>
             </a>
         </div>
     </div>
     <button id="sidebar-toggle" class="toggle-button">
        ☰
    </button>
     <button id="sidebar-toggle-off" class="toggle-button d-none">
        ☰
    </button>
     <div class="main-menu">
         <div class="menu-inner" style="height: 100%!imprtant">
             <nav>
                 <ul class="metismenu" id="menu">

                     @if ($usr->can('dashboard.view'))

                     <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>

                     @endif

                     @if ($usr->can('case.create') || $usr->can('case.view') || $usr->can('case.edit') || $usr->can('case.delete'))
                     <li>
                         <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                                 Cases
                             </span></a>
                         <ul class="collapse {{ Route::is('admin.case.create') || Route::is('admin.case.index') || Route::is('admin.case.edit') || Route::is('admin.case.show') ? 'in' : '' }}">
                             @if ($usr->can('case.view'))
                             <li class="{{ Route::is('admin.case.index')  || Route::is('admin.case.edit') ? 'active' : '' }}"><a href="{{ route('admin.case.index') }}">Search Cases</a></li>
                             @endif
                             @if ($usr->can('case.create'))
                             <li class="{{ Route::is('admin.case.create')  ? 'active' : '' }}"><a href="{{ route('admin.case.create') }}">Create Case</a></li>
                             @endif
                             @if ($usr->can('case.import'))
                             <li class="{{ Route::currentRouteName() == 'admin.case.import.view' ? 'active' : '' }}">
                                 <a href="{{ route('admin.case.import.view', ['id' => '1']) }}">Import Case</a>
                             </li>
                             @endif

                         </ul>
                     </li>
                     @endif
                     @if ($usr->can('report.create') || $usr->can('report.view') || $usr->can('report.edit') || $usr->can('report.delete'))
                     <li>
                         <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                                 Reports
                             </span></a>
                         <ul class="collapse {{ Route::is('admin.reports.create') || Route::is('admin.reports.index') || Route::is('admin.reports.edit') || Route::is('admin.reports.show') ? 'in' : '' }}">
                             @if ($usr->can('report.view'))
                             <li class="{{ Route::is('admin.reports.index')  || Route::is('admin.reports.edit') ? 'active' : '' }}"><a href="{{ route('admin.reports.index') }}">Verified Data</a></li>
                             <li class="{{ Route::is('admin.reports.billing')  ? 'active' : '' }}"><a href="{{ route('admin.reports.billing') }}">Billing Report</a></li>
                             @endif
                             @if ($usr->can('report.create'))
                             <li class="{{ Route::is('admin.reports.create')  ? 'active' : '' }}"><a href="{{ route('countReport') }}">Verified Count Report</a></li>
                             @endif
                         </ul>
                     </li>
                     @endif
                     <!-- <li style="color: #ffffff;">--------------------------------------------------</li> -->
                     @if ($usr->can('fitype.create') || $usr->can('fitype.view') || $usr->can('fitype.edit') || $usr->can('fitype.delete'))
                     <li>
                         <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                                 FI Types
                             </span></a>
                         <ul class="collapse {{ Route::is('admin.fitypes.create') || Route::is('admin.fitypes.index') || Route::is('admin.fitypes.edit') || Route::is('admin.fitypes.show') ? 'in' : '' }}">
                             @if ($usr->can('fitype.view'))
                             <li class="{{ Route::is('admin.fitypes.index')  || Route::is('admin.fitypes.edit') ? 'active' : '' }}"><a href="{{ route('admin.fitypes.index') }}">Fi Type </a></li>
                             @endif
                             @if ($usr->can('role.create'))
                             <li class="{{ Route::is('admin.fitypes.create')  ? 'active' : '' }}"><a href="{{ route('admin.fitypes.create') }}">Create Fi Type</a></li>
                             @endif
                         </ul>
                     </li>
                     @endif
                     @if ($usr->can('product.create') || $usr->can('product.view') || $usr->can('product.edit') || $usr->can('product.delete'))
                     <li>
                         <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                                 Products
                             </span></a>
                         <ul class="collapse {{ Route::is('admin.products.create') || Route::is('admin.products.index') || Route::is('admin.products.edit') || Route::is('admin.products.show') ? 'in' : '' }}">
                             @if ($usr->can('product.view'))
                             <li class="{{ Route::is('admin.products.index')  || Route::is('admin.products.edit') ? 'active' : '' }}"><a href="{{ route('admin.products.index') }}">All Product</a></li>
                             @endif
                             @if ($usr->can('product.create'))
                             <li class="{{ Route::is('admin.products.create')  ? 'active' : '' }}"><a href="{{ route('admin.products.create') }}">Create Product</a></li>
                             @endif
                         </ul>
                     </li>
                     @endif
                     @if ($usr->can('bank.create') || $usr->can('bank.view') || $usr->can('bank.edit') || $usr->can('bank.delete'))
                     <li>
                         <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                                 banks
                             </span></a>
                         <ul class="collapse {{ Route::is('admin.banks.create') || Route::is('admin.banks.index') || Route::is('admin.banks.edit') || Route::is('admin.banks.show') ? 'in' : '' }}">
                             @if ($usr->can('bank.view'))
                             <li class="{{ Route::is('admin.banks.index')  || Route::is('admin.banks.edit') ? 'active' : '' }}"><a href="{{ route('admin.banks.index') }}">All Bank</a></li>
                             @endif
                             @if ($usr->can('bank.create'))
                             <li class="{{ Route::is('admin.banks.create')  ? 'active' : '' }}"><a href="{{ route('admin.banks.create') }}">Create Bank</a></li>
                             @endif
                         </ul>
                     </li>
                     @endif
                     @if ($usr->can('branchcode.create') || $usr->can('branchcode.view') || $usr->can('branchcode.edit') || $usr->can('branchcode.delete'))
                     <li>
                         <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                                 Branch
                             </span></a>
                         <ul class="collapse {{ Route::is('admin.branchcode.create') || Route::is('admin.branchcode.index') || Route::is('admin.branchcode.edit') || Route::is('admin.branchcode.show') ? 'in' : '' }}">
                             {{-- @if ($usr->can('branchcode.view')) --}}
                             <li class="{{ Route::is('admin.branchcode.index')  || Route::is('admin.branchcodes.index') ? 'active' : '' }}"><a href="{{ route('admin.branchcodes.index') }}">All Branches</a></li>
                             {{-- @endif --}}
                             {{-- @if ($usr->can('branchcode.create')) --}}
                             <li class="{{ Route::is('admin.branchcode.create')  ? 'active' : '' }}"><a href="{{ route('admin.branchcodes.create') }}">Create Branch</a></li>
                             {{-- @endif --}}
                         </ul>
                     </li>
                     @endif




                     @if ($usr->can('role.create') || $usr->can('role.view') || $usr->can('role.edit') || $usr->can('role.delete'))
                     <li>
                         <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                                 Roles & Permissions
                             </span></a>
                         <ul class="collapse {{ Route::is('admin.roles.create') || Route::is('admin.roles.index') || Route::is('admin.roles.edit') || Route::is('admin.roles.show') ? 'in' : '' }}">
                             @if ($usr->can('role.view'))
                             <li class="{{ Route::is('admin.roles.index')  || Route::is('admin.roles.edit') ? 'active' : '' }}"><a href="{{ route('admin.roles.index') }}">All Roles</a></li>
                             @endif
                             @if ($usr->can('role.create'))
                             <li class="{{ Route::is('admin.roles.create')  ? 'active' : '' }}"><a href="{{ route('admin.roles.create') }}">Create Role</a></li>
                             @endif
                         </ul>
                     </li>
                     @endif


                     @if ($usr->can('admin.create') || $usr->can('admin.view') || $usr->can('admin.edit') || $usr->can('admin.delete'))
                     <li>
                         <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-user"></i><span>
                                 Account
                             </span></a>
                         <ul class="collapse {{ Route::is('admin.admins.create') || Route::is('admin.admins.index') || Route::is('admin.admins.edit') || Route::is('admin.admins.show') ? 'in' : '' }}">

                             @if ($usr->can('admin.view'))
                             <li class="{{ Route::is('admin.admins.index')  || Route::is('admin.admins.edit') ? 'active' : '' }}"><a href="{{ route('admin.admins.index') }}">All Account</a></li>
                             @endif

                             @if ($usr->can('admin.create'))
                             <li class="{{ Route::is('admin.admins.create')  ? 'active' : '' }}"><a href="{{ route('admin.admins.create') }}">Create Account</a></li>
                             @endif
                         </ul>
                     </li>
                     @endif

                     @if ($usr->can('user.create') || $usr->can('user.view') || $usr->can('user.edit') || $usr->can('user.delete'))
                     <li>
                         <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-user"></i><span>
                                 Agent
                             </span></a>
                         <ul class="collapse {{ Route::is('admin.users.create') || Route::is('admin.users.index') || Route::is('admin.users.edit') || Route::is('admin.users.show') ? 'in' : '' }}">

                             @if ($usr->can('user.view'))
                             <li class="{{ Route::is('admin.users.index')  || Route::is('admin.users.edit') ? 'active' : '' }}"><a href="{{ route('admin.users.index') }}">All Agent</a></li>
                             @endif

                             @if ($usr->can('user.create'))
                             <li class="{{ Route::is('admin.users.create')  ? 'active' : '' }}"><a href="{{ route('admin.users.create') }}">Create Agent</a></li>
                             @endif
                         </ul>
                     </li>
                     @endif

                 </ul>
             </nav>
         </div>
     </div>
 </div>
 <!-- sidebar menu area end -->