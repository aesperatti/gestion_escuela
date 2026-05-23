<?php
class form_alumnos extends gestion_escuela_ei_formulario
{
    function extender_objeto_js()
        {
            echo "
            {$this->objeto_js}.ini = function () {
                this.ef('nombre').input().onkeyup = function() {
                    var ef = {$this->objeto_js}.ef('nombre');
                    ef.set_estado(ef.get_estado().toUpperCase());
                }

                this.ef('apellido').input().onkeyup = function() {
                    var ef = {$this->objeto_js}.ef('apellido');
                    ef.set_estado(ef.get_estado().toUpperCase());
                }
                this.ef('dni').input().onkeyup = function() {
                    var ef = {$this->objeto_js}.ef('dni');
                    ef.set_estado(ef.get_estado().toUpperCase());
                }
                this.ef('carrera').input().onkeyup = function() {
                    var ef = {$this->objeto_js}.ef('carrera');
                    ef.set_estado(ef.get_estado().toUpperCase());
                }
                    
            }
            ";
        } 

}

?>