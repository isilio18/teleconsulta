@extends('layouts.dashboard')

@section('content')

<iframe id="dicom"
    title="Visor DICOM"
    width="100%"
    height="590"
    src="http://{{$remote_addr}}/teleconsulta/public/webDICOM-master/">
</iframe>


@endsection