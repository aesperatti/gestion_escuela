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

}
?>