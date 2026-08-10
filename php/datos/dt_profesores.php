<?php
class dt_profesores extends gestion_escuela_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, nombre FROM profesores ORDER BY nombre";
		return toba::db('gestion_escuela')->consultar($sql);
	}

}

?>