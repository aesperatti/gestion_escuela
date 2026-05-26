<?php
	class gestion_escuela
	{
        function get_alumnos($where='1=1')
		{
			$sql="select alu.id, alu.legajo, alu.nombre, 
							alu.apellido, alu.dni, alu.email, 
							ca.descripcion as desccarrera
			 from alumnos alu
			 join carrera ca 
			 on alu.id_carrera = ca.id
			where $where";
			return toba::db()->consultar($sql);
		}
        function get_profesores($where='1=1')
		{
			$sql="select * from profesores where $where";
			return toba::db()->consultar($sql);
		}
		function get_nombresprofesores($where='1=1')
		{
			$sql="select id, apellido || ', ' || nombre as nombre_completo from profesores where $where";
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
				pro.apellido || ', ' || pro.nombre as descprofesor,			 
				me.fecha, me.hora, me.aula, me.cupo 
				from mesas_examen me
				join materias ma
				on ma.id = me.id_materia
				join profesores pro
				on pro.id = me.id_profesor
				where $where";
			return toba::db()->consultar($sql);
		}

		function get_carrera($where='1=1')
		{
			$sql="select * from carrera where $where";
			return toba::db()->consultar($sql);
		}


    }