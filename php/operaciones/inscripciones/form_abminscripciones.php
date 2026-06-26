<?php
class form_abminscripciones extends gestion_escuela_ei_formulario
{
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		//---- Procesamiento de EFs --------------------------------
		
		/**
		 * M�todo que se invoca al cambiar el valor del ef en el cliente
		 * Se dispara inicialmente al graficar la pantalla, enviando en true el primer par�metro
		 */
		{$this->objeto_js}.evt__id_estado__procesar = function(es_inicial)
		{
			//if (!es_inicial) {
				estado = this.ef('id_estado').get_estado();
				this.controlador.ajax('validar_estado', estado, this, this.actualizar_comentario);
			//}

		}
		

		{$this->objeto_js}.actualizar_comentario = function(respuesta)
		{
			var ef = {$this->objeto_js}.ef('motivo');
			//ef.set_estado('');
			if (respuesta) {
				ef.ocultar(true)
			} else {
				ef.mostrar();	
			};
			ef.set_solo_lectura(respuesta);
		}	
		
            {$this->objeto_js}.ini = function () {
                this.ef('motivo').input().onkeyup = function() {
                    var ef = {$this->objeto_js}.ef('motivo');
                    ef.set_estado(ef.get_estado().toUpperCase());
                }
                   
            }
         

		";
	}

}

?>