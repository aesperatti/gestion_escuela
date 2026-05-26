<?php
class ci_consultainscripciones extends gestion_escuela_ci
{
	//-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__pant_inicial(toba_ei_pantalla $pantalla)
	{
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(gestion_escuela_ei_cuadro $cuadro)
	{
			if(isset($this->s__filtro)){
                $where = $this->dep('filtro')->get_sql_where();	
                $datos = toba::consulta_php('gestion_escuela')->get_inscripciones($where);
				$cuadro->set_datos($datos); 
            }  
	}

	function evt__cuadro__seleccion($seleccion)
	{
		ei_arbol($seleccion);
	}

	function conf_evt__cuadro__seleccion(toba_evento_usuario $evento, $fila)
	{
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

}

?>