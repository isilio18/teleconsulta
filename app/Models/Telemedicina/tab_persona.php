<?php

namespace App\Models\Telemedicina;

use Illuminate\Database\Eloquent\Model;

class tab_persona extends Model
{
    //Nombre de la conexion que utitlizara este modelo
    protected $connection= 'principal';

    //Todos los modelos deben extender la clase Eloquent
    protected $table = 'telemedicina.tab_persona';

    public static $validarCrear = array(
                "cedula" => "required|numeric",
                "nombres" => "required|min:1|max:600",
                "apellido" => "required|min:1|max:600",
                "sexo" => "required",
                "telefono" => "required",
                "municipio" => "required",
                "direccion" => "required",
                "correos" => "required"
        
	);

	public static $validarEditar = array(
                "cedula" => "required|numeric",
                "nombres" => "required|min:1|max:600",
                "apellido" => "required|min:1|max:600",
                "sexo" => "required",
                "telefono" => "required",
                "municipio" => "required",
                "direccion" => "required",
                "correos" => "required"
    );

    public function scopeSearch($query, $q, $sortBy)
    {
      switch ($sortBy) {
          case 'id':
              return $query->where('cedula', 'ILIKE', "%{$q}%");
          break;
            default:
              return $query;
          break;
      }
    }
}
