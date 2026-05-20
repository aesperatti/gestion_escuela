<?php
/**
 * Esta clase fue y será generada automáticamente. NO EDITAR A MANO.
 * @ignore
 */
class gestion_escuela_autoload 
{
	static function existe_clase($nombre)
	{
		return isset(self::$clases[$nombre]);
	}

	static function cargar($nombre)
	{
		if (self::existe_clase($nombre)) { 
			 require_once(dirname(__FILE__) .'/'. self::$clases[$nombre]); 
		}
	}

	static protected $clases = array(
		'gestion_escuela_ci' => 'extension_toba/componentes/gestion_escuela_ci.php',
		'gestion_escuela_cn' => 'extension_toba/componentes/gestion_escuela_cn.php',
		'gestion_escuela_datos_relacion' => 'extension_toba/componentes/gestion_escuela_datos_relacion.php',
		'gestion_escuela_datos_tabla' => 'extension_toba/componentes/gestion_escuela_datos_tabla.php',
		'gestion_escuela_ei_arbol' => 'extension_toba/componentes/gestion_escuela_ei_arbol.php',
		'gestion_escuela_ei_archivos' => 'extension_toba/componentes/gestion_escuela_ei_archivos.php',
		'gestion_escuela_ei_calendario' => 'extension_toba/componentes/gestion_escuela_ei_calendario.php',
		'gestion_escuela_ei_codigo' => 'extension_toba/componentes/gestion_escuela_ei_codigo.php',
		'gestion_escuela_ei_cuadro' => 'extension_toba/componentes/gestion_escuela_ei_cuadro.php',
		'gestion_escuela_ei_esquema' => 'extension_toba/componentes/gestion_escuela_ei_esquema.php',
		'gestion_escuela_ei_filtro' => 'extension_toba/componentes/gestion_escuela_ei_filtro.php',
		'gestion_escuela_ei_firma' => 'extension_toba/componentes/gestion_escuela_ei_firma.php',
		'gestion_escuela_ei_formulario' => 'extension_toba/componentes/gestion_escuela_ei_formulario.php',
		'gestion_escuela_ei_formulario_ml' => 'extension_toba/componentes/gestion_escuela_ei_formulario_ml.php',
		'gestion_escuela_ei_grafico' => 'extension_toba/componentes/gestion_escuela_ei_grafico.php',
		'gestion_escuela_ei_mapa' => 'extension_toba/componentes/gestion_escuela_ei_mapa.php',
		'gestion_escuela_servicio_web' => 'extension_toba/componentes/gestion_escuela_servicio_web.php',
		'gestion_escuela_comando' => 'extension_toba/gestion_escuela_comando.php',
		'gestion_escuela_modelo' => 'extension_toba/gestion_escuela_modelo.php',
	);
}
?>