<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Upload Card PDF</title>
    <style>
        body {
            color: #111;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }

        h1 {
            border-bottom: 2px solid #222;
            font-size: 20px;
            margin: 0 0 16px;
            padding-bottom: 8px;
        }

        h2 {
            font-size: 15px;
            margin: 18px 0 8px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #777;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f0f0f0;
            width: 28%;
        }

        .photo {
            border: 1px solid #777;
            height: 150px;
            object-fit: cover;
            width: 130px;
        }

        .top-table td {
            border: none;
            padding: 0 0 12px;
        }

        .photo-cell {
            text-align: right;
            width: 150px;
        }
    </style>
</head>
<body>
    <h1>Upload Card Details</h1>

    <table class="top-table">
        <tr>
            <td>
                <strong>{{ $uploadCard->name }}</strong><br>
                Employee Code: {{ $uploadCard->employee_code }}<br>
                Status: {{ optional($uploadCard->masterStatus)->name ?? 'N/A' }}
            </td>
            <td class="photo-cell">
                @if ($uploadCard->photo && file_exists(public_path($uploadCard->photo)))
                    <img class="photo" src="{{ public_path($uploadCard->photo) }}" alt="{{ $uploadCard->name }}">
                @endif
            </td>
        </tr>
    </table>

    <h2>Basic Information</h2>
    <table>
        <tr>
            <th>Use</th>
            <td>{{ $uploadCard->use_type == 'mobile_app' ? 'Mobile App' : 'Web' }}</td>
        </tr>
        <tr>
            <th>Mobile No</th>
            <td>{{ $uploadCard->mobile_no }}</td>
        </tr>
        <tr>
            <th>Aadhaar Card</th>
            <td>{{ $uploadCard->aadhaar_card }}</td>
        </tr>
        <tr>
            <th>Agent</th>
            <td>{{ optional($uploadCard->agent)->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Created On</th>
            <td>{{ $uploadCard->created_at ? $uploadCard->created_at->format('d-m-Y h:i A') : 'N/A' }}</td>
        </tr>
    </table>

    <h2>Address</h2>
    <table>
        <tr>
            <td>{{ $uploadCard->address }}</td>
        </tr>
    </table>

    <h2>Status Flow</h2>
    <table>
        <tr>
            <th>Verified By</th>
            <td>{{ optional(optional($uploadCard->commonFlow)->verifier)->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Verified On</th>
            <td>{{ optional($uploadCard->commonFlow)->verified_on ? \Carbon\Carbon::parse($uploadCard->commonFlow->verified_on)->format('d-m-Y h:i A') : 'N/A' }}</td>
        </tr>
        <tr>
            <th>Closed By</th>
            <td>{{ optional(optional($uploadCard->commonFlow)->closer)->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Closed On</th>
            <td>{{ optional($uploadCard->commonFlow)->closed_on ? \Carbon\Carbon::parse($uploadCard->commonFlow->closed_on)->format('d-m-Y h:i A') : 'N/A' }}</td>
        </tr>
    </table>
</body>
</html>
