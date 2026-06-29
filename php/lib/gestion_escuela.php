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
			$sql="select 
					TO_CHAR(fecha_mesa, 'DD/MM/YYYY') as fecha_mesa 
				  from materias where id='$id_materia'";
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
			$sql="select me.id, ca.descripcion as desccarrera,
			             ma.nombre as descmateria, 
						 pro.apellido || ', ' || pro.nombre as descprofesor, 
						 me.fecha, 
						 au.descripcion as descaula,
						 ca.id as id_carrera 
				    from mesas_examen me 
					join materias ma on ma.id = me.id_materia 
					join profesores pro on pro.id = ma.id_profesor 
					join carrera ca on ma.id_carrera=ca.id 
					join aulas au on me.id_aula=au.id 
					where $where";
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
		function get_condiciones($where='1=1')
		{
			$sql="select * from condicion_inscripcion where $where";
			return toba::db()->consultar($sql);
		}
		function get_inscripciones($where='1=1')
		{
			$sql="select insc.id_inscripcion, alu.apellido || ', ' || alu.nombre as nombre_completo, insc.fecha_inscripcion, ei.descripcion as estado, ca.descripcion as desccarrera, ci.descripcion as desc_condicion, alu.legajo, alu.dni
			from inscripciones insc 
			join estados_inscripcion ei on ei.id = insc.id_estado 
			join condicion_inscripcion ci on ci.id = insc.id_condicion
			join alumnos alu on insc.id_alumno=alu.id 
			join materias ma on ma.id = insc.id_materia
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

		function get_materias_xprofesor($profesor=null)
		{
			$sql="select * from materias where id_profesor=$profesor";
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
		function get_inscripcionesporalumno($idalumno)
		{
			$sql="select insc.id_inscripcion, ma.nombre as desc_materia, insc.fecha_inscripcion, ei.id as id_estado, 
			ci.descripcion as desc_condicion, ei.descripcion as desc_estado, ca.descripcion as desccarrera, 
			
			CASE
				WHEN insc.motivo IS NOT NULL AND LENGTH(insc.motivo) > 50 THEN LEFT(insc.motivo, 50) || '...'
				ELSE insc.motivo
    		END AS motivo
 
			from inscripciones insc 
			join estados_inscripcion ei on ei.id = insc.id_estado
			join condicion_inscripcion ci on ci.id = insc.id_condicion
			join materias ma on ma.id=insc.id_materia 
			join carrera ca on ma.id_carrera=ca.id where insc.id_alumno=$idalumno";
			return toba::db()->consultar($sql);
		}

		function get_estadospendientes_inscripciones($idalumno)
		{
			$sql="select count(1) as pendientes  
			from inscripciones 
            where id_alumno=$idalumno and id_estado=1";
			return toba::db()->consultar($sql);
		}

		function get_mesaincripcion($idmateria)
		{
			$sql="select * from mesas_examen where id_materia=$idmateria";
		
			return toba::db()->consultar($sql);
		}
		
		
    }

	