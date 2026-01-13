<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', 'View Detail - BV')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('backend.layouts.partials.styles')
    @yield('styles')
</head>
@section('title')
    View Detail - BV
@endsection

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection


@section('admin-content')

    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">Cases Detail</h4>
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
            <!-- data table start -->
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-body p-0">
                        <h4 class="header-title float-left">View Cases Detail</h4>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <span class="pull-right" height="30px;">
                                <a href="javascript:void(0)" onclick="printformFunction()">Click here to print</a>
                            </span>
                            

                            <table class="table table-bordered" id="outprint"
                                style="border: 2px solid #000; width: 100%; table-layout: fixed;">
                                <tbody>
                                    <!-- Header Row -->
                                    <tr style="border: 2px solid #000;">
                                        <td
                                            style="width: 30%; text-align: center; vertical-align: middle; border: 2px solid #000;">
                                            <img alt="{{ $case->getCase->bank_id == 13 ? 'SK ENTERPRISES' : 'TIGER 4 INDIA LTD' }}" src="{{ $case->getCase->bank_id == 13 ? asset('images/sk-logo.png') : asset('images/logo.jpg') }}" style="max-height: 120px;"
                                                style="max-width: 100%; height: auto;">
                                        </td>
                                        <td
                                            style="width: 70%; text-align: center; vertical-align: middle; border: 2px solid #000;">
                                            <strong>
                                                <h1 style="color: #ff0000; margin: 0;"><u><i>{{ $case->getCase->bank_id == 13 ? 'SK ENTERPRISES' : 'TIGER 4 INDIA LTD' }}</i></u></h1>
                                                {{ $case->getCase->bank_id == 13 ? 'No 752, Sainik Vihar, Saradhana Road, Kanker Khera, Meerut Uttar Pradesh - 250001' : 'VASANT KUNJ, NEW DELHI-110070' }} <br>
                                            </strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"style="text-align: center; vertical-align: middle; border: 2px solid #000;">
                                            <strong>
                                                State Bank of India <br>
                                                {{ $case->getFiType->name }}
                                            </strong>
                                        </td>
                                    </tr>

                                    <!-- Case Details -->
                                    <tr style="border: 2px solid #000;">
                                        <td style="border: 2px solid #000;">Sr No.</td>
                                        <td style="border: 2px solid #000;">{{ $case->getCase->refrence_number ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr style="border: 2px solid #000;">
                                        <td style="border: 2px solid #000;">Branch</td>
                                        <td style="border: 2px solid #000;">
                                            {{ optional($case->getCase->getBranch)->branch_name . ' (' . optional($case->getCase->getBranch)->branch_code . ')' ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr style="border: 2px solid #000;">
                                        <td style="border: 2px solid #000;">Product Name</td>
                                        <td style="border: 2px solid #000;">{{ $case->getCase->getProduct->name ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr style="border: 2px solid #000;">
                                        <td style="border: 2px solid #000;">Applicant Name</td>
                                        <td style="border: 2px solid #000;">{{ $case->getCase->applicant_name ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    <tr style="border: 2px solid #000;">
                                        <td style="border: 2px solid #000;">Mobile</td>
                                        <td style="border: 2px solid #000;">{{ $case->mobile ?? 'N/A' }}</td>
                                    </tr>
                                    @if($case->fi_type_id == 17)
                                    <tr style="border: 2px solid #000;">
                                        <td style="border: 2px solid #000;">Pan Card</td>
                                        <td style="border: 2px solid #000;">{{ $case->pan_number ?? ($case->getCase->pan_card ?? 'N/A') }}</td>
                                    </tr>
                                    <tr style="border: 2px solid #000;">
                                        <td style="border: 2px solid #000;">Aadhar Card</td>
                                        <td style="border: 2px solid #000;">{{ $case->aadhar_card ?? ($case->getCase->aadhar_card ?? 'N/A') }}</td>
                                    </tr>
                                    @endif
                                    @if($case->fi_type_id == 13)
                                    <tr style="border: 2px solid #000;">
                                        <td style="border: 2px solid #000;">Account Number</td>
                                        <td style="border: 2px solid #000;">{{ $case->accountnumber ??  'N/A' }}</td>
                                    </tr>
                                    <tr style="border: 2px solid #000;">
                                        <td style="border: 2px solid #000;">Verify Bank Name</td>
                                        <td style="border: 2px solid #000;">{{ $case->bank_name ?? 'N/A' }}</td>
                                    </tr>
                                    @endif
                                    <tr style="border: 2px solid #000;">
                                        <td style="border: 2px solid #000;">Date Reported</td>
                                        <td style="border: 2px solid #000;">{{ $case->getCase->created_at ?? 'N/A' }}</td>
                                    </tr>
                                    <tr style="border: 2px solid #000;">
                                        <td style="border: 2px solid #000;">Overall Status</td>
                                        <td style="border: 2px solid #000;">{{ $case->getStatus->name ?? 'N/A' }}</td>
                                    </tr>
                                    <!-- Remarks Section -->
                                    @if($case->fi_type_id == 17)
                                    <tr style="border: 2px solid #000;">
                                        <td style="width: 30%; border: 2px solid #000; text-align: left;">Remarks</td>
                                        <td style="width: 70%; border: 2px solid #000; text-align: left;">
                                            {{ !empty($case->consolidated_remarks) ? $case->consolidated_remarks : 'No remarks available for this case.' }}
                                        </td>
                                    </tr>
                                    @elseif($case->fi_type_id == 13)
                                        <tr style="border: 0px !important;">
                                            <td colspan="2" style="border: 0px; !important"></td>
                                        </tr>
                                        <tr style="border: 2px solid #000">
                                            <th style="width: 20%; border: 2px solid #000;">Date</th>
                                            <th style="width: 80%; border: 2px solid #000;">Remarks</th>
                                        </tr>
                                        @if(!empty($case->itr_form_remarks))
                                            @php
                                                $itrFormRemarks = json_decode($case->itr_form_remarks, true);
                                            @endphp
                                            @if(is_array($itrFormRemarks) && count($itrFormRemarks) > 0)
                                                @foreach($itrFormRemarks as $remark)
                                                    <tr style="border: 2px solid #000">
                                                        <td>{{ $remark['financial_year'] ?? 'N/A' }}</td>
                                                        <td>{{ $remark['fi_remark'] ?? 'N/A' }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="2">No remarks found.</td>
                                                </tr>
                                            @endif
                                        @else
                                            <tr>
                                                <td colspan="2">No remarks available for this case.</td>
                                            </tr>
                                        @endif
                                    @endif

                                    <tr style="border: 0!important;">
                                        <td colspan="2" style="border: 0px!important">
                                            <br>
                                        </td>
                                    </tr>
                                    <!-- Signature Section -->
                                    <tr style="border: 0px;">
                                        <td colspan="2" style="text-align: left; border: 0px;">
                                            <img src="{{ $case->getCase->bank_id == 13 ? asset('images/flexi-sign.jpeg') : $sign }}" style="width: 150px; margin-top: 15px;">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
            <!-- data table end -->

        </div>
    </div>
    @include('backend.layouts.partials.scripts')
    <script>
        function printformFunction() {
            var divToPrint = document.getElementById('outprint'); // Get the table element
            var newWindow = window.open('', '', 'width=800,height=600'); // Open a new window
            newWindow.document.write('<html><head><title>Print Table</title>');
                newWindow.document.write(
                    '<style>table, th, td {border: 1px solid black; border-collapse: collapse; padding: 10px;}</style>'
                ); // Optional: Add CSS for the table
                newWindow.document.write('</head><body>');
                    newWindow.document.write(divToPrint.outerHTML); // Add the table's HTML to the new window
                    newWindow.document.write('</body></html>');
                    newWindow.document.close(); // Close the document
                    newWindow.print(); // Trigger print
                }
                </script>
</html>