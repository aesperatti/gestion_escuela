<?php

class ci_notProfesores extends gestion_escuela_ci
{
	//-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------
	  protected $s__filtro;


	/**
	 * Ventana de extensi�n para configurar la pantalla. Se ejecuta previo a la configuraci�n de los componentes pertenecientes a la pantalla 
	 * por lo que es ideal por ejemplo para ocultarlos en base a una condici�n din�mica, ej. $pant->eliminar_dep("tal") 
	 * @param toba_ei_pantalla $pantalla
	 */
	function conf__pant_inicial(toba_ei_pantalla $pantalla)
	{
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	/**
	 * Permite cambiar la configuraci�n del cuadro previo a la generaci�n de la salida
	 * El formato de carga es de tipo recordset: array( array('columna' => valor, ...), ...)
	 */
	function conf__cuadro(gestion_escuela_ei_cuadro $cuadro)
	{
		$where = '1=1';
   
		if (isset($this->s__filtro)) { $where_filtro = trim((string) $this->dep('filtro')->get_sql_where());

		if ($where_filtro !== '') {
				$where = $where_filtro;
			}
		}	

   		 $datos = toba::consulta_php('gestion_escuela')->notprof_get_mesas_confirmadas($where);
		 $cuadro->set_datos($datos);
	}

	/**
	 * Atrapa la interacci�n del usuario con el bot�n asociado
	 * @param array $seleccion Id. de la fila seleccionada
	 */
	function evt__cuadro__enviar_mail($seleccion)
	{
		if (
			!isset($seleccion['id_mesa']) ||
			(int) $seleccion['id_mesa'] <= 0
		) {
			toba::notificacion()->agregar(
				'No se pudo identificar la mesa seleccionada.',
				'error'
			);

			return;
		}

		$id_mesa = (int) $seleccion['id_mesa'];

		$alumnos = toba::consulta_php('gestion_escuela')
			->notprof_get_alumnos_mesa($id_mesa);

		if (empty($alumnos)) {
			toba::notificacion()->agregar(
				'La mesa seleccionada no tiene alumnos aprobados.',
				'info'
			);

			return;
		}

		$nombres = array();

		foreach ($alumnos as $alumno) {
			$nombres[] = $alumno['alumno'];
		}

		$mensaje =
			'Mesa ID: ' . $id_mesa .
			'. Cantidad de alumnos aprobados: ' . count($alumnos) .
			'. Alumnos: ' . implode('; ', $nombres);

		toba::notificacion()->agregar(
			$mensaje,
			'info'
		);
	}
	

	//-----------------------------------------------------------------------------------
	//---- filtro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	/**
	 * Permite cambiar la configuraci�n del formulario previo a la generaci�n de la salida
	 * El formato del carga debe ser array(<campo> => <valor>, ...)
	 */
	function conf__filtro(gestion_escuela_ei_filtro $filtro)
	{
		$filas_carreras = toba::consulta_php('gestion_escuela')->notprof_get_carreras();
			$opciones_carreras = array('' => '-- Todas las carreras --');

        foreach ($filas_carreras as $fila) {
            $opciones_carreras[$fila['id']] = $fila['descripcion'];
        }

        $filtro
            ->columna('id_carrera')
            ->get_ef()
            ->set_opciones($opciones_carreras);


        $filas_profesores = toba::consulta_php('gestion_escuela')
            ->notprof_get_profesores();

        $opciones_profesores = array('' => '-- Todos los profesores --');

        foreach ($filas_profesores as $fila) {
            $opciones_profesores[$fila['id']] = $fila['nombre_completo'];
        }

        $filtro
            ->columna('id_profesor')
            ->get_ef()
            ->set_opciones($opciones_profesores);

   
        $filas_materias = toba::consulta_php('gestion_escuela')
            ->notprof_get_materias();

        $opciones_materias = array('' => '-- Todas las materias --');

        foreach ($filas_materias as $fila) {
            $opciones_materias[$fila['id']] = $fila['descripcion'];
        }

        $filtro
            ->columna('id_materia')
            ->get_ef()
            ->set_opciones($opciones_materias);


        if (isset($this->s__filtro)) {
            $filtro->set_datos($this->s__filtro);
        }
	}

	/**
	 * Atrapa la interacci�n del usuario con el bot�n asociado
	 * @param array $datos Estado del componente al momento de ejecutar el evento. El formato es el mismo que en la carga de la configuraci�n
	 */
	function evt__filtro__filtrar($datos)
	{
		$this->s__filtro = $datos;
	}

	/**
	 * Atrapa la interacci�n del usuario con el bot�n asociado
	 */
	function evt__filtro__cancelar()
	{
		unset($this->s__filtro);
	}

}

?>