<?php

class Ventanilla {
    private $db;
    private $columnCache = [];
    private $tableCache = [];

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Busca el primer valor existente y no vacío dentro de varias llaves posibles.
     */
    private function val(array $arr, array $keys, $default = null) {
        foreach ($keys as $key) {
            if (isset($arr[$key]) && $arr[$key] !== '' && $arr[$key] !== null) {
                return is_string($arr[$key]) ? trim($arr[$key]) : $arr[$key];
            }
        }

        return $default;
    }

    /**
     * Verifica si un arreglo tiene al menos un valor útil.
     */
    private function tieneValoresUtiles(array $arr) {
        foreach ($arr as $value) {
            if (is_array($value)) {
                if ($this->tieneValoresUtiles($value)) {
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

    /**
     * Normaliza valores para guardar en BD.
     */
    private function limpiar($value) {
        if ($value === null) {
            return null;
        }

        if (is_array($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        $value = trim((string)$value);

        return $value === '' ? null : $value;
    }

    /**
     * Normaliza valores tipo catálogo/ENUM.
     */
    private function normalizarCatalogo($value) {
        $value = $this->limpiar($value);

        if ($value === null) {
            return null;
        }

        $value = strtolower($value);
        $value = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'ñ', ' '],
            ['a', 'e', 'i', 'o', 'u', 'n', '_'],
            $value
        );

        return $value;
    }

    /**
     * Revisa si existe una tabla.
     * Versión robusta para servidor: usa INFORMATION_SCHEMA.
     */
    private function tableExists(string $table): bool {
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
            return false;
        }

        if (isset($this->tableCache[$table])) {
            return $this->tableCache[$table];
        }

        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*)
                FROM INFORMATION_SCHEMA.TABLES
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = ?
            ");
            $stmt->execute([$table]);
            $exists = ((int)$stmt->fetchColumn()) > 0;
        } catch (\Throwable $e) {
            error_log("VUT ERROR tableExists({$table}): " . $e->getMessage());
            $exists = false;
        }

        $this->tableCache[$table] = $exists;
        return $exists;
    }

    /**
     * Revisa si existe una columna.
     * Versión robusta para servidor: usa INFORMATION_SCHEMA.
     */
    private function columnExists(string $table, string $column): bool {
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $table) || !preg_match('/^[a-zA-Z0-9_]+$/', $column)) {
            return false;
        }

        $key = $table . '.' . $column;

        if (isset($this->columnCache[$key])) {
            return $this->columnCache[$key];
        }

