<?php
class ci_aulas extends gestion_escuela_ci
{
	protected $s__filtro;

	//-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	/**
	 * Ventana de extensi�n para configurar la pantalla. Se ejecuta previo a la configuraci�n de los componentes pertenecientes a la pantalla 
	 * por lo que es ideal por ejemplo para ocultarlos en base a una condici�n din�mica, ej. $pant->eliminar_dep("tal") 
	 * @param toba_ei_pantalla $pantalla
	 */
	function conf__pant_inicial(toba_ei_pantalla $pantalla)
	{
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	/**
	 * Permite cambiar la configuraci�n del cuadro previo a la generaci�n de la salida
	 * El formato de carga es de tipo recordset: array( array('columna' => valor, ...), ...)
	 */
	function conf__cuadro(gestion_escuela_ei_cuadro $cuadro)
	{
			if (isset($this->s__filtro)) {
			  $where = $this->dep('filtro')->get_sql_where();
          	}else {
              $where = 'true';
          	}
		  $datos = toba::consulta_php('gestion_escuela')->get_aulas($where);
          $cuadro->set_datos($datos);
	}

	/**
	 * Atrapa la interacci�n del usuario con el bot�n asociado
	 * @param array $seleccion Id. de la fila seleccionada
	 */
	function evt__cuadro__seleccion($seleccion)
	{
			$this->dep('datos')->cargar($seleccion);	
	}

	/**
	 * Permite configurar el evento por fila.
	 * �til para decidir si el evento debe estar disponible o no de acuerdo a los datos de la fila
	 * [wiki:Referencia/Objetos/ei_cuadro#Filtradodeeventosporfila Ver m�s]
	 */
	function conf_evt__cuadro__seleccion(toba_evento_usuario $evento, $fila)
	{
	}

	//-----------------------------------------------------------------------------------
	//---- filtro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	/**
	 * Permite cambiar la configuraci�n del formulario previo a la generaci�n de la salida
	 * El formato del carga debe ser array(<campo> => <valor>, ...)
	 */
	function conf__filtro(gestion_escuela_ei_filtro $filtro)
	{
		if (isset($this->s__filtro)) {
			$filtro->set_datos($this->s__filtro);

		}		
	}

	/**
	 * Atrapa la interacci�n del usuario con el bot�n asociado
	 * @param array $datos Estado del componente al momento de ejecutar el evento. El formato es el mismo que en la carga de la configuraci�n
	 */
	function evt__filtro__filtrar($datos)
	{
		$this->s__filtro = $datos;	
	}

	/**
	 * Atrapa la interacci�n del usuario con el bot�n asociado
	 */
	function evt__filtro__cancelar()
	{
		unset($this->s__filtro);	
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
	      if ($this->dep('datos')->esta_cargada()) {
				return $this->dep('datos')->get();    
		  }	
	}

	/**
	 * Atrapa la interacci�n del usuario con el bot�n asociado
	 * @param array $datos Estado del componente al momento de ejecutar el evento. El formato es el mismo que en la carga de la configuraci�n
	 */
	function evt__formulario__alta($datos)
	{
		try{
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
	function evt__formulario__baja()
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
	function evt__formulario__modificacion($datos)
	{
		try{
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
	function evt__formulario__cancelar()
	{
			$this->dep('datos')->resetear();
	}
}

?>