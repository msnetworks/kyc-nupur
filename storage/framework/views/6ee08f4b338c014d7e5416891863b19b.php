 <!-- sidebar menu area start -->
 <?php
 $usr = Auth::guard('admin')->user();
 ?>
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
             <a href="<?php echo e(route('admin.dashboard')); ?>">
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

                     <?php if($usr->can('dashboard.view')): ?>

                     <li class="<?php echo e(Route::is('admin.dashboard') ? 'active' : ''); ?>"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>

                     <?php endif; ?>

                     <?php if($usr->can('case.create') || $usr->can('case.view') || $usr->can('case.edit') || $usr->can('case.delete')): ?>
                     <li>
                         <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                                 Cases
                             </span></a>
                         <ul class="collapse <?php echo e(Route::is('admin.case.create') || Route::is('admin.case.index') || Route::is('admin.case.edit') || Route::is('admin.case.show') ? 'in' : ''); ?>">
                             <?php if($usr->can('case.view')): ?>
                             <li class="<?php echo e(Route::is('admin.case.index')  || Route::is('admin.case.edit') ? 'active' : ''); ?>"><a href="<?php echo e(route('admin.case.index')); ?>">Search Cases</a></li>
                             <?php endif; ?>
                             <?php if($usr->can('case.create')): ?>
                             <li class="<?php echo e(Route::is('admin.case.create')  ? 'active' : ''); ?>"><a href="<?php echo e(route('admin.case.create')); ?>">Create Case</a></li>
                             <?php endif; ?>
                             <?php if($usr->can('case.import')): ?>
                             <li class="<?php echo e(Route::currentRouteName() == 'admin.case.import.view' ? 'active' : ''); ?>">
                                 <a href="<?php echo e(route('admin.case.import.view', ['id' => '1'])); ?>">Import Case</a>
                             </li>
                             <?php endif; ?>

                         </ul>
                     </li>
                     <?php endif; ?>
                     <?php if($usr->can('report.create') || $usr->can('report.view') || $usr->can('report.edit') || $usr->can('report.delete')): ?>
                     <li>
                         <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                                 Reports
                             </span></a>
                         <ul class="collapse <?php echo e(Route::is('admin.reports.create') || Route::is('admin.reports.index') || Route::is('admin.reports.edit') || Route::is('admin.reports.show') ? 'in' : ''); ?>">
                             <?php if($usr->can('report.view')): ?>
                             <li class="<?php echo e(Route::is('admin.reports.index')  || Route::is('admin.reports.edit') ? 'active' : ''); ?>"><a href="<?php echo e(route('admin.reports.index')); ?>">Verified Data</a></li>
                             <li class="<?php echo e(Route::is('admin.reports.billing')  ? 'active' : ''); ?>"><a href="<?php echo e(route('admin.reports.billing')); ?>">Billing Report</a></li>
                             <?php endif; ?>
                             <?php if($usr->can('report.create')): ?>
                             <li class="<?php echo e(Route::is('admin.reports.create')  ? 'active' : ''); ?>"><a href="<?php echo e(route('countReport')); ?>">Verified Count Report</a></li>
                             <?php endif; ?>
                         </ul>
                     </li>
                     <?php endif; ?>
                     <!-- <li style="color: #ffffff;">--------------------------------------------------</li> -->
                     <?php if($usr->can('fitype.create') || $usr->can('fitype.view') || $usr->can('fitype.edit') || $usr->can('fitype.delete')): ?>
                     <li>
                         <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                                 FI Types
                             </span></a>
                         <ul class="collapse <?php echo e(Route::is('admin.fitypes.create') || Route::is('admin.fitypes.index') || Route::is('admin.fitypes.edit') || Route::is('admin.fitypes.show') ? 'in' : ''); ?>">
                             <?php if($usr->can('fitype.view')): ?>
                             <li class="<?php echo e(Route::is('admin.fitypes.index')  || Route::is('admin.fitypes.edit') ? 'active' : ''); ?>"><a href="<?php echo e(route('admin.fitypes.index')); ?>">Fi Type </a></li>
                             <?php endif; ?>
                             <?php if($usr->can('role.create')): ?>
                             <li class="<?php echo e(Route::is('admin.fitypes.create')  ? 'active' : ''); ?>"><a href="<?php echo e(route('admin.fitypes.create')); ?>">Create Fi Type</a></li>
                             <?php endif; ?>
                         </ul>
                     </li>
                     <?php endif; ?>
                     <?php if($usr->can('product.create') || $usr->can('product.view') || $usr->can('product.edit') || $usr->can('product.delete')): ?>
                     <li>
                         <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                                 Products
                             </span></a>
                         <ul class="collapse <?php echo e(Route::is('admin.products.create') || Route::is('admin.products.index') || Route::is('admin.products.edit') || Route::is('admin.products.show') ? 'in' : ''); ?>">
                             <?php if($usr->can('product.view')): ?>
                             <li class="<?php echo e(Route::is('admin.products.index')  || Route::is('admin.products.edit') ? 'active' : ''); ?>"><a href="<?php echo e(route('admin.products.index')); ?>">All Product</a></li>
                             <?php endif; ?>
                             <?php if($usr->can('product.create')): ?>
                             <li class="<?php echo e(Route::is('admin.products.create')  ? 'active' : ''); ?>"><a href="<?php echo e(route('admin.products.create')); ?>">Create Product</a></li>
                             <?php endif; ?>
                         </ul>
                     </li>
                     <?php endif; ?>
                     <?php if($usr->can('bank.create') || $usr->can('bank.view') || $usr->can('bank.edit') || $usr->can('bank.delete')): ?>
                     <li>
                         <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                                 banks
                             </span></a>
                         <ul class="collapse <?php echo e(Route::is('admin.banks.create') || Route::is('admin.banks.index') || Route::is('admin.banks.edit') || Route::is('admin.banks.show') ? 'in' : ''); ?>">
                             <?php if($usr->can('bank.view')): ?>
                             <li class="<?php echo e(Route::is('admin.banks.index')  || Route::is('admin.banks.edit') ? 'active' : ''); ?>"><a href="<?php echo e(route('admin.banks.index')); ?>">All Bank</a></li>
                             <?php endif; ?>
                             <?php if($usr->can('bank.create')): ?>
                             <li class="<?php echo e(Route::is('admin.banks.create')  ? 'active' : ''); ?>"><a href="<?php echo e(route('admin.banks.create')); ?>">Create Bank</a></li>
                             <?php endif; ?>
                         </ul>
                     </li>
                     <?php endif; ?>
                     <?php if($usr->can('branchcode.create') || $usr->can('branchcode.view') || $usr->can('branchcode.edit') || $usr->can('branchcode.delete')): ?>
                     <li>
                         <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                                 Branch
                             </span></a>
                         <ul class="collapse <?php echo e(Route::is('admin.branchcode.create') || Route::is('admin.branchcode.index') || Route::is('admin.branchcode.edit') || Route::is('admin.branchcode.show') ? 'in' : ''); ?>">
                             
                             <li class="<?php echo e(Route::is('admin.branchcode.index')  || Route::is('admin.branchcodes.index') ? 'active' : ''); ?>"><a href="<?php echo e(route('admin.branchcodes.index')); ?>">All Branches</a></li>
                             
                             
                             <li class="<?php echo e(Route::is('admin.branchcode.create')  ? 'active' : ''); ?>"><a href="<?php echo e(route('admin.branchcodes.create')); ?>">Create Branch</a></li>
                             
                         </ul>
                     </li>
                     <?php endif; ?>




                     <?php if($usr->can('role.create') || $usr->can('role.view') || $usr->can('role.edit') || $usr->can('role.delete')): ?>
                     <li>
                         <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                                 Roles & Permissions
                             </span></a>
                         <ul class="collapse <?php echo e(Route::is('admin.roles.create') || Route::is('admin.roles.index') || Route::is('admin.roles.edit') || Route::is('admin.roles.show') ? 'in' : ''); ?>">
                             <?php if($usr->can('role.view')): ?>
                             <li class="<?php echo e(Route::is('admin.roles.index')  || Route::is('admin.roles.edit') ? 'active' : ''); ?>"><a href="<?php echo e(route('admin.roles.index')); ?>">All Roles</a></li>
                             <?php endif; ?>
                             <?php if($usr->can('role.create')): ?>
                             <li class="<?php echo e(Route::is('admin.roles.create')  ? 'active' : ''); ?>"><a href="<?php echo e(route('admin.roles.create')); ?>">Create Role</a></li>
                             <?php endif; ?>
                         </ul>
                     </li>
                     <?php endif; ?>


                     <?php if($usr->can('admin.create') || $usr->can('admin.view') || $usr->can('admin.edit') || $usr->can('admin.delete')): ?>
                     <li>
                         <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-user"></i><span>
                                 Account
                             </span></a>
                         <ul class="collapse <?php echo e(Route::is('admin.admins.create') || Route::is('admin.admins.index') || Route::is('admin.admins.edit') || Route::is('admin.admins.show') ? 'in' : ''); ?>">

                             <?php if($usr->can('admin.view')): ?>
                             <li class="<?php echo e(Route::is('admin.admins.index')  || Route::is('admin.admins.edit') ? 'active' : ''); ?>"><a href="<?php echo e(route('admin.admins.index')); ?>">All Account</a></li>
                             <?php endif; ?>

                             <?php if($usr->can('admin.create')): ?>
                             <li class="<?php echo e(Route::is('admin.admins.create')  ? 'active' : ''); ?>"><a href="<?php echo e(route('admin.admins.create')); ?>">Create Account</a></li>
                             <?php endif; ?>
                         </ul>
                     </li>
                     <?php endif; ?>

                     <?php if($usr->can('user.create') || $usr->can('user.view') || $usr->can('user.edit') || $usr->can('user.delete')): ?>
                     <li>
                         <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-user"></i><span>
                                 Agent
                             </span></a>
                         <ul class="collapse <?php echo e(Route::is('admin.users.create') || Route::is('admin.users.index') || Route::is('admin.users.edit') || Route::is('admin.users.show') ? 'in' : ''); ?>">

                             <?php if($usr->can('user.view')): ?>
                             <li class="<?php echo e(Route::is('admin.users.index')  || Route::is('admin.users.edit') ? 'active' : ''); ?>"><a href="<?php echo e(route('admin.users.index')); ?>">All Agent</a></li>
                             <?php endif; ?>

                             <?php if($usr->can('user.create')): ?>
                             <li class="<?php echo e(Route::is('admin.users.create')  ? 'active' : ''); ?>"><a href="<?php echo e(route('admin.users.create')); ?>">Create Agent</a></li>
                             <?php endif; ?>
                         </ul>
                     </li>
                     <?php endif; ?>

                 </ul>
             </nav>
         </div>
     </div>
 </div>
 <!-- sidebar menu area end --><?php /**PATH C:\laragon\www\kyc-live\resources\views/backend/layouts/partials/sidebar.blade.php ENDPATH**/ ?>