<?php
class form_materias extends gestion_escuela_ei_formulario
{
    function extender_objeto_js()
        {
            echo "
            {$this->objeto_js}.ini = function () {
                this.ef('nombre').input().onkeyup = function() {
                    var ef = {$this->objeto_js}.ef('nombre');
                    ef.set_estado(ef.get_estado().toUpperCase());
                }

                this.ef('codigo').input().onkeyup = function() {
                    var ef = {$this->objeto_js}.ef('codigo');
                    ef.set_estado(ef.get_estado().toUpperCase());
                }
              
                 
            }
            ";
        } 
}

?>