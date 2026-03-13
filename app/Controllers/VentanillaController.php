<?php
// app/Controllers/VentanillaController.php

class VentanillaController {
    
    // Aquí simulamos la Base de Datos con la info de tu Word
    private function getCatalogoTramites() {
        return [
            'Protección Civil' => [
                'Autorización para la instalación y quema de pirotecnia' => [
                    'Formato TTLALPAN_AIQ_1 debidamente requisitado (Original y copia)',
                    'Identificación oficial vigente (Original y copia)',
                    'Documentos de acreditación de personalidad jurídica (Original y copia)',
                    'Copia del Permiso General otorgado por la SEDENA (Pirotecnia en exteriores)',
                    'Póliza de Seguro de Responsabilidad Civil',
                    'Constancias de capacitación expedidas por institución acreditada',
                    'Croquis y análisis de riesgos (Radio 500m)'
                ]
            ],
            'Mercados y Vía Pública' => [
                'Autorización de cambio de giro de local en Mercado Público' => [
                    'Formato TTLALPAN ACG_1 debidamente requisitado',
                    '3 fotografías tamaño credencial',
                    'Cédula de empadronamiento (Original y copia)',
                    'CURP (Copia simple)',
                    'Identificación oficial vigente',
                    'Comprobantes de pago de derechos (año actual y 4 anteriores)',
                    'Constancia de no adeudo al Fideicomiso (Auto administración)'
                ],
                'Cédula de empadronamiento para ejercer actividades comerciales' => [
                    'Formato TTLALPAN_REE_1 debidamente requisitado',
                    'Acta de nacimiento (Original y copia)',
                    '3 fotografías tamaño credencial',
                    'CURP (Copia simple)',
                    'Documento de Identificación oficial'
                ],
                'Permiso para ejercer el comercio en la vía pública' => [
                    'Formato TTLALPAN_PEC_1 debidamente requisitado',
                    'Identificación Oficial (INE, Pasaporte, etc)',
                    'CURP',
                    'Comprobante de domicilio (menor a 3 meses)',
                    'Dos fotografías tamaño credencial'
                ],
                'Refrendo de empadronamiento en Mercados Públicos' => [
                    'Formato TTLALPAN_ AAPP_1 debidamente requisitado',
                    'Identificación oficial del interesado',
                    'Cédula de empadronamiento original',
                    'Comprobante de pago de derechos del año en curso'
                ]
            ],
            'Obras y Desarrollo Urbano' => [
                'Expedición de Constancia de Alineamiento y/o Número Oficial' => [
                    'Formato TTLALPAN_CAY_1 debidamente requisitado',
                    'Identificación oficial vigente',
                    'Escritura o Documento que acredite la propiedad/posesión',
                    'Comprobante de pago de derechos',
                    'Constancia de no adeudo de Predial y Agua'
                ],
                'Licencia de Construcción Especial' => [
                    'Formato TTLALPAN_LCE_1 debidamente requisitado',
                    'Identificación oficial con fotografía',
                    'Constancia de Alineamiento y Número Oficial vigente',
                    'Certificado Único de Zonificación de Uso de Suelo',
                    'Dos tantos del proyecto arquitectónico (Planos)',
                    'Memoria descriptiva del proyecto',
                    'Proyecto estructural y Memoria de cálculo'
                ],
                'Registro de Manifestación de Construcción Tipo A' => [
                    'Formato TTLALPAN_RMC_3 debidamente requisitado',
                    'Identificación oficial con fotografía',
                    'Constancia de alineamiento y número oficial vigente',
                    'Plano o croquis con ubicación y superficie',
                    'Constancia de no adeudo de Predial y Agua'
                ],
                 'Registro de Manifestación de Construcción Tipo B o C' => [
                    'Formato TTLAPAN_RMC_1 debidamente requisitado',
                    'Identificación oficial vigente',
                    'Certificado Único de Zonificación de Uso del Suelo',
                    'Dos tantos del proyecto arquitectónico y estructural',
                    'Libro de bitácora de obra foliado',
                    'Póliza de seguro de responsabilidad civil'
                ]
            ],
            'Servicios Legales y Jurídicos' => [
                'Expedición de Certificado de Residencia' => [
                    'Formato TTLALPAN_ECR_1 debidamente requisitado',
                    'Identificación oficial vigente',
                    'Comprobante de domicilio',
                    'Dos fotografías tamaño infantil',
                    'Comprobante de pago de derechos'
                ],
                'Expedición de copias certificadas' => [
                    'Formato TTLALPAN_ ECS_2 debidamente requisitado',
                    'Identificación oficial',
                    'Documentos que acrediten interés jurídico',
                    'Comprobante de pago de derechos'
                ]
            ],
            'Giros Mercantiles' => [
                'Autorización para espectáculos públicos' => [
                    'Formato TTLALPAN_AAPP_1 debidamente requisitado',
                    'Identificación Oficial del solicitante',
                    'Visto bueno de Protección Civil (Masivos)',
                    'Comprobante de pago de derechos',
                    'Póliza de Seguro de Responsabilidad Civil'
                ]
            ]
        ];
    }

    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // 1. Obtenemos todo el catálogo
        $catalogoCompleto = $this->getCatalogoTramites();

        // 2. Extraemos solo las llaves (Materias) para el primer Select
        $materias = array_keys($catalogoCompleto);
        
        // 3. Preparamos los datos iniciales
        // Por defecto, cargamos los trámites de la primera materia para que no salga vacío
        $primeraMateria = $materias[0];
        $tramitesIniciales = array_keys($catalogoCompleto[$primeraMateria]);
        
        $data = [
            'pageTitle' => 'Gestión de Trámites - Ventanilla Única',
            'user' => $_SESSION['user'] ?? null,
            'materias' => $materias,
            // Pasamos TODO el catálogo a la vista para que JS lo use
            'catalogo_json' => $catalogoCompleto 
        ];

        $viewContent = '../app/Views/ventanilla/index.php';
        require_once '../app/Views/layouts/main.php';
    }
}