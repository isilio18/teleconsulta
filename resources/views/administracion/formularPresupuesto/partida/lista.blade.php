@extends('layouts.dashboard')

@section('css_before')
    <!-- Page JS Plugins CSS -->

@endsection

@section('js_after')
    <!-- Page JS Plugins -->

    <!-- Page JS Code -->
    <script>
        $('.pagination').addClass('justify-content-end');
        $('.pagination li').addClass('page-item');
        $('.pagination li a').addClass('page-link');
        $('.pagination span').addClass('page-link');
    </script>

    <script>
        $('#borrar').on('show.bs.modal', function (event) {
            $("#borrarForm").attr('action','{{ url('/administracion/formularPresupuesto/partida/eliminar') }}');
            var button = $(event.relatedTarget);
            var item_id = button.data('item_id');
            var modal = $(this);
            modal.find('.modal-content #registro_id').val(item_id);
    });    
    </script>

@endsection

@section('content')

<!-- Page Content -->
<div class="content content-full content-boxed">
    <!-- Partial Table -->
    <div class="block block-rounded block-bordered">
        <div class="block-header block-header-default">
            <a class="btn btn-light" href="{{ URL::to('administracion/formularPresupuesto/accionEspecifica/lista').'/'. $id_tab_formular_presupuesto }}">
                <i class="fa fa-arrow-left mr-1"></i> Volver
            </a>
            <div class="block-options">
                @if ($in_cargado == false)
                <button type="button" class="btn-block-option mr-2"><a href="{{ URL::to('administracion/formularPresupuesto/partida/nuevo').'/'. $id_tab_formular_accion_especifica }}"><i class="fa fa-plus mr-1"></i> Nuevo</a></button>
               @endif
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                    <i class="si si-refresh"></i>
                </button>
            </div>
        </div>
        <div class="block-content">
            
        <form action="{{ url('/administracion/formularPresupuesto/partida/lista') }}" method="get">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <label>
                        <select name="perPage" class="custom-select" value="{{ $perPage }}">
                            @foreach(['5','10','20'] as $page)
                            <option @if($page == $perPage) selected @endif value="{{ $page }}">{{ $page }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" id="q" name="q" value="{{ $q }}" placeholder="Buscar codigo partida...">
                        <div class="input-group-append">
                            <button type="submit" class="input-group-text">
                                <i class="fa fa-fw fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        
            <table class="table table-bordered table-striped table-vcenter">
                <thead class="thead-light">
                    <tr>
                        <th>Partida</th>
                        <th>Descripcion</th>
                        <th>Monto</th>
                        <th>Estado</th>
                        <th class="d-none d-md-table-cell text-center" style="width: 100px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($tab_formular_partida as $key => $value)
                    <tr>
                        <td class="font-w600">{{ $value->co_partida }}</td>
                        <td class="d-none d-sm-table-cell"><em class="text-muted">{{ $value->de_partida }}</em></td>
                        <td class="d-none d-sm-table-cell"><em class="text-muted">{{ $value->mo_partida }}</em></td>
                        <td class="d-none d-sm-table-cell">
                            @if ($in_cargado == false)
                            @if ($value->in_activo == true)
                            <a href="{{ url('/administracion/formularPresupuesto/partida/deshabilitar').'/'. $value->id }}">
                                <i class="fa fa-eye text-done mr-1"></i>
                            </a>
                            @else
                            <a href="{{ url('/administracion/formularPresupuesto/partida/habilitar').'/'. $value->id }}">
                                <i class="fa fa-eye-slash text-danger mr-1"></i>
                            </a>
                            @endif
                            @else
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Cargado">
                                <i class="fa fa-check-circle"></i>
                            </button>                            
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                @if ($in_cargado == false)
                                @if ($value->in_activo == true)
                                <a href="{{ url('/administracion/formularPresupuesto/partida/editar').'/'. $value->id }}">
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Editar">
                                        <i class="fa fa-pencil-alt"></i>
                                    </button>
                                </a>                                  
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" title="Borrar" data-target="#borrar" data-item_id="{{ $value->id }}" >
                                    <i class="fa fa-times"></i>
                                </button>
                                                        
                                @else
                                
                                @endif

                                 @else
                       
                                 @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $tab_formular_partida->appends(Request::only(['perPage','q']))->render() }}         

        </div>
    </div>
    <!-- END Partial Table -->
</div>
<!-- END Page Content -->

@endsection