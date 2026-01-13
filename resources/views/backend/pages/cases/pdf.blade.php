@php
if($case->getCase->bank_id == 12){
    $path = public_path('logos/synergeerisk-sign.jpeg');
    $logopath = public_path('logos/synergeerisk-logo.png');
}elseif($case->getCase->bank_id == 13){
    $path = public_path('images/flexi-sign.jpeg');
    $logopath = public_path('images/sk-logo.png');
}else{
    $path = public_path('images/sign.png');
    $logopath = public_path('images/logo.jpg');
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
    @include('backend.pages.cases.partials.pdf-address-verification')
@else
    @include('backend.pages.cases.partials.pdf-default')
@endif