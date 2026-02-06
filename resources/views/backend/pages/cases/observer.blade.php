@extends('backend.layouts.master')

@section('title')
Observer Cases - Admin Panel
@endsection

@section('admin-content')
<style>
    .pagination-container .sm\:flex {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
    }
    .pagination-container nav svg {
        width: 1em!important;
        height: 1em!important;
        vertical-align: middle;
        display: inline-block;
    }
    .pagination-container p{
        margin-left: 65px;
    }
    .observer-filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 12px;
        width: 100%;
    }
    .observer-date-filter {
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    }
    .observer-filter-action {
        align-self: end;
        margin-bottom: 0;
    }
</style>

@php
    $perPage = $perPage ?? 25;
    $perPageOptions = [10, 25, 50, 100];
@endphp

@php
    $perPage = $perPage ?? 25;
    $perPageOptions = [10, 25, 50, 100];
@endphp

<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Observer Cases</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><span>Observer</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            @include('backend.layouts.partials.logout')
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="main-content-inner">
    <div class="row">
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column mb-3">
                        {{-- <h4 class="header-title mb-2 mb-md-0">Observer Case Details</h4> --}}
                        <div class="observer-filter-grid observer-date-filter">
                            <div class="form-group">
                                <label class="small text-muted">Start date</label>
                                <input id="observer-start-date" type="date" class="form-control" value="{{ request('start_date') }}">
                            </div>
                            <div class="form-group">
                                <label class="small text-muted">End date</label>
                                <input id="observer-end-date" type="date" class="form-control" value="{{ request('end_date') }}">
                            </div>
                            <div class="form-group">
                                <label class="small text-muted">Action</label>
                                <button id="observerApplyFilters" type="button" class="btn btn-outline-primary w-100">Search</button>
                            </div>
                        </div>
                        <div class="observer-filter-grid observer-search-row mt-3">
                            <div class="form-group">
                                <label class="small text-muted">Search</label>
                                <input id="observer-search" type="text" class="form-control" placeholder="Search by reference, name, or location" value="{{ request('search') }}">
                            </div>
                            <div class="form-group">
                                
                            </div>
                            <div class="form-group">

                            </div>
                            <div class="form-group">
                                <label class="small text-muted">Records</label>
                                <select id="observer-per-page" class="custom-select">
                                    @foreach ($perPageOptions as $value)
                                        <option value="{{ $value }}" {{ (int) $perPage === $value ? 'selected' : '' }}>{{ $value }} per page</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    @include('backend.layouts.partials.messages')

                    <div class="mb-3">
                        {{-- <button id="observerBulkDownloadPdf" type="button" class="btn btn-success" style="display: none;">
                            <i class="fa fa-download"></i> Bulk Download PDF
                        </button> --}}
                    </div>

                    <div id="observerCaseTableContainer" class="table-responsive">
                        @include('backend.pages.cases.observerCaseTable', ['cases' => $cases])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Observer CPV Remarks Modal -->
<div class="modal fade" id="observerCpvRemarksModel" tabindex="-1" role="dialog" aria-labelledby="observerCpvRemarksModelLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">CPV Comments</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12 col-sm-12">
                    <label><strong>Comments:</strong></label>
                    <div id="observer_cpv_comments" style="background-color: #f5f5f5; padding: 10px; border-radius: 4px; min-height: 100px; max-height: 300px; overflow-y: auto;">
                        <p style="color: #999;">Loading...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

                @section('scripts')
                <script>
                    function fetchObserverCases(page = 1) {
                        var payload = {
                            search: $('#observer-search').val(),
                            perPage: $('#observer-per-page').val(),
                            start_date: $('#observer-start-date').val(),
                            end_date: $('#observer-end-date').val(),
                            page: page
                        };

                        $.ajax({
                            url: '{{ route('admin.case.observer') }}',
                            type: 'GET',
                            data: payload,
                            success: function(response) {
                                $('#observerCaseTableContainer').html(response);
                            },
                            error: function() {
                                alert('Unable to load observer cases.');
                            }
                        });
                    }

                    $(document).ready(function() {
                        $('#observerApplyFilters').on('click', function(e) {
                            e.preventDefault();
                            fetchObserverCases();
                        });

                        $('#observer-per-page').on('change', function() {
                            fetchObserverCases();
                        });

                        $('#observer-search').on('keyup', function() {
                            fetchObserverCases();
                        });

                        $(document).on('click', '#observerCaseTableContainer .pagination-container a', function(e) {
                            e.preventDefault();
                            var href = $(this).attr('href');
                            var pageMatch = href ? href.match(/page=(\d+)/) : null;
                            var page = pageMatch ? pageMatch[1] : 1;
                            fetchObserverCases(page);
                        });

                        $(document).on('click', '#selectAllObserver', function() {
                            $('.observerCaseCheckbox').prop('checked', this.checked);
                            toggleBulkButton();
                        });

                        $(document).on('click', '.observerCaseCheckbox', function() {
                            toggleBulkButton();
                        });

                        function toggleBulkButton() {
                            var checkedCount = $('.observerCaseCheckbox:checked').length;
                            if (checkedCount > 0) {
                                $('#observerBulkDownloadPdf').show();
                            } else {
                                $('#observerBulkDownloadPdf').hide();
                            }
                        }

                        $('#observerBulkDownloadPdf').on('click', function() {
                            var selectedIds = [];
                            $('.observerCaseCheckbox:checked').each(function() {
                                selectedIds.push($(this).val());
                            });

                            if (selectedIds.length === 0) {
                                alert('Please select at least one case.');
                                return;
                            }

                            var url = '{{ route('admin.case.observer.bulk.download') }}';
                            var form = $('<form>', {
                                'method': 'POST',
                                'action': url,
                                'style': 'display: none;'
                            });
                            form.append($('<input>', {
                                'type': 'hidden',
                                'name': '_token',
                                'value': '{{ csrf_token() }}'
                            }));
                            form.append($('<input>', {
                                'type': 'hidden',
                                'name': 'case_ids',
                                'value': JSON.stringify(selectedIds)
                            }));
                            $('body').append(form);
                            form.submit();
                            form.remove();
                        });
                    });
                </script>

                <!-- Observer CPV Remarks Handler -->
                <script>
                    $(document).on('click', '.observerCpvRemarks', function() {
                        let caseId = $(this).data('row');
                        
                        // Fetch existing remarks
                        $.ajax({
                            url: "{{ route('admin.case.getCase', 'CASE_ID') }}".replace('CASE_ID', caseId),
                            type: 'GET',
                            success: function(response) {
                                // Display comments
                                if(response.case_fi_type && response.case_fi_type.app_remarks && response.case_fi_type.app_remarks.trim() != '') {
                                    $('#observer_cpv_comments').html('<p style="white-space: pre-wrap;">' + response.case_fi_type.app_remarks + '</p>');
                                } else {
                                    $('#observer_cpv_comments').html('<p style="color: #999;">No comments available</p>');
                                }
                                
                                // Show the modal
                                $('#observerCpvRemarksModel').modal('show');
                            },
                            error: function() {
                                $('#observer_cpv_comments').html('<p style="color: #d32f2f;">Error loading comments. Please try again.</p>');
                                $('#observerCpvRemarksModel').modal('show');
                            }
                        });
                    });
                </script>
                @endsection
