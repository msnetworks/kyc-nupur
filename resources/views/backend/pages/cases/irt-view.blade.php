<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITR Report</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>{{ $case->getCase->bank_id == 13 ? 'SK ENTERPRISES' : 'TIGER4INDIALTD' }}</h1>
        <p>{{ $case->getCase->bank_id == 13 ? 'No 752, Sainik Vihar, Saradhana Road, Kanker Khera, Meerut Uttar Pradesh - 250001' : 'VASANT KUNJ, NEW DELHI - 110070' }}</p>
        <p>STATE BANK OF INDIA</p>
        <h2>PRODUCT: ITR</h2>
        
        <table>
            <tr>
                <th>Sr. No.</th>
                <td>0</td>
            </tr>
            <tr>
                <th>Location</th>
                <td>PBB JANAKPURI (04208)</td>
            </tr>
            <tr>
                <th>Branch</th>
                <td>PBB JANAKPURI (04208)</td>
            </tr>
            <tr>
                <th>Product</th>
                <td>ITR</td>
            </tr>
            <tr>
                <th>Application Name</th>
                <td>0</td>
            </tr>
            <tr>
                <th>Applicant Name</th>
                <td>SANTOSH KUMARI</td>
            </tr>
            <tr>
                <th>Date Reported</th>
                <td>08/10/2024</td>
            </tr>
            <tr>
                <th>PAN Card</th>
                <td>DJXPK7449J</td>
            </tr>
            <tr>
                <th>Pick Up Document</th>
                <td>ITR</td>
            </tr>
            <tr>
                <th>Overall Status</th>
                <td>POSITIVE</td>
            </tr>
        </table>

        <h3>ITR Details:</h3>
        <p>E-FILING NO ACKNOWLEDGEMENT NUMBER: 573195400200723</p>
        <p>Given ITR checked and found Total Income: ₹4,95,020 /- & Total Taxes Paid: ₹0</p>
        <br>
        <p>E-FILING NO ACKNOWLEDGEMENT NUMBER: 707440040140724</p>
        <p>Given ITR checked and found Total Income: ₹5,45,810 /- & Total Taxes Paid: ₹0</p>
    </div>
</body>
</html>
