@php
if($case->getCase->bank_id == 12){
    $path = public_path('images/synergeerisk-sign.jpeg');
}else{
    $path = public_path('images/sign.png');
}
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$sign = 'data:image/' . $type . ';base64,' . base64_encode($data);
$logopath = public_path('images/logo.jpg');
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