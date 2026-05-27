<?php
class form_mesasexamene extends gestion_escuela_ei_formulario
{
        function extender_objeto_js()
        {
            echo "
            {$this->objeto_js}.ini = function () {
                this.ef('hora').input().onkeyup = function() {
                    var ef = {$this->objeto_js}.ef('hora');
                    ef.set_estado(ef.get_estado().toUpperCase());
                }

            }
            ";
        } 
}

?>