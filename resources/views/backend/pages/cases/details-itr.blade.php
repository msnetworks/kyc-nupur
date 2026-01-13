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
    View Detail - ITR
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

                        <div id="printableArea">
                            <!-- Printable Content Start -->
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td style="width: 30%; text-align:center; border:none;">
                                                <img alt="{{ $case->getCase->bank_id == 13 ? 'SK ENTERPRISES' : 'TIGER 4 INDIA LTD' }}" src="{{ $case->getCase->bank_id == 13 ? asset('images/sk-logo.png') : asset('images/logo.jpg') }}" style="max-height: 120px;">
                                            </td>
                                            <td style="width: 70%; text-align:center; border:none;">
                                            <strong>    <h1 style="color: #ff0000; "><u><i> {{ $case->getCase->bank_id == 13 ? 'SK ENTERPRISES' : 'TIGER 4 INDIA LTD' }} </i></u></h1>
                                            {{ $case->getCase->bank_id == 13 ? 'No 752, Sainik Vihar, Saradhana Road, Kanker Khera, Meerut Uttar Pradesh - 250001' : 'VASANTKUNJNEW DELHI-110070' }}</strong>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <th colspan="2" class="text-center">
                                                @php
                                                $fiType = $case->getFiType->name ?? null;
                                                $bank = $case->getCase->getBank->name ?? null;
                                                $product = $case->getCase->getProduct->name ?? null;

                                                $columnValue = null;

                                                if($bank){
                                                $columnValue = $bank;
                                                }

                                                if($product){
                                                if($columnValue){
                                                $columnValue .= ' ';
                                                }
                                                $columnValue .= $product;
                                                }

                                                if($fiType){
                                                if($columnValue){
                                                $columnValue .= ' ';
                                                }
                                                $columnValue .= $fiType == 'BV' ? 'BUSINESS VERIFICATION' : ($fiType == 'RV' ? 'RESIDENCE VERIFICATION' : $fiType);
                                                }
                                                @endphp
                                                {{ $columnValue }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Sr No.</td>
                                            <td>{{ $case->getCase->refrence_number ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Branch</td>
                                            <td>{{ optional($case->getCase->getBranch)->branch_name . ' (' . optional($case->getCase->getBranch)->branch_code . ')' ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Product Name</td>
                                            <td>{{ $case->getCase->getProduct->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Applicant Name</td>
                                            <td>{{ $case->getCase->applicant_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Pan Card</td>
                                            <td>{{ $case->pan_number ?? ($case->getCase->pan_card ?? 'N/A') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Assesment Year</td>
                                            <td>{{ $case->assessment_year ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Date Reported</td>
                                            <td>{{ $case->getCase->created_at ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Overall Status</td>
                                            <td>{{ $case->consolidated_remarks ?? ($case->getStatus->name ?? 'N/A') }}</td>
                                        </tr>
                                        <tr style="border: 0px !important;">
                                            <td colspan="2" style="border: 0px; !important"></td>
                                        </tr>
                                        <tr style="border: 2px solid #000">
                                            <th style="width: 20%; border: 2px solid #000;">Financial Year</th>
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
                                    </tbody>
                                </table>
                                <img src="{{ $case->getCase->bank_id == 13 ? asset('images/flexi-sign.jpeg') : asset('images/sign.png') }}" style="width:150px;margin-top:15px;">
                            </div>
                            <!-- Printable Content End -->
                        </div>
                        <button onclick="printformFunction()" class="btn btn-primary mt-3">Print</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printformFunction() {
            var printContents = document.getElementById('printableArea').innerHTML;
            var newWindow = window.open('', '', 'width=800,height=600');
            newWindow.document.write('<html><head><title>Print Document</title>');
            newWindow.document.write('<style>table, th, td {border: 1px solid black; padding: 8px; text-align: left;} img {max-width: 100%; height: auto;}</style>');
            newWindow.document.write('</head><body>');
            newWindow.document.write(printContents);
            newWindow.document.write('</body></html>');
            newWindow.document.close();
            newWindow.print();
        }
    </script>
</html>
