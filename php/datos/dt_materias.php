<?php
class dt_materias extends gestion_escuela_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, nombre FROM materias ORDER BY nombre";
		return toba::db('gestion_escuela')->consultar($sql);
	}

}

?>