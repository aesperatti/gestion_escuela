<?php
class ci_abminscripciones extends gestion_escuela_ci
{
	protected $s__filtro;
	protected $s__datos;
	protected $s__alumno;
	protected $s__confirmar_mail;

	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(gestion_escuela_ei_cuadro $cuadro)
	{
			if(isset($this->s__filtro)){
				$where = $this->dep('filtro')->get_sql_where();  
				$datos = toba::consulta_php('gestion_escuela')->get_alumnos($where);
			}else{
				$datos = toba::consulta_php('gestion_escuela')->get_alumnos();
			}
			$cuadro->set_datos($datos);  
	}

	function evt__cuadro__seleccion($seleccion)
	{
		$id=$seleccion['id'];
		$where = "alu.id=$id";
		$this->s__datos = toba::consulta_php('gestion_escuela')->get_alumnos($where);
		$this->set_pantalla('pant_edicion'); 
		
		
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

	//-----------------------------------------------------------------------------------
	//---- cuadroalumnos ----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadroalumnos(gestion_escuela_ei_cuadro $cuadro)
	{
			$cuadro->set_datos($this->s__datos);
	}


	function evt__cuadroalumnos__seleccion($seleccion)
	{
		$this->dep('datos')->cargar($seleccion);
		$this->dep('formularioinscripcion')->set_datos($this->dep('datos'));
	}

	//-----------------------------------------------------------------------------------
	//---- formulario -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	/**
	 * Permite cambiar la configuraci�n del formulario previo a la generaci�n de la salida
	 * El formato del carga debe ser array(<campo> => <valor>, ...)
	 */
	function conf__formulario(gestion_escuela_ei_formulario $form)
	{
		$form->set_datos($this->s__datos[0]);
		if($this->s__datos[0]['email']==null){

			$this->evento('mail')->desactivar();
			$form->ef('email')->set_solo_lectura(false);
		} else {
			$form->evento('guardar')->desactivar();
		}
	}

	/**
	 * Atrapa la interacci�n del usuario con el bot�n asociado
	 * @param array $datos Estado del componente al momento de ejecutar el evento. El formato es el mismo que en la carga de la configuraci�n
	 */
	function evt__formulario__modificacion($datos)
	{
			try{	
			//$datos['fecha_inscripcion'] = $this->formatear_fecha_bd($datos['fecha_inscripcion']);
			$this->s__datos[0]['email']=$datos['email'];
			$clave= array('id' => $this->s__datos[0]['id']);
			$this->dep('datos')->cargar($clave);
			$this->dep('datos')->set($this->s__datos[0]);
			$this->dep('datos')->sincronizar();
			$this->dep('datos')->resetear();
		}catch (toba_error_db $e){
			if($e->get_sqlstate()=="db_23505"){
				toba::notificacion()->agregar('ATENCION!! El registro ya Existe.');
			}}
	}
	

	function get_materiasporcarrera(){
		
		if (!isset($this->s__datos)){
			return array();
		}

		$idcarrera = $this->s__datos[0]['id_carrera'];

		//Metodo toba para prevenir inyeccion sql
		$idcarrera = toba::db()->quote($idcarrera); 
		
		$sql = "select * from materias where id_carrera=$idcarrera";
		
		return toba::db()->consultar($sql);
	}
	//-----------------------------------------------------------------------------------
	//---- cuadro2 ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
	
	/**
	 * Permite cambiar la configuraci�n del cuadro previo a la generaci�n de la salida
	 * El formato de carga es de tipo recordset: array( array('columna' => valor, ...), ...)
	 */
	function conf__cuadro2(gestion_escuela_ei_cuadro $cuadro)
	{                
		$id=$this->s__datos[0]['id'];
		$datos = toba::consulta_php('gestion_escuela')->get_inscripcionesporalumno($id);
		foreach ($datos as $clave => $fila) {
            $color = '';
            
            switch ($fila['id_estado']) {
                case 1: $color = '#ff9800'; break; // Naranja
                case 2: $color = '#4caf50'; break; // Verde
                case 3: $color = '#f44336'; break; // Rojo
                case 4: $color = '#9e9e9e'; break; // Gris
                case 5: $color = '#2196f3'; break; // Azul
            }
            
            // 3. Sobrescribimos el texto plano con una etiqueta <span> HTML
            $datos[$clave]['desc_estado'] = "<span style='color: {$color}; font-weight: bold;'>{$fila['desc_estado']}</span>";
        }
		$cuadro->set_datos($datos); 
	}

	/**
	 * Atrapa la interacci�n del usuario con el bot�n asociado
	 * @param array $seleccion Id. de la fila seleccionada
	 */
	function evt__cuadro2__seleccion($seleccion)
	{
		$this->dep('datos')->cargar($seleccion);	
	}

	//-----------------------------------------------------------------------------------
	//---- formulario_inscri ------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	/**
	 * Permite cambiar la configuraci�n del formulario previo a la generaci�n de la salida
	 * El formato del carga debe ser array(<campo> => <valor>, ...)
	 */
	function conf__formulario_inscri(gestion_escuela_ei_formulario $form)
	{
		if ($this->dep('datos')->esta_cargada()) {
			return $this->dep('datos')->get();    
		}			
	}

	/**
	 * Atrapa la interacci�n del usuario con el bot�n asociado
	 * @param array $datos Estado del componente al momento de ejecutar el evento. El formato es el mismo que en la carga de la configuraci�n
	 */
	protected function formatear_fecha_bd($fecha_pantalla)
    {
        // Si no mandaron nada, no hacemos nada
        if (empty($fecha_pantalla)) {
            return $fecha_pantalla;
        }

        // Intentamos crear el objeto desde el formato argentino
        $fecha_objeto = DateTime::createFromFormat('d/m/Y', $fecha_pantalla);
        
        if ($fecha_objeto !== false) {
            // Si tuvo éxito, la devolvemos en formato base de datos
            return $fecha_objeto->format('Y-m-d');
        }

        // Si falló (por ejemplo, si ya venía como Y-m-d por algún motivo), 
        // devolvemos el original para que la base de datos decida qué hacer.
        return $fecha_pantalla;
    }
	function get_fechamesa($id){
		$sql = "select fecha from mesas_examen where id=$id";
		
		return toba::db()->consultar($sql);
	}
	function evt__formulario_inscri__alta($datos)
	{
		try{
			$datos['id_alumno']= $this->s__datos[0]['id'];
			$idfecha=$datos['fecha_inscripcion'];
			$fechas=$this->get_fechamesa($idfecha);
			
			$datos['fecha_inscripcion']=$fechas[0]['fecha'];
			//Formateo de fecha porque postgres me da error de formato, valor fuera de rango
			//$datos['fecha_inscripcion'] = $this->formatear_fecha_bd($datos['fecha_inscripcion']);
			$this->dep('datos')->set($datos);
			$this->dep('datos')->sincronizar();
			$this->dep('datos')->resetear();
		}catch (toba_error_db $e){
			
			if($e->get_sqlstate()=="db_23505"){
				toba::notificacion()->agregar('ATENCION!! El registro ya Existe.');
			}
		}		
	}

	/**
	 * Atrapa la interacci�n del usuario con el bot�n asociado
	 */
	function evt__formulario_inscri__baja()
	{
		try{
			$this->dep('datos')->eliminar_todo();
			$this->dep('datos')->sincronizar();
		}catch (toba_error_db $e){
			if ($e->get_sqlstate() =="db_23503") {
				toba::notificacion()->agregar('ATENCION!! El registro no puede eliminarse por que esta En Uso.');
			}
		}		
	}

	/**
	 * Atrapa la interacci�n del usuario con el bot�n asociado
	 * @param array $datos Estado del componente al momento de ejecutar el evento. El formato es el mismo que en la carga de la configuraci�n
	 */
	function evt__formulario_inscri__modificacion($datos)
	{
		try{
			//$datos['fecha_inscripcion'] = $this->formatear_fecha_bd($datos['fecha_inscripcion']);
			$idfecha=$datos['fecha_inscripcion'];
			$fechas=$this->get_fechamesa($idfecha);
			
			$datos['fecha_inscripcion']=$fechas[0]['fecha'];
			$this->dep('datos')->set($datos);
			$this->dep('datos')->sincronizar();
			$this->dep('datos')->resetear();
		}catch (toba_error_db $e){
			if($e->get_sqlstate()=="db_23505"){
				toba::notificacion()->agregar('ATENCION!! El registro ya Existe.');
			}
		}		
	}

	/**
	 * Atrapa la interacci�n del usuario con el bot�n asociado
	 */
	function evt__formulario_inscri__cancelar()
	{
		$this->dep('datos')->resetear();		
	}

	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__volver()
	{
			$this->dep('datos')->resetear();
            unset($this->s__datos);
            $this->set_pantalla('pant_inicial');  
	}

	function get_resumen_envio_mail($id_alumno)
{
    $id_alumno = (int) $id_alumno;

    $sql = "
        SELECT 
            COUNT(*) AS cantidad,
            MAX(enviado_en) AS ultimo_envio
        FROM marcador_envio_de_mail
        WHERE id_alumno = $id_alumno
          AND resultado = 'OK'
    ";

    return toba::db('gestion_escuela')->consultar($sql);
}


	function registrar_envio_mail($id_alumno, $email, $cantidad_inscripciones)
	{
		$db = toba::db('gestion_escuela');

		$id_alumno = (int) $id_alumno;
		$cantidad_inscripciones = (int) $cantidad_inscripciones;

		$email = $db->quote($email);
		$asunto = $db->quote('Estado de tus Inscripciones a las Mesas de Examen');

		$sql = "
			INSERT INTO marcador_envio_de_mail
				(id_alumno, email_destino, asunto, cantidad_inscripciones, resultado)
			VALUES
				($id_alumno, $email, $asunto, $cantidad_inscripciones, 'OK')
		";

		$db->ejecutar($sql);
	}

	function cambiar_texto_boton_mail($texto)
	{
		try {
			$evento = $this->evento('mail');

			if (method_exists($evento, 'set_etiqueta')) {
				$evento->set_etiqueta($texto);
			}
		} catch (Exception $e) {

		}
	}

	/**
	 * Atrapa la interacci�n del usuario a trav�s del bot�n asociado. El m�todo no recibe par�metros
	 */
	function evt__mail()
	{
		$id_alumno = $this->s__datos[0]['id'];
		$email = trim($this->s__datos[0]['email']);

		$datos = toba::consulta_php('gestion_escuela')->get_inscripcionesporalumno($id_alumno);

		if ($email === '') {
			toba::notificacion()->agregar('El alumno no tiene email cargado.', 'error');
			return;
		}

		if (!count($datos)) {
			toba::notificacion()->agregar('El alumno no tiene inscripciones para informar.', 'error');
			return;
		}

		$resumen = $this->get_resumen_envio_mail($id_alumno);
		$cantidad_envios = isset($resumen[0]['cantidad']) ? (int) $resumen[0]['cantidad'] : 0;
		$ultimo_envio = isset($resumen[0]['ultimo_envio']) ? $resumen[0]['ultimo_envio'] : null;

		if (!isset($this->s__confirmar_mail)) {
			$this->s__confirmar_mail = true;

			if ($cantidad_envios > 0) {
				$mensaje = "Ya se envio correo a $email. Envios registrados: $cantidad_envios.";

				if ($ultimo_envio !== null) {
					$mensaje .= " Ultimo envio: " . date("d/m/Y H:i", strtotime($ultimo_envio)) . ".";
				}

				$mensaje .= " Presione nuevamente Reenviar correo para confirmar.";
			} else {
				$mensaje = "Se enviara correo a $email. Presione nuevamente Enviar correo para confirmar.";
			}

			toba::notificacion()->agregar($mensaje, 'info');
			return;
		}

		unset($this->s__confirmar_mail);

		$enviado = $this->procesar_envio($this->s__datos, $datos);

		if ($enviado) {
			$this->registrar_envio_mail($id_alumno, $email, count($datos));
		}
	}

	//-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	/**
	 * Ventana de extensi�n para configurar la pantalla. Se ejecuta previo a la configuraci�n de los componentes pertenecientes a la pantalla 
	 * por lo que es ideal por ejemplo para ocultarlos en base a una condici�n din�mica, ej. $pant->eliminar_dep("tal") 
	 * @param toba_ei_pantalla $pantalla
	 */
	function conf__pant_edicion(toba_ei_pantalla $pantalla)
	{
		//Valida que tenga Pendientes
		$id=$this->s__datos[0]['id'];
		$datos = toba::consulta_php('gestion_escuela')->get_estadospendientes_inscripciones($id);
		$pendientes=$datos[0]['pendientes'];

		//Valido que tenga Inscripciones
		$datos2 = toba::consulta_php('gestion_escuela')->get_inscripcionesporalumno($id);

		if (!count($datos2) || $pendientes > 0 || trim($this->s__datos[0]['email']) === '') {
    	$this->evento('mail')->desactivar();
   		 return;
		}

		$resumen_mail = $this->get_resumen_envio_mail($id);
		$cantidad_envios = isset($resumen_mail[0]['cantidad']) ? (int) $resumen_mail[0]['cantidad'] : 0;

		if (isset($this->s__confirmar_mail)) {
			$this->cambiar_texto_boton_mail('Confirmar envio');
		} elseif ($cantidad_envios > 0) {
			$this->cambiar_texto_boton_mail('Reenviar correo (' . $cantidad_envios . ')');
		} else {
			$this->cambiar_texto_boton_mail('Enviar correo');
		}
	}


function procesar_envio($alumno, $mesas){
    $nombre = $alumno[0]['nombre'];
    $apellido = $alumno[0]['apellido'];
    $legajo = $alumno[0]['legajo'];
    $dni = $alumno[0]['dni'];
    $email = trim($alumno[0]['email']);
    $carrera = $alumno[0]['desccarrera'];

    if ($email === '') {
        toba::notificacion()->agregar('El alumno no tiene email cargado.', 'error');
        toba::logger()->error('No se pudo enviar correo: alumno sin email.');
        return false;
    }

    $asunto = "Estado de tus Inscripciones a las Mesas de Examen";

    $cuerpo = "
	<div style='color:#222222; font-size:15px; line-height:1.4;'>
		Hola: <b>$apellido, $nombre</b> - <i>(Legajo: $legajo - DNI: $dni)</i>
	</div><br>";

    $cuerpoa = "";
    $cuerpor = "";

    foreach ($mesas as $elem) {
        $desc_materia = $elem['desc_materia'];
        $fecha_inscripcion = date("d/m/Y", strtotime($elem['fecha_inscripcion']));
        $motivo = $elem['motivo'];

        if ($elem['id_estado'] == 2) {
            $cuerpoa .= "- $fecha_inscripcion - $desc_materia - $carrera<br>";
        } else {
            $cuerpor .= "- $fecha_inscripcion - $desc_materia - $carrera";

            if ($motivo !== null && $motivo !== '') {
                $cuerpor .= "<br>&nbsp;&nbsp;<b>Motivo:</b> $motivo";
            }

            $cuerpor .= "<br><br>";
        }
    }

	if ($cuerpoa) {
    $cuerpo .= "
    <div style='color:#2e7d32; font-weight:bold; font-size:15px; margin:12px 0 4px 0;'>
        Inscripciones Aprobadas
    </div>";
    $cuerpo .= "<div style='color:#222222; font-size:14px; line-height:1.4;'>" . $cuerpoa . "</div><br>";
}

if ($cuerpor) {
    $cuerpo .= "
    <div style='color:#c62828; font-weight:bold; font-size:15px; margin:12px 0 4px 0;'>
        Inscripciones Rechazadas
    </div>";
    $cuerpo .= "<div style='color:#222222; font-size:14px; line-height:1.4;'>" . $cuerpor . "</div><br>";
}

	$cuerpo .= "
	<hr style='border:0; border-top:1px solid #ddd; margin-top:20px;'>
	<div style='font-size:12px; color:#666; line-height:1.4;'>
		<b>Gestion Escuela</b><br>
		Mensaje generado automaticamente por el sistema de inscripciones.<br>
		No responda este correo si no corresponde al tramite academico informado.
	</div>";

    try {
        $mail = new toba_mail($email, $asunto, $cuerpo);
        $mail->set_configuracion_smtp('gestion_escuela_smtp');
        $mail->set_html(true);
        $mail->enviar();

		toba::notificacion()->agregar('Correo enviado correctamente.', 'info');
		toba::logger()->debug("Mail enviado a: $email");
		return true;

    } catch (Exception $e) {
        toba::logger()->error('Error de correo Toba: ' . $e->getMessage());
        toba::notificacion()->agregar('Error al enviar: ' . $e->getMessage(), 'error');
		return false;
    }
}


	  	// Llamada al WebService
		/*
		$client = new SoapClient("http://192.168.0.30/despacharmail/wsdm.asmx?wsdl", array('cache_wsdl' => WSDL_CACHE_NONE,'trace' => TRUE));

		if (!mb_detect_encoding($asunto, 'UTF-8', true)) {
    		$asunto = mb_convert_encoding($asunto, 'UTF-8', 'ISO-8859-1');
		}
		if (!mb_detect_encoding($cuerpo, 'UTF-8', true)) {
    		$cuerpo = mb_convert_encoding($cuerpo, 'UTF-8', 'ISO-8859-1');
		}


		$param = array('asmail' => $para,'asasunto' =>$asunto, 'astexto'=>$cuerpo);
        $ready = $client->insertarmail($param);

        $objeto= $ready->ExecuteFileTransactionSLResult;
        $xml = @new SimpleXMLElement($objeto);
		var_dump($xml);
		*/
		
	//}	

	function evt__formulario__guardar()
	{
		 try{
                $this->dep('datos')->sincronizar();
				$this->dep('datos')->resetear();
            }catch(toba_error_db $e){
                /* Error al grabar */
                if($e->get_sqlstate()=="db_23505"){
                    /* Clave Duplicada */
                    $mensaje ="";
                    toba::notificacion()->agregar($mensaje);
                }else {
                    $mensaje_usuario='ERROR al guardar. Los cambios NO fueron registrados.';
                    $mensaje='<br><br>Información Adicional: ';
                    $mensaje.='<br><strong>Error Nº </strong>'.$e->get_sqlstate();
                    $mensaje.='<br><br><strong> Mensaje: </strong>'.$e->get_mensaje_motor();
                    throw new toba_error($mensaje_usuario,$mensaje);
                    //toba::notificacion()->agregar($mensaje);
                }
            }
          
	}


	function ajax__validar_estado($estado, toba_ajax_respuesta $respuesta)
	{

		if($estado==3){
			$vuelta=false;
		}else{
			$vuelta=true;
		}

		$respuesta->set($vuelta);

	}
}
?>