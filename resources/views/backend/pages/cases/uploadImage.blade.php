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

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Upload Case Image</h4>
                    @include('backend.layouts.partials.messages')
                    <form action="{{ route('admin.case.upload.image', $case_fi_type_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <input type="file" name="images[]" accept=".jpeg, .jpg, .png, .gif, .svg" multiple required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Upload Images</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <div class="container">
    </div>
    <div class="container">
        <div class="row">
            @if(!empty($case_img['image_1']))
            <div class="col-sm image_1">
                <img class="btn-delete float-right" onclick="deleteImage('1');" src="http://cdn1.iconfinder.com/data/icons/diagona/icon/16/101.png" />
                <img title='' style='width:100px;float:left; height:100px;margin-bottom:5px; margin-left:5px;border:2px solid #b06c1c;border-radius:10px;' src="{{ asset($case_img['image_1']) }}" />
            </div>
            @endif
            @if(!empty($case_img['image_2']))
            <div class="col-sm image_2">
                <img class="btn-delete float-right" onclick="deleteImage('2');" src="http://cdn1.iconfinder.com/data/icons/diagona/icon/16/101.png" />
                <img title='' style='width:100px;float:left; height:100px;margin-bottom:5px; margin-left:5px;border:2px solid #b06c1c;border-radius:10px;' src="{{ asset($case_img['image_2']) }}" />
            </div>
            @endif
            @if(!empty($case_img['image_3']))
            <div class="col-sm image_3">
                <img class="btn-delete float-right" onclick="deleteImage('3');" src="http://cdn1.iconfinder.com/data/icons/diagona/icon/16/101.png" />
                <img title='' style='width:100px;float:left; height:100px;margin-bottom:5px; margin-left:5px;border:2px solid #b06c1c;border-radius:10px;' src="{{ asset($case_img['image_3']) }}" />
            </div>
            @endif
            @if(!empty($case_img['image_4']))
            <div class="col-sm image_4">
                <img class="btn-delete float-right" onclick="deleteImage('4');" src="http://cdn1.iconfinder.com/data/icons/diagona/icon/16/101.png" />
                <img title='' style='width:100px;float:left; height:100px;margin-bottom:5px; margin-left:5px;border:2px solid #b06c1c;border-radius:10px;' src="{{ asset($case_img['image_4']) }}" />
            </div>
            @endif
            @if(!empty($case_img['image_5']))
            <div class="col-sm image_5">
                <img class="btn-delete float-right" onclick="deleteImage('5');" src="http://cdn1.iconfinder.com/data/icons/diagona/icon/16/101.png" />
                <img title='' style='width:100px;float:left; height:100px;margin-bottom:5px; margin-left:5px;border:2px solid #b06c1c;border-radius:10px;' src="{{ asset($case_img['image_5']) }}" />
            </div>
            @endif
            @if(!empty($case_img['image_6']))
            <div class="col-sm image_6">
                <img class="btn-delete float-right" onclick="deleteImage('6');" src="http://cdn1.iconfinder.com/data/icons/diagona/icon/16/101.png" />
                <img title='' style='width:100px;float:left; height:100px;margin-bottom:5px; margin-left:5px;border:2px solid #b06c1c;border-radius:10px;' src="{{ asset($case_img['image_6']) }}" />
            </div>
            @endif
            @if(!empty($case_img['image_7']))
            <div class="col-sm image_7">
                <img class="btn-delete float-right" onclick="deleteImage('7');" src="http://cdn1.iconfinder.com/data/icons/diagona/icon/16/101.png" />
                <img title='' style='width:100px;float:left; height:100px;margin-bottom:5px; margin-left:5px;border:2px solid #b06c1c;border-radius:10px;' src="{{ asset($case_img['image_7']) }}" />
            </div>
            @endif
            @if(!empty($case_img['image_8']))
            <div class="col-sm image_8">
                <img class="btn-delete float-right" onclick="deleteImage('8');" src="http://cdn1.iconfinder.com/data/icons/diagona/icon/16/101.png" />
                <img title='' style='width:100px;float:left; height:100px;margin-bottom:5px; margin-left:5px;border:2px solid #b06c1c;border-radius:10px;' src="{{ asset($case_img['image_8']) }}" />
            </div>
            @endif
            @if(!empty($case_img['image_9']))
            <div class="col-sm image_9">
                <img class="btn-delete float-right" onclick="deleteImage('9');" src="http://cdn1.iconfinder.com/data/icons/diagona/icon/16/101.png" />
                <img title='' style='width:100px;float:left; height:100px;margin-bottom:5px; margin-left:5px;border:2px solid #b06c1c;border-radius:10px;' src="{{ asset($case_img['image_9']) }}" />
            </div>
            @endif

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