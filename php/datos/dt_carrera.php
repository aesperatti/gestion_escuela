<?php
class dt_carrera extends gestion_escuela_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, descripcion FROM carrera ORDER BY descripcion";
		return toba::db('gestion_escuela')->consultar($sql);
	}

}

?>