-- Adminer 5.4.2 PostgreSQL 15.17 dump

CREATE DATABASE "gestion_escuela";
\connect "gestion_escuela";

DROP TABLE IF EXISTS "alumnos";
DROP SEQUENCE IF EXISTS "public".alumnos_id_seq;
CREATE SEQUENCE "public".alumnos_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 83 CACHE 1;

CREATE TABLE "public"."alumnos" (
    "id" integer DEFAULT nextval('alumnos_id_seq') NOT NULL,
    "legajo" integer NOT NULL,
    "nombre" character varying(50) NOT NULL,
    "apellido" character varying(50) NOT NULL,
    "dni" character varying(20) NOT NULL,
    "email" character varying(100),
    "id_carrera" integer,
    CONSTRAINT "alumnos_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

CREATE UNIQUE INDEX alumnos_legajo_key ON public.alumnos USING btree (legajo);

CREATE UNIQUE INDEX alumnos_dni_key ON public.alumnos USING btree (dni);

INSERT INTO "alumnos" ("id", "legajo", "nombre", "apellido", "dni", "email", "id_carrera") VALUES
(63,	4164,	'VERONICA',	'ZALACAIN',	'21508110',	'vzalacain@gmail.com',	6),
(66,	4503,	'LUCAS',	'PÉREZ',	'43556987',	'lucas.perez@alumno.edu.ar',	3),
(67,	4504,	'MARÍA',	'RODRÍGUEZ',	'40112365',	'maria.rod@alumno.edu.ar',	3),
(68,	4505,	'JUAN',	'LÓPEZ',	'39852147',	'jlopez@alumno.edu.ar',	4),
(69,	4506,	'SOFÍA',	'MARTÍNEZ',	'42336589',	'smartinez@alumno.edu.ar',	5),
(70,	4507,	'TOMÁS',	'GARCÍA',	'41589745',	'tgarcia@alumno.edu.ar',	7),
(71,	4508,	'VALENTINA',	'FERNÁNDEZ',	'44125896',	'vfernandez@alumno.edu.ar',	5),
(73,	4510,	'MARTINA',	'DÍAZ',	'39556874',	'mdiaz@alumno.edu.ar',	3),
(74,	4511,	'BAUTISTA',	'ALONSO',	'43225698',	'balonso@alumno.edu.ar',	4),
(76,	4513,	'NICOLÁS',	'ÁLVAREZ',	'41885698',	'nalvarez@alumno.edu.ar',	4),
(77,	4514,	'JULIETA',	'TORRES',	'40336589',	'jtorres@alumno.edu.ar',	5),
(79,	4516,	'EMILIA',	'SOSA',	'44558965',	'esosa@alumno.edu.ar',	3),
(80,	4517,	'FEDERICO',	'CASTILLO',	'41225896',	'fcastillo@alumno.edu.ar',	7),
(81,	4518,	'VICTORIA',	'GIMÉNEZ',	'43112589',	'vgimenez@alumno.edu.ar',	4),
(62,	3263,	'SILVIO',	'SOLARI',	'22044187',	'solariunlu@gmail.com',	NULL),
(64,	4501,	'RODRIGO',	'MÉNDEZ',	'42105698',	'rodrigo.mendez@alumno.edu.ar',	NULL),
(65,	4502,	'ANA',	'GÓMEZ',	'41225364',	'ana.gomez@alumno.edu.ar',	NULL),
(72,	4509,	'MATEO',	'SILVA',	'40558712',	'msilva@alumno.edu.ar',	NULL),
(75,	4512,	'CAMILA',	'ROMERO',	'42558963',	'cromero@alumno.edu.ar',	NULL),
(78,	4515,	'JOAQUÍN',	'RUIZ',	'39114587',	'jruiz@alumno.edu.ar',	NULL),
(82,	4519,	'SANTIAGO',	'IGLESIAS',	'40889654',	'siglesias@alumno.edu.ar',	NULL);

DROP TABLE IF EXISTS "aulas";
DROP SEQUENCE IF EXISTS "public".aula_id_seq;
CREATE SEQUENCE "public".aula_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 4 CACHE 1;

CREATE TABLE "public"."aulas" (
    "id" integer DEFAULT nextval('aula_id_seq') NOT NULL,
    "descripcion" character varying(100) NOT NULL,
    CONSTRAINT "aulas_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

INSERT INTO "aulas" ("id", "descripcion") VALUES
(1,	'LAB REDES'),
(2,	'AULA 301'),
(3,	'AULA 102');

DROP TABLE IF EXISTS "carrera";
DROP SEQUENCE IF EXISTS "public".carrera_id_seq;
CREATE SEQUENCE "public".carrera_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 8 CACHE 1;

CREATE TABLE "public"."carrera" (
    "id" integer DEFAULT nextval('carrera_id_seq') NOT NULL,
    "descripcion" character varying(100) NOT NULL,
    CONSTRAINT "carrera_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

CREATE UNIQUE INDEX carrera_descripcion_key ON public.carrera USING btree (descripcion);

INSERT INTO "carrera" ("id", "descripcion") VALUES
(3,	'TECNICATURA SUPERIOR EN ANÁLISIS DE SISTEMAS'),
(4,	'TECNICATURA SUPERIOR EN DESARROLLO DE SOFTWARE'),
(5,	'TECNICATURA SUPERIOR EN REDES E INFRAESTRUCTURA'),
(6,	'PROFESORADO EN INFORMÁTICA'),
(7,	'CERTIFICACIÓN SUPERIOR EN PROGRAMACIÓN WEB');

DROP TABLE IF EXISTS "estados_inscripcion";
DROP SEQUENCE IF EXISTS "public".estados_inscripcion_id_seq;
CREATE SEQUENCE "public".estados_inscripcion_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 6 CACHE 1;

CREATE TABLE "public"."estados_inscripcion" (
    "id" integer DEFAULT nextval('estados_inscripcion_id_seq') NOT NULL,
    "descripcion" character varying(50) NOT NULL,
    CONSTRAINT "estados_inscripcion_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

CREATE UNIQUE INDEX estados_inscripcion_descripcion_key ON public.estados_inscripcion USING btree (descripcion);

INSERT INTO "estados_inscripcion" ("id", "descripcion") VALUES
(1,	'PENDIENTE'),
(2,	'APROBADA'),
(3,	'RECHAZADA');

DROP TABLE IF EXISTS "inscripciones";
DROP SEQUENCE IF EXISTS "public".inscripciones_id_inscripcion_seq;
CREATE SEQUENCE "public".inscripciones_id_inscripcion_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 8 CACHE 1;

CREATE TABLE "public"."inscripciones" (
    "id_inscripcion" integer DEFAULT nextval('inscripciones_id_inscripcion_seq') NOT NULL,
    "id_alumno" integer,
    "id_materia" integer,
    "fecha_inscripcion" date DEFAULT CURRENT_DATE,
    "id_estado" integer DEFAULT '1',
    CONSTRAINT "inscripciones_pkey" PRIMARY KEY ("id_inscripcion")
)
WITH (oids = false);

INSERT INTO "inscripciones" ("id_inscripcion", "id_alumno", "id_materia", "fecha_inscripcion", "id_estado") VALUES
(2,	71,	32,	'2026-06-30',	3),
(3,	73,	6,	'2026-06-03',	1),
(4,	71,	27,	'2026-06-09',	1),
(5,	71,	27,	'2026-06-30',	3),
(6,	71,	27,	'2026-07-05',	1),
(7,	77,	32,	'2026-06-27',	1);

DROP TABLE IF EXISTS "materias";
DROP SEQUENCE IF EXISTS "public".materias_id_seq;
CREATE SEQUENCE "public".materias_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 33 CACHE 1;

CREATE TABLE "public"."materias" (
    "id" integer DEFAULT nextval('materias_id_seq') NOT NULL,
    "codigo" character varying(10) NOT NULL,
    "nombre" character varying(100) NOT NULL,
    "anio" integer,
    "id_carrera" integer,
    "id_profesor" integer,
    "fecha_mesa" date,
    CONSTRAINT "materias_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

CREATE UNIQUE INDEX materias_codigo_key ON public.materias USING btree (codigo);

INSERT INTO "materias" ("id", "codigo", "nombre", "anio", "id_carrera", "id_profesor", "fecha_mesa") VALUES
(28,	'BD1',	'BASES DE DATOS I',	1,	4,	2,	NULL),
(29,	'SO1',	'SISTEMAS OPERATIVOS',	2,	3,	3,	NULL),
(27,	'PROG1',	'PROGRAMACIÓN I',	1,	5,	6,	'2026-06-03'),
(30,	'ANASIS',	'ANÁLISIS DE SISTEMAS',	2,	3,	4,	NULL),
(31,	'LAB2',	'LABORATORIO DE SOFTWARE II',	3,	3,	6,	NULL),
(6,	'11034',	'PROGRAMACION 1',	1,	3,	2,	'2026-06-03'),
(32,	'ING001',	'INGLES TECNICO',	2,	5,	6,	'2026-06-30');

DROP TABLE IF EXISTS "mesas_examen";
DROP SEQUENCE IF EXISTS "public".mesas_examen_id_seq;
CREATE SEQUENCE "public".mesas_examen_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 13 CACHE 1;

CREATE TABLE "public"."mesas_examen" (
    "id" integer DEFAULT nextval('mesas_examen_id_seq') NOT NULL,
    "id_materia" integer,
    "fecha" date NOT NULL,
    "id_aula" integer,
    CONSTRAINT "mesas_examen_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

INSERT INTO "mesas_examen" ("id", "id_materia", "fecha", "id_aula") VALUES
(7,	27,	'2026-07-05',	1),
(9,	27,	'2026-06-30',	1),
(10,	27,	'2026-07-08',	1),
(11,	32,	'2026-06-27',	3),
(12,	32,	'2026-09-06',	2);

DROP TABLE IF EXISTS "profesores";
DROP SEQUENCE IF EXISTS "public".profesores_id_seq;
CREATE SEQUENCE "public".profesores_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 7 CACHE 1;

CREATE TABLE "public"."profesores" (
    "id" integer DEFAULT nextval('profesores_id_seq') NOT NULL,
    "nombre" character varying(50) NOT NULL,
    "apellido" character varying(50) NOT NULL,
    "email" character varying(100),
    CONSTRAINT "profesores_pkey" PRIMARY KEY ("id")
)
WITH (oids = false);

INSERT INTO "profesores" ("id", "nombre", "apellido", "email") VALUES
(2,	'CARLOS',	'VIGNOLO',	'cvignolo@escuela.edu.ar'),
(3,	'PATRICIA',	'ROSSI',	'prossi@escuela.edu.ar'),
(4,	'JORGE',	'FERNÁNDEZ',	'jfernandez@escuela.edu.ar'),
(5,	'MARTA',	'GÓMEZ',	'mgomez@escuela.edu.ar'),
(6,	'SILVIA',	'MARTINI',	'smartini@escuela.edu.ar');

ALTER TABLE ONLY "public"."alumnos" ADD CONSTRAINT "alumnos_id_carrera_fkey" FOREIGN KEY (id_carrera) REFERENCES carrera(id) ON UPDATE CASCADE ON DELETE SET NULL;

ALTER TABLE ONLY "public"."inscripciones" ADD CONSTRAINT "inscripciones_id_alumno_fkey" FOREIGN KEY (id_alumno) REFERENCES alumnos(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE ONLY "public"."inscripciones" ADD CONSTRAINT "inscripciones_id_estado_fkey" FOREIGN KEY (id_estado) REFERENCES estados_inscripcion(id) ON UPDATE CASCADE ON DELETE SET NULL;
ALTER TABLE ONLY "public"."inscripciones" ADD CONSTRAINT "inscripciones_id_materia_fkey" FOREIGN KEY (id_materia) REFERENCES materias(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY "public"."materias" ADD CONSTRAINT "materias_id_carrera_fkey" FOREIGN KEY (id_carrera) REFERENCES carrera(id) ON UPDATE CASCADE ON DELETE SET NULL;
ALTER TABLE ONLY "public"."materias" ADD CONSTRAINT "materias_id_profesor_fkey" FOREIGN KEY (id_profesor) REFERENCES profesores(id) ON UPDATE CASCADE ON DELETE SET NULL;

ALTER TABLE ONLY "public"."mesas_examen" ADD CONSTRAINT "mesas_examen_id_aulas_fkey" FOREIGN KEY (id_aula) REFERENCES aulas(id) ON UPDATE CASCADE ON DELETE SET NULL;

-- 2026-06-09 23:34:34 UTC
