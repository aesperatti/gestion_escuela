<?php
class form_carrera extends gestion_escuela_ei_formulario
{
        function extender_objeto_js()
        {
            echo "
            {$this->objeto_js}.ini = function () {
                this.ef('descripcion').input().onkeyup = function() {
                    var ef = {$this->objeto_js}.ef('descripcion');
                    ef.set_estado(ef.get_estado().toUpperCase());
                }  
            }
            ";
        } 
}

?>