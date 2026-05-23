@php
if($case->getCase->bank_id == 12){
    $logopath = public_path('logos/synergeerisk-logo.png');
    $path = public_path('logos/synergeerisk-sign.jpeg');
    $sign_title = 'Synergee Risk Management Pvt. Ltd.';
    $address = 'G-75, Jagjeet Nagar, East Delhi, Delhi, India, 110053';
}else if($case->getCase->bank_id == 13){
    $logopath = public_path('images/sk-logo.png');
    $path = public_path('images/flexi-sign.jpeg');
    $sign_title = 'S K Enterprises';
    $address = 'No 752, Sainik Vihar, Saradhana Road, Kanker Khera, Meerut Uttar Pradesh - 250001';
} else{
    $logopath = public_path('images/logo.jpg');
    $path = public_path('images/sign.png');
    $sign_title = 'TIGER 4 INDIA LTD';
    $address = 'VASANT KUNJ NEW DELHI-110070';
}
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$sign = 'data:image/' . $type . ';base64,' . base64_encode($data);

$logotype = pathinfo($logopath, PATHINFO_EXTENSION);
$logodata = file_get_contents($logopath);
$logo = 'data:image/' . $logotype . ';base64,' . base64_encode($logodata);
$bankId = $case->getCase->bank_id ?? null;
@endphp

@if($bankId == 12)
    @include('backend.pages.cases.partials.pdf-axis')
@else
    @include('backend.pages.cases.partials.pdf-default')
@endif