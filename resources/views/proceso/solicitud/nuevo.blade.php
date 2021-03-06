@extends('layouts.dashboard')

@section('css_before')
    <!-- Page JS Plugins CSS -->

@endsection

@section('js_after')
    <!-- Page JS Plugins -->

    <!-- Page JS Code -->

@endsection

@section('content')

<!-- Page Content -->
<div class="content content-full content-boxed">
    <!-- Partial Table -->
    <div class="block block-rounded block-bordered">
    <!-- New Post -->
    <form action="{{ URL::to('proceso/solicitud/guardar') }}" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id_persona" value="{{ $id_persona }}">
        <input type="hidden" name="id_centro_asistencial" value="{{ $id_centro_asistencial }}">
        <div class="block">
            <div class="block-header block-header-default">
                <a class="btn btn-light" href="{{ URL::to('proceso/solicitud/lista') }}">
                    <i class="fa fa-arrow-left mr-1"></i> Volver
                </a>
                {{--<div class="block-options">
                    <div class="custom-control custom-switch custom-control-success">
                        <input type="checkbox" class="custom-control-input" id="dm-post-edit-active" name="dm-post-edit-active" checked>
                        <label class="custom-control-label" for="dm-post-edit-active">Set post as active</label>
                    </div>
                </div>--}}
            </div>
            <div class="block-content">
                <div class="row justify-content-center push">
                    <div class="col-md-10">

                        {{--
                        @if (count($errors) > 0)
                            <div class="alert alert-danger d-flex align-items-center justify-content-between" role="alert">
                                <div class="flex-fill mr-3">
                                    <p class="mb-0">Hay problemas con su validacion!</p>
                                </div>
                                <div class="flex-00-auto">
                                    <i class="fa fa-fw fa-times-circle"></i>
                                </div>
                            </div>
                        @endif
                        --}}
                        @if( $errors->has('da_alert_form') )
                        <div class="alert alert-danger d-flex align-items-center justify-content-between" role="alert">
                            <div class="flex-fill mr-3">
                                <p class="mb-0">{{ $errors->first('da_alert_form') }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="form-group">
                            <label for="solicitud">Solicitud</label>
                            <select class="custom-select {!! $errors->has('solicitud') ? 'is-invalid' : '' !!}" name="solicitud" id="solicitud" {{ $errors->has('solicitud') ? 'aria-describedby="solicitud-error" aria-invalid="true"' : '' }}>
                                @foreach($tab_solicitud_usuario as $solicitud)
                                    <option value="{{ $solicitud->id }}" {{ $solicitud->id == old('solicitud') ? 'selected' : '' }}>{{ $solicitud->nu_identificador }}-{{ $solicitud->de_solicitud }}</option>
                                @endforeach
                            </select>
                            @if( $errors->has('solicitud') )
                                <div id="solicitud-error" class="invalid-feedback animated fadeIn">{{ $errors->first('solicitud') }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="observacion">Observacion</label>
                            <textarea class="form-control {!! $errors->has('observacion') ? 'is-invalid' : '' !!}" id="observacion" name="observacion" rows="3" placeholder="Observacion.." {{ $errors->has('observacion') ? 'aria-describedby="observacion-error" aria-invalid="true"' : '' }}>{{ old('observacion') }}</textarea>
                            <div class="form-text text-muted font-size-sm font-italic">Breve Observaci??n del Tramite.</div>
                            @if( $errors->has('observacion') )
                                <div id="observacion-error" class="invalid-feedback animated fadeIn">{{ $errors->first('observacion') }}</div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="block-content bg-body-light">
            <div class="row justify-content-center push">
                <div class="col-md-10">
                    <button type="submit" class="btn btn-alt-primary">
                        <i class="fa fa-fw fa-save mr-1"></i> Guardar
                    </button>
                </div>
            </div>
        </div>
    </form>
    </div>
    <!-- END New Post -->
</div>
<!-- END Page Content -->

@endsection