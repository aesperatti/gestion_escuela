<?php
class ci_materias extends gestion_escuela_ci
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

	/**
	 * Ventana de extensi�n para configurar la pantalla. Se ejecuta previo a la configuraci�n de los componentes pertenecientes a la pantalla 
	 * por lo que es ideal por ejemplo para ocultarlos en base a una condici�n din�mica, ej. $pant->eliminar_dep("tal") 
	 * @param toba_ei_pantalla $pantalla
	 */
	function conf__pant_edicion(toba_ei_pantalla $pantalla)
	{
		    $hay_cambios = $this->dep('datos')->hay_cambios();
            toba::menu()->set_modo_confirmacion('Esta a punto de abandonar la edición del materia sin grabar, ¿Desea continuar?', $hay_cambios);
	}

	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	/**
	 * Atrapa la interacci�n del usuario a trav�s del bot�n asociado. El m�todo no recibe par�metros
	 */
	function evt__agregar()
	{
		$this->set_pantalla('pant_edicion');   
	}

	/**
	 * Originalmente este m�todo limpia las variables y definiciones del componente, y en caso de exisitr un CN asociado ejecuta su cancelar. Para mantener este comportamiento llamar a parent::evt__cancelar
	 */
	function evt__cancelar()
	{
		   	$this->dep('datos')->resetear();
            unset($this->s__filtro);
            $this->set_pantalla('pant_inicial');  
	}

	/**
	 * Atrapa la interacci�n del usuario a trav�s del bot�n asociado. El m�todo no recibe par�metros
	 */
	function evt__guardar()
	{
		    try{
                $this->dep('datos')->sincronizar();
				$this->dep('datos')->resetear();
            }catch(toba_error_db $e){
                /* Error al grabar */
                if($e->get_sqlstate()=="db_23505"){
                    /* Clave Duplicada */
                    $mensaje ="Ya existe el materia que desea agregar";
                    toba::notificacion()->agregar($mensaje);
                }else {
                    $mensaje_usuario='ERROR al guardar. Los cambios NO fueron registrados.';
                    $mensaje='<br><br>Información Adicional: ';
                    $mensaje.='<br><strong>Error Nº </strong>'.$e->get_sqlstate();
                    $mensaje.='<br><br><strong> Mensaje: </strong>'.$e->get_mensaje_motor();
                    throw new toba_error($mensaje_usuario,$mensaje);
                    //toba::notificacion()->agregar($mensaje);
                }
            }
            $this->set_pantalla('pant_inicial');   
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
		    if(isset($this->s__filtro)){
                $where = $this->dep('filtro')->get_sql_where();	
                $datos = toba::consulta_php('gestion_escuela')->get_materiasconcarrera($where);
            }else{
                $datos = toba::consulta_php('gestion_escuela')->get_materiasconcarrera();
            }
            $cuadro->set_datos($datos);   
	}

	/**
	 * Atrapa la interacci�n del usuario con el bot�n asociado
	 * @param array $seleccion Id. de la fila seleccionada
	 */
	function evt__cuadro__seleccion($seleccion)
	{
		    $this->dep('datos')->cargar($seleccion);
            $this->set_pantalla('pant_edicion');  
	}

	/**
	 * Atrapa la interacci�n del usuario con el bot�n asociado
	 * @param array $seleccion Id. de la fila seleccionada
	 */
	function evt__cuadro__eliminar($seleccion)
	{
		    try{
                $this->dep('datos')->cargar($seleccion);
                $this->dep('datos')->eliminar_todo();
                $this->dep('datos')->sincronizar();
            }catch (toba_error_db $e) {
                if($e->get_sqlstate()=="db_23503"){
                    toba::notificacion()->agregar('ATENCION!! El registro No será eliminado para mantener la integridad de los datos.');
                }else{
                    toba::notificacion()->agregar('El registro no puede borrarse: '.$e->get_sqlstate() );
                }    
            }
            $this->dep('datos')->resetear();  
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
		$form->set_datos($this->dep('datos')->get());	           
	
	}

	/**
	 * Atrapa la interacci�n del usuario con el bot�n asociado
	 * @param array $datos Estado del componente al momento de ejecutar el evento. El formato es el mismo que en la carga de la configuraci�n
	 */
	function evt__formulario__modificacion($datos)
	{
		
		$this->dep('datos')->set($datos);
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

}

?>