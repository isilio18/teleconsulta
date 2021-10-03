<?php

namespace App\Http\Controllers\Proceso;
//*******agregar esta linea******//
use App\Models\Proceso\tab_documento;
use App\Models\Proceso\tab_ruta;
use App\Models\Telemedicina\tab_persona;
use View;
use Validator;
use Response;
use DB;
use Session;
use Storage;
use File;
use Illuminate\Http\Response as ResposeFile;
use Redirect;
use Mail;
//*******************************//
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class descargaController extends Controller
{
    public function __construct()
    {
      
    }
    
    public function verAnexo($id)
    {
	    $adjuntos = tab_documento::where('id', '=', $id)->first();

		$directorio = '/App/documento/'.$id.'.'.$adjuntos->de_extension;
		$archivo = Storage::disk('local')->get($directorio);

        //if($adjuntos->de_extension == 'zip' || $adjuntos->de_extension == 'rar'){
            
           return Response::download(storage_path('app').$directorio,$adjuntos->nb_archivo);
           
    }

   
}
