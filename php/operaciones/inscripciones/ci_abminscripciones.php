<?php
class ci_abminscripciones extends gestion_escuela_ci
{
	protected $s__filtro;
	protected $s__datos;
	protected $s__alumno;


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

	/**
	 * Atrapa la interacci�n del usuario a trav�s del bot�n asociado. El m�todo no recibe par�metros
	 */
	function evt__mail()
	{
		$mesas=$this->s__datos[0]['id'];
		$datos = toba::consulta_php('gestion_escuela')->get_inscripcionesporalumno($mesas);
		$this->procesar_envio($this->s__datos,$datos);
		//ei_arbol($datos);
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

		if(!count($datos2) or $pendientes>0){
			$this->evento('mail')->desactivar();
		}
	}


	function procesar_envio($alumno,$mesas)
	{
		
		require_once '/var/www/html/vendor/autoload.php';

		$nombre=$alumno[0]['nombre'];
		$apellido=$alumno[0]['apellido'];
		$legajo=$alumno[0]['legajo'];
		$dni=$alumno[0]['dni'];
		$email=$alumno[0]['email'];
		$carrera=$alumno[0]['desccarrera'];

		$asunto= "Estado de tus Inscripciones a las Mesas de Examen";
		$para=$email;		
		$cuerpo = "	Hola: <b>$apellido, $nombre</b> - <i>($legajo - $dni)</i>
		            <br><br>
					";

		$cuerpoa="";	
		$cuerpor="";		
		foreach ($mesas as $indice=>$elem) {

		    $desc_materia=$elem['desc_materia'];
			$fecha_inscripcion=date("d/m/Y",strtotime($elem['fecha_inscripcion']));
			$motivo = $elem['motivo'];

			if($elem['id_estado']==2){  // Materias aprobadas
				$cuerpoa.= "$fecha_inscripcion - $desc_materia - $carrera";
			}else{
				$cuerpor.="$fecha_inscripcion - $desc_materia - $carrera ";
				if ($motivo !== null && $motivo !== '') {
            		$cuerpor .= "<br>";
					$cuerpor .= " - MOTIVO: $motivo";
				}
				$cuerpor .= "<br>";
			}

		}

		if($cuerpoa){
			$cuerpo .= "<b>Inscripciones Aprobadas</b><br>".$cuerpoa."<br><br>";
		}

		if($cuerpor){
			$cuerpo .= "<b>Inscripciones Rechazadas</b><br>".$cuerpor."<br><br>";
		}

		$cuerpo .= "Saludos Cordiales...";
		try {
			//el usuario y key de resend
			$resend = Resend::client(
				're_iJ9yGeNR_8MyNipvAMcxmn7gTu5ZKGMFL'
			);
			//dominio del mail(es generico)
			$resultado = $resend->emails->send([
				'from' => 'INSTITUTO 189<onboarding@resend.dev>',
				'to'      => [$email],
				'subject' => $asunto,
				'html'    => $cuerpo,
			]);

			toba::notificacion()->agregar(
				'Correo enviado correctamente.'
			);

			toba::logger()->debug(
				'Mail enviado. ID: ' .
				(isset($resultado->id) ? $resultado->id : '')
			);

		} catch (Exception $e) {

			toba::logger()->error(
				'Error Resend: ' . $e->getMessage()
			);

			toba::notificacion()->agregar(
				'Error al enviar correo: ' .
				$e->getMessage()
			);
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
		
	}	

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