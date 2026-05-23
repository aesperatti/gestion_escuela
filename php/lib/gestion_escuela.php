<?php
	class gestion_escuela
	{
        function get_alumnos($where='1=1')
		{
			$sql="select * from alumnos where $where";
			return toba::db()->consultar($sql);
		}
    }