<?php
	class gestion_escuela
	{
        function get_alumnos($where='1=1')
		{
			$sql="select alu.id, alu.legajo, alu.nombre, 
							alu.apellido, alu.dni, alu.email, 
							ca.descripcion as desccarrera,
							alu.id_carrera
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

		function get_materias_fecha($id_materia)
		{
			$sql="select fecha_mesa from materias where id='$id_materia'";
			return toba::db()->consultar($sql);
		}

		function get_materiasconcarrera($where='1=1')
		{
			$sql="select ma.id, ma.codigo, ma.nombre, ma.anio, pro.apellido || ', ' || pro.nombre as descprofesor, ca.descripcion desccarrera 
			from materias ma
			join carrera ca
			on ma.id_carrera=ca.id
			join profesores pro
			on pro.id=ma.id_profesor
			where $where";
			return toba::db()->consultar($sql);
		}
		function get_mesas($where='1=1')
		{
			$sql="select me.id, ca.descripcion as desccarrera, ma.nombre as descmateria, pro.apellido || ', ' || pro.nombre as descprofesor, me.fecha, me.hora, me.cupo, au.descripcion as descaula from mesas_examen me join materias ma on ma.id = me.id_materia join profesores pro on pro.id = ma.id_profesor join carrera ca on ma.id_carrera=ca.id join aulas au on me.id_aula=au.id where $where";
			return toba::db()->consultar($sql);
		}
		function get_mesasparacombo($where='1=1')
		{
			$sql="select me.id, ma.nombre || ', ' || pro.apellido || ' ' 
			|| pro.nombre || ', ' || me.fecha || ', ' || me.hora as descripcion from mesas_examen me 	join materias ma on ma.id = me.id_materia join profesores pro on pro.id = me.id_profesor where $where";
			return toba::db()->consultar($sql);
		}
		function get_estadosinscripcion($where='1=1')
		{
			$sql="select * from estados_inscripcion where $where";
			return toba::db()->consultar($sql);
		}
		function get_carrera($where='1=1')
		{
			$sql="select * from carrera where $where";
			return toba::db()->consultar($sql);
		}
		function get_inscripciones($where='1=1')
		{
			$sql="select insc.id_inscripcion, alu.apellido || ', ' || alu.nombre as nombre_completo, insc.id_mesa, insc.fecha_inscripcion, ei.descripcion as estado, ca.descripcion as desccarrera 
			from inscripciones insc 
			join estados_inscripcion ei on ei.id = insc.id_estado 
			join alumnos alu on insc.id_alumno=alu.id 
			join carrera ca on alu.id_carrera=ca.id where $where";
			return toba::db()->consultar($sql);
		}

		function get_inscripcionesabm($where='1=1')
		{
			$sql="select insc.id_inscripcion, alu.apellido || ', ' || alu.nombre as nombre_completo, insc.id_mesa, insc.fecha_inscripcion, insc.id_estado , ca.descripcion as desccarrera 
			from inscripciones insc 
			join estados_inscripcion ei on ei.id = insc.id_estado 
			join alumnos alu on insc.id_alumno=alu.id 
			join carrera ca on alu.id_carrera=ca.id where $where";
			return toba::db()->consultar($sql);
		}


		function get_carrera_materias($carrera=null)
		{
			$sql="select * from materias where id_carrera=$carrera";
			return toba::db()->consultar($sql);
		}
		function get_aulas($where='1=1')
		{
			$sql="select * from aulas where $where";
			return toba::db()->consultar($sql);
		}

    }