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
    <div class="container">
        <div class="row pl-5 pr-5 pt-5">
            <h3>Original Images</h3>
        </div>
    </div>
    <div class="container">
        <div class="row p-5">
            @foreach($case_img as $key => $value)
                @php
                    $imagePath = public_path($value->images); // Convert asset path to public path
                @endphp
                @if(file_exists($imagePath))
                    <div class="col-md-4 image_1 mb-5">
                        <img title='' style='width:100%; border:2px solid #b06c1c;border-radius:10px;' src="{{ asset($value->images) }}" />
                    </div>
                @endif
            @endforeach


        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    function deleteImage(image_id) {
        var url = "{{ route('admin.case.delete.image','IMAGE_ID')}}";
        url = url.replace('IMAGE_ID', image_id);

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}", // $case_fi_type_id
                'case_fi_type_id': "{{ $case_fi_type_id }}", // $case_fi_type_id
            },
            success: function(response) {
                if (response.success) {
                    alert('Image deleted successfully.');
                    // Optionally, you can remove the image element from the DOM
                    $('.image_' + image_id).remove();
                } else {
                    alert('Failed to delete image.');
                }

            },
            error: function() {
                alert('Request failed');
            }
        });

    }
</script>
@endsection