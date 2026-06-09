<?php
class ci_abminscripciones extends gestion_escuela_ci
{
	protected $s__filtro;
	protected $s__datos;
	protected $mesa;
	protected $alumno;

	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(gestion_escuela_ei_cuadro $cuadro)
	{
			if(isset($this->s__filtro)){
				$where = $this->dep('filtro')->get_sql_where();  
				$datos = toba::consulta_php('gestion_escuela')->get_alumnos($where);
			}else{
				$datos = toba::consulta_php('gestion_escuela')->get_alumnos();
			}
			$cuadro->set_datos($datos);  
	}

	function evt__cuadro__seleccion($seleccion)
	{
		$id=$seleccion['id'];
		$where = "alu.id=$id";
		$this->s__datos = toba::consulta_php('gestion_escuela')->get_alumnos($where);
		$this->set_pantalla('pant_edicion'); 
		
		
	}

	//-----------------------------------------------------------------------------------
	//---- filtro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro(gestion_escuela_ei_filtro $filtro)
	{
		if (isset($this->s__filtro)) {
			$filtro->set_datos($this->s__filtro);
		} 
	}

	function evt__filtro__filtrar($datos)
	{
		$this->s__filtro = $datos;
	}

	function evt__filtro__cancelar()
	{
		unset($this->s__filtro); 
	}

	//-----------------------------------------------------------------------------------
	//---- cuadroalumnos ----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadroalumnos(gestion_escuela_ei_cuadro $cuadro)
	{
			$cuadro->set_datos($this->s__datos);
	}


	function evt__cuadroalumnos__seleccion($seleccion)
	{
		$this->dep('datos')->cargar($seleccion);
		$this->dep('formularioinscripcion')->set_datos($this->dep('datos'));
	}

	//-----------------------------------------------------------------------------------
	//---- formulario -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	/**
	 * Permite cambiar la configuraci�n del formulario previo a la generaci�n de la salida
	 * El formato del carga debe ser array(<campo> => <valor>, ...)
	 */
	function conf__formulario(gestion_escuela_ei_formulario $form)
	{
		$form->set_datos($this->s__datos[0]);	
	}

	/**
	 * Atrapa la interacci�n del usuario con el bot�n asociado
	 * @param array $datos Estado del componente al momento de ejecutar el evento. El formato es el mismo que en la carga de la configuraci�n
	 */
	function evt__formulario__modificacion($datos)
	{
	}

	function get_materiasporcarrera(){
		
		if (!isset($this->s__datos)){
			return array();
		}

		$idcarrera = $this->s__datos[0]['id_carrera'];

		//Metodo toba para prevenir inyeccion sql
		$idcarrera = toba::db()->quote($idcarrera); 
		
		$sql = "select * from materias where id_carrera=$idcarrera";
		
		return toba::db()->consultar($sql);
	}
	//-----------------------------------------------------------------------------------
	//---- cuadro2 ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
	
	/**
	 * Permite cambiar la configuraci�n del cuadro previo a la generaci�n de la salida
	 * El formato de carga es de tipo recordset: array( array('columna' => valor, ...), ...)
	 */
	function conf__cuadro2(gestion_escuela_ei_cuadro $cuadro)
	{                
		$id=$this->s__datos[0]['id'];
		$datos = toba::consulta_php('gestion_escuela')->get_inscripcionesporalumno($id);
		foreach ($datos as $clave => $fila) {
            $color = '';
            
            switch ($fila['id_estado']) {
                case 1: $color = '#ff9800'; break; // Naranja
                case 2: $color = '#4caf50'; break; // Verde
                case 3: $color = '#f44336'; break; // Rojo
                case 4: $color = '#9e9e9e'; break; // Gris
                case 5: $color = '#2196f3'; break; // Azul
            }
            
            // 3. Sobrescribimos el texto plano con una etiqueta <span> HTML
            $datos[$clave]['desc_estado'] = "<span style='color: {$color}; font-weight: bold;'>{$fila['desc_estado']}</span>";
        }
		$cuadro->set_datos($datos); 
	}

	/**
	 * Atrapa la interacci�n del usuario con el bot�n asociado
	 * @param array $seleccion Id. de la fila seleccionada
	 */
	function evt__cuadro2__seleccion($seleccion)
	{
		$this->dep('datos')->cargar($seleccion);	
	}

