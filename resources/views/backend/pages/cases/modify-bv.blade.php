@php
    $bankId = optional(optional($case)->getCase)->bank_id;
@endphp

@if($bankId == 12)
    @include('backend.pages.cases.partials.modify-bv-address-verification')
@else
    @include('backend.pages.cases.partials.modify-bv-default')
@endif

<script src="{{ asset('backend/assets/js/jquery.validate.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('.updateBtn').click(function(e) {
            e.preventDefault();
            var form = $(this).closest('form');

            let formData = form.serializeArray();
            let rowId = form.find('input[name="case_fi_id"]').val();
            let actionPath = "{{ route('admin.case.modifyBVCase','ID')}}";
            actionPath = actionPath.replace('ID', rowId);
            $.ajax({
                url: actionPath,
                type: 'POST',
                data: formData,
                success: function(response) {
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                },
                error: function(xhr) {
                    $("#errors").empty();
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(key, item) {
                            $("#errors").append("<li class='alert alert-danger'>" + item + "</li>");
                        });
                    }
                }
            });
        });
    });
</script>