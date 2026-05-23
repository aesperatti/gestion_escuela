<?php
	class gestion_escuela
	{
        function get_alumnos($where='1=1')
		{
			$sql="select * from alumnos where $where";
			return toba::db()->consultar($sql);
		}
        function get_profesores($where='1=1')
		{
			$sql="select * from profesores where $where";
			return toba::db()->consultar($sql);
		}
		function get_materias($where='1=1')
		{
			$sql="select * from materias where $where";
			return toba::db()->consultar($sql);
		}
    }