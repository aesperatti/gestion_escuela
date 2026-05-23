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
		function get_mesas($where='1=1')
		{
			$sql="select me.id,
				ma.nombre as descmateria,
				pro.apellido as descprofesor,			 
				me.fecha, me.hora, me.aula, me.cupo 
				from mesas_examen me
				join materias ma
				on ma.id = me.id_materia
				join profesores pro
				on pro.id = me.id_profesor
				where $where";
			return toba::db()->consultar($sql);
		}
    }