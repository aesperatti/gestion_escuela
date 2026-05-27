------------------------------------------------------------
--[4000051]--  DT - materias 
------------------------------------------------------------

------------------------------------------------------------
-- apex_objeto
------------------------------------------------------------

--- INICIO Grupo de desarrollo 4
INSERT INTO apex_objeto (proyecto, objeto, anterior, identificador, reflexivo, clase_proyecto, clase, punto_montaje, subclase, subclase_archivo, objeto_categoria_proyecto, objeto_categoria, nombre, titulo, colapsable, descripcion, fuente_datos_proyecto, fuente_datos, solicitud_registrar, solicitud_obj_obs_tipo, solicitud_obj_observacion, parametro_a, parametro_b, parametro_c, parametro_d, parametro_e, parametro_f, usuario, creacion, posicion_botonera) VALUES (
	'gestion_escuela', --proyecto
	'4000051', --objeto
	NULL, --anterior
	NULL, --identificador
	NULL, --reflexivo
	'toba', --clase_proyecto
	'toba_datos_tabla', --clase
	'4000002', --punto_montaje
	NULL, --subclase
	NULL, --subclase_archivo
	NULL, --objeto_categoria_proyecto
	NULL, --objeto_categoria
	'DT - materias', --nombre
	NULL, --titulo
	NULL, --colapsable
	NULL, --descripcion
	'gestion_escuela', --fuente_datos_proyecto
	'gestion_escuela', --fuente_datos
	NULL, --solicitud_registrar
	NULL, --solicitud_obj_obs_tipo
	NULL, --solicitud_obj_observacion
	NULL, --parametro_a
	NULL, --parametro_b
	NULL, --parametro_c
	NULL, --parametro_d
	NULL, --parametro_e
	NULL, --parametro_f
	NULL, --usuario
	'2026-05-22 17:13:13', --creacion
	NULL  --posicion_botonera
);
--- FIN Grupo de desarrollo 4

------------------------------------------------------------
-- apex_objeto_db_registros
------------------------------------------------------------
INSERT INTO apex_objeto_db_registros (objeto_proyecto, objeto, max_registros, min_registros, punto_montaje, ap, ap_clase, ap_archivo, tabla, tabla_ext, alias, modificar_claves, fuente_datos_proyecto, fuente_datos, permite_actualizacion_automatica, esquema, esquema_ext) VALUES (
	'gestion_escuela', --objeto_proyecto
	'4000051', --objeto
	NULL, --max_registros
	NULL, --min_registros
	'4000002', --punto_montaje
	'1', --ap
	NULL, --ap_clase
	NULL, --ap_archivo
	'materias', --tabla
	NULL, --tabla_ext
	NULL, --alias
	'0', --modificar_claves
	'gestion_escuela', --fuente_datos_proyecto
	'gestion_escuela', --fuente_datos
	'1', --permite_actualizacion_automatica
	'public', --esquema
	'public'  --esquema_ext
);

------------------------------------------------------------
-- apex_objeto_db_registros_col
------------------------------------------------------------

--- INICIO Grupo de desarrollo 4
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'gestion_escuela', --objeto_proyecto
	'4000051', --objeto
	'4000018', --col_id
	'id', --columna
	'E', --tipo
	'1', --pk
	'materias_id_seq', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'1', --no_nulo_db
	'0', --externa
	'materias'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'gestion_escuela', --objeto_proyecto
	'4000051', --objeto
	'4000019', --col_id
	'codigo', --columna
	'C', --tipo
	'0', --pk
	NULL, --secuencia
	'10', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'materias'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'gestion_escuela', --objeto_proyecto
	'4000051', --objeto
	'4000020', --col_id
	'nombre', --columna
	'C', --tipo
	'0', --pk
	NULL, --secuencia
	'100', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'materias'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'gestion_escuela', --objeto_proyecto
	'4000051', --objeto
	'4000021', --col_id
	'anio', --columna
	'E', --tipo
	'0', --pk
	NULL, --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'materias'  --tabla
);
--- FIN Grupo de desarrollo 4

--- INICIO Grupo de desarrollo 91
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'gestion_escuela', --objeto_proyecto
	'4000051', --objeto
	'91000048', --col_id
	'id_carrera', --columna
	'E', --tipo
	'0', --pk
	NULL, --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'materias'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'gestion_escuela', --objeto_proyecto
	'4000051', --objeto
	'91000049', --col_id
	'id_profesor', --columna
	'E', --tipo
	'0', --pk
	NULL, --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	'0', --externa
	'materias'  --tabla
);
--- FIN Grupo de desarrollo 91
