<?php
	class gestion_escuela
	{
        function get_alumnos($where='1=1')
		{
			$sql="select 
					alu.id, 
					alu.legajo, 
					alu.nombre, 
					alu.apellido, 
					alu.dni, 
					alu.email, 
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
			$sql="select 
					id, 
					apellido || ', ' || nombre as nombre_completo 
				from profesores 
				where $where";
			return toba::db()->consultar($sql);
		}

		function get_materias($where='1=1')
		{
			$sql="select * from materias 
				where $where";
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
			$sql="select 
					ma.id, 
					ma.codigo, 
					ma.nombre, 
					ma.anio, 
					pro.apellido || ', ' || 
					pro.nombre as descprofesor, 
					ca.descripcion as desccarrera 
				from materias ma
				join carrera ca on ma.id_carrera = ca.id
				join profesores pro on pro.id = ma.id_profesor
			where $where";
			return toba::db()->consultar($sql);
		}

		function get_mesas($where='1=1')
		{
			$sql="select 
						 me.id, ca.descripcion as desccarrera,
			             ma.nombre as descmateria, 
						 pro.apellido || ', ' || pro.nombre as descprofesor, 
						 me.fecha, 
						 au.descripcion as descaula,
						 ca.id as id_carrera 
				    from mesas_examen me 
					join materias ma on ma.id = me.id_materia 
					join profesores pro on pro.id = ma.id_profesor 
					join carrera ca on ma.id_carrera = ca.id 
					join aulas au on me.id_aula = au.id 
					where $where";
			return toba::db()->consultar($sql);
		}

		function get_mesasparacombo($where='1=1')
		{
			$sql="select 
					me.id, 
					ma.nombre || ', ' || 
					pro.apellido || ' ' || 
					pro.nombre || ', ' || 
					me.fecha || ', ' || 
					me.hora as descripcion 
					from mesas_examen me 	
					join materias ma on ma.id = me.id_materia 
					join profesores pro on pro.id = me.id_profesor 
					where $where";
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
			$sql="select 
				insc.id_inscripcion, 
				alu.apellido || ', ' || 
				alu.nombre as nombre_completo,
				insc.fecha_inscripcion, 
				ei.descripcion as estado, 
				ca.descripcion as desccarrera, 
				ci.descripcion as desc_condicion, 
				alu.legajo, alu.dni
			from inscripciones insc 
			join estados_inscripcion ei on ei.id = insc.id_estado 
			join condicion_inscripcion ci on ci.id = insc.id_condicion
			join alumnos alu on insc.id_alumno=alu.id 
			join materias ma on ma.id = insc.id_materia
			join carrera ca on alu.id_carrera=ca.id 
			where $where";
			return toba::db()->consultar($sql);
		}

		function get_inscripcionesabm($where='1=1')
		{
			$sql="select insc.id_inscripcion, 
				alu.apellido || ', ' || alu.nombre as nombre_completo, 
				insc.id_mesa, 
				insc.fecha_inscripcion,
				insc.id_estado , 
				ca.descripcion as desccarrera 
			from inscripciones insc 
			join estados_inscripcion ei on ei.id = insc.id_estado 
			join alumnos alu on insc.id_alumno=alu.id 
			join carrera ca on alu.id_carrera=ca.id 
			where $where";
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
			$sql="select 
				insc.id_inscripcion, 
				ma.nombre as desc_materia, 
				insc.fecha_inscripcion, 
				ei.id as id_estado, 
				ci.descripcion as desc_condicion, 
				ei.descripcion as desc_estado, 
				ca.descripcion as desccarrera,
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
		
	
		function get_profesoresconsulta($where='1=1')
        {
            $sql = "select
                        pro.id AS id_profesor,
                        pro.apellido || ', ' || pro.nombre AS nombre_completo,
                        pro.email,
                        ca.descripcion AS desccarrera,
                        ma.nombre AS descmateria
                    from profesores pro
                    join materias ma on ma.id_profesor = pro.id
                    join carrera ca on ca.id = ma.id_carrera
                    where $where
                    order by
                        pro.apellido,
                        pro.nombre,
                        ma.nombre";

            return toba::db()->consultar($sql);
        }
		function get_alumnos_profesor($id_profesor)
        {
            $id_profesor = (int)$id_profesor;

            // ACA SE CORRIGIO EL 'selecy' Y EL 'wherew'
            $sql = "select
                        pro.id AS id_profesor,
                        pro.apellido || ', ' || pro.nombre AS nombre_completo,
                        pro.email,

                        ma.id AS id_materia,
                        ma.nombre AS descmateria,

                        ca.descripcion AS desccarrera,

                        alu.id,
                        alu.legajo,
                        alu.apellido,
                        alu.nombre,
                        alu.dni,
                        alu.email AS email_alumno

                    from profesores pro
                    join materias ma on ma.id_profesor = pro.id
                    join carrera ca on ca.id = ma.id_carrera
                    join inscripciones insc on insc.id_materia = ma.id
                    join alumnos alu on alu.id = insc.id_alumno
                    where pro.id = $id_profesor
                    order by
                        ma.nombre,
                        alu.apellido,
                        alu.nombre";

            return toba::db()->consultar($sql);
        }


	function notprof_get_carreras(){
		$sql = "
			SELECT id, descripcion
			FROM carrera
			ORDER BY descripcion
		";
		return toba::db('gestion_escuela')->consultar($sql);	
	}

	function notprof_get_profesores(){
		$sql = "
			SELECT id, apellido || ', ' || nombre AS nombre_completo
			FROM profesores 					
			ORDER BY apellido,nombre
		";
		return toba::db('gestion_escuela')->consultar($sql);
	}
	
	function notprof_get_materias(){
		$sql = "
			SELECT id, nombre as descripcion
			FROM materias
			ORDER BY nombre
		";
		return toba::db('gestion_escuela')->consultar($sql);
	}

	function notprof_get_resultados($Where = '1=1'){
		$sql = "
			SELECT pro.id as id_profesor, ma.id as id_materia, ca.id as id_carrera, pro.apellido || ', ' || pro.nombre AS nombre_completo,
					pro.email, ca.descripcion as desccarrera, ma.nombre as descmateria
			FROM materias as ma
			JOIN profesores pro ON pro.id = ma.id_profesor
			JOIN carrera ca ON ca.id = ma.id_carrera
			WHERE $Where
			ORDER BY ca.descripcion, ma.nombre, pro.apellido, pro.nombre
		";
		return toba::db('gestion_escuela')->consultar($sql);
	}

	function notprof_get_mesas_confirmadas($where = '1=1')
	{
		$where = trim((string) $where);

		if ($where === '') {
			$where = '1=1';
		}

		$sql = "
			SELECT
				me.id AS id_mesa,

				ca.id AS id_carrera,
				ca.descripcion AS desccarrera,

				ma.id AS id_materia,
				ma.nombre AS descmateria,

				me.fecha AS fecha_mesa_orden,
				TO_CHAR(me.fecha, 'DD/MM/YYYY') AS fecha_mesa,

				pro.id AS id_profesor,
				pro.apellido || ', ' || pro.nombre AS nombre_completo,
				pro.email AS email_profesor,

				COUNT(DISTINCT insc.id_inscripcion) AS cantidad_alumnos

			FROM mesas_examen me

			JOIN materias ma
				ON ma.id = me.id_materia

			JOIN carrera ca
				ON ca.id = ma.id_carrera

			JOIN profesores pro
				ON pro.id = ma.id_profesor

			LEFT JOIN inscripciones insc
				ON insc.id_mesa = me.id
			AND insc.id_estado = 2

			WHERE me.id_materia IS NOT NULL
			AND me.fecha IS NOT NULL
			AND ($where)

			GROUP BY
				me.id,
				ca.id,
				ca.descripcion,
				ma.id,
				ma.nombre,
				me.fecha,
				pro.id,
				pro.apellido,
				pro.nombre,
				pro.email

			ORDER BY
				ca.descripcion,
				me.fecha,
				ma.nombre,
				pro.apellido,
				pro.nombre
		";

		return toba::db('gestion_escuela')->consultar($sql);
	}
	function notprof_get_alumnos_mesa($id_mesa)
	{
		$id_mesa = (int) $id_mesa;

		$sql = "
			SELECT
				insc.id_inscripcion,
				alu.id AS id_alumno,
				alu.legajo,
				alu.apellido || ', ' || alu.nombre AS alumno,
				alu.dni,
				alu.email AS email_alumno

			FROM inscripciones insc

			JOIN alumnos alu
				ON alu.id = insc.id_alumno

			WHERE insc.id_mesa = $id_mesa
			AND insc.id_estado = 2

			ORDER BY
				alu.apellido,
				alu.nombre
		";

		return toba::db('gestion_escuela')->consultar($sql);
	}

}

	