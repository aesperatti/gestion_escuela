<?php
class ci_consultainscripciones extends gestion_escuela_ci
{
	protected $s__filtro;
	//-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__pant_inicial(toba_ei_pantalla $pantalla)
	{
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(gestion_escuela_ei_cuadro $cuadro)
	{
			if(isset($this->s__filtro)){
                $where = $this->dep('filtro')->get_sql_where();	
                $datos = toba::consulta_php('gestion_escuela')->get_profesoresconsulta($where);
				$cuadro->set_datos($datos); 
            }  
	}

	function evt__cuadro__seleccion($seleccion)
	{
		ei_arbol($seleccion);
	}

	function conf_evt__cuadro__seleccion(toba_evento_usuario $evento, $fila)
	{
	}

	//-----------------------------------------------------------------------------------
	//---- filtro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro(gestion_escuela_ei_filtro $filtro)
	{
			if (isset($this->s__filtro)) {
                $filtro->set_datos($this->s__filtro);
            } 
	}

	function evt__filtro__filtrar($datos)
	{
		$this->s__filtro = $datos;  
	}

	function evt__filtro__cancelar()
	{
		unset($this->s__filtro);
	}

	/**
	 * Atrapa la interacci�n del usuario con el bot�n asociado
	 * @param array $seleccion Id. de la fila seleccionada
	 */
	function evt__cuadro__mailindividual($seleccion)
	{
		$id_profesor = $seleccion['id_inscripcion'];

		$datos = toba::consulta_php('gestion_escuela')
						->get_alumnos_profesor($id_profesor);

		if (empty($datos)) {
			toba::notificacion()->agregar(
				'No existen alumnos inscriptos para las materias del profesor.',
				'error'
			);
			return;
		}

		$this->procesar_envio_profesor(array($datos[0]), $datos);
	}


	function procesar_envio_profesor($profesor, $alumnos)
	{
		$nombre_profesor = $profesor[0]['nombre_completo'];
		$email = trim($profesor[0]['email']);
		$materia = $profesor[0]['descmateria'];
		$carrera = $profesor[0]['desccarrera'];

		if ($email == '') {
			toba::notificacion()->agregar('El profesor no tiene email cargado.', 'error');
			return false;
		}

		$asunto = "Listado de alumnos inscriptos - $materia";

		$cuerpo = "
		<div style='font-family: Arial, Helvetica, sans-serif; font-size:14px'>
			<p>Estimado/a <b>$nombre_profesor</b>:</p>

			<p>Se informa el listado de alumnos inscriptos a la mesa de examen.</p>

			<p>
				<b>Carrera:</b> $carrera<br>
				<b>Materia:</b> $materia
			</p>

			<table border='1' cellpadding='5' cellspacing='0' width='100%'>
				<tr style='background:#f2f2f2'>
					<th>Legajo</th>
					<th>Apellido y Nombre</th>
					<th>DNI</th>
					<th>Email</th>
				</tr>";
				foreach ($alumnos as $alumno) {

			$cuerpo .= "
			<tr>
				<td>{$alumno['legajo']}</td>
				<td>{$alumno['apellido']}, {$alumno['nombre']}</td>
				<td>{$alumno['dni']}</td>
				<td>{$alumno['email_alumno']}</td>
			</tr>";
		}

		$cuerpo .= "
			</table>

			<br><hr>

			<small>
				Gestión Escuela<br>
				Mensaje generado automáticamente por el sistema.
			</small>

		</div>";

		try {

			$mail = new toba_mail($email, $asunto, $cuerpo);

			$mail->set_configuracion_smtp('gestion_escuela_smtp');
			$mail->set_html(true);
			$mail->enviar();

			toba::notificacion()->agregar(
				'Correo enviado correctamente.',
				'info'
			);

			return true;

		} catch (Exception $e) {

			toba::logger()->error($e->getMessage());

			toba::notificacion()->agregar(
				'Error al enviar el correo.',
				'error'
			);

			return false;
		}
	}			

}
?>