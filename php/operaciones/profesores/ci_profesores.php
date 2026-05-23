<?php
class ci_profesores extends gestion_escuela_ci
{
	//-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	/**
	 * Ventana de extensión para configurar la pantalla. Se ejecuta previo a la configuración de los componentes pertenecientes a la pantalla 
	 * por lo que es ideal por ejemplo para ocultarlos en base a una condición dinámica, ej. $pant->eliminar_dep("tal") 
	 * @param toba_ei_pantalla $pantalla
	 */
	function conf__pant_inicial(toba_ei_pantalla $pantalla)
	{
	}

	/**
	 * Ventana de extensión para configurar la pantalla. Se ejecuta previo a la configuración de los componentes pertenecientes a la pantalla 
	 * por lo que es ideal por ejemplo para ocultarlos en base a una condición dinámica, ej. $pant->eliminar_dep("tal") 
	 * @param toba_ei_pantalla $pantalla
	 */
	function conf__pant_edicion(toba_ei_pantalla $pantalla)
	{
	}

	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	/**
	 * Atrapa la interacción del usuario a través del botón asociado. El método no recibe parámetros
	 */
	function evt__agregar()
	{
	}

	/**
	 * Originalmente este método limpia las variables y definiciones del componente, y en caso de exisitr un CN asociado ejecuta su cancelar. Para mantener este comportamiento llamar a parent::evt__cancelar
	 */
	function evt__cancelar()
	{
	}

	/**
	 * Atrapa la interacción del usuario a través del botón asociado. El método no recibe parámetros
	 */
	function evt__guardar()
	{
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	/**
	 * Permite cambiar la configuración del cuadro previo a la generación de la salida
	 * El formato de carga es de tipo recordset: array( array('columna' => valor, ...), ...)
	 */
	function conf__cuadro(gestion_escuela_ei_cuadro $cuadro)
	{
	}

	/**
	 * Atrapa la interacción del usuario con el botón asociado
	 * @param array $seleccion Id. de la fila seleccionada
	 */
	function evt__cuadro__seleccion($seleccion)
	{
	}

	/**
	 * Atrapa la interacción del usuario con el botón asociado
	 * @param array $seleccion Id. de la fila seleccionada
	 */
	function evt__cuadro__eliminar($seleccion)
	{
	}

	//-----------------------------------------------------------------------------------
	//---- formulario -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	/**
	 * Permite cambiar la configuración del formulario previo a la generación de la salida
	 * El formato del carga debe ser array(<campo> => <valor>, ...)
	 */
	function conf__formulario(gestion_escuela_ei_formulario $form)
	{
	}

	/**
	 * Atrapa la interacción del usuario con el botón asociado
	 * @param array $datos Estado del componente al momento de ejecutar el evento. El formato es el mismo que en la carga de la configuración
	 */
	function evt__formulario__modificacion($datos)
	{
	}

	//-----------------------------------------------------------------------------------
	//---- filtro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	/**
	 * Permite cambiar la configuración del formulario previo a la generación de la salida
	 * El formato del carga debe ser array(<campo> => <valor>, ...)
	 */
	function conf__filtro(gestion_escuela_ei_filtro $filtro)
	{
	}

	/**
	 * Atrapa la interacción del usuario con el botón asociado
	 * @param array $datos Estado del componente al momento de ejecutar el evento. El formato es el mismo que en la carga de la configuración
	 */
	function evt__filtro__filtrar($datos)
	{
	}

	/**
	 * Atrapa la interacción del usuario con el botón asociado
	 */
	function evt__filtro__cancelar()
	{
	}

}
?>