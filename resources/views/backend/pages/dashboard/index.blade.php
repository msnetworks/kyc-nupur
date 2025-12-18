@extends('backend.layouts.master')

@section('title')
Dashboard Page - Admin Panel
@endsection


@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Dashboard</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="index.html">Home</a></li>
                    <li><span>Dashboard</span></li>
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



    <div class="row mt-4">
        <div class="col-lg-10 col-md-10">
            @if (Auth::guard('admin')->user()->role !== 'Bank')
            <form method="" action="" id="filterForm">
                <div class="row">
                    
                    <div class="form-group col-md-3">
                        <label for="agent">Agent</label>
                        <select name="agent" id="agent" class="form-control">
                            <option selected="selected" value="">--Select--</option>
                            @if($agentLists)
                            @foreach ($agentLists as $id => $user)
                            <option value="{{ $id }}">{{ $user }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="fromDate">From Date</label>
                        <input type="date" class="form-control" id="fromDate" name="fromDate">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="toDate">To Date</label>
                        <input type="date" class="form-control" id="toDate" name="toDate">
                    </div>
                    <div class="form-group col-md-3">
                        <input type="submit" class="form-control btn btn-sm btn-primary mt-4" id="submit" name="submit" value="Filter">
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-2 col-md-2">
            <p class="mt-3"><strong>Unassigned Cases</strong>: <a href="{{ route('admin.case.caseStatus', ['status' => '0']) }}">{{ $total_Unassigned }} </a> </p>
            <p class="mt-3"><strong>Dedup Cases</strong>: <a href="{{ route('admin.case.caseStatus', ['status' => '8']) }}">{{ $total_dedup }} </a> </p>
        </div>
        @endif
    </div>
    <div class="row mt-4">
        <div class="col-md-12 col-lg-12" id="recordtable">
            <table id="dataTable" class="table table-responsive table-bordered">
                <thead>
                    <tr>
                        <th valign="middle">Agent</th>
                        <th valign="middle">Total</th>
                        <th valign="middle">Inprogress</th>
                        <th valign="middle">Positive Resolved</th>
                        <th valign="middle">Negative Resolved</th>
                        <th valign="middle">Positive Verified</th>
                        <th valign="middle">Negetive Verified</th>
                        <th valign="middle">Hold</th>
                        <th valign="middle">Close</th>
                        @if (Auth::guard('admin')->user()->role == 'Bank')
                        {{-- <th valign="middle">Unassigned</th> --}}
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if (Auth::guard('admin')->user()->role !== 'Bank')
                    <tr style="color: #0000FF ! Important;background-color: #00F000;">
                        <td>Total</td>

                        <td><a href="{{ $totalSum['total'] != 0 ? route('admin.case.caseStatus', ['status' => 'aaa','user_id' => 0]) : 'javascript' }}">{{ $totalSum['total'] ?? 0 }}</a></td>
                        <td><a href="{{ $totalSum['inprogressTotal'] != 0 ? route('admin.case.caseStatus', ['status' => 1,'user_id' => 0]) : 'javascript:;' }}">{{ $totalSum['inprogressTotal'] ?? 0 }}</a></td>
                        <td><a href="{{ $totalSum['positive_resolvedTotal'] != 0 ? route('admin.case.caseStatus', ['status' => 2,'user_id' => 0]) : 'javascript:;' }}">{{ $totalSum['positive_resolvedTotal'] ?? 0 }} </a></td>
                        <td><a href="{{ $totalSum['negative_resolvedTotal'] != 0 ? route('admin.case.caseStatus', ['status' => 3,'user_id' => 0]) : 'javascript:;' }}">{{ $totalSum['negative_resolvedTotal'] ?? 0 }} </a></td>
                        <td><a href="{{ $totalSum['positive_verifiedTotal'] != 0 ? route('admin.case.caseStatus', ['status' => 4,'user_id' => 0]) : 'javascript:;' }}">{{ $totalSum['positive_verifiedTotal'] ?? 0 }} </a></td>
                        <td><a href="{{ $totalSum['negative_verifiedTotal'] != 0 ? route('admin.case.caseStatus', ['status' => 5,'user_id' => 0]) : 'javascript:;' }}">{{ $totalSum['negative_verifiedTotal'] ?? 0 }} </a></td>
                        <td><a href="{{ $totalSum['holdTotal'] != 0 ? route('admin.case.caseStatus', ['status' => 6,'user_id' => 0]) : 'javascript:;' }}">{{ $totalSum['holdTotal'] ?? 0 }} </a></td>
                        <td><a href="{{ $totalSum['closeTotal'] != 0 ? route('admin.case.caseStatus', ['status' => 7,'user_id' => 0]) : 'javascript:;' }}">{{ $totalSum['closeTotal'] ?? 0 }} </a></td>
                        {{-- <td><a href="{{ route('admin.case.caseStatus', ['status' => 1]) }}">{{ 0 }} </a></td>
                        <td><a href="{{ route('admin.case.caseStatus', ['status' => 1]) }}">{{ 0 }} </a></td>
                        <td><i class='fa fa-map-marker fa-2x' style='color:#9b479f;'></i></td> --}}
                    </tr>

                    @if($userCount)
                    @foreach ($userCount as $userWise)
                    <tr>
                        <td><a href="{{ $userWise['agentName'] != '' ?  route('admin.case.caseStatus', ['status' => 'aaa', 'user_id' => $userWise['agentid']]) : 'javascript:;'  }}">{{ $userWise['agentName'] ?? '' }}</a></td>
                        <td><a href="{{ $userWise['total'] != 0 ?  route('admin.case.caseStatus', ['status' => 'aaa', 'user_id' => $userWise['agentid']]) : 'javascript:;'  }}">{{ $userWise['total'] ?? 0 }}</a></td>
                        <td><a href="{{ $userWise['inprogress'] != 0 ?  route('admin.case.caseStatus', ['status' => 1, 'user_id' => $userWise['agentid']]) : 'javascript:;'  }}">{{ $userWise['inprogress'] ?? 0 }}</a></td>
                        <td><a href="{{ $userWise['positive_resolved'] != 0 ?  route('admin.case.caseStatus', ['status' => 2, 'user_id' => $userWise['agentid']]) : 'javascript:;'  }}">{{ $userWise['positive_resolved'] ?? 0 }} </a></td>
                        <td><a href="{{ $userWise['negative_resolved'] != 0 ?  route('admin.case.caseStatus', ['status' => 3, 'user_id' => $userWise['agentid']]) : 'javascript:;'  }}">{{ $userWise['negative_resolved'] ?? 0 }}</a></td>
                        <td><a href="{{ $userWise['positive_verified'] != 0 ?  route('admin.case.caseStatus', ['status' => 4, 'user_id' => $userWise['agentid']]) : 'javascript:;'  }}">{{ $userWise['positive_verified'] ?? 0 }}</a></td>
                        <td><a href="{{ $userWise['negative_verified'] != 0 ?  route('admin.case.caseStatus', ['status' => 5, 'user_id' => $userWise['agentid']]) : 'javascript:;'  }}">{{ $userWise['negative_verified'] ?? 0 }} </a></td>
                        <td><a href="{{ $userWise['hold'] != 0 ?  route('admin.case.caseStatus', ['status' => 6, 'user_id' => $userWise['agentid']]) : 'javascript:;'  }}">{{ $userWise['hold'] ?? 0 }} </a></td>
                        <td><a href="{{ $userWise['close'] != 0 ?  route('admin.case.caseStatus', ['status' => 7, 'user_id' => $userWise['agentid']]) : 'javascript:;'  }}">{{ $userWise['close'] ?? 0 }}</a></td>
                        {{-- <td><a href="{{ route('admin.case.caseStatus', ['status' => 1]) }}">{{ 0 }} </a></td>
                        <td><a href="{{ route('admin.case.caseStatus', ['status' => 1]) }}">{{ 0 }} </a></td>
                        <td><i class='fa fa-map-marker fa-2x' style='color:#9b479f;'></i></td> --}}
                    </tr>
                    @endforeach
                    @endif
                    
                    @endif
                    @if (Auth::guard('admin')->user()->role == 'Bank')
                    @foreach ($data as $userWise)
                        {{-- @if(!empty($userWise->user_id)) --}}
                        <tr>
                            <td><a href="{{ $userWise->agent_name != '' ?  route('admin.case.caseStatus', ['status' => 'aaa', 'user_id' => $userWise->user_id]) : 'javascript:;'  }}">{{ $userWise->agent_name != null  ? $userWise->agent_name : 'Unassigned' }}</a></td>
                            <td><a href="{{ $userWise->total_cases != 0 ?  route('admin.case.caseStatus', ['status' => 'aaa', 'user_id' => $userWise->user_id]) : 'javascript:;'  }}">{{ $userWise->total_cases ?? 0 }}</a></td>
                            <td><a href="{{ $userWise->inprogress != 0 ?  route('admin.case.caseStatus', ['status' => 1, 'user_id' => $userWise->user_id]) : 'javascript:;'  }}">{{ $userWise->inprogress ?? 0 }}</a></td>
                            <td><a href="{{ $userWise->positive_resolve != 0 ?  route('admin.case.caseStatus', ['status' => 2, 'user_id' => $userWise->user_id]) : 'javascript:;'  }}">{{ $userWise->positive_resolve ?? 0 }} </a></td>
                            <td><a href="{{ $userWise->negative_resolve != 0 ?  route('admin.case.caseStatus', ['status' => 3, 'user_id' => $userWise->user_id]) : 'javascript:;'  }}">{{ $userWise->negative_resolve ?? 0 }}</a></td>
                            <td><a href="{{ $userWise->positive_verified != 0 ?  route('admin.case.caseStatus', ['status' => 4, 'user_id' => $userWise->user_id]) : 'javascript:;'  }}">{{ $userWise->positive_verified ?? 0 }}</a></td>
                            <td><a href="{{ $userWise->negative_verified != 0 ?  route('admin.case.caseStatus', ['status' => 5, 'user_id' => $userWise->user_id]) : 'javascript:;'  }}">{{ $userWise->negative_verified ?? 0 }} </a></td>
                            <td><a href="{{ $userWise->hold != 0 ?  route('admin.case.caseStatus', ['status' => 6, 'user_id' => $userWise->user_id]) : 'javascript:;'  }}">{{ $userWise->hold ?? 0 }} </a></td>
                            <td><a href="{{ $userWise->closed != 0 ?  route('admin.case.caseStatus', ['status' => 7, 'user_id' => $userWise->user_id]) : 'javascript:;'  }}">{{ $userWise->closed ?? 0 }}</a></td>
                            {{-- <td><a href="{{ $userWise->unassigned != 0 ?  route('admin.case.caseStatus', ['status' => '0', 'user_id' => $userWise->user_id]) : 'javascript:;'  }}">{{ $userWise->unassigned ?? 0 }}</a></td> --}}
                            {{-- <td><a href="{{ route('admin.case.caseStatus', ['status' => 1]) }}">{{ 0 }} </a></td>
                            <td><a href="{{ route('admin.case.caseStatus', ['status' => 1]) }}">{{ 0 }} </a></td>
                            <td><i class='fa fa-map-marker fa-2x' style='color:#9b479f;'></i></td> --}}
                        </tr>
                        {{-- @endif --}}
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#filterForm').on('submit', function(event) {
                event.preventDefault(); 
    
                // Validation to ensure at least one filter is selected
                if ($('#agent').val() === '' && $('#fromDate').val() === '' && $('#toDate').val() === '') {
                    alert('Please select a date range or agent to filter.');
                    return false;
                }
    
                // Prepare form data
                var formData = {
                    _token: "{{ csrf_token() }}", // Ensure CSRF token is included
                    agent: $('#agent').val(),
                    fromDate: $('#fromDate').val(),
                    toDate: $('#toDate').val(),
                };
    
                // Send AJAX request
                $.ajax({
                    url: "{{ route('admin.filter') }}", // Route to handle the filter logic
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        // Update the table with the filtered results
                        $('#recordtable').html(response);
                    },
                    error: function(xhr, status, error) {
                        // Handle errors gracefully
                        console.error("Error:", xhr.responseText || status);
                        alert("An error occurred while fetching the records. Please try again.");
                    }
                });
            });
        });
    </script>
    
@endsection