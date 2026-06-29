ALTER TABLE public.inscripciones
ADD COLUMN IF NOT EXISTS motivo text;

CREATE TABLE IF NOT EXISTS public.marcador_envio_de_mail (
    id_marcador_envio_de_mail serial PRIMARY KEY,
    id_alumno integer NOT NULL REFERENCES public.alumnos(id) ON UPDATE CASCADE ON DELETE CASCADE,
    email_destino text NOT NULL,
    asunto text NOT NULL,
    cantidad_inscripciones integer NOT NULL DEFAULT 0,
    resultado varchar(20) NOT NULL DEFAULT 'OK',
    detalle text,
    enviado_en timestamp without time zone NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_marcador_envio_de_mail_alumno
ON public.marcador_envio_de_mail(id_alumno);

CREATE INDEX IF NOT EXISTS idx_marcador_envio_de_mail_enviado_en
ON public.marcador_envio_de_mail(enviado_en);