        if (!$this->tableExists($table)) {
            $this->columnCache[$key] = false;
            return false;
        }

        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*)
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = ?
                  AND COLUMN_NAME = ?
            ");
            $stmt->execute([$table, $column]);
            $exists = ((int)$stmt->fetchColumn()) > 0;
        } catch (\Throwable $e) {
            error_log("VUT ERROR columnExists({$table}.{$column}): " . $e->getMessage());
            $exists = false;
        }

        $this->columnCache[$key] = $exists;
        return $exists;
    }

    /**
     * Inserta un registro filtrando columnas inexistentes.
     * Ahora lanza errores reales para no ocultar fallas de BD.
     */
    private function insertarRegistro(string $table, array $data) {
        if (!$this->tableExists($table)) {
            $dbActual = 'DESCONOCIDA';

            try {
                $dbActual = (string)$this->db->query("SELECT DATABASE()")->fetchColumn();
            } catch (\Throwable $e) {
                $dbActual = 'NO SE PUDO LEER DATABASE()';
            }

            throw new \Exception("La tabla '{$table}' no existe o no es visible en la base '{$dbActual}'.");
        }

        $filtered = [];

        foreach ($data as $column => $value) {
            if ($this->columnExists($table, $column)) {
                $filtered[$column] = $value;
            } else {
                error_log("VUT WARN columna omitida: {$table}.{$column}");
            }
        }

        if (empty($filtered)) {
            $columnasReales = [];

            try {
                $stmt = $this->db->prepare("
                    SELECT COLUMN_NAME
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE TABLE_SCHEMA = DATABASE()
                      AND TABLE_NAME = ?
                    ORDER BY ORDINAL_POSITION
                ");
                $stmt->execute([$table]);
                $columnasReales = $stmt->fetchAll(\PDO::FETCH_COLUMN);
            } catch (\Throwable $e) {
                error_log("VUT ERROR leyendo columnas reales: " . $e->getMessage());
            }

            error_log("VUT DATA INTENTADA {$table}: " . print_r($data, true));
            error_log("VUT COLUMNAS REALES {$table}: " . print_r($columnasReales, true));

            throw new \Exception("No hay columnas válidas para insertar en '{$table}'. Revisa estructura de tabla.");
        }

        $columns = array_keys($filtered);
        $placeholders = array_fill(0, count($columns), '?');

        $sql = "INSERT INTO `{$table}` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $placeholders) . ")";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(array_values($filtered));
            return $this->db->lastInsertId();
        } catch (\Throwable $e) {
            error_log("VUT ERROR INSERT {$table}: " . $e->getMessage());
            error_log("VUT SQL: " . $sql);
            error_log("VUT DATA FILTRADA: " . print_r($filtered, true));

            throw new \Exception("Error insertando en '{$table}': " . $e->getMessage());
        }
    }

    /**
     * Ejecuta SELECT y devuelve una fila.
     */
    private function fetchOne(string $sql, array $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    /**
     * Ejecuta SELECT y devuelve varias filas.
     */
    private function fetchAll(string $sql, array $params = []): array {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return is_array($rows) ? $rows : [];
    }

    /**
     * Convierte llave técnica en etiqueta legible para detalles_tramite_especifico.
     */
    private function etiquetaCampo($key): string {
        $campo = strtoupper((string)$key);

        $campo = str_replace(
            [
                'MERCADO_',
                'PREDIO_',
                'PROPIETARIO_',
                'BIFURCACION_',
                'FOLIO_RECIBO_',
                'MONTO_RECIBO_',
                '_',
                '-'
            ],
            [
                '',
                '',
                'PROPIETARIO ',
                'BIFURCACIÓN ',
                'FOLIO RECIBO ',
                'MONTO RECIBO ',
                ' ',
                ' '
            ],
            $campo
        );

        return trim(preg_replace('/\s+/', ' ', $campo));
    }

    /**
     * Clasifica un campo dinámico para auditoría/reporte.
     */
    private function grupoCampo($key): string {
        $key = strtoupper((string)$key);

        if (strpos($key, 'BIFURCACION_') === 0) return 'bifurcacion';
        if (strpos($key, 'FOLIO_RECIBO_') === 0 || strpos($key, 'MONTO_RECIBO_') === 0) return 'recibos';
        if (strpos($key, 'PROPIETARIO_') === 0 || $key === 'CHECK_AGREGAR_PROPIETARIO') return 'propietario';
        if (strpos($key, 'MERCADO_') === 0) return 'mercado';
        if (strpos($key, 'PREDIO_') === 0) return 'predio';

        return 'especificos';
    }

    /**
     * Limpia monto.
     */
    private function normalizarMonto($value): float {
        $value = str_replace([',', '$', ' '], '', (string)$value);

        return is_numeric($value) ? (float)$value : 0.0;
    }

    /**
     * Valida folio de recibo real.
     */
    private function folioReciboValido($folio): bool {
        $folio = strtoupper(trim((string)$folio));

        return !in_array($folio, ['', 'N/A', 'NA', 'S/N', 'SN', 'SIN FOLIO'], true);
    }

    /**
     * Guarda una solicitud completa:
     * - solicitudes
     * - datos_interesado
     * - datos_representantes
     * - datos_propietario
     * - requisitos_presentados
     * - recibos_pago
     * - detalles_tramite_especifico
     */
    public function guardarSolicitudCompleta($data) {
        try {
            if (!$this->db->inTransaction()) {
                $this->db->beginTransaction();
            }

            if (!is_array($data)) {
                throw new \Exception("Payload inválido.");
            }

            $folio = "V-" . date('Ymd') . "-" . strtoupper(substr(uniqid(), -4));

            $payloadJson = json_encode(
                $data,
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );

            if ($payloadJson === false) {
                throw new \Exception("No se pudo codificar el payload JSON.");
            }

            $sol = $data['solicitud'] ?? [];
            $bif = $data['bifurcacion'] ?? [];

            if (!is_array($sol)) $sol = [];
            if (!is_array($bif)) $bif = [];

            /**
             * 1. SOLICITUD PRINCIPAL
             * Insert directo para evitar fallos silenciosos con insertarRegistro().
             */
            $materia = $this->limpiar($sol['materia'] ?? null);
            $nombreTramite = $this->limpiar($sol['tramite'] ?? null);
            $tipoPersona = $this->normalizarCatalogo($sol['tipo_persona'] ?? null);
            $tipoRepresentante = $this->normalizarCatalogo($sol['tipo_representante'] ?? null);

            // Normalización extra por si el front manda textos largos.
            $mapTipoPersona = [
                'persona_fisica' => 'fisica',
                'fisico' => 'fisica',
                'fisica' => 'fisica',
                'persona_moral' => 'moral',
                'moral' => 'moral'
            ];

            $mapTipoRepresentante = [
                'propietario' => 'propietario',
                'interesado' => 'propietario',
                'sin_representante' => 'propietario',
                'ninguno' => 'propietario',
                'legal' => 'legal',
                'representante_legal' => 'legal',
                'autorizada' => 'autorizada',
                'autorizado' => 'autorizada',
                'persona_autorizada' => 'autorizada',
                'representante' => 'representante'
            ];

            $tipoPersona = $mapTipoPersona[$tipoPersona] ?? $tipoPersona;
            $tipoRepresentante = $mapTipoRepresentante[$tipoRepresentante] ?? $tipoRepresentante;

            $bifurcacionClave = $this->limpiar($this->val($bif, ['clave', 'CLAVE']));
            $modalidad = $this->limpiar($this->val($bif, ['modalidad', 'MODALIDAD']));
            $modalidadTexto = $this->limpiar($this->val($bif, ['modalidad_texto', 'MODALIDAD_TEXTO']));
            $detalle = $this->limpiar($this->val($bif, ['detalle', 'DETALLE']));
            $detalleTexto = $this->limpiar($this->val($bif, ['detalle_texto', 'DETALLE_TEXTO']));

            if ($materia === null) {
                throw new \Exception("No se recibió la materia de la solicitud.");
            }

            if ($nombreTramite === null) {
                throw new \Exception("No se recibió el nombre del trámite.");
            }

            if ($tipoPersona === null) {
                throw new \Exception("No se recibió el tipo de persona.");
            }

            if ($tipoRepresentante === null) {
                $tipoRepresentante = 'propietario';
            }

            if (!in_array($tipoPersona, ['fisica', 'moral'], true)) {
                throw new \Exception("Tipo de persona inválido: " . $tipoPersona);
            }

            if (!in_array($tipoRepresentante, ['propietario', 'legal', 'autorizada', 'representante'], true)) {
                throw new \Exception("Tipo de representante inválido: " . $tipoRepresentante);
            }

            try {
                $sqlSolicitud = "
                    INSERT INTO solicitudes (
                        folio,
                        materia,
                        nombre_tramite,
                        tipo_persona,
                        tipo_representante,
                        payload,
                        bifurcacion_clave,
                        modalidad,
                        modalidad_texto,
                        detalle,
                        detalle_texto,
                        estatus,
                        estado_proceso,
                        etapa_actual,
                        prioridad,
                        fecha_estado
                    ) VALUES (
                        :folio,
                        :materia,
                        :nombre_tramite,
                        :tipo_persona,
                        :tipo_representante,
                        :payload,
                        :bifurcacion_clave,
                        :modalidad,
                        :modalidad_texto,
                        :detalle,
                        :detalle_texto,
                        :estatus,
                        :estado_proceso,
                        :etapa_actual,
                        :prioridad,
                        NOW()
                    )
                ";

                $stmtSolicitud = $this->db->prepare($sqlSolicitud);

                $stmtSolicitud->execute([
                    ':folio' => $folio,
                    ':materia' => $materia,
                    ':nombre_tramite' => $nombreTramite,
                    ':tipo_persona' => $tipoPersona,
                    ':tipo_representante' => $tipoRepresentante,
                    ':payload' => $payloadJson,
                    ':bifurcacion_clave' => $bifurcacionClave,
                    ':modalidad' => $modalidad,
                    ':modalidad_texto' => $modalidadTexto,
                    ':detalle' => $detalle,
                    ':detalle_texto' => $detalleTexto,
                    ':estatus' => 'finalizado',
                    ':estado_proceso' => 'INGRESADO',
                    ':etapa_actual' => 'RECEPCION_VUT',
                    ':prioridad' => 'NORMAL'
                ]);

                $id_solicitud = (int)$this->db->lastInsertId();

                if ($id_solicitud <= 0) {
                    throw new \Exception("El INSERT se ejecutó, pero no regresó id_solicitud.");
                }

            } catch (\Throwable $e) {
                error_log("VUT ERROR INSERT solicitudes: " . $e->getMessage());
                error_log("VUT PAYLOAD solicitud: " . print_r($sol, true));
                error_log("VUT PAYLOAD bifurcacion: " . print_r($bif, true));

                throw new \Exception("Error al registrar solicitud principal: " . $e->getMessage());
            }

            /**
             * 2. DATOS DEL INTERESADO
             */
            $this->insertarInteresado($id_solicitud, $data);

            /**
             * 3. REPRESENTANTE LEGAL
             */
            if (
                !empty($data['representante_legal']) &&
                is_array($data['representante_legal']) &&
                $this->tieneValoresUtiles($data['representante_legal'])
            ) {
                $this->insertarRepresentante(
                    $id_solicitud,
                    'legal',
                    $data['representante_legal']
                );
            }

            /**
             * 4. PERSONA AUTORIZADA
             */
            if (
                !empty($data['persona_autorizada']) &&
                is_array($data['persona_autorizada']) &&
                $this->tieneValoresUtiles($data['persona_autorizada'])
            ) {
                $this->insertarRepresentante(
                    $id_solicitud,
                    'autorizada',
                    $data['persona_autorizada']
                );
            }

            /**
             * 5. PROPIETARIO DEL PREDIO
             */
            $this->insertarPropietario($id_solicitud, $data);

            /**
             * 6. REQUISITOS PRESENTADOS
             */
            $this->insertarRequisitos($id_solicitud, $data);

            /**
             * 7. RECIBOS / PAGOS NORMALIZADOS
             */
            $this->insertarRecibosPago($id_solicitud, $data);

            /**
             * 8. DETALLES DINÁMICOS / AUDITORÍA
             */
            $this->insertarDetallesDinamicos($id_solicitud, $data);

            $this->db->commit();

            return [
                'success' => true,
                'folio' => $folio,
                'id' => $id_solicitud
            ];

        } catch (\Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            error_log("ERROR EN Ventanilla::guardarSolicitudCompleta(): " . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Inserta interesado físico/moral.
     */
    private function insertarInteresado($id_solicitud, array $data): void {
        $int = $data['interesado']['datos'] ?? [];
        $intDin = $data['interesado']['datos_dinamicos'] ?? [];

        if (!is_array($int)) $int = [];
        if (!is_array($intDin)) $intDin = [];

        $nombreORazon = $this->val($intDin, [
            'MORAL_RAZON_SOCIAL',
            'INTERESADO_NOMBRES',
            'INTERESADO_NOMBRE'
        ], $this->val($int, [
            'MORAL_RAZON_SOCIAL',
            'INTERESADO_NOMBRES',
            'INTERESADO_NOMBRE'
        ]));

        $apellidoPaterno = $this->val($intDin, [
            'INTERESADO_APE_PATERNO',
            'INTERESADO_PATERNO',
            'INTERESADO_APELLIDO_PATERNO'
        ], $this->val($int, [
            'INTERESADO_APE_PATERNO',
            'INTERESADO_PATERNO',
            'INTERESADO_APELLIDO_PATERNO'
        ]));

        $apellidoMaterno = $this->val($intDin, [
            'INTERESADO_APE_MATERNO',
            'INTERESADO_MATERNO',
            'INTERESADO_APELLIDO_MATERNO'
        ], $this->val($int, [
            'INTERESADO_APE_MATERNO',
            'INTERESADO_MATERNO',
            'INTERESADO_APELLIDO_MATERNO'
        ]));

        $rfc = $this->val($intDin, [
            'MORAL_RFC',
            'INTERESADO_RFC',
            'RFC'
        ], $this->val($int, [
            'MORAL_RFC',
            'INTERESADO_RFC',
            'RFC'
        ]));

        $telefono = $this->val($intDin, [
            'MORAL_TELEFONO',
            'INTERESADO_TELEFONO',
            'TELEFONO'
        ], $this->val($int, [
            'MORAL_TELEFONO',
            'INTERESADO_TELEFONO',
            'TELEFONO'
        ]));

        $email = $this->val($intDin, [
            'MORAL_EMAIL',
            'INTERESADO_EMAIL',
            'EMAIL'
        ], $this->val($int, [
            'MORAL_EMAIL',
            'INTERESADO_EMAIL',
            'EMAIL'
        ]));

        $this->insertarRegistro('datos_interesado', [
            'id_solicitud'            => $id_solicitud,
            'nombres_o_razon_social' => $this->limpiar($nombreORazon),
            'apellido_paterno'       => $this->limpiar($apellidoPaterno),
            'apellido_materno'       => $this->limpiar($apellidoMaterno),
            'rfc'                    => $this->limpiar($rfc),
            'telefono'               => $this->limpiar($telefono),
            'email'                  => $this->limpiar($email),
            'alcaldia'               => $this->limpiar($this->val($int, ['INTERESADO_ALCALDIA', 'ALCALDIA', 'SELECT_ALCALDIA'])),
            'colonia'                => $this->limpiar($this->val($int, ['INTERESADO_COLONIA', 'COLONIA_NOMBRE', 'SELECT_COLONIA', 'COLONIA'])),
            'calle'                  => $this->limpiar($this->val($int, ['INTERESADO_CALLE', 'CALLE'])),
            'num_exterior'           => $this->limpiar($this->val($int, ['INTERESADO_NUMERO_EXTERIOR', 'NUMERO_EXTERIOR', 'NUM_EXT', 'NO_EXTERIOR'])),
            'cp'                     => $this->limpiar($this->val($int, ['INTERESADO_CP', 'CP', 'CODIGO_POSTAL'])),
            'no_escritura'           => $this->limpiar($this->val($intDin, ['MORAL_NO_ESCRITURA', 'NO_ESCRITURA'])),
            'no_notario'             => $this->limpiar($this->val($intDin, ['MORAL_NO_NOTARIO', 'NO_NOTARIO'])),
            'nombre_notario'         => $this->limpiar($this->val($intDin, ['MORAL_NOMBRE_NOTARIO', 'NOMBRE_NOTARIO']))
        ]);
    }

    /**
     * Inserta representante legal o persona autorizada.
     */
    private function insertarRepresentante($id_solicitud, $tipo, array $repData): void {
        if ($tipo === 'legal') {
            $nombres = $this->val($repData, [
                'LEG_NOMBRES',
                'LEGAL_NOMBRES',
                'REPRESENTANTE_LEGAL_NOMBRES'
            ]);

            $paterno = $this->val($repData, [
                'LEG_PATERNO',
                'LEGAL_PATERNO',
                'LEGAL_APELLIDO_PATERNO',
                'REPRESENTANTE_LEGAL_PATERNO'
            ]);

            $materno = $this->val($repData, [
                'LEG_MATERNO',
                'LEGAL_MATERNO',
                'LEGAL_APELLIDO_MATERNO',
                'REPRESENTANTE_LEGAL_MATERNO'
            ]);

            $rfc = $this->val($repData, [
                'LEG_RFC',
                'LEGAL_RFC',
                'REPRESENTANTE_LEGAL_RFC'
            ]);

            $telefono = $this->val($repData, [
                'LEG_TELEFONO',
                'LEGAL_TELEFONO',
                'REPRESENTANTE_LEGAL_TELEFONO'
            ]);

            $email = $this->val($repData, [
                'LEG_EMAIL',
                'LEGAL_EMAIL',
                'REPRESENTANTE_LEGAL_EMAIL'
            ]);

            $docPersonalidad = $this->val($repData, [
                'LEG_DOC_PERSONALIDAD',
                'LEGAL_DOC_PERSONALIDAD',
                'LEGAL_DOCUMENTO_PERSONALIDAD',
                'REPRESENTANTE_LEGAL_DOC_PERSONALIDAD'
            ]);

            $alcaldia = $this->val($repData, [
                'LEG_DOM_DEL',
                'LEG_ALCALDIA',
                'LEGAL_ALCALDIA',
                'LEGAL_DOMICILIO_DELEGACION'
            ]);

            $colonia = $this->val($repData, [
                'LEG_DOM_COLONIA',
                'LEG_COLONIA',
                'LEGAL_COLONIA',
                'LEGAL_DOMICILIO_COLONIA',
                'LEG_COLONIA_NOMBRE'
            ]);

            $calle = $this->val($repData, [
                'LEG_DOM_CALLE',
                'LEGAL_CALLE',
                'LEGAL_DOMICILIO_CALLE',
                'LEG_DIRECCION_SIMPLE'
            ]);

            $numExterior = $this->val($repData, [
                'LEG_DOM_NUM_EXT',
                'LEGAL_NUMERO_EXTERIOR',
                'LEGAL_DOMICILIO_NUMERO_EXTERIOR'
            ]);

            $cp = $this->val($repData, [
                'LEG_DOM_CP',
                'LEGAL_CP',
                'LEGAL_DOMICILIO_CP',
                'LEGAL_CODIGO_POSTAL'
            ]);

        } else {
            $nombres = $this->val($repData, [
                'AUT_NOMBRES',
                'AUTORIZADA_NOMBRES',
                'PERSONA_AUTORIZADA_NOMBRES'
            ]);

            $paterno = $this->val($repData, [
                'AUT_PATERNO',
                'AUTORIZADA_PATERNO',
                'AUTORIZADA_APELLIDO_PATERNO',
                'PERSONA_AUTORIZADA_PATERNO'
            ]);

            $materno = $this->val($repData, [
                'AUT_MATERNO',
                'AUTORIZADA_MATERNO',
                'AUTORIZADA_APELLIDO_MATERNO',
                'PERSONA_AUTORIZADA_MATERNO'
            ]);

            $rfc = $this->val($repData, [
                'AUT_RFC',
                'AUTORIZADA_RFC',
                'PERSONA_AUTORIZADA_RFC'
            ]);

            $telefono = $this->val($repData, [
                'AUT_TELEFONO',
                'AUTORIZADA_TELEFONO',
                'PERSONA_AUTORIZADA_TELEFONO'
            ]);

            $email = $this->val($repData, [
                'AUT_EMAIL',
                'AUTORIZADA_EMAIL',
                'PERSONA_AUTORIZADA_EMAIL'
            ]);

            $docPersonalidad = $this->val($repData, [
                'AUT_DOC_PERSONALIDAD',
                'AUTORIZADA_DOC_PERSONALIDAD',
                'AUTORIZADA_DOCUMENTO_PERSONALIDAD',
                'PERSONA_AUTORIZADA_DOC_PERSONALIDAD'
            ]);

            $alcaldia = $this->val($repData, [
                'AUT_DOM_DEL',
                'AUTORIZADA_DOM_DEL',
                'AUTORIZADA_DOMICILIO_DELEGACION',
                'AUTORIZADA_ALCALDIA'
            ]);

            $colonia = $this->val($repData, [
                'AUT_DOM_COLONIA',
                'AUTORIZADA_DOM_COLONIA',
                'AUTORIZADA_DOMICILIO_COLONIA',
                'AUTORIZADA_COLONIA'
            ]);

            $calle = $this->val($repData, [
                'AUT_DOM_CALLE',
                'AUTORIZADA_DOM_CALLE',
                'AUTORIZADA_DOMICILIO_CALLE',
                'AUTORIZADA_CALLE',
                'AUT_DOM_CALLE_MANUAL',
                'AUTORIZADA_DOMICILIO_CALLE_MANUAL'
            ]);

            $numExterior = $this->val($repData, [
                'AUT_DOM_NUM_EXT',
                'AUTORIZADA_DOM_NUM_EXT',
                'AUTORIZADA_DOMICILIO_NUMERO_EXTERIOR',
                'AUTORIZADA_NUMERO_EXTERIOR'
            ]);

            $cp = $this->val($repData, [
                'AUT_DOM_CP',
                'AUTORIZADA_DOM_CP',
                'AUTORIZADA_DOMICILIO_CP',
                'AUTORIZADA_CODIGO_POSTAL'
            ]);
        }

        $this->insertarRegistro('datos_representantes', [
            'id_solicitud'               => $id_solicitud,
            'tipo_rep'                   => $tipo,
            'nombres'                    => $this->limpiar($nombres),
            'apellido_paterno'           => $this->limpiar($paterno),
            'apellido_materno'           => $this->limpiar($materno),
            'rfc'                        => $this->limpiar($rfc),
            'telefono'                   => $this->limpiar($telefono),
            'email'                      => $this->limpiar($email),
            'doc_acredita_personalidad'  => $this->limpiar($docPersonalidad),
            'alcaldia'                   => $this->limpiar($alcaldia),
            'colonia'                    => $this->limpiar($colonia),
            'calle'                      => $this->limpiar($calle),
            'num_exterior'               => $this->limpiar($numExterior),
            'cp'                         => $this->limpiar($cp)
        ]);
    }

    /**
     * Inserta propietario del predio en tabla normalizada.
     */
    private function insertarPropietario($id_solicitud, array $data): void {
        if (!$this->tableExists('datos_propietario')) {
            return;
        }

        $esp = $data['especificos'] ?? [];

        if (!is_array($esp)) {
            return;
        }

        $nombres = $this->val($esp, ['PROPIETARIO_NOMBRES', 'propietario_nombres']);
        $paterno = $this->val($esp, ['PROPIETARIO_APE_PATERNO', 'propietario_ape_paterno']);
        $materno = $this->val($esp, ['PROPIETARIO_APE_MATERNO', 'propietario_ape_materno']);
        $rfc = $this->val($esp, ['PROPIETARIO_RFC', 'propietario_rfc']);
        $telefono = $this->val($esp, ['PROPIETARIO_TELEFONO', 'propietario_telefono']);
        $email = $this->val($esp, ['PROPIETARIO_EMAIL', 'propietario_email']);

        $tieneDatos = (
            $this->limpiar($nombres) !== null ||
            $this->limpiar($paterno) !== null ||
            $this->limpiar($materno) !== null ||
            $this->limpiar($rfc) !== null ||
            $this->limpiar($telefono) !== null ||
            $this->limpiar($email) !== null
        );

        if (!$tieneDatos) {
            return;
        }

        $this->insertarRegistro('datos_propietario', [
            'id_solicitud'      => $id_solicitud,
            'nombres'           => $this->limpiar($nombres),
            'apellido_paterno'  => $this->limpiar($paterno),
            'apellido_materno'  => $this->limpiar($materno),
            'rfc'               => $this->limpiar($rfc),
            'telefono'          => $this->limpiar($telefono),
            'email'             => $this->limpiar($email)
        ]);
    }

    /**
     * Inserta requisitos.
     */
    private function insertarRequisitos($id_solicitud, array $data): void {
        if (empty($data['requisitos_validados']) || !is_array($data['requisitos_validados'])) {
            return;
        }

        foreach ($data['requisitos_validados'] as $nombre_doc) {
            $nombre_doc = $this->limpiar($nombre_doc);

            if ($nombre_doc === null) {
                continue;
            }

            $this->insertarRegistro('requisitos_presentados', [
                'id_solicitud'     => $id_solicitud,
                'nombre_documento' => $nombre_doc,
                'validado'         => 1
            ]);
        }
    }

    /**
     * Inserta recibos reales en tabla normalizada.
     */
    private function insertarRecibosPago($id_solicitud, array $data): void {
        if (!$this->tableExists('recibos_pago')) {
            return;
        }

        $recibos = $data['recibos'] ?? [];

        if (!is_array($recibos)) {
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            $folio = $this->val($recibos, [
                "FOLIO_RECIBO_{$i}",
                "folio_recibo_{$i}"
            ], '');

            $monto = $this->val($recibos, [
                "MONTO_RECIBO_{$i}",
                "monto_recibo_{$i}"
            ], '');

            $folio = strtoupper(trim((string)$folio));
            $montoNum = $this->normalizarMonto($monto);

            $tieneFolio = $this->folioReciboValido($folio);
            $tieneMonto = $montoNum > 0;

            if (!$tieneFolio && !$tieneMonto) {
                continue;
            }

            $this->insertarRegistro('recibos_pago', [
                'id_solicitud'  => $id_solicitud,
                'numero_recibo' => $i,
                'folio_recibo'  => $tieneFolio ? $folio : null,
                'monto'         => $tieneMonto ? $montoNum : 0
            ]);
        }
    }

    /**
     * Inserta detalles dinámicos con llave técnica, etiqueta, valor, grupo y orden.
     */
    private function insertarDetallesDinamicos($id_solicitud, array $data): void {
        $detalles = [];

        if (!empty($data['especificos']) && is_array($data['especificos'])) {
            foreach ($data['especificos'] as $key => $value) {
                $detalles[$key] = $value;
            }
        }

        if (!empty($data['recibos']) && is_array($data['recibos'])) {
            foreach ($data['recibos'] as $key => $value) {
                $detalles[$key] = $value;
            }
        }

        if (!empty($data['bifurcacion']) && is_array($data['bifurcacion'])) {
            foreach ($data['bifurcacion'] as $key => $value) {
                $detalles['BIFURCACION_' . strtoupper((string)$key)] = $value;
            }
        }

        $orden = 1;

        foreach ($detalles as $id_html => $valor) {
            $valor = $this->limpiar($valor);

            if ($valor === null) {
                continue;
            }

            $campoKey = strtoupper((string)$id_html);

            $this->insertarRegistro('detalles_tramite_especifico', [
                'id_solicitud' => $id_solicitud,
                'campo_key'    => $campoKey,
                'campo_nombre' => $this->etiquetaCampo($campoKey),
                'campo_valor'  => $valor,
                'grupo'        => $this->grupoCampo($campoKey),
                'orden'        => $orden
            ]);

            $orden++;
        }
    }

    /**
     * Recupera todo para PDF.
     *
     * Prioridad:
     * 1. payload original, porque conserva todo lo dinámico.
     * 2. tablas normalizadas como respaldo si el payload no trae alguna sección.
     */
    public function obtenerTodoParaPDF($id_solicitud) {
        $stmt = $this->db->prepare("
            SELECT *
            FROM solicitudes
            WHERE id_solicitud = ?
            LIMIT 1
        ");

        $stmt->execute([$id_solicitud]);
        $solicitud = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$solicitud) {
            return null;
        }

        $payloadRaw = $solicitud['payload'] ?? '{}';
        $payload = json_decode($payloadRaw, true);

        if (!is_array($payload)) {
            $payload = [];
        }

        /**
         * Solicitud.
         */
        if (empty($payload['solicitud']) || !is_array($payload['solicitud'])) {
            $payload['solicitud'] = [
                'materia'             => $solicitud['materia'] ?? '',
                'tramite'             => $solicitud['nombre_tramite'] ?? '',
                'tipo_persona'        => $solicitud['tipo_persona'] ?? '',
                'tipo_representante'  => $solicitud['tipo_representante'] ?? ''
            ];
        }

        /**
         * Bifurcación desde columnas consultables.
         */
        if (empty($payload['bifurcacion']) || !is_array($payload['bifurcacion'])) {
            $payload['bifurcacion'] = [
                'clave'            => $solicitud['bifurcacion_clave'] ?? '',
                'modalidad'        => $solicitud['modalidad'] ?? '',
                'modalidad_texto'  => $solicitud['modalidad_texto'] ?? '',
                'detalle'          => $solicitud['detalle'] ?? '',
                'detalle_texto'    => $solicitud['detalle_texto'] ?? ''
            ];
        }

        /**
         * Interesado desde tabla normalizada si hiciera falta.
         */
        if (empty($payload['interesado']) || !is_array($payload['interesado'])) {
            $interesado = $this->fetchOne("
                SELECT *
                FROM datos_interesado
                WHERE id_solicitud = ?
                LIMIT 1
            ", [$id_solicitud]);

            if ($interesado) {
                $payload['interesado'] = [
                    'datos' => [
                        'INTERESADO_TELEFONO' => $interesado['telefono'] ?? '',
                        'INTERESADO_EMAIL' => $interesado['email'] ?? '',
                        'INTERESADO_ALCALDIA' => $interesado['alcaldia'] ?? '',
                        'INTERESADO_COLONIA' => $interesado['colonia'] ?? '',
                        'INTERESADO_CALLE' => $interesado['calle'] ?? '',
                        'INTERESADO_NUMERO_EXTERIOR' => $interesado['num_exterior'] ?? '',
                        'INTERESADO_CP' => $interesado['cp'] ?? ''
                    ],
                    'datos_dinamicos' => [
                        'INTERESADO_NOMBRES' => $interesado['nombres_o_razon_social'] ?? '',
                        'INTERESADO_APE_PATERNO' => $interesado['apellido_paterno'] ?? '',
                        'INTERESADO_APE_MATERNO' => $interesado['apellido_materno'] ?? '',
                        'INTERESADO_RFC' => $interesado['rfc'] ?? '',
                        'MORAL_NO_ESCRITURA' => $interesado['no_escritura'] ?? '',
                        'MORAL_NO_NOTARIO' => $interesado['no_notario'] ?? '',
                        'MORAL_NOMBRE_NOTARIO' => $interesado['nombre_notario'] ?? ''
                    ]
                ];
            }
        }

        /**
         * Representantes desde tabla normalizada.
         */
        if (
            (empty($payload['representante_legal']) || !is_array($payload['representante_legal'])) ||
            (empty($payload['persona_autorizada']) || !is_array($payload['persona_autorizada']))
        ) {
            $representantes = $this->fetchAll("
                SELECT *
                FROM datos_representantes
                WHERE id_solicitud = ?
            ", [$id_solicitud]);

            foreach ($representantes as $rep) {
                if (($rep['tipo_rep'] ?? '') === 'legal' && (empty($payload['representante_legal']) || !is_array($payload['representante_legal']))) {
                    $payload['representante_legal'] = [
                        'LEG_NOMBRES' => $rep['nombres'] ?? '',
                        'LEG_PATERNO' => $rep['apellido_paterno'] ?? '',
                        'LEG_MATERNO' => $rep['apellido_materno'] ?? '',
                        'LEG_RFC' => $rep['rfc'] ?? '',
                        'LEG_TELEFONO' => $rep['telefono'] ?? '',
                        'LEG_EMAIL' => $rep['email'] ?? '',
                        'LEG_DOC_PERSONALIDAD' => $rep['doc_acredita_personalidad'] ?? '',
                        'LEG_DOM_DEL' => $rep['alcaldia'] ?? '',
                        'LEG_DOM_COLONIA' => $rep['colonia'] ?? '',
                        'LEG_DOM_CALLE' => $rep['calle'] ?? '',
                        'LEG_DOM_NUM_EXT' => $rep['num_exterior'] ?? '',
                        'LEG_DOM_CP' => $rep['cp'] ?? ''
                    ];
                }

                if (($rep['tipo_rep'] ?? '') === 'autorizada' && (empty($payload['persona_autorizada']) || !is_array($payload['persona_autorizada']))) {
                    $payload['persona_autorizada'] = [
                        'AUT_NOMBRES' => $rep['nombres'] ?? '',
                        'AUT_PATERNO' => $rep['apellido_paterno'] ?? '',
                        'AUT_MATERNO' => $rep['apellido_materno'] ?? '',
                        'AUT_RFC' => $rep['rfc'] ?? '',
                        'AUT_TELEFONO' => $rep['telefono'] ?? '',
                        'AUT_EMAIL' => $rep['email'] ?? '',
                        'AUT_DOC_PERSONALIDAD' => $rep['doc_acredita_personalidad'] ?? '',
                        'AUT_DOM_DEL' => $rep['alcaldia'] ?? '',
                        'AUT_DOM_COLONIA' => $rep['colonia'] ?? '',
                        'AUT_DOM_CALLE' => $rep['calle'] ?? '',
                        'AUT_DOM_NUM_EXT' => $rep['num_exterior'] ?? '',
                        'AUT_DOM_CP' => $rep['cp'] ?? ''
                    ];
                }
            }
        }

        /**
         * Requisitos desde tabla normalizada.
         */
        if (empty($payload['requisitos_validados']) || !is_array($payload['requisitos_validados'])) {
            $rowsReq = $this->fetchAll("
                SELECT nombre_documento
                FROM requisitos_presentados
                WHERE id_solicitud = ?
                ORDER BY id_requisito_solicitud ASC
            ", [$id_solicitud]);

            $payload['requisitos_validados'] = [];

            foreach ($rowsReq as $row) {
                if (!empty($row['nombre_documento'])) {
                    $payload['requisitos_validados'][] = $row['nombre_documento'];
                }
            }
        }

        /**
         * Detalles dinámicos desde EAV si hiciera falta.
         */
        if (empty($payload['especificos']) || !is_array($payload['especificos'])) {
            $payload['especificos'] = [];

            if ($this->tableExists('detalles_tramite_especifico')) {
                if ($this->columnExists('detalles_tramite_especifico', 'campo_key')) {
                    $rowsDet = $this->fetchAll("
                        SELECT campo_key, campo_valor
                        FROM detalles_tramite_especifico
                        WHERE id_solicitud = ?
                        ORDER BY COALESCE(orden, 9999), id_detalle ASC
                    ", [$id_solicitud]);

                    foreach ($rowsDet as $row) {
                        $key = $row['campo_key'] ?? '';

                        if ($key !== '' && strpos($key, 'BIFURCACION_') !== 0 && strpos($key, 'FOLIO_RECIBO_') !== 0 && strpos($key, 'MONTO_RECIBO_') !== 0) {
                            $payload['especificos'][$key] = $row['campo_valor'] ?? '';
                        }
                    }
                }
            }
        }

        /**
         * Propietario desde tabla normalizada.
         */
        if ($this->tableExists('datos_propietario')) {
            $prop = $this->fetchOne("
                SELECT *
                FROM datos_propietario
                WHERE id_solicitud = ?
                LIMIT 1
            ", [$id_solicitud]);

            if ($prop) {
                if (empty($payload['especificos']) || !is_array($payload['especificos'])) {
                    $payload['especificos'] = [];
                }

                $payload['especificos']['CHECK_AGREGAR_PROPIETARIO'] = 'SÍ';
                $payload['especificos']['PROPIETARIO_NOMBRES'] = $prop['nombres'] ?? '';
                $payload['especificos']['PROPIETARIO_APE_PATERNO'] = $prop['apellido_paterno'] ?? '';
                $payload['especificos']['PROPIETARIO_APE_MATERNO'] = $prop['apellido_materno'] ?? '';
                $payload['especificos']['PROPIETARIO_RFC'] = $prop['rfc'] ?? '';
                $payload['especificos']['PROPIETARIO_TELEFONO'] = $prop['telefono'] ?? '';
                $payload['especificos']['PROPIETARIO_EMAIL'] = $prop['email'] ?? '';
            }
        }

        /**
         * Recibos desde tabla normalizada.
         */
        if (empty($payload['recibos']) || !is_array($payload['recibos'])) {
            $payload['recibos'] = [];

            if ($this->tableExists('recibos_pago')) {
                $rowsRec = $this->fetchAll("
                    SELECT *
                    FROM recibos_pago
                    WHERE id_solicitud = ?
                    ORDER BY COALESCE(numero_recibo, 9999), id_recibo ASC
                ", [$id_solicitud]);

                foreach ($rowsRec as $row) {
                    $num = (int)($row['numero_recibo'] ?? 0);

                    if ($num <= 0) {
                        $num = count($payload['recibos']) + 1;
                    }

                    if (!empty($row['folio_recibo'])) {
                        $payload['recibos']["FOLIO_RECIBO_{$num}"] = $row['folio_recibo'];
                    }

                    if (isset($row['monto']) && (float)$row['monto'] > 0) {
                        $payload['recibos']["MONTO_RECIBO_{$num}"] = number_format((float)$row['monto'], 2, '.', '');
                    }
                }
            }
        }

        $solicitud['_payload_raw'] = $payloadRaw;

        return array_merge($solicitud, $payload);
    }


    /**
     * Estados oficiales del flujo VUT.
     */
    public function estadosProcesoPermitidos(): array {
        return [
            'NUEVO',
            'INGRESADO',
            'EN_VALIDACION',
            'PREVENIDO',
            'EN_REVISION',
            'APROBADO',
            'RECHAZADO',
            'TERMINADO',
            'CANCELADO'
        ];
    }

    /**
     * Etiquetas amigables para estados.
     */
    public function etiquetasEstadosProceso(): array {
        return [
            'NUEVO'         => 'Nuevo',
            'INGRESADO'     => 'Ingresado',
            'EN_VALIDACION' => 'En validación',
            'PREVENIDO'     => 'Prevenido',
            'EN_REVISION'   => 'En revisión',
            'APROBADO'      => 'Aprobado',
            'RECHAZADO'     => 'Rechazado',
            'TERMINADO'     => 'Terminado',
            'CANCELADO'     => 'Cancelado'
        ];
    }

    /**
     * Expresión SQL segura para fecha principal de solicitudes.
     */
    private function dashboardFechaExpr(): string {
        if ($this->columnExists('solicitudes', 'fecha_ingreso')) {
            return 's.fecha_ingreso';
        }

        if ($this->columnExists('solicitudes', 'created_at')) {
            return 's.created_at';
        }

        if ($this->columnExists('solicitudes', 'fecha_creacion')) {
            return 's.fecha_creacion';
        }

        return 'NULL';
    }

    /**
     * Expresión SQL segura para estado de proceso.
     */
    private function dashboardEstadoExpr(): string {
        if ($this->columnExists('solicitudes', 'estado_proceso')) {
            return "COALESCE(NULLIF(s.estado_proceso, ''), 'INGRESADO')";
        }

        if ($this->columnExists('solicitudes', 'estatus')) {
            return "CASE\n                WHEN UPPER(COALESCE(s.estatus, '')) IN ('APROBADO','RECHAZADO','TERMINADO','CANCELADO') THEN UPPER(s.estatus)\n                WHEN UPPER(COALESCE(s.estatus, '')) IN ('FINALIZADO','FINALIZADA') THEN 'INGRESADO'\n                ELSE 'NUEVO'\n            END";
        }

        return "'INGRESADO'";
    }

    /**
     * Expresión SQL segura de titular/interesado.
     */
    private function dashboardTitularExpr(bool $joinInteresado): string {
        if ($joinInteresado) {
            return "TRIM(CONCAT_WS(' ', di.nombres_o_razon_social, di.apellido_paterno, di.apellido_materno))";
        }

        return "''";
    }

    /**
     * Construye WHERE para dashboard.
     */
    private function dashboardWhere(array $filtros, array &$params, bool $incluirEstado = true): string {
        $where = [];
        $params = [];
        $joinInteresado = $this->tableExists('datos_interesado');
        $fechaExpr = $this->dashboardFechaExpr();
        $estadoExpr = $this->dashboardEstadoExpr();

        $q = trim((string)($filtros['q'] ?? $filtros['search'] ?? ''));
        if ($q !== '') {
            $searchParts = [];

            if ($this->columnExists('solicitudes', 'folio')) {
                $searchParts[] = 's.folio LIKE ?';
                $params[] = '%' . $q . '%';
            }

            if ($this->columnExists('solicitudes', 'nombre_tramite')) {
                $searchParts[] = 's.nombre_tramite LIKE ?';
                $params[] = '%' . $q . '%';
            }

            if ($this->columnExists('solicitudes', 'materia')) {
                $searchParts[] = 's.materia LIKE ?';
                $params[] = '%' . $q . '%';
            }

            if ($this->columnExists('solicitudes', 'modalidad_texto')) {
                $searchParts[] = 's.modalidad_texto LIKE ?';
                $params[] = '%' . $q . '%';
            }

            if ($joinInteresado) {
                $searchParts[] = 'di.nombres_o_razon_social LIKE ?';
                $params[] = '%' . $q . '%';
                $searchParts[] = 'di.apellido_paterno LIKE ?';
                $params[] = '%' . $q . '%';
                $searchParts[] = 'di.apellido_materno LIKE ?';
                $params[] = '%' . $q . '%';
                $searchParts[] = 'di.rfc LIKE ?';
                $params[] = '%' . $q . '%';
            }

            if (!empty($searchParts)) {
                $where[] = '(' . implode(' OR ', $searchParts) . ')';
            }
        }

        $materia = trim((string)($filtros['materia'] ?? ''));
        if ($materia !== '' && $this->columnExists('solicitudes', 'materia')) {
            $where[] = 's.materia = ?';
            $params[] = $materia;
        }

        $tramite = trim((string)($filtros['tramite'] ?? ''));
        if ($tramite !== '' && $this->columnExists('solicitudes', 'nombre_tramite')) {
            $where[] = 's.nombre_tramite = ?';
            $params[] = $tramite;
        }

        $estado = strtoupper(trim((string)($filtros['estado'] ?? '')));
        if ($incluirEstado && $estado !== '') {
            $where[] = "{$estadoExpr} = ?";
            $params[] = $estado;
        }

        $fechaInicio = trim((string)($filtros['fecha_inicio'] ?? ''));
        if ($fechaInicio !== '' && $fechaExpr !== 'NULL') {
            $where[] = "DATE({$fechaExpr}) >= ?";
            $params[] = $fechaInicio;
        }

        $fechaFin = trim((string)($filtros['fecha_fin'] ?? ''));
        if ($fechaFin !== '' && $fechaExpr !== 'NULL') {
            $where[] = "DATE({$fechaExpr}) <= ?";
            $params[] = $fechaFin;
        }

        return empty($where) ? '' : ' WHERE ' . implode(' AND ', $where);
    }

    /**
     * Devuelve KPIs del dashboard VUT.
     */
    public function obtenerResumenDashboard(array $filtros = []): array {
        $params = [];
        $where = $this->dashboardWhere($filtros, $params, false);
        $join = $this->tableExists('datos_interesado') ? ' LEFT JOIN datos_interesado di ON di.id_solicitud = s.id_solicitud ' : '';
        $estadoExpr = $this->dashboardEstadoExpr();

        $sql = "SELECT {$estadoExpr} AS estado, COUNT(*) AS total\n                FROM solicitudes s\n                {$join}\n                {$where}\n                GROUP BY {$estadoExpr}";

        $rows = $this->fetchAll($sql, $params);

        $resumen = [
            'TOTAL'         => 0,
            'NUEVO'         => 0,
            'INGRESADO'     => 0,
            'EN_VALIDACION' => 0,
            'PREVENIDO'     => 0,
            'EN_REVISION'   => 0,
            'APROBADO'      => 0,
            'RECHAZADO'     => 0,
            'TERMINADO'     => 0,
            'CANCELADO'     => 0
        ];

        foreach ($rows as $row) {
            $estado = strtoupper((string)($row['estado'] ?? 'NUEVO'));
            $total = (int)($row['total'] ?? 0);

            if (!array_key_exists($estado, $resumen)) {
                $resumen[$estado] = 0;
            }

            $resumen[$estado] += $total;
            $resumen['TOTAL'] += $total;
        }

        return $resumen;
    }

    /**
     * Lista solicitudes para la bandeja del dashboard.
     */
    public function listarSolicitudesDashboard(array $filtros = []): array {
        $params = [];
        $where = $this->dashboardWhere($filtros, $params, true);
        $joinInteresado = $this->tableExists('datos_interesado');
        $join = $joinInteresado ? ' LEFT JOIN datos_interesado di ON di.id_solicitud = s.id_solicitud ' : '';

        $fechaExpr = $this->dashboardFechaExpr();
        $estadoExpr = $this->dashboardEstadoExpr();
        $titularExpr = $this->dashboardTitularExpr($joinInteresado);

        $modalidadExpr = $this->columnExists('solicitudes', 'modalidad_texto') ? 's.modalidad_texto' : "''";
        $detalleExpr = $this->columnExists('solicitudes', 'detalle_texto') ? 's.detalle_texto' : "''";
        $bifExpr = $this->columnExists('solicitudes', 'bifurcacion_clave') ? 's.bifurcacion_clave' : "''";
        $estatusExpr = $this->columnExists('solicitudes', 'estatus') ? 's.estatus' : "''";
        $prioridadExpr = $this->columnExists('solicitudes', 'prioridad') ? 's.prioridad' : "'NORMAL'";
        $fechaEstadoExpr = $this->columnExists('solicitudes', 'fecha_estado') ? 's.fecha_estado' : $fechaExpr;

        $rfcExpr = $joinInteresado ? 'di.rfc' : "''";
        $telefonoExpr = $joinInteresado ? 'di.telefono' : "''";
        $emailExpr = $joinInteresado ? 'di.email' : "''";

        $limit = (int)($filtros['limit'] ?? 25);
        $offset = (int)($filtros['offset'] ?? 0);

        if ($limit <= 0 || $limit > 200) {
            $limit = 25;
        }

        if ($offset < 0) {
            $offset = 0;
        }

        $countSql = "SELECT COUNT(*) AS total FROM solicitudes s {$join} {$where}";
        $totalRow = $this->fetchOne($countSql, $params);
        $total = (int)($totalRow['total'] ?? 0);

        $orderExpr = $fechaExpr !== 'NULL' ? $fechaExpr : 's.id_solicitud';

        $sql = "SELECT\n                    s.id_solicitud,\n                    s.folio,\n                    {$fechaExpr} AS fecha_ingreso,\n                    s.materia,\n                    s.nombre_tramite,\n                    {$modalidadExpr} AS modalidad_texto,\n                    {$detalleExpr} AS detalle_texto,\n                    {$bifExpr} AS bifurcacion_clave,\n                    {$estadoExpr} AS estado_proceso,\n                    {$estatusExpr} AS estatus,\n                    {$prioridadExpr} AS prioridad,\n                    {$fechaEstadoExpr} AS fecha_estado,\n                    {$titularExpr} AS titular,\n                    {$rfcExpr} AS rfc,\n                    {$telefonoExpr} AS telefono,\n                    {$emailExpr} AS email\n                FROM solicitudes s\n                {$join}\n                {$where}\n                ORDER BY {$orderExpr} DESC, s.id_solicitud DESC\n                LIMIT {$limit} OFFSET {$offset}";

        return [
            'total' => $total,
            'rows'  => $this->fetchAll($sql, $params)
        ];
    }

    /**
     * Materias disponibles para filtros.
     */
    public function obtenerMateriasDashboard(): array {
        if (!$this->columnExists('solicitudes', 'materia')) {
            return [];
        }

        $rows = $this->fetchAll("SELECT DISTINCT materia FROM solicitudes WHERE materia IS NOT NULL AND materia <> '' ORDER BY materia ASC");
        return array_values(array_filter(array_map(fn($r) => $r['materia'] ?? '', $rows)));
    }

    /**
     * Trámites disponibles para filtros.
     */
    public function obtenerTramitesDashboard(): array {
        if (!$this->columnExists('solicitudes', 'nombre_tramite')) {
            return [];
        }

        $rows = $this->fetchAll("SELECT DISTINCT nombre_tramite FROM solicitudes WHERE nombre_tramite IS NOT NULL AND nombre_tramite <> '' ORDER BY nombre_tramite ASC");
        return array_values(array_filter(array_map(fn($r) => $r['nombre_tramite'] ?? '', $rows)));
    }

    /**
     * Cambia estado de una solicitud y registra historial si existe la tabla.
     */
    public function cambiarEstadoSolicitud(
        int $idSolicitud,
        string $estadoNuevo,
        string $observaciones = '',
        ?int $usuarioId = null,
        ?string $usuarioNombre = null
    ): array {
        $estadoNuevo = strtoupper(trim($estadoNuevo));

        if (!in_array($estadoNuevo, $this->estadosProcesoPermitidos(), true)) {
            return [
                'success' => false,
                'error' => 'Estado no permitido.'
            ];
        }

        $actual = $this->fetchOne("SELECT * FROM solicitudes WHERE id_solicitud = ? LIMIT 1", [$idSolicitud]);

        if (!$actual) {
            return [
                'success' => false,
                'error' => 'La solicitud no existe.'
            ];
        }

        $estadoAnterior = $this->columnExists('solicitudes', 'estado_proceso')
            ? (string)($actual['estado_proceso'] ?? 'NUEVO')
            : (string)($actual['estatus'] ?? 'NUEVO');

        try {
            if (!$this->db->inTransaction()) {
                $this->db->beginTransaction();
                $ownTransaction = true;
            } else {
                $ownTransaction = false;
            }

            $sets = [];
            $params = [];

            if ($this->columnExists('solicitudes', 'estado_proceso')) {
                $sets[] = 'estado_proceso = ?';
                $params[] = $estadoNuevo;
            }

            if ($this->columnExists('solicitudes', 'etapa_actual')) {
                $sets[] = 'etapa_actual = ?';
                $params[] = $estadoNuevo;
            }

            if ($this->columnExists('solicitudes', 'estado_observaciones')) {
                $sets[] = 'estado_observaciones = ?';
                $params[] = $observaciones;
            }

            if ($estadoNuevo === 'RECHAZADO' && $this->columnExists('solicitudes', 'motivo_rechazo')) {
                $sets[] = 'motivo_rechazo = ?';
                $params[] = $observaciones;
            }

            if ($this->columnExists('solicitudes', 'fecha_estado')) {
                $sets[] = 'fecha_estado = NOW()';
            }

            if (in_array($estadoNuevo, ['APROBADO', 'RECHAZADO', 'TERMINADO', 'CANCELADO'], true) && $this->columnExists('solicitudes', 'fecha_resolucion')) {
                $sets[] = 'fecha_resolucion = NOW()';
            }

            if ($this->columnExists('solicitudes', 'estatus')) {
                $sets[] = 'estatus = ?';
                $params[] = ($estadoNuevo === 'CANCELADO') ? 'cancelado' : 'finalizado';
            }

            if (!empty($sets)) {
                $params[] = $idSolicitud;
                $sql = 'UPDATE solicitudes SET ' . implode(', ', $sets) . ' WHERE id_solicitud = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);
            }

            if ($this->tableExists('historial_solicitud_estados')) {
                $this->insertarRegistro('historial_solicitud_estados', [
                    'id_solicitud'     => $idSolicitud,
                    'estado_anterior'  => $estadoAnterior,
                    'estado_nuevo'     => $estadoNuevo,
                    'observaciones'    => $this->limpiar($observaciones),
                    'usuario_id'       => $usuarioId,
                    'usuario_nombre'   => $this->limpiar($usuarioNombre)
                ]);
            }

            if ($ownTransaction) {
                $this->db->commit();
            }

            return [
                'success' => true,
                'id' => $idSolicitud,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $estadoNuevo
            ];

        } catch (\Throwable $e) {
            if (!empty($ownTransaction) && $this->db->inTransaction()) {
                $this->db->rollBack();
            }

            error_log('ERROR cambiarEstadoSolicitud: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Obtiene detalle completo para modal del dashboard.
     */
    public function obtenerDetalleDashboard(int $idSolicitud): ?array {
        $registro = $this->obtenerTodoParaPDF($idSolicitud);

        if (!$registro) {
            return null;
        }

        $historial = [];

        if ($this->tableExists('historial_solicitud_estados')) {
            $historial = $this->fetchAll("SELECT * FROM historial_solicitud_estados WHERE id_solicitud = ? ORDER BY fecha_movimiento DESC, id_historial DESC", [$idSolicitud]);
        }

        $registro['_historial_estados'] = $historial;

        return $registro;
    }


    /**
     * Obtiene una solicitud completa para precargar el formulario de edición.
     */
    public function obtenerSolicitudParaEditar(int $idSolicitud): ?array {
        return $this->obtenerDetalleDashboard($idSolicitud);
    }

    /**
     * Borra tablas hijas normalizadas para reconstruirlas desde el payload actualizado.
     * No borra la solicitud principal ni el historial de estados.
     */
    private function borrarDatosNormalizadosSolicitud(int $idSolicitud): void {
        $tablas = [
            'datos_interesado',
            'datos_representantes',
            'datos_propietario',
            'requisitos_presentados',
            'recibos_pago',
            'detalles_tramite_especifico'
        ];

        foreach ($tablas as $tabla) {
            if (!$this->tableExists($tabla) || !$this->columnExists($tabla, 'id_solicitud')) {
                continue;
            }

            $stmt = $this->db->prepare("DELETE FROM `{$tabla}` WHERE id_solicitud = ?");
            $stmt->execute([$idSolicitud]);
        }
    }

    /**
     * Actualiza un registro existente sin generar folio nuevo.
     * Mantiene el historial de estados y reconstruye las tablas normalizadas.
     */
    public function actualizarSolicitudCompleta(int $idSolicitud, array $data): array {
        try {
            if ($idSolicitud <= 0) {
                throw new \Exception('ID de solicitud inválido.');
            }

            if (!$this->tableExists('solicitudes')) {
                throw new \Exception('La tabla solicitudes no existe.');
            }

            $actual = $this->fetchOne("SELECT * FROM solicitudes WHERE id_solicitud = ? LIMIT 1", [$idSolicitud]);

            if (!$actual) {
                throw new \Exception('La solicitud que intentas editar no existe.');
            }

            if (!$this->db->inTransaction()) {
                $this->db->beginTransaction();
                $ownTransaction = true;
            } else {
                $ownTransaction = false;
            }

            $payloadJson = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

            if ($payloadJson === false) {
                throw new \Exception('No se pudo codificar el payload actualizado.');
            }

            $sol = $data['solicitud'] ?? [];
            $bif = $data['bifurcacion'] ?? [];

            if (!is_array($sol)) $sol = [];
            if (!is_array($bif)) $bif = [];

            $updateData = [
                'materia'             => $this->limpiar($sol['materia'] ?? null),
                'nombre_tramite'      => $this->limpiar($sol['tramite'] ?? null),
                'tipo_persona'        => $this->normalizarCatalogo($sol['tipo_persona'] ?? null),
                'tipo_representante'  => $this->normalizarCatalogo($sol['tipo_representante'] ?? null),
                'payload'             => $payloadJson,
                'bifurcacion_clave'   => $this->limpiar($this->val($bif, ['clave', 'CLAVE'])),
                'modalidad'           => $this->limpiar($this->val($bif, ['modalidad', 'MODALIDAD'])),
                'modalidad_texto'     => $this->limpiar($this->val($bif, ['modalidad_texto', 'MODALIDAD_TEXTO'])),
                'detalle'             => $this->limpiar($this->val($bif, ['detalle', 'DETALLE'])),
                'detalle_texto'       => $this->limpiar($this->val($bif, ['detalle_texto', 'DETALLE_TEXTO'])),
            ];

            if ($this->columnExists('solicitudes', 'updated_at')) {
                $updateData['updated_at'] = date('Y-m-d H:i:s');
            }

            $sets = [];
            $params = [];

            foreach ($updateData as $column => $value) {
                if ($this->columnExists('solicitudes', $column)) {
                    $sets[] = "`{$column}` = ?";
                    $params[] = $value;
                }
            }

            if (!empty($sets)) {
                $params[] = $idSolicitud;
                $sql = 'UPDATE solicitudes SET ' . implode(', ', $sets) . ' WHERE id_solicitud = ?';
                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);
            }

            $this->borrarDatosNormalizadosSolicitud($idSolicitud);

            $this->insertarInteresado($idSolicitud, $data);

            if (!empty($data['representante_legal']) && is_array($data['representante_legal']) && $this->tieneValoresUtiles($data['representante_legal'])) {
                $this->insertarRepresentante($idSolicitud, 'legal', $data['representante_legal']);
            }

            if (!empty($data['persona_autorizada']) && is_array($data['persona_autorizada']) && $this->tieneValoresUtiles($data['persona_autorizada'])) {
                $this->insertarRepresentante($idSolicitud, 'autorizada', $data['persona_autorizada']);
            }

            $this->insertarPropietario($idSolicitud, $data);
            $this->insertarRequisitos($idSolicitud, $data);
            $this->insertarRecibosPago($idSolicitud, $data);
            $this->insertarDetallesDinamicos($idSolicitud, $data);

            if ($this->tableExists('historial_solicitud_estados')) {
                $estadoActual = $actual['estado_proceso'] ?? 'INGRESADO';

                $this->insertarRegistro('historial_solicitud_estados', [
                    'id_solicitud'     => $idSolicitud,
                    'estado_anterior'  => $estadoActual,
                    'estado_nuevo'     => $estadoActual,
                    'observaciones'    => 'Captura editada / información actualizada desde dashboard.',
                    'usuario_id'       => null,
                    'usuario_nombre'   => 'SISTEMA'
                ]);
            }

            if (!empty($ownTransaction)) {
                $this->db->commit();
            }

            return [
                'success' => true,
                'id' => $idSolicitud,
                'folio' => $actual['folio'] ?? null,
                'updated' => true
            ];

        } catch (\Throwable $e) {
            if (!empty($ownTransaction) && $this->db->inTransaction()) {
                $this->db->rollBack();
            }

            error_log('ERROR EN Ventanilla::actualizarSolicitudCompleta(): ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

}