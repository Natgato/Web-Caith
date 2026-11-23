<?php

return [
    'portada' => [
        'hero' => [
            'label' => 'Hero',
            'fields' => [
                'title' => ['Titular (una línea por renglón)', 'Un lugar
para
crecer.'],
                'lead' => ['Descripción', 'En Cusco, acompañamos a niñas, adolescentes y jóvenes con acogida, educación y herramientas para una vida autónoma.'],
                'link' => ['Enlace a la historia', 'Conoce nuestra historia'],
                'since' => ['Etiqueta de fecha', 'Desde'],
                'year' => ['Fecha destacada', '1994'],
                'alt' => ['Texto alternativo de la ilustración', 'Ilustración conceptual de jóvenes y una educadora compartiendo libros y cuadernos alrededor de una mesa'],
                'scene' => ['Palabras sobre la ilustración (una por línea)', 'Aprender.
Compartir.
Decidir.'],
                'image' => ['Ilustración principal', null],
            ],
        ],
        'intro' => [
            'label' => 'Presentación',
            'fields' => [
                'folio' => ['Encabezado', '01 — Presentación'],
                'place' => ['Lugar', 'Cusco, Perú'],
                'eyebrow' => ['Entrada', 'Conoce a'],
                'name' => ['Nombre, primera línea', 'Casa'],
                'emblem' => ['Nombre, segunda línea', 'CAITH'],
                'values' => ['Valores (uno por línea)', 'Acogida
Autonomía
Comunidad'],
                'title' => ['Titular', 'Una casa para'],
                'accent' => ['Énfasis del titular', 'Seguir creciendo.'],
                'lead' => ['Entrada de la historia', 'En Cusco, Casa CAITH ofrece acogida y acompañamiento a niñas, adolescentes y jóvenes.'],
                'body' => ['Primer párrafo', 'Como programa del Centro Yanapanakusun, la casa acompaña a las jóvenes en su día a día: continuar sus estudios, fortalecer su confianza y tomar decisiones sobre su futuro.'],
                'year' => ['Año', '1994'],
                'year_note' => ['Nota del año', 'El año en que empezó la historia de Casa CAITH.'],
                'closing' => ['Segundo párrafo', 'La educación, el cuidado y los derechos se encuentran en esa historia. Volver a sus comienzos ayuda a entender cómo nació la casa y qué significa acompañar a una joven mientras construye su propio camino.'],
                'link' => ['Enlace a la historia', 'Conoce nuestra historia'],
                'note' => ['Pie del texto', 'Síntesis editorial institucional'],
                'mission' => ['Enlace institucional', 'Misión y visión'],
                'image' => ['Ilustración de la presentación', null],
            ],
        ],
        'directory' => [
            'label' => 'Directorio',
            'fields' => [
                'title' => ['Titular', 'Explora Casa CAITH'],
                'nosotros_title' => ['Nosotros · título', 'Nosotros'],
                'nosotros_lead' => ['Nosotros · descripción', 'Conoce cómo nació Casa CAITH y su relación con el Centro Yanapanakusun.'],
                'nosotros_image' => ['Nosotros · ilustración', null],
                'nuestra-labor_title' => ['Nuestra labor · título', 'Nuestra labor'],
                'nuestra-labor_lead' => ['Nuestra labor · descripción', 'Descubre los programas de acogida y acompañamiento educativo para niñas, adolescentes y jóvenes.'],
                'nuestra-labor_image' => ['Nuestra labor · ilustración', null],
                'realidad-social_title' => ['Realidad social · título', 'Realidad social'],
                'realidad-social_lead' => ['Realidad social · descripción', 'Explora el contexto del trabajo del hogar, los derechos laborales y la prevención de la violencia.'],
                'realidad-social_image' => ['Realidad social · ilustración', null],
                'stories_title' => ['Historias compartidas · título', 'Historias compartidas'],
                'stories_lead' => ['Historias compartidas · descripción', 'Lee testimonios que sus protagonistas han autorizado a compartir.'],
                'stories_image' => ['Historias compartidas · ilustración', null],
                'news_title' => ['Noticias y publicaciones · título', 'Noticias y publicaciones'],
                'news_lead' => ['Noticias y publicaciones · descripción', 'Consulta novedades, actividades y recursos de Casa CAITH.'],
                'news_image' => ['Noticias y publicaciones · ilustración', null],
                'gallery_title' => ['Galería · título', 'Galería'],
                'gallery_lead' => ['Galería · descripción', 'Explora fotografías y videos de la casa cuando haya material autorizado para compartir.'],
                'gallery_image' => ['Galería · ilustración', null],
                'note' => ['Crédito', 'Ilustraciones conceptuales para Casa CAITH.'],
            ],
        ],
        'timeline' => [
            'label' => 'Línea de tiempo',
            'fields' => [
                'title' => ['Titular', 'Una historia'],
                'accent' => ['Énfasis del titular', 'que sigue.'],
                'link' => ['Enlace a la cronología', 'Ver la línea de tiempo completa'],
                'empty' => ['Mensaje sin hitos', 'Los hitos se incorporarán según su revisión institucional.'],
            ],
        ],
        'news' => [
            'label' => 'Actualidad',
            'fields' => [
                'title' => ['Titular', 'La casa'],
                'accent' => ['Énfasis del titular', 'al día.'],
                'lead' => ['Descripción', 'Actividades, publicaciones y recursos para seguir de cerca el trabajo de Casa CAITH.'],
                'empty' => ['Mensaje sin publicaciones', 'Las publicaciones aparecerán aquí después de su revisión.'],
                'fallback' => ['Enlace cuando no hay publicaciones', 'Conoce nuestra labor'],
                'link' => ['Enlace a publicaciones', 'Ver todas las publicaciones'],
                'image' => ['Imagen cuando no hay publicaciones', null],
            ],
        ],
        'data' => [
            'label' => 'Datos e indicadores',
            'fields' => [
                'title' => ['Titular', 'El trabajo del hogar'],
                'accent' => ['Énfasis del titular', 'en cifras.'],
                'lead' => ['Bajada', 'Perú · Serie histórica'],
                'link' => ['Enlace a indicadores', 'Ver indicadores y fuentes'],
                'caption' => ['Descripción del gráfico', 'Personas ocupadas en el trabajo del hogar'],
                'empty' => ['Mensaje sin indicadores', 'Los indicadores se mostrarán cuando la fuente, el año y el alcance estén verificados.'],
            ],
        ],
        'voices' => [
            'label' => 'Voces de la casa',
            'fields' => [
                'title' => ['Titular', 'Voces'],
                'accent' => ['Énfasis del titular', 'de la casa.'],
                'lead' => ['Descripción', 'Cada historia se comparte con el permiso de quien la cuenta.'],
                'link' => ['Enlace a la historia destacada', 'Leer la historia completa'],
                'fallback' => ['Enlace al archivo de testimonios', 'Historias y testimonios'],
                'empty' => ['Mensaje sin testimonios', 'Aquí aparecerán relatos cuando tengan autorización para publicarse.'],
                'image' => ['Ilustración de voces', null],
            ],
        ],
        'help' => [
            'label' => 'Participación',
            'fields' => [
                'title' => ['Titular', 'Hay más de una forma'],
                'accent' => ['Énfasis del titular', 'de acompañar.'],
                'lead' => ['Descripción', 'Puedes ofrecer tu tiempo, participar en una actividad o proponer una colaboración con tu organización.'],
                'link' => ['Enlace a cómo ayudar', 'Ver formas de colaborar'],
                'voluntariado_title' => ['Voluntariado · título', 'Voluntariado'],
                'voluntariado_lead' => ['Voluntariado · descripción', 'Ofrece tu tiempo'],
                'actividades_title' => ['Actividades · título', 'Participar en actividades'],
                'actividades_lead' => ['Actividades · descripción', 'Súmate a una actividad'],
                'patrocinio_title' => ['Colaboración · título', 'Apoyo institucional'],
                'patrocinio_lead' => ['Colaboración · descripción', 'Propón una colaboración'],
            ],
        ],
    ],
    'menu' => [
        'brand' => [
            'label' => 'Identidad',
            'fields' => [
                'name' => ['Nombre del sitio', 'Casa CAITH'],
                'caption' => ['Subtítulo', 'Centro Yanapanakusun'],
            ],
        ],
        'nosotros' => [
            'label' => 'Nosotros',
            'fields' => [
                'label' => ['Etiqueta', 'Nosotros'],
            ],
        ],
        'nuestra-labor' => [
            'label' => 'Nuestra labor',
            'fields' => [
                'label' => ['Etiqueta', 'Nuestra labor'],
            ],
        ],
        'realidad-social' => [
            'label' => 'Realidad social',
            'fields' => [
                'label' => ['Etiqueta', 'Realidad social'],
            ],
        ],
        'explore' => [
            'label' => 'Explora',
            'fields' => [
                'label' => ['Etiqueta', 'Explora'],
            ],
        ],
        'como-ayudar' => [
            'label' => 'Participa',
            'fields' => [
                'label' => ['Etiqueta', 'Participa'],
            ],
        ],
    ],
    'pie' => [
        'brand' => [
            'label' => 'Identidad',
            'fields' => [
                'name' => ['Nombre del sitio', 'Casa CAITH'],
                'caption' => ['Afiliación', 'Centro Yanapanakusun · Cusco, Perú'],
                'note' => ['Crédito de las ilustraciones', 'Ilustraciones conceptuales.'],
                'disclosure' => ['Pie de página', 'Vista para revisión institucional.'],
            ],
        ],
        'contact' => [
            'label' => 'Contacto',
            'fields' => [
                'label' => ['Etiqueta', 'Contacto'],
            ],
        ],
        'privacy' => [
            'label' => 'Privacidad',
            'fields' => [
                'label' => ['Etiqueta', 'Privacidad y retiro de datos'],
            ],
        ],
        'donations' => [
            'label' => 'Donaciones',
            'fields' => [
                'label' => ['Etiqueta', 'Donaciones: próximamente'],
            ],
        ],
        'opinions' => [
            'label' => 'Opiniones',
            'fields' => [
                'label' => ['Etiqueta', 'Opiniones'],
            ],
        ],
        'community_index' => [
            'label' => 'Mi cuenta',
            'fields' => [
                'label' => ['Etiqueta', 'Mi cuenta'],
            ],
        ],
        'admin_login' => [
            'label' => 'Administración',
            'fields' => [
                'label' => ['Etiqueta', 'Administración'],
            ],
        ],
    ],
];