	//-----------------------------------------------------------------------------------
	//---- formulario_inscri ------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	/**
	 * Permite cambiar la configuraci�n del formulario previo a la generaci�n de la salida
	 * El formato del carga debe ser array(<campo> => <valor>, ...)
	 */
	function conf__formulario_inscri(gestion_escuela_ei_formulario $form)
	{
		if ($this->dep('datos')->esta_cargada()) {
			return $this->dep('datos')->get();    
		}			
	}

	/**
	 * Atrapa la interacci�n del usuario con el bot�n asociado
	 * @param array $datos Estado del componente al momento de ejecutar el evento. El formato es el mismo que en la carga de la configuraci�n
	 */
	protected function formatear_fecha_bd($fecha_pantalla)
    {
        // Si no mandaron nada, no hacemos nada
        if (empty($fecha_pantalla)) {
            return $fecha_pantalla;
        }

        // Intentamos crear el objeto desde el formato argentino
        $fecha_objeto = DateTime::createFromFormat('d/m/Y', $fecha_pantalla);
        
        if ($fecha_objeto !== false) {
            // Si tuvo éxito, la devolvemos en formato base de datos
            return $fecha_objeto->format('Y-m-d');
        }

        // Si falló (por ejemplo, si ya venía como Y-m-d por algún motivo), 
        // devolvemos el original para que la base de datos decida qué hacer.
        return $fecha_pantalla;
    }
	function get_fechamesa($id){
		$sql = "select fecha from mesas_examen where id=$id";
		
		return toba::db()->consultar($sql);
	}
	function evt__formulario_inscri__alta($datos)
	{
		try{
			$datos['id_alumno']= $this->s__datos[0]['id'];
			$idfecha=$datos['fecha_inscripcion'];
			$fechas=$this->get_fechamesa($idfecha);
			
			$datos['fecha_inscripcion']=$fechas[0]['fecha'];
			//Formateo de fecha porque postgres me da error de formato, valor fuera de rango
			//$datos['fecha_inscripcion'] = $this->formatear_fecha_bd($datos['fecha_inscripcion']);
			$this->dep('datos')->set($datos);
			$this->dep('datos')->sincronizar();
			$this->dep('datos')->resetear();
		}catch (toba_error_db $e){
			
			if($e->get_sqlstate()=="db_23505"){
				toba::notificacion()->agregar('ATENCION!! El registro ya Existe.');
			}
		}		
	}

	/**
	 * Atrapa la interacci�n del usuario con el bot�n asociado
	 */
	function evt__formulario_inscri__baja()
	{
		try{
			$this->dep('datos')->eliminar_todo();
			$this->dep('datos')->sincronizar();
		}catch (toba_error_db $e){
			if ($e->get_sqlstate() =="db_23503") {
				toba::notificacion()->agregar('ATENCION!! El registro no puede eliminarse por que esta En Uso.');
			}
		}		
	}

	/**
	 * Atrapa la interacci�n del usuario con el bot�n asociado
	 * @param array $datos Estado del componente al momento de ejecutar el evento. El formato es el mismo que en la carga de la configuraci�n
	 */
	function evt__formulario_inscri__modificacion($datos)
	{
		try{
			//$datos['fecha_inscripcion'] = $this->formatear_fecha_bd($datos['fecha_inscripcion']);
			$idfecha=$datos['fecha_inscripcion'];
			$fechas=$this->get_fechamesa($idfecha);
			
			$datos['fecha_inscripcion']=$fechas[0]['fecha'];
			$this->dep('datos')->set($datos);
			$this->dep('datos')->sincronizar();
			$this->dep('datos')->resetear();
		}catch (toba_error_db $e){
			if($e->get_sqlstate()=="db_23505"){
				toba::notificacion()->agregar('ATENCION!! El registro ya Existe.');
			}
		}		
	}

	/**
	 * Atrapa la interacci�n del usuario con el bot�n asociado
	 */
	function evt__formulario_inscri__cancelar()
	{
		$this->dep('datos')->resetear();		
	}

	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__volver()
	{
			$this->dep('datos')->resetear();
            unset($this->s__datos);
            $this->set_pantalla('pant_inicial');  
	}

}
?>