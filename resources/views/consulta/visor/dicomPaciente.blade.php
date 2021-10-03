@extends('layouts.dashboard')

@section('content')

<iframe id="dicom"
    title="Visor DICOM"
    width="100%"
    height="590"
    src="https://teleconsulta.gobeltech.com/public/webDICOM-paciente/index.html?id={{$archivo}}">
</iframe>


@endsection