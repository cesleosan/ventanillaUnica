<?php

if (!isset($datos) || !is_array($datos)) {
    $datos = [];
}

/**
 * Escape HTML seguro.
 */
if (!function_exists('vut_pdf_h')) {
    function vut_pdf_h($value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_array($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Convierte a texto limpio.
 */
if (!function_exists('vut_pdf_txt')) {
    function vut_pdf_txt($value, string $default = ''): string
    {
        if ($value === null) {
            return $default;
        }

        if (is_array($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        $value = trim((string)$value);

        return $value === '' ? $default : $value;
    }
}

/**
 * Busca el primer valor disponible en varios arrays y varias llaves.
 */
if (!function_exists('vut_pdf_val')) {
    function vut_pdf_val(array $sources, array $keys, string $default = ''): string
    {
        foreach ($sources as $source) {
            if (!is_array($source)) {
                continue;
            }

            foreach ($keys as $key) {
                if (isset($source[$key]) && $source[$key] !== null && trim((string)$source[$key]) !== '') {
                    return trim((string)$source[$key]);
                }
            }
        }

        return $default;
    }
}

/**
 * Saber si un arreglo tiene datos reales.
 */
if (!function_exists('vut_pdf_has_data')) {
    function vut_pdf_has_data($arr): bool
    {
        if (!is_array($arr)) {
            return false;
        }

        foreach ($arr as $value) {
            if (is_array($value)) {
                if (vut_pdf_has_data($value)) {
                    return true;
                }
            } else {
                if ($value !== null && trim((string)$value) !== '') {
                    return true;
                }
            }
        }

        return false;
    }
}

/**
 * Etiqueta legible desde llave técnica.
 */
if (!function_exists('vut_pdf_label')) {
    function vut_pdf_label(string $key): string
    {
        $key = strtoupper($key);

        $replace = [
            'INTERESADO_' => '',
            'MORAL_' => '',
            'PREDIO_' => '',
            'MERCADO_' => '',
            'PROPIETARIO_' => 'PROPIETARIO ',
            'LEGAL_' => '',
            'LEG_' => '',
            'AUTORIZADA_' => '',
            'AUT_' => '',
            'BIFURCACION_' => 'BIFURCACIÓN ',
            'DOM_' => 'DOMICILIO ',
            'NUM_EXT' => 'NÚMERO EXTERIOR',
            'CP' => 'C.P.',
            'RFC' => 'RFC',
            'APE_' => 'APELLIDO ',
            'NO_' => 'NÚMERO ',
            '_' => ' ',
            '-' => ' ',
        ];

        $key = str_replace(array_keys($replace), array_values($replace), $key);
        $key = preg_replace('/\s+/', ' ', trim($key));

        return $key;
    }
}

/**
 * Construye URI local para Dompdf.
 * Se mantiene por compatibilidad, aunque los logos se embeben en base64.
 */
if (!function_exists('vut_pdf_file_uri')) {
    function vut_pdf_file_uri(string $path): string
    {
        $real = realpath($path);

        if (!$real) {
            return '';
        }

        $real = str_replace('\\', '/', $real);

        if (preg_match('/^[A-Za-z]:\//', $real)) {
            return 'file:///' . $real;
        }

        return 'file://' . $real;
    }
}

/**
 * Convierte imagen local a data URI.
 * Esto evita problemas con espacios en nombres, rutas Windows, chroot y file://.
 */
if (!function_exists('vut_pdf_image_data_uri')) {
    function vut_pdf_image_data_uri(string $path): string
    {
        $real = realpath($path);

        if (!$real || !is_file($real)) {
            return '';
        }

        $ext = strtolower(pathinfo($real, PATHINFO_EXTENSION));

        $mimeMap = [
            'png'  => 'image/png',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif'  => 'image/gif',
            'webp' => 'image/webp',
        ];

        if (!isset($mimeMap[$ext])) {
            return '';
        }

        $content = file_get_contents($real);

        if ($content === false) {
            return '';
        }

        return 'data:' . $mimeMap[$ext] . ';base64,' . base64_encode($content);
    }
}

/**
 * Construye dirección evitando basura como "#, COL. , C.P. ,".
 */
if (!function_exists('vut_pdf_address')) {
    function vut_pdf_address(
        string $calle = '',
        string $numExt = '',
        string $colonia = '',
        string $cp = '',
        string $alcaldia = '',
        string $default = 'N/A'
    ): string {
        $calle = trim($calle);
        $numExt = trim($numExt);
        $colonia = trim($colonia);
        $cp = trim($cp);
        $alcaldia = trim($alcaldia);

        $partes = [];

        if ($calle !== '') {
            $linea = $calle;

            if ($numExt !== '') {
                $linea .= ' #' . $numExt;
            }

            $partes[] = $linea;
        }

        if ($colonia !== '') {
            $partes[] = 'COL. ' . $colonia;
        }

        if ($cp !== '') {
            $partes[] = 'C.P. ' . $cp;
        }

        if ($alcaldia !== '') {
            $partes[] = $alcaldia;
        }

        if (empty($partes)) {
            return $default;
        }

        return strtoupper(implode(', ', $partes));
    }
}

/**
 * Render de fila simple.
 */
if (!function_exists('vut_pdf_row')) {
    function vut_pdf_row(string $label, $value, string $default = 'N/A'): string
    {
        $value = vut_pdf_txt($value, $default);

        return '
            <tr>
                <td class="label">' . vut_pdf_h($label) . '</td>
                <td colspan="3">' . vut_pdf_h($value) . '</td>
            </tr>
        ';
    }
}

/**
 * Render de fila doble.
 */
if (!function_exists('vut_pdf_row2')) {
    function vut_pdf_row2(string $label1, $value1, string $label2, $value2, string $default = 'N/A'): string
    {
        $value1 = vut_pdf_txt($value1, $default);
        $value2 = vut_pdf_txt($value2, $default);

        return '
            <tr>
                <td class="label">' . vut_pdf_h($label1) . '</td>
                <td>' . vut_pdf_h($value1) . '</td>
                <td class="label">' . vut_pdf_h($label2) . '</td>
                <td>' . vut_pdf_h($value2) . '</td>
            </tr>
        ';
    }
}

/**
 * Render de sección.
 */
if (!function_exists('vut_pdf_section_title')) {
    function vut_pdf_section_title(string $title): string
    {
        return '
            <div class="section-title">
                ' . vut_pdf_h($title) . '
            </div>
        ';
    }
}

/**
 * Renderiza tabla dinámica de key/value.
 */
if (!function_exists('vut_pdf_dynamic_table')) {
    function vut_pdf_dynamic_table(array $items, array $excludeKeys = []): string
    {
        $rows = '';
        $buffer = [];

        $excludeNormalized = array_map('strtoupper', $excludeKeys);

        foreach ($items as $key => $value) {
            if (is_array($value)) {
                continue;
            }

            $keyUpper = strtoupper((string)$key);

            if (in_array($keyUpper, $excludeNormalized, true)) {
                continue;
            }

            $value = trim((string)$value);

            if ($value === '') {
                continue;
            }

            $buffer[] = [
                'label' => vut_pdf_label($keyUpper),
                'value' => $value
            ];
        }

        for ($i = 0; $i < count($buffer); $i += 2) {
            $a = $buffer[$i];
            $b = $buffer[$i + 1] ?? null;

            if ($b) {
                $rows .= vut_pdf_row2($a['label'], $a['value'], $b['label'], $b['value']);
            } else {
                $rows .= '
                    <tr>
                        <td class="label">' . vut_pdf_h($a['label']) . '</td>
                        <td colspan="3">' . vut_pdf_h($a['value']) . '</td>
                    </tr>
                ';
            }
        }

        if ($rows === '') {
            return '';
        }

        return '<table class="tabla-datos">' . $rows . '</table>';
    }
}

/**
 * Normalización de fuentes.
 */
$solicitud = isset($datos['solicitud']) && is_array($datos['solicitud']) ? $datos['solicitud'] : [];
$bif = isset($datos['bifurcacion']) && is_array($datos['bifurcacion']) ? $datos['bifurcacion'] : [];

$interesado = isset($datos['interesado']) && is_array($datos['interesado']) ? $datos['interesado'] : [];
$interesadoBase = isset($interesado['datos']) && is_array($interesado['datos']) ? $interesado['datos'] : [];
$interesadoDin = isset($interesado['datos_dinamicos']) && is_array($interesado['datos_dinamicos']) ? $interesado['datos_dinamicos'] : [];

$representanteLegal = isset($datos['representante_legal']) && is_array($datos['representante_legal']) ? $datos['representante_legal'] : [];
$personaAutorizada = isset($datos['persona_autorizada']) && is_array($datos['persona_autorizada']) ? $datos['persona_autorizada'] : [];

$especificos = isset($datos['especificos']) && is_array($datos['especificos']) ? $datos['especificos'] : [];
$recibos = isset($datos['recibos']) && is_array($datos['recibos']) ? $datos['recibos'] : [];

$sourcesInteresado = [$datos, $interesadoDin, $interesadoBase];

/**
 * Datos principales.
 */
$folio = vut_pdf_val([$datos], ['folio', 'FOLIO'], 'V-PENDIENTE');

$fechaIngresoRaw = vut_pdf_val(
    [$datos, $solicitud],
    ['fecha_ingreso', 'fecha_creacion', 'created_at', 'FECHA_INGRESO', 'FECHA_CREACION'],
    date('Y-m-d H:i:s')
);

$fechaIngreso = date('d/m/Y H:i', strtotime($fechaIngresoRaw));

$materia = vut_pdf_val(
    [$datos, $solicitud],
    ['META_MATERIA', 'materia', 'MATERIA'],
    'N/A'
);

$tramitePrincipal = vut_pdf_val(
    [$datos, $solicitud],
    ['META_TRAMITE_NOMBRE', 'tramite', 'nombre_tramite', 'TRAMITE', 'NOMBRE_TRAMITE'],
    'NO ESPECIFICADO'
);

$modalidadTexto = vut_pdf_val(
    [$bif, $datos],
    ['modalidad_texto', 'MODALIDAD_TEXTO', 'BIFURCACION_MODALIDAD_TEXTO'],
    ''
);

$detalleTexto = vut_pdf_val(
    [$bif, $datos],
    ['detalle_texto', 'DETALLE_TEXTO', 'BIFURCACION_DETALLE_TEXTO'],
    ''
);

/**
 * La clave técnica se conserva solo para lógica interna.
 * No se imprime en el acuse ciudadano.
 */
$claveBifurcacion = vut_pdf_val(
    [$bif, $datos],
    ['clave', 'CLAVE', 'BIFURCACION_CLAVE'],
    ''
);

$partesTramite = [];

foreach ([$tramitePrincipal, $modalidadTexto, $detalleTexto] as $parte) {
    $parte = trim((string)$parte);

    if ($parte === '' || $parte === '-1') {
        continue;
    }

    $normal = strtoupper($parte);

    if (!isset($partesTramite[$normal])) {
        $partesTramite[$normal] = $parte;
    }
}

$tramiteCompleto = implode(' / ', array_values($partesTramite));

/**
 * Interesado.
 */
$razonSocial = vut_pdf_val(
    $sourcesInteresado,
    ['MORAL_RAZON_SOCIAL', 'RAZON_SOCIAL'],
    ''
);

$nombre = vut_pdf_val(
    $sourcesInteresado,
    ['INTERESADO_NOMBRES', 'INTERESADO_NOMBRE', 'NOMBRES'],
    ''
);

$apePat = vut_pdf_val(
    $sourcesInteresado,
    ['INTERESADO_APE_PATERNO', 'INTERESADO_PATERNO', 'INTERESADO_APELLIDO_PATERNO', 'APELLIDO_PATERNO'],
    ''
);

$apeMat = vut_pdf_val(
    $sourcesInteresado,
    ['INTERESADO_APE_MATERNO', 'INTERESADO_MATERNO', 'INTERESADO_APELLIDO_MATERNO', 'APELLIDO_MATERNO'],
    ''
);

$titular = $razonSocial !== '' ? $razonSocial : trim("$nombre $apePat $apeMat");

if ($titular === '') {
    $titular = 'TITULAR NO ESPECIFICADO';
}

$rfcCurp = vut_pdf_val(
    $sourcesInteresado,
    ['MORAL_RFC', 'INTERESADO_RFC', 'RFC', 'CURP', 'INTERESADO_CURP'],
    'N/A'
);

$telefonoInteresado = vut_pdf_val(
    $sourcesInteresado,
    ['MORAL_TELEFONO', 'INTERESADO_TELEFONO', 'TELEFONO'],
    'N/A'
);

$emailInteresado = vut_pdf_val(
    $sourcesInteresado,
    ['MORAL_EMAIL', 'INTERESADO_EMAIL', 'EMAIL'],
    'N/A'
);

$intAlcaldia = vut_pdf_val(
    $sourcesInteresado,
    ['INTERESADO_ALCALDIA', 'ALCALDIA', 'SELECT_ALCALDIA'],
    ''
);

$intColonia = vut_pdf_val(
    $sourcesInteresado,
    ['INTERESADO_COLONIA', 'COLONIA_NOMBRE', 'SELECT_COLONIA', 'COLONIA'],
    ''
);

$intCalle = vut_pdf_val(
    $sourcesInteresado,
    ['INTERESADO_CALLE', 'CALLE'],
    ''
);

$intNumExt = vut_pdf_val(
    $sourcesInteresado,
    ['INTERESADO_NUMERO_EXTERIOR', 'NUMERO_EXTERIOR', 'NUM_EXT', 'NO_EXTERIOR'],
    ''
);

$intCp = vut_pdf_val(
    $sourcesInteresado,
    ['INTERESADO_CP', 'CP', 'CODIGO_POSTAL'],
    ''
);

$domicilioInteresado = vut_pdf_address(
    $intCalle,
    $intNumExt,
    $intColonia,
    $intCp,
    $intAlcaldia,
    'N/A'
);

$noEscritura = vut_pdf_val($sourcesInteresado, ['MORAL_NO_ESCRITURA', 'NO_ESCRITURA'], '');
$noNotario = vut_pdf_val($sourcesInteresado, ['MORAL_NO_NOTARIO', 'NO_NOTARIO'], '');
$nombreNotario = vut_pdf_val($sourcesInteresado, ['MORAL_NOMBRE_NOTARIO', 'NOMBRE_NOTARIO'], '');

/**
 * Dirección principal del objeto del trámite.
 * Prioridad: Mercado > Predio > Domicilio del interesado.
 */
$hayMercado = vut_pdf_val(
    [$datos, $especificos],
    ['MERCADO_NOMBRE', 'mercado_nombre'],
    ''
) !== '';

$mercadoNombre = vut_pdf_val([$datos, $especificos], ['MERCADO_NOMBRE'], '');
$mercadoLocal = vut_pdf_val([$datos, $especificos], ['MERCADO_LOCAL'], '');
$mercadoGiro = vut_pdf_val([$datos, $especificos], ['MERCADO_GIRO'], '');

if ($hayMercado) {
    $tituloUbicacion = 'DATOS DEL MERCADO PÚBLICO';

    $calle = vut_pdf_val([$datos, $especificos], ['MERCADO_CALLE'], 'S/N');
    $numExt = vut_pdf_val([$datos, $especificos], ['MERCADO_NUM_EXT', 'MERCADO_NUMERO_EXTERIOR'], 'S/N');
    $colonia = vut_pdf_val([$datos, $especificos], ['MERCADO_COLONIA_NOMBRE', 'MERCADO_COLONIA', 'MERCADO_COLONIA_MANUAL'], 'NO ESPECIFICADA');
    $alcaldia = vut_pdf_val([$datos, $especificos], ['MERCADO_ALCALDIA'], 'TLALPAN');
    $cp = vut_pdf_val([$datos, $especificos], ['MERCADO_CP'], '00000');
} else {
    $predioCalle = vut_pdf_val([$datos, $especificos], ['PREDIO_CALLE'], '');

    if ($predioCalle !== '') {
        $tituloUbicacion = 'UBICACIÓN DEL OBJETO / PREDIO';

        $calle = $predioCalle;
        $numExt = vut_pdf_val([$datos, $especificos], ['PREDIO_NUMERO_EXTERIOR', 'PREDIO_NUM_EXT'], 'S/N');
        $colonia = vut_pdf_val([$datos, $especificos], ['PREDIO_COLONIA_NOMBRE', 'PREDIO_COLONIA', 'PREDIO_COLONIA_NOLISTA'], 'NO ESPECIFICADA');
        $alcaldia = vut_pdf_val([$datos, $especificos], ['PREDIO_ALCALDIA'], 'TLALPAN');
        $cp = vut_pdf_val([$datos, $especificos], ['PREDIO_CODIGO_POSTAL', 'PREDIO_CP'], '00000');
    } else {
        $tituloUbicacion = 'DOMICILIO DEL INTERESADO';

        $calle = $intCalle !== '' ? $intCalle : 'S/N';
        $numExt = $intNumExt !== '' ? $intNumExt : 'S/N';
        $colonia = $intColonia !== '' ? $intColonia : 'NO ESPECIFICADA';
        $alcaldia = $intAlcaldia !== '' ? $intAlcaldia : 'TLALPAN';
        $cp = $intCp !== '' ? $intCp : '00000';
    }
}

$direccionFinal = vut_pdf_address(
    $calle,
    $numExt,
    $colonia,
    $cp,
    $alcaldia,
    'N/A'
);

/**
 * Propietario del predio.
 * Estos campos vienen dentro de "especificos" porque están en contenedor-dinamico-captura.
 */
$propSources = [$datos, $especificos];

$propietarioCheck = strtoupper(vut_pdf_val(
    $propSources,
    ['CHECK_AGREGAR_PROPIETARIO', 'check_agregar_propietario'],
    ''
));

$propietarioNombre = trim(
    vut_pdf_val($propSources, ['PROPIETARIO_NOMBRES', 'propietario_nombres'], '') . ' ' .
    vut_pdf_val($propSources, ['PROPIETARIO_APE_PATERNO', 'propietario_ape_paterno'], '') . ' ' .
    vut_pdf_val($propSources, ['PROPIETARIO_APE_MATERNO', 'propietario_ape_materno'], '')
);

$propietarioRfc = vut_pdf_val(
    $propSources,
    ['PROPIETARIO_RFC', 'propietario_rfc'],
    ''
);

$propietarioTelefono = vut_pdf_val(
    $propSources,
    ['PROPIETARIO_TELEFONO', 'propietario_telefono'],
    ''
);

$propietarioEmail = vut_pdf_val(
    $propSources,
    ['PROPIETARIO_EMAIL', 'propietario_email'],
    ''
);

$propietarioTieneDatos = (
    $propietarioNombre !== '' ||
    $propietarioRfc !== '' ||
    $propietarioTelefono !== '' ||
    $propietarioEmail !== ''
);

$mostrarPropietario = (
    $propietarioCheck === 'SÍ' ||
    $propietarioCheck === 'SI' ||
    $propietarioTieneDatos
);

/**
 * Representante legal.
 */
$legalNombre = trim(
    vut_pdf_val([$representanteLegal, $datos], ['LEG_NOMBRES', 'LEGAL_NOMBRES'], '') . ' ' .
    vut_pdf_val([$representanteLegal, $datos], ['LEG_PATERNO', 'LEGAL_PATERNO', 'LEGAL_APELLIDO_PATERNO'], '') . ' ' .
    vut_pdf_val([$representanteLegal, $datos], ['LEG_MATERNO', 'LEGAL_MATERNO', 'LEGAL_APELLIDO_MATERNO'], '')
);

$legalRfc = vut_pdf_val([$representanteLegal, $datos], ['LEG_RFC', 'LEGAL_RFC'], 'N/A');
$legalTel = vut_pdf_val([$representanteLegal, $datos], ['LEG_TELEFONO', 'LEGAL_TELEFONO'], 'N/A');
$legalEmail = vut_pdf_val([$representanteLegal, $datos], ['LEG_EMAIL', 'LEGAL_EMAIL'], 'N/A');
$legalDoc = vut_pdf_val([$representanteLegal, $datos], ['LEG_DOC_PERSONALIDAD', 'LEGAL_DOC_PERSONALIDAD', 'LEGAL_DOCUMENTO_PERSONALIDAD'], 'N/A');

$legalAlcaldia = vut_pdf_val([$representanteLegal, $datos], ['LEG_DOM_DEL', 'LEG_ALCALDIA', 'LEGAL_ALCALDIA'], '');
$legalColonia = vut_pdf_val([$representanteLegal, $datos], ['LEG_DOM_COLONIA', 'LEG_COLONIA', 'LEGAL_COLONIA'], '');
$legalCalle = vut_pdf_val([$representanteLegal, $datos], ['LEG_DOM_CALLE', 'LEGAL_CALLE', 'LEG_DIRECCION_SIMPLE'], '');
$legalNum = vut_pdf_val([$representanteLegal, $datos], ['LEG_DOM_NUM_EXT', 'LEGAL_NUMERO_EXTERIOR'], '');
$legalCp = vut_pdf_val([$representanteLegal, $datos], ['LEG_DOM_CP', 'LEGAL_CP'], '');

$legalDireccion = vut_pdf_address(
    $legalCalle,
    $legalNum,
    $legalColonia,
    $legalCp,
    $legalAlcaldia,
    ''
);

/**
 * Persona autorizada.
 */
$autNombre = trim(
    vut_pdf_val([$personaAutorizada, $datos], ['AUT_NOMBRES', 'AUTORIZADA_NOMBRES'], '') . ' ' .
    vut_pdf_val([$personaAutorizada, $datos], ['AUT_PATERNO', 'AUTORIZADA_PATERNO', 'AUTORIZADA_APELLIDO_PATERNO'], '') . ' ' .
    vut_pdf_val([$personaAutorizada, $datos], ['AUT_MATERNO', 'AUTORIZADA_MATERNO', 'AUTORIZADA_APELLIDO_MATERNO'], '')
);

$autRfc = vut_pdf_val([$personaAutorizada, $datos], ['AUT_RFC', 'AUTORIZADA_RFC'], 'N/A');
$autTel = vut_pdf_val([$personaAutorizada, $datos], ['AUT_TELEFONO', 'AUTORIZADA_TELEFONO'], 'N/A');
$autEmail = vut_pdf_val([$personaAutorizada, $datos], ['AUT_EMAIL', 'AUTORIZADA_EMAIL'], 'N/A');
$autDoc = vut_pdf_val([$personaAutorizada, $datos], ['AUT_DOC_PERSONALIDAD', 'AUTORIZADA_DOCUMENTO_PERSONALIDAD', 'AUTORIZADA_DOC_PERSONALIDAD'], 'N/A');

$autAlcaldia = vut_pdf_val([$personaAutorizada, $datos], ['AUT_DOM_DEL', 'AUTORIZADA_DOM_DEL', 'AUTORIZADA_ALCALDIA'], '');
$autColonia = vut_pdf_val([$personaAutorizada, $datos], ['AUT_DOM_COLONIA', 'AUTORIZADA_DOM_COLONIA', 'AUTORIZADA_COLONIA'], '');
$autCalle = vut_pdf_val([$personaAutorizada, $datos], ['AUT_DOM_CALLE', 'AUTORIZADA_DOMICILIO_CALLE', 'AUT_DOM_CALLE_MANUAL', 'AUTORIZADA_DOMICILIO_CALLE_MANUAL'], '');
$autNum = vut_pdf_val([$personaAutorizada, $datos], ['AUT_DOM_NUM_EXT', 'AUTORIZADA_DOMICILIO_NUMERO_EXTERIOR'], '');
$autCp = vut_pdf_val([$personaAutorizada, $datos], ['AUT_DOM_CP', 'AUTORIZADA_DOM_CP', 'AUTORIZADA_CODIGO_POSTAL'], '');

$autDireccion = vut_pdf_address(
    $autCalle,
    $autNum,
    $autColonia,
    $autCp,
    $autAlcaldia,
    ''
);

$autDomicilioProcesal = vut_pdf_val(
    [$personaAutorizada, $datos],
    ['AUT_DOMICILIO_PROCESAL', 'AUTORIZADA_DOMICILIO_PROCESAL'],
    ''
);

$autPersonaExtra = vut_pdf_val(
    [$personaAutorizada, $datos],
    ['AUT_PERSONA_NOMBRE_EXTRA', 'AUTORIZADA_PERSONA_NOMBRE_EXTRA'],
    ''
);

/**
 * Requisitos.
 */
$requisitos = [];

if (isset($datos['requisitos_validados']) && is_array($datos['requisitos_validados'])) {
    foreach ($datos['requisitos_validados'] as $req) {
        $req = trim((string)$req);

        if ($req !== '') {
            $requisitos[] = $req;
        }
    }
}

$reqFlat = [];

foreach ($datos as $key => $value) {
    if (is_array($value)) {
        continue;
    }

    if (strpos((string)$key, 'REQ_') === 0) {
        $reqFlat[$key] = $value;
    }
}

if (!empty($reqFlat)) {
    ksort($reqFlat, SORT_NATURAL);

    foreach ($reqFlat as $req) {
        $req = trim((string)$req);

        if ($req !== '') {
            $requisitos[] = $req;
        }
    }
}

$requisitosUnicos = [];

foreach ($requisitos as $req) {
    $normal = strtoupper($req);

    if (!isset($requisitosUnicos[$normal])) {
        $requisitosUnicos[$normal] = $req;
    }
}

$requisitos = array_values($requisitosUnicos);

/**
 * Observaciones.
 */
$observaciones = vut_pdf_val(
    [$datos],
    ['OBSERVACIONES', 'observaciones'],
    'SIN OBSERVACIONES ADICIONALES.'
);

/**
 * Firmas digitales.
 * Llegan desde el payload como data:image/png;base64,...
 */
$firmas = [];

if (isset($datos['firmas']) && is_array($datos['firmas'])) {
    $firmas = $datos['firmas'];
} elseif (isset($datos['FIRMAS']) && is_array($datos['FIRMAS'])) {
    $firmas = $datos['FIRMAS'];
} elseif (!empty($datos['payload']) && is_string($datos['payload'])) {
    $payloadFirmas = json_decode($datos['payload'], true);

    if (is_array($payloadFirmas) && isset($payloadFirmas['firmas']) && is_array($payloadFirmas['firmas'])) {
        $firmas = $payloadFirmas['firmas'];
    }
}

$firmaCapturistaImg = $firmas['capturista']['imagen'] ?? '';
$firmaInteresadoImg = $firmas['interesado']['imagen'] ?? '';

$capturistaFirmo = !empty($firmas['capturista']['firmo']);
$interesadoFirmo = !empty($firmas['interesado']['firmo']);
$interesadoNoPresente = !empty($firmas['interesado']['no_presente']);
$motivoNoFirmaInteresado = trim((string)($firmas['interesado']['motivo_no_firma'] ?? ''));

/**
 * Recibos.
 * Solo se imprimen si tienen folio real o monto mayor a 0.
 */
$recibosLimpios = [];

for ($i = 1; $i <= 10; $i++) {
    $folioRecibo = vut_pdf_val(
        [$recibos, $datos],
        ["FOLIO_RECIBO_$i", "folio_recibo_$i"],
        ''
    );

    $montoRecibo = vut_pdf_val(
        [$recibos, $datos],
        ["MONTO_RECIBO_$i", "monto_recibo_$i"],
        ''
    );

    $folioRecibo = strtoupper(trim((string)$folioRecibo));
    $montoRecibo = trim((string)$montoRecibo);

    if (in_array($folioRecibo, ['', 'N/A', 'NA', 'S/N', 'SN', 'SIN FOLIO'], true)) {
        $folioRecibo = '';
    }

    $montoNumerico = str_replace([',', '$', ' '], '', $montoRecibo);
    $montoNumerico = is_numeric($montoNumerico) ? (float)$montoNumerico : 0;

    $tieneFolio = $folioRecibo !== '';
    $tieneMonto = $montoNumerico > 0;

    if ($tieneFolio || $tieneMonto) {
        $recibosLimpios[] = [
            'folio' => $tieneFolio ? $folioRecibo : 'N/A',
            'monto' => $tieneMonto ? number_format($montoNumerico, 2, '.', ',') : 'N/A'
        ];
    }
}

/**
 * Logos institucionales.
 * Todos viven en public/logos.
 *
 * Usamos base64/data-uri para que Dompdf no falle con:
 * - espacios en nombres de archivo
 * - rutas Windows
 * - file://
 * - chroot
 */
$logosDirCandidates = [
    __DIR__ . '/../../../public/logos',
    dirname(__DIR__, 3) . '/public/logos',
    getcwd() . '/public/logos',
    getcwd() . '/logos',
];

$logosDir = '';

foreach ($logosDirCandidates as $dir) {
    $realDir = realpath($dir);

    if ($realDir && is_dir($realDir)) {
        $logosDir = $realDir;
        break;
    }
}

$logoSrc = '';
$vutLogoHeaderSrc = '';
$tlalpanWatermarkSrc = '';

if ($logosDir !== '') {
    $logoTlalpanHeaderFiles = [
        'Logo AT Horizontal guinda 100PX.png',
        'Logo AT Horizontal 100PX.png',
        'Logo AT Horizontal N 100PX.png',
        'Logo AT Horizontal B 100PX.png',
    ];

    $logoVutHeaderFiles = [
        'VUT COMPLETO-01.png',
        'VUT COMPLETO BN.png',
        'VUT_Mesa de trabajo 1.png',
        'VUT-02.png',
    ];

    $logoTlalpanWatermarkFiles = [
        'Logo AT Vertical guinda 100 PX.png',
        'Logo AT Vertical guinda 100PX.png',
        'Logo AT Vertical 100PX.png',
        'Logo AT Vertical N 100PX.png',
        'Logo AT Vertical B 100PX.png',
    ];

    foreach ($logoTlalpanHeaderFiles as $file) {
        $src = vut_pdf_image_data_uri($logosDir . DIRECTORY_SEPARATOR . $file);

        if ($src !== '') {
            $logoSrc = $src;
            break;
        }
    }

    foreach ($logoVutHeaderFiles as $file) {
        $src = vut_pdf_image_data_uri($logosDir . DIRECTORY_SEPARATOR . $file);

        if ($src !== '') {
            $vutLogoHeaderSrc = $src;
            break;
        }
    }

    foreach ($logoTlalpanWatermarkFiles as $file) {
        $src = vut_pdf_image_data_uri($logosDir . DIRECTORY_SEPARATOR . $file);

        if ($src !== '') {
            $tlalpanWatermarkSrc = $src;
            break;
        }
    }
}

// Debug para confirmar en error_log si encontró las imágenes.
error_log("PDF LOGOS DIR: " . ($logosDir ?: 'NO ENCONTRADO'));
error_log("PDF LOGO TLALPAN HEADER: " . ($logoSrc !== '' ? 'OK' : 'NO'));
error_log("PDF LOGO VUT HEADER: " . ($vutLogoHeaderSrc !== '' ? 'OK' : 'NO'));
error_log("PDF LOGO WATERMARK: " . ($tlalpanWatermarkSrc !== '' ? 'OK' : 'NO'));

/**
 * Campos que no queremos duplicar en tabla dinámica porque ya salen en secciones principales.
 */
$excludeEspecificos = [
    'MERCADO_NOMBRE',
    'MERCADO_LOCAL',
    'MERCADO_GIRO',
    'MERCADO_ALCALDIA',
    'MERCADO_COLONIA',
    'MERCADO_COLONIA_NOMBRE',
    'MERCADO_COLONIA_MANUAL',
    'MERCADO_CALLE',
    'MERCADO_NUM_EXT',
    'MERCADO_NUMERO_EXTERIOR',
    'MERCADO_CP',

    'PREDIO_USO_ACTUAL',
    'PREDIO_USO_SOLICITADO',
    'PREDIO_DIRECCION',
    'PREDIO_ALCALDIA',
    'PREDIO_COLONIA',
    'PREDIO_COLONIA_NOMBRE',
    'PREDIO_COLONIA_NOLISTA',
    'PREDIO_CALLE',
    'PREDIO_NUMERO_EXTERIOR',
    'PREDIO_NUM_EXT',
    'PREDIO_CODIGO_POSTAL',
    'PREDIO_CP',

    'CHECK_AGREGAR_PROPIETARIO',
    'PROPIETARIO_NOMBRES',
    'PROPIETARIO_APE_PATERNO',
    'PROPIETARIO_APE_MATERNO',
    'PROPIETARIO_RFC',
    'PROPIETARIO_TELEFONO',
    'PROPIETARIO_EMAIL',
];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 1cm 1.1cm 1.1cm 1.1cm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 9.5px;
            color: #333333;
            text-transform: uppercase;
            line-height: 1.25;
            position: relative;
            background: #ffffff;
        }

        .watermark {
            position: fixed;
            top: 17%;
            left: 14%;
            width: 72%;
            text-align: center;
            z-index: 0;
            opacity: 0.045;
        }

        .watermark img {
            width: 100%;
            height: auto;
        }

        .pdf-content {
            position: relative;
            z-index: 1;
        }

        .top-band {
            width: 100%;
            height: 8px;
            background: #773357;
            margin-bottom: 12px;
        }

        .header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .header td {
            vertical-align: middle;
            border: none;
        }

        .logo-box {
            width: 180px;
            text-align: left;
        }

        .logo-box img {
            max-width: 165px;
            height: auto;
        }

        .vut-box {
            width: 190px;
            text-align: right;
        }

        .vut-box img {
            max-width: 175px;
            height: auto;
        }

        .institution-title {
            text-align: center;
            color: #333333;
            padding: 0 10px;
        }

        .institution-title .main {
            font-size: 12px;
            font-weight: bold;
            color: #773357;
            letter-spacing: 0.3px;
        }

        .institution-title .sub {
            font-size: 8px;
            letter-spacing: 0.7px;
            color: #444444;
        }

        .hero-card {
            border: 1px solid #e7d9df;
            background: #fcf8fa;
            padding: 9px 12px;
            margin-bottom: 10px;
        }

        .hero-card .hero-title {
            font-size: 12px;
            font-weight: bold;
            color: #773357;
            margin-bottom: 3px;
            letter-spacing: 0.3px;
        }

        .hero-card .hero-subtitle {
            font-size: 8.3px;
            color: #666666;
            line-height: 1.35;
        }

        .folio-box {
            border: 2px solid #773357;
            padding: 8px;
            text-align: center;
            width: 190px;
            float: right;
            background: #ffffff;
        }

        .folio-box .label-folio {
            font-size: 7.5px;
            color: #777777;
            font-weight: bold;
            letter-spacing: 0.4px;
        }

        .folio-box .folio {
            color: #773357;
            font-size: 13px;
            font-weight: bold;
            margin: 2px 0;
        }

        .aviso {
            background: #fff9fb;
            border: 1px solid #773357;
            padding: 8px;
            margin-bottom: 10px;
            font-size: 8.5px;
            text-align: justify;
            line-height: 1.35;
        }

        .section-title {
            border-left: 4px solid #773357;
            padding-left: 7px;
            margin: 12px 0 5px 0;
            font-weight: bold;
            font-size: 9px;
            color: #773357;
            letter-spacing: 0.2px;
            page-break-after: avoid;
        }

        .tabla-datos {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            page-break-inside: avoid;
            background: #ffffff;
        }

        .tabla-datos td {
            border: 1px solid #dddddd;
            padding: 5px;
            vertical-align: top;
            line-height: 1.25;
        }

        .tabla-datos .label {
            background-color: #f7f3f5;
            font-weight: bold;
            width: 22%;
            font-size: 7.8px;
            color: #666666;
        }

        .tabla-datos .value-strong {
            font-weight: bold;
            color: #222222;
        }

        .mini-note {
            color: #777777;
            font-size: 8px;
            font-style: italic;
        }

        .req-box {
            border: 1px solid #dddddd;
            padding: 8px;
            min-height: 70px;
            margin-bottom: 8px;
            background: #ffffff;
            page-break-inside: avoid;
        }

        .req-item {
            margin-bottom: 3px;
            padding-left: 6px;
            line-height: 1.25;
        }

        .observaciones {
            border: 1px solid #eeeeee;
            padding: 7px;
            min-height: 45px;
            font-style: italic;
            text-align: justify;
            background: #ffffff;
            line-height: 1.35;
            page-break-inside: avoid;
        }

        .firma-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 34px;
            page-break-inside: avoid;
        }

        .firma-table td {
            width: 50%;
            text-align: center;
            font-size: 8.5px;
            padding-top: 28px;
            border: none;
        }

        .footer-note {
            margin-top: 15px;
            border-top: 1px solid #dddddd;
            padding-top: 5px;
            font-size: 7.5px;
            color: #777777;
            text-align: center;
            line-height: 1.3;
        }

        .text-guinda {
            color: #773357;
        }

        .bg-guinda {
            background: #773357;
            color: #ffffff;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .bold {
            font-weight: bold;
        }

        .no-border {
            border: none !important;
        }

        .page-break {
            page-break-before: always;
        }

        .avoid-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>

<?php if ($tlalpanWatermarkSrc !== ''): ?>
    <div class="watermark">
        <img src="<?= vut_pdf_h($tlalpanWatermarkSrc) ?>" alt="Tlalpan">
    </div>
<?php endif; ?>

<div class="pdf-content">

<div class="top-band"></div>

<table class="header">
    <tr>
        <td class="logo-box">
            <?php if ($logoSrc !== ''): ?>
                <img src="<?= vut_pdf_h($logoSrc) ?>" width="165" alt="Alcaldía Tlalpan">
            <?php else: ?>
                <strong style="color:#773357;">ALCALDÍA TLALPAN</strong>
            <?php endif; ?>
        </td>

        <td class="institution-title">
            <div class="main">ALCALDÍA TLALPAN</div>
            <div class="sub">VENTANILLA ÚNICA DE TRÁMITES</div>
            <div class="sub">ACUSE DE RECEPCIÓN DOCUMENTAL</div>
        </td>

        <td class="vut-box">
            <?php if ($vutLogoHeaderSrc !== ''): ?>
                <img src="<?= vut_pdf_h($vutLogoHeaderSrc) ?>" width="175" alt="VUT">
            <?php else: ?>
                <strong style="color:#773357;">VUT</strong>
            <?php endif; ?>
        </td>
    </tr>
</table>

<div class="hero-card">
    <div class="hero-title">COMPROBANTE OFICIAL DE INGRESO DOCUMENTAL</div>
    <div class="hero-subtitle">
        ESTE ACUSE REGISTRA LA INFORMACIÓN CAPTURADA EN LA VENTANILLA ÚNICA DE TRÁMITES DE LA ALCALDÍA TLALPAN.
    </div>
</div>

<table style="width:100%; border-collapse: collapse; margin-bottom: 10px;">
    <tr>
        <td style="width: 60%; vertical-align: top;">
            <div class="aviso" style="margin-bottom:0;">
                <strong>AVISO:</strong>
                ESTE ACUSE REGISTRA LA RECEPCIÓN DE DOCUMENTOS Y NO GARANTIZA LA RESOLUCIÓN POSITIVA DEL TRÁMITE.
                LA INFORMACIÓN AQUÍ MOSTRADA CORRESPONDE A LOS DATOS CAPTURADOS EN VENTANILLA.
            </div>
        </td>
        <td style="width: 40%; vertical-align: top; text-align:right;">
            <div class="folio-box">
                <div class="label-folio">FOLIO</div>
                <div class="folio"><?= vut_pdf_h($folio) ?></div>
                <div class="label-folio">FECHA: <?= vut_pdf_h($fechaIngreso) ?></div>
            </div>
        </td>
    </tr>
</table>

<?= vut_pdf_section_title('DATOS GENERALES DEL TRÁMITE') ?>

<table class="tabla-datos">
    <tr>
        <td class="label">TRÁMITE SOLICITADO</td>
        <td colspan="3" class="value-strong"><?= vut_pdf_h($tramiteCompleto) ?></td>
    </tr>
    <?= vut_pdf_row2('MATERIA', $materia, 'FECHA DE INGRESO', $fechaIngreso) ?>

    <?php if ($modalidadTexto !== '' || $detalleTexto !== ''): ?>
        <?= vut_pdf_row2('MODALIDAD', $modalidadTexto ?: 'N/A', 'TIPO / DETALLE', $detalleTexto ?: 'N/A') ?>
    <?php endif; ?>
</table>

<?= vut_pdf_section_title('DATOS DEL INTERESADO') ?>

<table class="tabla-datos">
    <?= vut_pdf_row2('NOMBRE / RAZÓN SOCIAL', $titular, 'RFC / CURP', $rfcCurp) ?>
    <?= vut_pdf_row2('TELÉFONO', $telefonoInteresado, 'CORREO ELECTRÓNICO', $emailInteresado) ?>
    <?= vut_pdf_row('DOMICILIO DEL INTERESADO', $domicilioInteresado) ?>

    <?php if ($razonSocial !== '' || $noEscritura !== '' || $noNotario !== '' || $nombreNotario !== ''): ?>
        <?= vut_pdf_row2('NO. ESCRITURA', $noEscritura ?: 'N/A', 'NO. NOTARIO', $noNotario ?: 'N/A') ?>
        <?= vut_pdf_row('NOMBRE DEL NOTARIO', $nombreNotario ?: 'N/A') ?>
    <?php endif; ?>
</table>

<?= vut_pdf_section_title($tituloUbicacion) ?>

<table class="tabla-datos">
    <?php if ($hayMercado): ?>
        <?= vut_pdf_row2('MERCADO', $mercadoNombre, 'LOCAL', $mercadoLocal) ?>
        <?= vut_pdf_row('GIRO SOLICITADO', $mercadoGiro) ?>
    <?php endif; ?>

    <?= vut_pdf_row('DIRECCIÓN COMPLETA', $direccionFinal) ?>
</table>

<?php if ($mostrarPropietario && $propietarioTieneDatos): ?>
    <?= vut_pdf_section_title('DATOS DEL PROPIETARIO DEL PREDIO') ?>

    <table class="tabla-datos">
        <?= vut_pdf_row2('NOMBRE DEL PROPIETARIO', $propietarioNombre ?: 'N/A', 'RFC', $propietarioRfc ?: 'N/A') ?>
        <?= vut_pdf_row2('TELÉFONO', $propietarioTelefono ?: 'N/A', 'CORREO ELECTRÓNICO', $propietarioEmail ?: 'N/A') ?>
    </table>
<?php endif; ?>

<?php if (vut_pdf_has_data($representanteLegal) && $legalNombre !== ''): ?>
    <?= vut_pdf_section_title('REPRESENTANTE LEGAL') ?>

    <table class="tabla-datos">
        <?= vut_pdf_row2('NOMBRE', $legalNombre, 'RFC', $legalRfc) ?>
        <?= vut_pdf_row2('TELÉFONO', $legalTel, 'CORREO ELECTRÓNICO', $legalEmail) ?>
        <?= vut_pdf_row('DOCUMENTO CON QUE ACREDITA PERSONALIDAD', $legalDoc) ?>

        <?php if ($legalDireccion !== ''): ?>
            <?= vut_pdf_row('DOMICILIO', $legalDireccion) ?>
        <?php endif; ?>
    </table>
<?php endif; ?>

<?php if (vut_pdf_has_data($personaAutorizada) && $autNombre !== ''): ?>
    <?= vut_pdf_section_title('PERSONA AUTORIZADA PARA OÍR Y RECIBIR NOTIFICACIONES') ?>

    <table class="tabla-datos">
        <?= vut_pdf_row2('NOMBRE', $autNombre, 'RFC', $autRfc) ?>
        <?= vut_pdf_row2('TELÉFONO', $autTel, 'CORREO ELECTRÓNICO', $autEmail) ?>
        <?= vut_pdf_row('DOCUMENTO CON QUE ACREDITA PERSONALIDAD', $autDoc) ?>

        <?php if ($autDireccion !== ''): ?>
            <?= vut_pdf_row('DOMICILIO', $autDireccion) ?>
        <?php endif; ?>

        <?php if ($autDomicilioProcesal !== ''): ?>
            <?= vut_pdf_row('DOMICILIO PARA OÍR Y RECIBIR NOTIFICACIONES', $autDomicilioProcesal) ?>
        <?php endif; ?>

        <?php if ($autPersonaExtra !== ''): ?>
            <?= vut_pdf_row('PERSONA AUTORIZADA ADICIONAL', $autPersonaExtra) ?>
        <?php endif; ?>
    </table>
<?php endif; ?>

<?php
$tablaEspecificos = vut_pdf_dynamic_table($especificos, $excludeEspecificos);
?>

<?php if ($tablaEspecificos !== ''): ?>
    <?= vut_pdf_section_title('DATOS ESPECÍFICOS CAPTURADOS DEL TRÁMITE') ?>
    <?= $tablaEspecificos ?>
<?php endif; ?>

<?php if (!empty($recibosLimpios)): ?>
    <?= vut_pdf_section_title('REGISTRO DE RECIBOS / PAGOS') ?>

    <table class="tabla-datos">
        <tr>
            <td class="label">NO.</td>
            <td class="label">FOLIO DEL RECIBO</td>
            <td class="label">MONTO</td>
            <td class="label">OBSERVACIÓN</td>
        </tr>

        <?php foreach ($recibosLimpios as $idx => $recibo): ?>
            <tr>
                <td><?= vut_pdf_h($idx + 1) ?></td>
                <td><?= vut_pdf_h($recibo['folio']) ?></td>
                <td><?= vut_pdf_h($recibo['monto']) ?></td>
                <td>REGISTRADO EN CAPTURA</td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?= vut_pdf_section_title('DOCUMENTACIÓN PRESENTADA') ?>

<div class="req-box">
    <?php if (!empty($requisitos)): ?>
        <?php foreach ($requisitos as $req): ?>
            <div class="req-item">• <?= vut_pdf_h($req) ?></div>
        <?php endforeach; ?>
    <?php else: ?>
        <span class="mini-note">NO SE REGISTRARON REQUISITOS FÍSICOS.</span>
    <?php endif; ?>
</div>

<?= vut_pdf_section_title('OBSERVACIONES') ?>

<div class="observaciones">
    <?= nl2br(vut_pdf_h($observaciones)) ?>
</div>

<?= vut_pdf_section_title('FIRMAS DIGITALES') ?>

<table class="firma-table">
    <tr>
        <td>
            <?php if ($firmaInteresadoImg !== ''): ?>
                <img src="<?= vut_pdf_h($firmaInteresadoImg) ?>" style="max-width:230px; max-height:82px;"><br>
            <?php elseif ($interesadoNoPresente): ?>
                <strong>INTERESADO NO PRESENTE / NO FIRMA</strong><br>
                <?php if ($motivoNoFirmaInteresado !== ''): ?>
                    <span style="font-size:7.5px;color:#777;">
                        <?= vut_pdf_h($motivoNoFirmaInteresado) ?>
                    </span><br>
                <?php endif; ?>
            <?php else: ?>
                _______________________________________<br>
            <?php endif; ?>
            FIRMA DEL INTERESADO
        </td>
        <td>
            <?php if ($firmaCapturistaImg !== ''): ?>
                <img src="<?= vut_pdf_h($firmaCapturistaImg) ?>" style="max-width:230px; max-height:82px;"><br>
            <?php else: ?>
                _______________________________________<br>
            <?php endif; ?>
            FIRMA DEL CAPTURISTA / VUT
        </td>
    </tr>
</table>

<div class="footer-note">
    ALCALDÍA TLALPAN · VENTANILLA ÚNICA DE TRÁMITES · ESTE DOCUMENTO ES UN ACUSE DE RECEPCIÓN.
</div>

</div>

</body>
</html>
