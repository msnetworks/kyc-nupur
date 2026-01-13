@extends('backend.layouts.master')

@section('title')
Case Edit - Admin Panel
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
                <h4 class="page-title pull-left">Edit Case</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.fitypes.index') }}">All Create Case</a></li>
                    <li><span>Edit Case</span></li>
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
                    <h4 class="header-title">Edit Case </h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.case.update',$cases->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Bank</label>
                                <select class="custom-select selectBank" name="bank_id" id="selectBank" disabled>
                                    <option value="">--Select Option--</option>
                                    @foreach ($banks as $bank)
                                    <option value="{{ $bank['id'] }}" @if($cases->bank_id == $bank['id']) selected @endif >{{ $bank['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Branch Code <span class="text-danger">*</span></label>
                                <select class="custom-select branchDropdown" name="branch_code" id="branchDropdown" required>
                                    <option value="">--Select Option--</option>
                                    @if(!empty($cases->bank_id))
                                        @foreach ($branchcode as $branch)
                                            @if($branch->bank_id == $cases->bank_id)
                                                <option value="{{ $branch['id'] }}" @if($cases->branch_code == $branch->branch_code) selected @endif >{{ $branch->branch_name }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Product</label>
                                <select id="productSelect" name="product_id" class="custom-select" disabled>
                                    <option value="">--Select Option--</option>
                                    @if($AvailbleProduct)
                                    @foreach ($AvailbleProduct as $product)
                                    <option value="{{ $product['id'] }}" @if($cases->product_id == $product['id']) selected @endif >{{ $product['name'] }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">FI Type</label>
                                @foreach ($fitypes as $fitype)
                                <div class="form-check">
                                    <input class="form-check-input fytpe_checkbox" type="checkbox" name="fi_type_id[][id]" value="{{ $fitype['id'] }}" rel-name="{{ $fitype['name'] }}" @if(isset($fi_type_ids) && in_array($fitype['id'],$fi_type_ids) ) checked @endif onclick="return false">
                                    <label class="form-check-label" for="fitype{{ $fitype['id'] }}"> {{ $fitype['name'] }} </label>
                                </div>
                                @endforeach
                            </div>
                            {!! $fitypesFeild !!}
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="application_type">Application Type</label>
                                <select class="custom-select application_type" name="application_type" id="application_type">
                                    <option value="">--Select Option--</option>
                                    @foreach ($ApplicationTypes as $ApplicationType)
                                    <option value="{{ $ApplicationType['id'] }}" @if($cases->application_type == $ApplicationType['id']) selected @endif>{{ $ApplicationType['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            {{-- <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Reference Number</label>
                                <input type="text" class="form-control" id="refrence_number" name="refrence_number" placeholder="Enter Reference Number" value="{{ $cases->refrence_number ?? '' }}">
                            </div> --}}
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Amount</label>
                                <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter Amount" value="{{ $cases->amount ?? '' }}">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <!-- TAT Start -->
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="tat_start">TAT Start</label>
                                <input type="datetime-local" class="form-control" id="tat_start" name="tat_start" value="{{ $cases->tat_start }}" required>
                            </div>
                            
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="tat_end">TAT End <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" id="tat_end" name="tat_end" value="{{ $cases->tat_end }}" required>
                            </div>
                            
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12 name Applicant co_applicant_name d-none">
                                <label for="name">Applicant Name</label>
                                <input type="text" class="form-control" name="applicant_name" placeholder="Enter Applicant Name" value="{{ $cases->applicant_name ?? '' }}">
                            </div>
                            <div class="form-group col-md-6 col-sm-12 name co_applicant_name d-none">
                                <label for="name">Co-Applicant Name</label>
                                <input type="text" class="form-control" name="co_applicant_name" placeholder="Enter Co-Applicant Name" value="{{ $cases->co_applicant_name ?? '' }}">
                            </div>
                            <div class="form-group col-md-6 col-sm-12 name Guranter d-none">
                                <label for="name">Guarantee Name</label>
                                <input type="text" class="form-control" name="guarantee_name" placeholder="Enter Guarantee Name" value="{{ $cases->guarantee_name ?? '' }}">
                            </div>

                            <div class="form-group col-md-6 col-sm-12 name Seller d-none">
                                <label for="name">Seller Name</label>
                                <input type="text" class="form-control" name="applicant_name" placeholder="Enter Seller Name" value="{{ $cases->applicant_name ?? '' }}">
                            </div>
                        </div>
                        <div class="form-row">

                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Vehicle</label>
                                <input type="text" class="form-control" id="vehicle" name="vehicle" placeholder="Enter Vehicle" value="{{ $cases->vehicle ?? '' }}">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="geo_limit">Geo Limit *</label>
                                <select id="geo_limit" name="geo_limit" class="custom-select">
                                    <option value="">--Select Option--</option>
                                    <option value="Local" @if($cases->geo_limit == 'Local') selected @endif>Local</option>
                                    <option value="Outstation" @if($cases->geo_limit == 'Outstation') selected @endif>Outstation</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Remarks</label>
                                <textarea name="remarks" rows="2" cols="20" id="remarks" class="form-control" placeholder="Remarks">{{ $cases->remarks ?? '' }}</textarea>
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Update Case</button>
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
    $(document).ready(function() {
        $('.select2').select2();
    })
</script>
<script>
    function appliCationType(selectedApplicationType) {
        $('.name').addClass('d-none');
        if (selectedApplicationType == 'Applicant/Co-Applicant') {
            $(".co_applicant_name").removeClass('d-none');
        } else {
            $('.' + selectedApplicationType).removeClass('d-none');
        }
        return true;
    }

    $(document).ready(function() {
        var baseUrl = "{{ url('/') }}";

        let selectedApplicationType = $('#application_type').find("option:selected").text();
        appliCationType(selectedApplicationType);

        $('.fytpe_checkbox').each(function() {
            var isChecked = $(this).prop('checked');
            var relName = $(this).attr('rel-name');
            if (isChecked) {
                console.log();
                $('.' + relName + "_section").removeClass('d-none');
            } else {
                $('.' + relName + "_section").addClass('d-none');
            }
        });

        $('#selectBank').on('change', function(e) {
            var bankId = $(this).val();
            var customGetPath = "{{ route('admin.case.item','ID')}}";
            customGetPath = customGetPath.replace('ID', bankId);
            $.ajax({
                url: customGetPath,
                type: 'GET',
                success: function(response) {
                    var select = $('#productSelect');
                    select.empty(); // Clear any existing options
                    select.append('<option value="">--Select Option--</option>'); // Add default option
                    $.each(response, function(key, products) {
                        $.each(products, function(index, product) {
                            console.log(product);
                            var option = $('<option></option>')
                                .attr('value', product.product_id)
                                .text(product.name + ' (' + product.product_code + ')');
                            select.append(option);
                        });
                    });
                },
                error: function() {
                    alert('Request failed');
                }
            });
        });
        $('#application_type').on('change', function(e) {
            var selectedApplicationType = $(this).find("option:selected").text();
            appliCationType(selectedApplicationType);

        });
    });
</script>
<script>
    $(document).ready(function () {
    $('#selectBank').change(function () {
        var bankId = $(this).val(); // Get selected bank ID
        var branchDropdown = $('#branchDropdown'); // Reference to branch dropdown

        if (bankId) {
            // Clear existing options and add a loading message
            branchDropdown.html('<option value="">Loading...</option>');

            // Make AJAX call to fetch branches
            $.ajax({
                url: '{{ route('get.branches', ':bankId') }}'.replace(':bankId', bankId),
                type: 'GET',
                success: function (data) {
                    console.log(data);
                    // Clear and populate branch dropdown
                    branchDropdown.empty().append('<option value="">--Select Option--</option>');
                    $.each(data, function (key, branch) {
                        branchDropdown.append('<option value="' + branch.id + '">' + branch.branch_code + '</option>');
                    });
                },
                error: function () {
                    alert('Unable to fetch branch codes. Please try again.');
                }
            });
        } else {
            // Reset branch dropdown if no bank selected
            branchDropdown.html('<option value="">--Select Option--</option>');
        }
    });
});

</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tatStartInput = document.getElementById('tat_start');
        const tatEndInput = document.getElementById('tat_end');
        const form = document.querySelector('form'); // Replace with your specific form selector

        form.addEventListener('submit', function (e) {
            const tatStart = new Date(tatStartInput.value);
            const tatEnd = new Date(tatEndInput.value);

            if (tatEnd <= tatStart) {
                e.preventDefault(); // Prevent form submission
                alert('TAT End must be greater than TAT Start.');
            }
        });
    });
    tatEndInput.addEventListener('change', function () {
        const tatStart = new Date(tatStartInput.value);
        const tatEnd = new Date(tatEndInput.value);

        if (tatEnd <= tatStart) {
            tatEndInput.setCustomValidity('TAT End must be greater than TAT Start.');
        } else {
            tatEndInput.setCustomValidity('');
        }
    });

</script>
@endsection