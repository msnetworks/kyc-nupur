@if($cases->count())
<div class="table-responsive">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <span class="fw-bold">Showing {{ $cases->firstItem() }} to {{ $cases->lastItem() }} of {{ $cases->total() }}
                entries</span>
        </div>
        <form method="GET" action="{{ route('admin.reports.billing.export') }}" target="_blank" class="mb-0">
            <input type="hidden" name="dateFrom" value="{{ request('dateFrom') }}">
            <input type="hidden" name="dateTo" value="{{ request('dateTo') }}">
            <button type="submit" class="btn btn-success btn-sm">Export Billing Excel</button>
        </form>
    </div>
    <table class="table table-bordered table-striped" id="dataTable">
        <thead>
            <tr>
                <th>Enternal Code</th>
                <th>Name</th>
                <th>No.</th>
                <th>Address</th>
                <th>Type</th>
                <th>ITR Form Remarks</th>
                <th>Geo Limit</th>
                <th>Pan Card</th>
                <th>Assesment Year</th>
                <th>Account No</th>
                <th>Bank Reference No.</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Rate</th>
                <th>Telecalling</th>
                <th>GST (18%)</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
            $rate = 125;
            $gst_percent = 0.18;
            $subtotal_rate = 0;
            $subtotal_telecalling = 0;
            $subtotal_gst = 0;
            $subtotal_total = 0;
            @endphp
            @foreach($cases as $case)
            @php
            $geoLimit = optional($case->getCase)->geo_limit;
            $baseRate = in_array($case->fi_type_id, [7,8]) && $geoLimit === 'Outstation'
                ? 190
                : (in_array($case->fi_type_id, [12,13,14,17]) ? 125 : 120);
            
            $telecalling = in_array($case->fi_type_id, [7,8]) ? 10 : 0;
            
            // Check for ITR forms (fi_type_id 12 and 13) and calculate based on number of visits
            $visit_multiplier = 1;
            if (in_array($case->fi_type_id, [12, 13]) && !empty($case->itr_form_remarks)) {
                $itr_remarks = json_decode($case->itr_form_remarks, true);
                if (is_array($itr_remarks) && count($itr_remarks) > 0) {
                    $visit_multiplier = count($itr_remarks);
                }
            }
            
            $adjusted_rate = $rate * $visit_multiplier;
            $subtotal_before_gst = $adjusted_rate + $telecalling;
            $gst = $subtotal_before_gst * $gst_percent;
            $total = $subtotal_before_gst + $gst;
            if ($case->status == '1155') {
                $adjusted_rate = 0;
                $telecalling = 0;
                $gst = 0;
                $total = 0;
            }

            $subtotal_rate += $adjusted_rate;
            $subtotal_telecalling += $telecalling;
            $subtotal_gst += $gst;
            $subtotal_total += $total;
            
            $itr_remarks_display = '-';
            if (!empty($case->itr_form_remarks)) {
                $decoded_itr = json_decode($case->itr_form_remarks, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded_itr)) {
                    $flattened_itr = [];
                    array_walk_recursive($decoded_itr, function ($value) use (&$flattened_itr) {
                        if (is_string($value) && trim($value) !== '') {
                            $flattened_itr[] = trim($value);
                        }
                    });
                    $itr_remarks_display = !empty($flattened_itr) ? implode(' | ', $flattened_itr) : '-';
                } else {
                    $itr_remarks_display = $case->itr_form_remarks;
                }
            }
            @endphp
            <tr>
                <td>{{ optional($case->getCase)->refrence_number ?? '' }}</td>
                <td>{{ optional($case->getCase)->applicant_name ?? '' }}</td>
                <td>{{ $case->mobile ?? '' }}</td>
                <td>{{ $case->address ?? '' }}</td>
                <td>{{ optional($case->getFiType)->name ?? '' }}</td>
                <td>{{ $itr_remarks_display }}</td>
                <td>{{ $geoLimit ?? '' }}</td>
                <td>{{ $case->pan_number ?? ($case->pan_card ?? '-') }}</td>
                <td>{{ $case->assessment_year ?? '-' }}</td>
                <td>{{ $case->accountnumber ?? '-' }}</td>
                <td>{{ $case->bank_name ?? '-' }}</td>
                <td>
                    @if($case->status == 4)
                    Positive
                    @elseif($case->status == 5)
                    Negative
                    @else
                    Closed
                    @endif
                </td>
                <td>{{ $case->getCaseStatus->name ?? '' }}</td>
                <td>{{ number_format($adjusted_rate, 2) }}</td>
                <td>{{ number_format($telecalling, 2) }}</td>
                <td>{{ number_format($gst, 2) }}</td>
                <td>{{ number_format($total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="13" class="text-end">Sub Total</th>
                <th>{{ number_format($subtotal_rate, 2) }}</th>
                <th>{{ number_format($subtotal_telecalling, 2) }}</th>
                <th>{{ number_format($subtotal_gst, 2) }}</th>
                <th>{{ number_format($subtotal_total, 2) }}</th>
            </tr>
        </tfoot>
    </table>
    <div class="d-flex justify-content-center mt-3" id="pagination-links">
        {!! $cases->withQueryString()->links() !!}
    </div>
</div>
@else
<div class="alert alert-info">No records found.</div>
@endif

<script>
    $(function () {
        $('#dataTable').DataTable({
            responsive: true,
            paging: false, // Laravel handles pagination
            searching: false,
            info: false
        });

        // AJAX pagination
        $(document).off('click', '#pagination-links .pagination a').on('click', '#pagination-links .pagination a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            // Get dateFrom and dateTo from the form or current request
            var dateFrom = $('input[name="dateFrom"]').val() || '{{ request('dateFrom') }}';
            var dateTo = $('input[name="dateTo"]').val() || '{{ request('dateTo') }}';

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    dateFrom: dateFrom,
                    dateTo: dateTo,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#billingReportResult').html(response);
                },
                error: function() {
                    $('#billingReportResult').html('<div class="alert alert-danger">Something went wrong.</div>');
                }
            });
        });
    });
</script>