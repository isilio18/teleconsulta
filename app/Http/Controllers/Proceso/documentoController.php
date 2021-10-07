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
use ZipArchive;
use Mail;
//*******************************//
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class documentoController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

        /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function lista( Request $request, $id)
    {
        $sortBy = 'id';
        $orderBy = 'desc';
        $perPage = 100;
        $q = null;
        $columnas = [
          ['valor'=>'bnumberdialed', 'texto'=>'Número de Origen'],
          ['valor'=>'bnumberdialed', 'texto'=>'Número de Destino']
        ];

        if ($request->has('orderBy')){
            $orderBy = $request->query('orderBy');
        }
        if ($request->has('sortBy')){
            $sortBy = $request->query('sortBy');
        } 
        if ($request->has('perPage')){
            $perPage = $request->query('perPage');
        } 
        if ($request->has('q')){
            $q = $request->query('q');
        }

        $tab_ruta = tab_ruta::select( 'id', 'id_tab_solicitud', 'id_tab_tipo_solicitud', 'id_tab_usuario', 
        'de_observacion', 'id_tab_estatus', 'nu_orden', 'id_tab_proceso', 'in_actual', 
        'in_activo', 'created_at', 'updated_at', DB::raw("to_char(created_at, 'dd/mm/YYYY hh12:mi AM') as fe_creado"))
        ->where('id_tab_solicitud', '=', $id)
        ->where('in_activo', '=', true)
        ->where('in_actual', '=', true)
        ->first();

        $tab_documento = tab_documento::select( 'id', 'id_tab_ruta', 'id_tab_solicitud', 'de_documento', 'nb_archivo', 
        'mime', 'de_extension', 'in_activo', 'created_at', 'updated_at')
        ->where('id_tab_ruta', '=', $tab_ruta->id)
        ->where('in_activo', '=', true)
        ->where('in_dicom', '=', false)
        //->search($q, $sortBy)
        ->orderBy($sortBy, $orderBy)
        ->paginate($perPage);

        return View::make('proceso.documento.lista')->with([
          'tab_documento' => $tab_documento,
          'orderBy' => $orderBy,
          'sortBy' => $sortBy,
          'perPage' => $perPage,
          'columnas' => $columnas,
          'q' => $q,
          'id' => $id,
          'ruta' => $tab_ruta->id
        ]);
    }

    public function listaDicom( Request $request, $id)
    {
        $sortBy = 'id';
        $orderBy = 'desc';
        $perPage = 100;
        $q = null;
        $columnas = [
          ['valor'=>'bnumberdialed', 'texto'=>'Número de Origen'],
          ['valor'=>'bnumberdialed', 'texto'=>'Número de Destino']
        ];

        if ($request->has('orderBy')){
            $orderBy = $request->query('orderBy');
        }
        if ($request->has('sortBy')){
            $sortBy = $request->query('sortBy');
        } 
        if ($request->has('perPage')){
            $perPage = $request->query('perPage');
        } 
        if ($request->has('q')){
            $q = $request->query('q');
        }

        $tab_ruta = tab_ruta::select( 'id', 'id_tab_solicitud', 'id_tab_tipo_solicitud', 'id_tab_usuario', 
        'de_observacion', 'id_tab_estatus', 'nu_orden', 'id_tab_proceso', 'in_actual', 
        'in_activo', 'created_at', 'updated_at', DB::raw("to_char(created_at, 'dd/mm/YYYY hh12:mi AM') as fe_creado"))
        ->where('id_tab_solicitud', '=', $id)
        ->where('in_activo', '=', true)
        ->where('in_actual', '=', true)
        ->first();

        $tab_documento = tab_documento::select( 'id', 'id_tab_ruta', 'id_tab_solicitud', 'de_documento', 'nb_archivo', 
        'mime', 'de_extension', 'in_activo', 'created_at', 'updated_at')
        ->where('id_tab_ruta', '=', $tab_ruta->id)
        ->where('in_activo', '=', true)
        ->where('in_dicom', '=', true)
        ->orderBy($sortBy, $orderBy)
        ->paginate($perPage);

        return View::make('proceso.documento.listaDicom')->with([
          'tab_documento' => $tab_documento,
          'orderBy' => $orderBy,
          'sortBy' => $sortBy,
          'perPage' => $perPage,
          'columnas' => $columnas,
          'q' => $q,
          'id' => $id,
          'ruta' => $tab_ruta->id
        ]);
    }

        /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function nuevo( Request $request, $id)
    {
        $tab_ruta = tab_ruta::select( 'id', 'id_tab_solicitud')
        ->where('id', '=', $id)
        ->first();

        return View::make('proceso.documento.nuevo')->with([
            'ruta' => $id,
            'id' => $tab_ruta->id_tab_solicitud
        ]);
    }

    public function nuevoDicom( Request $request, $id)
    {
        $tab_ruta = tab_ruta::select( 'id', 'id_tab_solicitud')
        ->where('id', '=', $id)
        ->first();

        return View::make('proceso.documento.nuevoDicom')->with([
            'ruta' => $id,
            'id' => $tab_ruta->id_tab_solicitud
        ]);
    }

        /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function editar($id)
    {
        $data = tab_documento::select( 'id', 'id_tab_ruta', 'id_tab_solicitud', 'de_documento', 'nb_archivo', 
        'mime', 'de_extension', 'in_activo', 'created_at', 'updated_at')
        ->where('id', '=', $id)
        ->first();

        return View::make('proceso.documento.editar')->with([
            'data' => $data
        ]);
    }

        /**
    * Update the specified resource in storage.
    *
    * @param  int  $id
    * @return Response
    */
    public function guardar( Request $request, $id = NULL)
    {
        DB::beginTransaction();

        if($request->in_dicom <> 1){
  
            try {

                $validator= Validator::make($request->all(), tab_documento::$validarEditar);

                if ($validator->fails()){
                    return Redirect::back()->withErrors( $validator)->withInput( $request->all());
                }

                $extension = strtolower(File::extension(basename($request->file('archivo')->getClientOriginalName())));

              
                $tab_documento = new tab_documento;
                $tab_documento->id_tab_ruta = $request->ruta;
                $tab_documento->id_tab_solicitud = $request->solicitud;
                $tab_documento->de_documento = $request->descripcion;
                $tab_documento->nb_archivo = $request->file('archivo')->getClientOriginalName();
                $tab_documento->de_extension = $extension;
                $tab_documento->mime = $request->file('archivo')->getMimeType();
                $tab_documento->in_activo = true;
                $tab_documento->in_dicom  = false;
                $tab_documento->save();


                              
                $directorio = '/App/documento';
                $disk = Storage::disk('local');
                $disk->makeDirectory($directorio);

                $disk->put($directorio.'/'.$tab_documento->id.'.'.$extension, file_get_contents($request->file('archivo')->getRealPath()));

                DB::commit();

                Session::flash('msg_side_overlay', 'Registro Editado con Exito!');
                return Redirect::to('/proceso/documento/lista'.'/'.$request->solicitud);

            }catch (\Illuminate\Database\QueryException $e){

                DB::rollback();
                return Redirect::back()->withErrors([
                    'da_alert_form' => $e->getMessage()
                ])->withInput( $request->all());

            }
  
        }else{
  
            try {

                $validator = Validator::make($request->all(), tab_documento::$validarCrear);

                if ($validator->fails()){
                    return Redirect::back()->withErrors( $validator)->withInput( $request->all());
                }
    
                $cant_documento = tab_documento::where('id_tab_ruta','=',$request->ruta)->count();


                $nb_dir = $request->ruta.($cant_documento+1);

                $dir = storage_path().'/app/App/documento/'.$nb_dir;
               
                mkdir($dir, 0777);   


                $directorio = '/App/documento/'.$nb_dir;
                $disk = Storage::disk('local');
                $disk->makeDirectory($directorio);          

                for($i=0; $i<count($request->file('archivo'));$i++){
                
                    $archivo = $request->file('archivo')[$i]->getClientOriginalName();

                    $extension = strtolower(File::extension(basename($request->file('archivo')[$i]->getClientOriginalName())));
                   
                    $disk->put($directorio.'/'.$request->file('archivo')[$i]->getClientOriginalName(), file_get_contents($request->file('archivo')[$i]->getRealPath()));
                }

               

                $zip = new ZipArchive;

                $zip->open(storage_path().'/app/App/documento/'.$nb_dir.'.zip', ZipArchive::CREATE);     
               
                for($i=0; $i<count($request->file('archivo'));$i++){ 

                       $zip->addFile(storage_path().'/app/App/documento/'.$nb_dir.'/'.$request->file('archivo')[$i]->getClientOriginalName(), $request->file('archivo')[$i]->getClientOriginalName());
                }

                $zip->close();   


                $files = array_diff(scandir($dir), array('.','..'));
                foreach ($files as $file) {
                  (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
                }
                
                rmdir($dir);                                  


                $tab_documento = new tab_documento;
                $tab_documento->id_tab_ruta = $request->ruta;
                $tab_documento->id_tab_solicitud = $request->solicitud;
                $tab_documento->de_documento = $request->descripcion;
                $tab_documento->nb_archivo = $nb_dir;
                $tab_documento->de_extension = 'zip';
                $tab_documento->mime = 'application/zip';
                $tab_documento->in_activo = true;
                $tab_documento->in_dicom  = true;
                $tab_documento->save();

                

                DB::commit();

                Session::flash('msg_side_overlay', 'Registro Guardado con Exito!');
                return Redirect::to('/proceso/documento/listaDicom'.'/'.$request->solicitud);

            }catch (\Illuminate\Database\QueryException $e){

                DB::rollback();
                return Redirect::back()->withErrors([
                    'da_alert_form' => $e->getMessage()
                ])->withInput( $request->all());

            }
        }
    }

        /**
    * Show the form for creating a new resource.
    *
    * @return Response
    */
    public function eliminar( Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $tab_documento = tab_documento::find( $request->id);
            //$tab_documento->delete();
            $tab_documento->in_activo = false;
            $tab_documento->save();

            $adjuntos = tab_documento::where('id', '=', $request->id)->first();

            $directorio = '/App/documento';
            $disk = Storage::disk('local');
            $disk->delete($directorio.'/'.$adjuntos->id.'.'.$adjuntos->de_extension);

            DB::commit();

            Session::flash('msg_side_overlay', 'Registro Borrado con Exito!');
            if($tab_documento->in_dicom == true)
              return Redirect::to('/proceso/documento/listaDicom'.'/'.$id);
            else
              return Redirect::to('/proceso/documento/lista'.'/'.$id);

        }catch (\Illuminate\Database\QueryException $e)
        {
            DB::rollback();
            return Redirect::back()->withErrors([
                'da_alert_form' => $e->getMessage()
            ])->withInput( $request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function verAnexo($id, $t)
    {
	    $adjuntos = tab_documento::where('id', '=', $id)->first();

		$directorio = '/App/documento/'.$id.'.'.$adjuntos->de_extension;
		$archivo = Storage::disk('local')->get($directorio);

        //if($adjuntos->de_extension == 'zip' || $adjuntos->de_extension == 'rar'){
            
           return Response::download(storage_path('app').$directorio,$adjuntos->nb_archivo);
           
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function verAnexoDICOM($id)
    {
          $adjuntos = tab_documento::where('id', '=', $id)->first();

          $directorio = '/App/documento/'.$adjuntos->nb_archivo.'.'.$adjuntos->de_extension;
          $archivo = Storage::disk('local')->get($directorio);

          return Response::download(storage_path('app').$directorio,$adjuntos->nb_archivo.'.'.$adjuntos->de_extension);
           
    }

    /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function listaVer( Request $request, $id)
    {
        $sortBy = 'id';
        $orderBy = 'desc';
        $perPage = 100;
        $q = null;
        $columnas = [
          ['valor'=>'bnumberdialed', 'texto'=>'Número de Origen'],
          ['valor'=>'bnumberdialed', 'texto'=>'Número de Destino']
        ];

        if ($request->has('orderBy')){
            $orderBy = $request->query('orderBy');
        }
        if ($request->has('sortBy')){
            $sortBy = $request->query('sortBy');
        } 
        if ($request->has('perPage')){
            $perPage = $request->query('perPage');
        } 
        if ($request->has('q')){
            $q = $request->query('q');
        }

        $data = tab_ruta::select( 'id_tab_solicitud')
        ->where('id', '=', $id)
        ->first();

        $tab_documento = tab_documento::select( 'id', 'id_tab_ruta', 'id_tab_solicitud', 'de_documento', 'nb_archivo', 
        'mime', 'de_extension', 'in_activo', 'created_at', 'updated_at')
        ->where('id_tab_ruta', '=', $id)
        ->where('in_activo', '=', true)
        //->search($q, $sortBy)
        ->orderBy($sortBy, $orderBy)
        ->paginate($perPage);

        return View::make('proceso.documento.listaVer')->with([
          'tab_documento' => $tab_documento,
          'orderBy' => $orderBy,
          'sortBy' => $sortBy,
          'perPage' => $perPage,
          'columnas' => $columnas,
          'q' => $q,
          'id' => $data->id_tab_solicitud,
          'ruta' => $id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function verReporte($id, $t)
    {
		$directorio = '/App/reporte/'.$id.'.pdf';
		$archivo = Storage::disk('local')->get($directorio);

        return (new ResposeFile($archivo, 200))->header('Content-Type', 'application/pdf');
        
    }


     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mail($id)
    {
        

        $tab_persona = tab_persona::join('proceso.tab_ruta as t01','t01.id_persona','=','telemedicina.tab_persona.id')->where('t01.id','=',$id)->first();

        $email = $tab_persona->correo;
        $name  = $tab_persona->nombres.' '.$tab_persona->apellidos;


        
       // echo $email; exit();

        try{
            Mail::send(
                        'emails.plantilla', array('codigo_confirmacion' =>"sss", 'usuario' => "admin" ), 
                        function($message) use ($email, $name,$id){
                            $message->sender('teleconsulta@gobeltech.com');
                            $message->to($email, $name )->subject('Telemedicina Informe '.$name);

                            $filename = tab_documento::where('id_tab_ruta','=',$id)->get();

                            foreach($filename as $key => $value){
                                $archivo = storage_path('app').'/App/documento/'.$value->id.'.'.$value->de_extension;                               
                                 $message->attach( $archivo, array(
                                      'as' => $value->nb_archivo,
                                      'mime' => $value->mime)
                                 );
                            }

                            $tab_ruta = tab_ruta::where('id','=',$id)->where('in_reporte','=',true)->first();

                            if(!empty($tab_ruta->id)){

                                 $archivo = storage_path('app').'/App/reporte/'.$tab_ruta->id.'.pdf';                               
                                 $message->attach( $archivo, array(
                                      'as' => "Informe".'.pdf',
                                      'mime' => 'application/pdf')
                                 );

                            }


                        }
                    );

            Session::flash('msg_side_overlay', 'El correo se envió exitosamente!');
            return Redirect::to('/proceso/ruta/lista/'.$id);

         }catch(\Exception $e){

           Session::flash('msg_side_overlay', 'Occurió un problema al enviar el correo!');
            return Redirect::to('/proceso/ruta/lista/'.$id);
          }
        
    }
}
