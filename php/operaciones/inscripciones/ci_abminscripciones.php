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
				$datos = toba::consulta_php('gestion_escuela')->get_mesas($where);
			}else{
				$datos = toba::consulta_php('gestion_escuela')->get_mesas();
			}
			$cuadro->set_datos($datos);  
	}

	function evt__cuadro__inscribir($seleccion)
	{
		$this->set_pantalla('pant_edicion'); 
		
		$where = 'insc.id_mesa=\''. $seleccion['id'] .'\'';
		$this->s__datos = toba::consulta_php('gestion_escuela')->get_inscripciones($where);
		
		
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

	//-----------------------------------------------------------------------------------
	//---- formularioinscripcion --------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__formularioinscripcion(gestion_escuela_ei_formulario $form)
	{
		$form->set_datos($this->dep('datos')->get());	
	}

	function evt__formularioinscripcion__modificacion($datos)
	{
		//TODO agregar bloques try catch
		ei_arbol($datos);
		//$where='legajo=\''.$datos['legajo'].'\'';
		//$coso = toba::consulta_php('gestion_escuela')->get_alumnos($where);
		//$datos['id_alumno']=$coso[0]['id'];
		//unset($datos['legajo']);
		//$this->dep('datos')->set($datos);	
	}

	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__agregar()
	{
			try{
                $this->dep('datos')->sincronizar();
				$this->dep('datos')->resetear();
            }catch(toba_error_db $e){
                /* Error al grabar */
                if($e->get_sqlstate()=="db_23505"){
                    /* Clave Duplicada */
                    $mensaje ="Ya existe el alumno que desea agregar";
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
	}

	function evt__cancelar()
	{

	}

	function evt__eliminar()
	{
	}

	function evt__guardar()
	{
	}

	function evt__cuadroalumnos__seleccion($seleccion)
	{
		$this->dep('datos')->cargar($seleccion);
		
		$this->dep('formularioinscripcion')->set_datos($this->dep('datos'));
	}

}
?>