<?php
// app/Controllers/VentanillaController.php

class VentanillaController {
    
    // Aquí simulamos la Base de Datos con la info de tu Word
    private function getCatalogoTramites() {
        return [
            'Protección Civil' => [
                'Autorización para la instalación y quema de pirotecnia y efectos especiales' => [
                    'tipo_captura' => 'predio',
                    'requisitos' => [
                    'Formato TTLALPAN_AIQ_1 debidamente requisitado (Original y copia)',
                    'Identificación oficial vigente (Credencial para votar, Pasaporte, Cartilla del Servicio Militar Nacional, Cédula Profesional, en su caso documento migratorio). Original y copia para cotejo',
                    'Documentos de acreditación de personalidad jurídica (Carta Poder firmada ante dos testigos con ratificación de las firmas ante Notario Público, Carta Poder firmada ante dos testigos e identificación oficial del interesado y de quien realiza el trámite, Poder Notarial e Identificación Oficial del representante o apoderado, Acta Constitutiva, Poder Notarial e Identificación Oficial del representante o apoderado). Original y copia',
                    'En caso de pirotecnia en exteriores: Copia del Permiso General otorgado por la Secretaría de la Defensa Nacional. Copia del Permiso de Transporte de sustancias peligrosas otorgado por la Secretaría de Comunicaciones y Transporte.',
                    'Copia de Póliza de Seguro que Ampare la Responsabilidad Civil y Daños a Terceros.  Carta de Corresponsabilidad del Tercer Acreditado. ',
                    'Constancias de capacitación expedidas por institución o terceros acreditados.',
                    'Copia del contrato de prestación de servicio entre el organizador y el permisionario.',
                    'Croquis y análisis de riesgos en un rango de 500 metros. Croquis y análisis de riesgo del área en que se detonarán.',
                    'Relación de artificios pirotécnicos especificando cantidad y potencia, así como gráfica de altura y expansión. Programa de Quema.',
                    'Propuesta de distancias mínimas de seguridad y, en su caso, medidas de seguridad adicionales previstas.',
                    'Procedimientos de emergencia que considere al menos los siguientes riesgos: Heridos, incendio, detonación imprevista, robo, condiciones climáticas adversas, sabotaje y sismo.',
                    'Carta responsiva de carga vigente de los extintores a utilizar.',
                    'Relación del personal técnico pirotécnico designado por el permisionario, especificando el responsable del traslado, montaje y quema de los artificios pirotécnicos. Adjuntando copia de identificación oficial.'
                    ],
                    'detalles' => [
                        'observaciones' => 'Trámite mediante el cual se autoriza la instalación y quema de pirotecnia en espectáculos públicos y tradicionales en la Alcaldía, con los parámetros de seguridad para salvaguardar la integridad física y psicológica de los asistentes a dichos eventos.',
                        'costo' => 'Sin Costo',
                        'materia' => '20 Protección Civil',
                        'tiempo' => '30 Días Hábiles',
                        'en_linea' => 'No',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]

                ]
            ],
            'Via Pública' => [
                'Permiso para ejercer actividades comerciales en Romerías en Vía Pública' => [
                    'requisitos' => [
                    'Formato TTLALPAN_PRVP_1, por duplicado debidamente requisitados, con firmas autógrafas.',
                    'Identificación oficial de la persona interesada (Credencial para votar, Pasaporte, Cédula Profesional o Cartilla del Servicio Militar Nacional). (Original y copia para cotejo)',
                    'Documento que acredita el carácter de representante o apoderado (Carta poder firmada ante dos testigos con ratificación firmas ante Notario Público, Carta Poder firmada ante dos testigos e identificación oficial del interesado y de quien realiza el trámite o Poder Notarial e identificación oficial del representante o apoderado). (Original y copia)',
                    'Comprobante de Domicilio no mayor a tres meses de expedición (Agua, Luz, Teléfono o Predial) (Original y copia para cotejo)',
                    'Clave Única de Registro de Población (CURP). (1 Copia simple)',
                    'Comprobante de pago de derechos del año anterior en caso de ser autorizada (no aplica para solicitudes nuevas). (1 Copia simple)',
                    'Croquis de localización'
                    ],
                    'detalles' => [
                        'observaciones' => 'a)	La autoridad llevará a cabo la revisión y el análisis de factibilidad de la solicitud, considerando lo siguiente:
                                            b)	Las Romerías solo podrán realizarse en las fechas y temporadas establecidas, y únicamente podrán comercializarse bienes y productos de temporada autorizados.
                                            c)	El Órgano Político Administrativo asignará los espacios y determinará las dimensiones del local o puesto para el desarrollo de la actividad comercial en las Romerías.
                                            d)	No se otorgarán permisos para vender o distribuir, artículos de procedencia ilegal, juegos pirotécnicos, material inflamable o explosivo y bebidas embriagantes.
                                            e)	En caso de no contar con el Comprobante de pago de derechos, se deberá presentar una certificación de pago por los derechos de uso o aprovechamiento de bienes del dominio público, correspondiente al año en que se realiza la solicitud.
                                            ',
                        'costo' => 'Código Fiscal Vigente',
                        'materia' => '22 Via Pública',
                        'tiempo' => '15 Días hábiles',
                        'en_linea' => 'Sí. en: pagina.cdmx.gob.mx ',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h'
                    ]
                ]
            ],
            'Giros Mercantiles' => [
                'Autorización, aviso o permiso para la celebración de espectáculos públicos' => [
                    'requisitos' => [
                    'Formato de solicitud TTLALPAN_AAPP_1 debidamente requisitado (Original y copia)',
                    'Identificación Oficial del solicitante (Original y copia para cotejo)',
                    'Documento con el que se acredite el carácter de representante o apoderado (Original y copia para cotejo)',
                    'Permiso o Aviso expedido por la Secretaría de Desarrollo Económico que ampare el funcionamiento del establecimiento mercantil y/o Revalidación correspondiente. (Una copia simple)',
                    'Visto bueno para la celebración de espectáculos públicos masivos en lo relativo a extintores, señalización para el caso de incendio y sismos, rutas de evacuación y salidas de emergencia. (Original y copia para cotejo)',
                    'Comprobante de pago de derechos contemplado en el artículo 256 inciso a), fracción II del Código Fiscal de la Ciudad de México, por la supervisión de campo en el lugar donde se celebren espectáculos públicos masivos. (Original)',
                    'Comprobante de pago de derechos contemplado en el artículo 256 inciso b) del Código Fiscal de la Ciudad de México, respecto a los servicios de Protección Ciudadana proporcionados por la Secretaría de Seguridad Pública de la Ciudad de México, en su caso. (Original)',
                    'Comprobante de pago de derechos contemplado en el artículo 257 del Código Fiscal de la Ciudad de México, respecto a los servicios de prevención de incendios, en su caso. (Original)',
                    'Para permiso y autorización para la realización de Ferias en la vía pública de los pueblos, barrios y colonias de la Ciudad de México, presentar los requisitos adicionales señalados en el formato TTLALPAN_AAPP_1'
                    ],
                    'detalles' => [
                        'observaciones' => 'a)	Trámite mediante el cual las y los particulares obtienen autorización, aviso o permiso, para presentar espectáculos en lugares públicos o privados según sea el caso.',
                        'costo' => 'Sin Costo',
                        'materia' => '22 Giros Mercantiles',
                        'tiempo' => 'Inmediata (se envía a la DGJG para su revisión derivado que en la VUT el trámite cumple con los requisitos para su registro, falta el análisis del contenido del mismo).',
                        'en_linea' => 'No',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],
                'Solicitud de la tarifa autorizada para estacionamiento público' => [
                    'requisitos' => [
                    'Formato TTLAPAN_ATEP_1 por duplicado debidamente requisitado con firmas autógrafas',
                    'Declaración de apertura o Licencia de Funcionamiento según sea el caso. (Original y copia)',
                    'Identificación Oficial (Credencial para votar, Pasaporte, Cédula Profesional o Cartilla del Servicio Militar Nacional). (Original y copia para cotejo)',
                    'Acta Constitutiva de la Persona Moral, así como del Poder Notarial donde se acreditará la Representación Legal acompañada de Identificación Oficial del Representante Legal (Credencial para votar, Pasaporte, Cédula Profesional o Cartilla del Servicio Militar Nacional), en su caso. (Original y copia)',
                    'Póliza de Seguro Vigente que ampare el número de cajones de Estacionamientos Manifestados. (Original y copia)',
                    'Visto bueno de Seguridad y Operación. (Original y copia)',
                    'Programa Interno de Protección Civil. (Original y copia)',
                    'Contrato de Arrendamiento o Escritura pública. (Original y copia)',
                    'Ultima tarifa autorizada.'
                    ],
                    'detalles' => [
                        'observaciones' => 'De conformidad con el Artículo 14 del Reglamento de Estacionamientos Públicos del Distrito Federal, hoy Ciudad de México, el propietario o administrador queda notificado que deberá colocar la cartulina autorizada en la caseta de cobro a la vista del público.',
                        'costo' => 'Sin Costo',
                        'materia' => '22 Giros Mercantiles',
                        'tiempo' => 'Indefinido',
                        'en_linea' => 'No',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ]
            ],
            'Mercados' => [
                'Autorización de cambio de giro de local en Mercado Público' => [
                    'tipo_captura' => 'mercado',
                    'requisitos' => [  
                    'Formato TTLALPAN ACG_1 debidamente requisitado (Original y copia)',
                    '3 fotografías tamaño credencial',
                    'Cédula de empadronamiento (Original y copia para cotejo)',
                    'Clave Única de Registro de Población (CURP) (1 copia simple)',
                    'Documento de Identificación oficial (Credencial para votar, Pasaporte, Cédula Profesional o Cartilla del Servicio Militar Nacional) (Original y copia para cotejo)',
                    'Comprobantes de pago de derechos por el uso y utilización de Locales de Mercados Públicos del Distrito Federal correspondiente al año en que se realiza la solicitud y de los cuatro años anteriores. (Original y copia para cotejo)',
                    'Comprobante de no adeudo al Fideicomiso del Mercado, correspondiente al año en que se realiza la solicitud y de los cuatro años anteriores (tratándose de los Mercados Públicos en Auto Administración) (Original y copia para cotejo)',
                    'Autorización sanitaria expedida por la Secretaría de Salud (para aquellos comerciantes que para el ejercicio de sus actividades la requieran) (Original y copia para cotejo)',
                    'Documento que acredita el carácter de representante o apoderado, en su caso (Carta Poder firmada ante dos testigos e identificación oficial del interesado y de quien realiza el trámite, Carta Poder firmada ante dos testigos con ratificación de las firmas ante notario o Poder Notarial e Identificación Oficial del Representante o apoderado) (Original y copia para cotejo)'
                    ],
                    'detalles' => [
                        'observaciones' => 'Trámite que deben realizar las y los locatarios de mercados públicos, para poder ejercer un giro diferente del señalado en la cédula de empadronamiento precedente, con quince días hábiles previos a la fecha en que se pretenda cambiar de giro.',
                        'costo' => 'Sin Costo',
                        'materia' => '10 Mercados',
                        'tiempo' => '15 Días hábiles',
                        'en_linea' => 'No',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],
                'Autorización de remodelación de local' => [
                    'tipo_captura' => 'mercado',
                    'requisitos' => [
                    'Formato TTLALPAN _ARL_1 debidamente requisitado (Original y copia)',
                    'Cédula de empadronamiento. (Original y copia para cotejo)',
                    'Dictamen técnico de la Dirección de Patrimonio Cultural Urbano de la Secretaría de Desarrollo Urbano y Vivienda, en caso de que el inmueble esté catalogado con valor patrimonial. (Original y copia para cotejo)',
                    'Autorización del Instituto Nacional de Antropología e Historia, en caso de que el inmueble esté catalogado con valor histórico. (1 copia simple y original para cotejo, 5. Documento de Identificación oficial del titular (Credencial para votar, Pasaporte, Cédula Profesional o Cartilla del Servicio Militar Nacional) (1copia simple y original para cotejo)',
                    'Autorización del Instituto Nacional de Bellas Artes, en caso de que el inmueble esté catalogado con valor artístico. (Original y copia para cotejo)',
                    'Clave Única de Registro de Población (CURP) (1 copia simple)',
                    'Documento que acredita el carácter de representante o apoderado (Carta Poder firmada ante dos testigos e identificación oficial del interesado y de quien realiza el trámite, Carta Poder firmada ante dos testigos con ratificación de las firmas ante notario o Poder Notarial e Identificación Oficial del Representante o apoderado). (Original y copia para cotejo)',
                    'Opinión favorable de la Dirección General de Obras y Desarrollo Urbano, emitida por el Órgano Político - Administrativo correspondiente. (Original y copia para cotejo)',
                    'Comprobantes de pago de derechos por el uso y utilización de Locales de Mercados Públicos del Distrito Federal del año de la solicitud y cuatro años anteriores. (1 copia simple)',
                    'Opinión favorable de la Dirección General de Protección Civil, emitida por el Órgano Político - Administrativo correspondiente. (Original y copia para cotejo)',
                    'Comprobante de no adeudo al Fideicomiso del Mercado, en su caso, (tratándose de los Mercados Públicos en Auto Administración) correspondiente al año en que se realiza la solicitud y cuatro años anteriores. (1 copia simple)'
                    ],
                    'detalles' => [
                        'observaciones' => 'Trámite mediante el cual las y los locatarios realizan obras de mantenimiento, reparación o remodelación del local, sin variar sus características esenciales.',
                        'costo' => 'Sin Costo',
                        'materia' => '10 Mercados',
                        'tiempo' => '15 Días hábiles',
                        'en_linea' => 'No',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],
                'Autorización hasta por 90 días para que una persona distinta del empadronado pueda ejercer el comercio en puestos permanentes o temporales en Mercados Públicos por cuenta del empadronado' => [
                    'tipo_captura' => 'mercado',
                    'requisitos' => [
                    'Formato TTLALPAN_A9D_1 debidamente requisitado (Original y copia)',
                    'Clave Única de Registro de Población (CURP) (1 copia simple)',
                    'Cédula de empadronamiento (Original y copia para cotejo)',
                    'Comprobantes de pago de derechos por el uso y utilización de Locales de Mercados Públicos del Distrito Federal correspondiente al año en que se realiza la solicitud y de los cuatro años anteriores. (1 copia simple)',
                    'Documento de Identificación oficial del titular de la cédula de empadronamiento (Credencial para votar, Pasaporte, Cédula Profesional o Cartilla del Servicio Militar Nacional). (Original y copia para cotejo)',
                    'Documento de identificación oficial de la persona que ejercerá la actividad comercial en nombre del titular (Credencial para votar, Pasaporte, Cédula Profesional o Cartilla del Servicio Militar Nacional). (1 copia simple)',
                    'Comprobante de no adeudo al Fideicomiso del Mercado, correspondiente al año en que se realiza la solicitud y de los cuatro años anteriores (tratándose de los Mercados Públicos en Auto Administración). (1 copia simple)',
                    'Autorización sanitaria expedida por la Secretaría de Salud (para aquellos comerciantes que para el ejercicio de sus actividades la requieran). (Original y copia para cotejo)',
                    'Documento que acredita el carácter de representante o apoderado (Carta Poder firmada ante dos testigos e identificación oficial del interesado y de quien realiza el trámite, Carta Poder firmada ante dos testigos con ratificación de las firmas ante notario o Poder Notarial e Identificación Oficial del Representante o apoderado). (Original y copia para cotejo)'
                    ],
                    'detalles' => [
                        'observaciones' => 'Trámite mediante el cual se obtiene la autorización para que una persona distinta al empadronado realice actividades comerciales en mercados públicos, por un periodo de hasta 90 días.',
                        'costo' => 'Sin Costo',
                        'materia' => '10 Mercados',
                        'tiempo' => '15 Días hábiles',
                        'en_linea' => 'No',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],
                'Autorización para el traspaso de derechos de Cédula de Empadronamiento del local en Mercado Público' => [
                    'tipo_captura' => 'mercado',
                    'requisitos' => [
                    'Formato TTLALPAN_ATDN_1 debidamente requisitado. (Original y copia)',
                    'Clave Única de Registro de Población (CURP) del titular (cedente). (1 copia simple)',
                    'Cédula de empadronamiento. (Original y copia para cotejo)',
                    'Fotografías tamaño credencial del cesionario.',
                    'Documento de Identificación oficial del titular (Credencial para votar, Pasaporte, Cédula Profesional o Cartilla del Servicio Militar Nacional) (1 copia simple y original para cotejo). Documento de Identificación oficial del cesionario (Credencial para votar, Pasaporte, Cédula Profesional o Cartilla del Servicio Militar Nacional). (Original y copia para cotejo)',
                    'Comprobantes de pago de derechos por el uso y utilización de Locales de Mercados Públicos del Distrito Federal del año en que se realiza la solicitud y de los cuatro años anteriores. (1 copia simple)',
                    'Autorización sanitaria expedida por la Secretaría de Salud (para aquellos. comerciantes que para el ejercicio de sus actividades la requieran). (Original y copia para cotejo)',
                    'Comprobante de no adeudo al Fideicomiso del Mercado, correspondiente al año en que se realiza la solicitud y de los cuatro años anteriores (tratándose de los Mercados Públicos en Auto Administración) (1 copia simple). Clave Única de Registro de Población (CURP) del cesionario. (1 copia simple)',
                    'Documento que acredita el carácter de representante o apoderado, en su caso (Carta poder firmada ante dos testigos con ratificación de las firmas ante Notario Público, Carta Poder firmada ante dos testigos e identificación oficial del interesado y de quien realiza el trámite o Poder Notarial e identificación oficial del representante o apoderado). (Original y copia para cotejo)',
                    'Comprobante de domicilio del cesionario (Persona que recibe los derechos) (Recibo del Servicio de Luz, Boleta de Servicio de Agua o Estado de Cuenta de Servicio Telefónico, no mayor a tres meses de antigüedad). (Original y copia para cotejo)',
                    'Acta de nacimiento del cesionario (Original y copia para cotejo)'
                    ],
                    'detalles' => [
                        'observaciones' => 'Trámite mediante el cual la o el titular de la cédula de empadronamiento traspasa sus derechos por voluntad propia a otra persona para ejercer la actividad comercial establecida en la cédula.',
                        'costo' => 'Sin Costo',
                        'materia' => '10 Mercados',
                        'tiempo' => '15 Días hábiles',
                        'en_linea' => 'No',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],
                'Cambio de nombre del titular de la cédula de empadronamiento de locales en Mercados públicos por fallecimiento del empadronado' => [
                    'requisitos' => [
                    'Formato TTLALPAN_CND_1 debidamente requisitado. (Original y copia)',
                    '3 fotografías tamaño credencial del interesado.',
                    'Cédula de empadronamiento. (Original y copia para cotejo)',
                    'Clave Única de Registro de Población (CURP) del interesado (1 copia simple). 5. Documento de Identificación oficial (Credencial para votar, Pasaporte, Cédula Profesional o Cartilla del Servicio Militar Nacional). (Original y copia para cotejo)',
                    'Autorización sanitaria expedida por la Secretaría de Salud (para aquellos. comerciantes que para el ejercicio de sus actividades la requieran). (Original y copia para cotejo)',
                    'Acta de nacimiento del interesado. (Original y copia para cotejo)',
                    'Acta de defunción del titular de la cédula de empadronamiento. (Original y copia para cotejo)',
                    'Documento que acredita el carácter de representante o apoderado (Carta Poder firmada ante dos testigos e identificación oficial del interesado y de quien realiza el trámite, Carta Poder firmada ante dos testigos con ratificación de las firmas ante notario o Poder Notarial e Identificación Oficial del Representante o apoderado). (Original y copia para cotejo)',
                    'Comprobante de domicilio del interesado (Recibo del Servicio de Luz, Boleta de Servicio de Agua o Estado de Cuenta de Servicio Telefónico, no mayor a tres meses de antigüedad). (Original y copia para cotejo)',
                    'Comprobantes de pago de derechos por el uso y utilización de Locales de Mercados Públicos del Distrito Federal correspondiente al año en que se realiza la solicitud y de los cuatro años anteriores. (1 copia simple)',
                    'Comprobante de no adeudo al Fideicomiso del Mercado, correspondiente al año en que se realiza la solicitud y de los cuatro años anteriores (tratándose de los Mercados Públicos en Auto Administración). (1 copia simple)'
                    ],
                    'detalles' => [
                        'observaciones' => 'Trámite que debe realizar quien acredite tener el derecho de preferencia sobre la cédula de empadronamiento para continuar con la práctica del comercio, en virtud del fallecimiento de la o el empadronado.',
                        'costo' => 'Sin Costo',
                        'materia' => '10 Mercados',
                        'tiempo' => '40 Días hábiles',
                        'en_linea' => 'No',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],
                'Cédula de empadronamiento para ejercer actividades comerciales en mercados públicos o su Reexpedición' => [
                    'requisitos' => [
                    'Formato TTLALPAN_REE_1 debidamente requisitado. (Original y copia)',
                    'Acta de nacimiento. (Original y copia para cotejo)',
                    '3 fotografías tamaño credencial. (Original y copia para cotejo)',
                    'Clave Única de Registro de Población (CURP). (1 copia simple)',
                    'Documento de Identificación oficial (Credencial para votar, Pasaporte, Cédula Profesional o Cartilla del Servicio Militar Nacional). (Original y copia para cotejo)',
                    'Documento que acredita el carácter de representante o apoderado, en su caso (Carta poder firmada ante dos testigos con ratificación de las firmas ante Notario Público, Carta Poder firmada ante dos testigos e identificación oficial del interesado y de quien realiza el trámite o Poder Notarial e identificación oficial del representante o apoderado). (Original y copia para cotejo)',
                    'Documento expedido por autoridad competente para el caso de robo y/o extravío, tratándose de reexpedición de la cédula. (Original y copia para cotejo)',
                    'Comprobante de domicilio no mayor a tres meses de antigüedad (Recibo de luz, boleta de servicio de agua o estado de cuenta de servicio telefónico). (Original y copia para cotejo)',
                    'Cédula de empadronamiento para el caso de actualización, tratándose de reexpedición. (Original y copia para cotejo)',
                    'Autorización sanitaria expedida por la Secretaría de Salud (para aquellos comerciantes que para el ejercicio de sus actividades la requieran). (Original y copia para cotejo)'
                    ],
                    'detalles' => [
                        'observaciones' => 'Trámite que deben realizar las personas físicas que pretendan ejercer actividades comerciales en mercados públicos o las y los locatarios que requieran la reexpedición por causa de robo, extravío o cambio por actualización.',
                        'costo' => 'Sin Costo',
                        'materia' => '10 Mercados',
                        'tiempo' => '15 Días hábiles',
                        'en_linea' => 'No',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],
                'Permiso para ejercer actividades comerciales en Romerías' => [
                    'requisitos' => [
                    'Formato TTLALPAN_ AAPP_1 debidamente requisitado. (Original y copia)',
                    'Documento de identificación oficial de la persona que ejercerá la actividad comercial a nombre del titular de la cédula de empadronamiento (Credencial para votar, Pasaporte, Cédula Profesional o Cartilla del Servicio Militar Nacional). (Original y copia para cotejo)',
                    'Cédula de empadronamiento. (Original y copia para cotejo)',
                    'Clave Única de Registro de Población (CURP). (1 copia simple)',
                    'Documento que acredita el carácter de representante o apoderado (Carta poder firmada ante dos testigos con ratificación de las firmas ante Notario Público, Carta Poder firmada ante dos testigos e identificación oficial del interesado y de quien realiza el trámite o Poder Notarial e identificación oficial del representante o apoderado). (Original y copia para cotejo)',
                    'Documento de Identificación oficial del interesado (Credencial para votar, Pasaporte, Cédula Profesional o Cartilla del Servicio Militar Nacional). (Original y copia para cotejo)',
                    'Comprobante de pago de la contribución que proceda conforme a lo establecido en el Código Fiscal del Distrito Federal o en los ordenamientos legales que se emitan, por el periodo correspondiente, en su caso. (1 copia simple)',
                    'Comprobante de pago de derechos por el uso y utilización de Locales de Mercados Públicos del Distrito Federal, correspondiente al año en que se realiza la solicitud. (1 copia simple)',
                    'Refrendo correspondiente al año en que se realiza la solicitud. (Original y copia para cotejo)',
                    'Comprobante de no adeudo al Fideicomiso del Mercado, en su caso, correspondiente al año en que se realiza la solicitud (tratándose de los Mercados Públicos en Auto Administración). (1 copia simple)'
                    ],
                    'detalles' => [
                        'observaciones' => 'Trámite que deben realizar las y los titulares de la cédula de empadronamiento, que pretendan ejercer actividades comerciales en romerías de mercados públicos, con al menos 15 días de anticipación a la fecha de inicio de las mismas.',
                        'costo' => 'Sin costo',
                        'materia' => '10 Mercados',
                        'tiempo' => '15 Días hábiles',
                        'en_linea' => 'No',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],
                'Refrendo de empadronamiento para ejercer actividades comerciales en Mercados Públicos' => [
                    'requisitos' => [
                    'Formato TTLALPAN_ AAPP_1 debidamente requisitado. (Original y copia)',
                    'Documento de Identificación oficial del interesado (Credencial para votar, Pasaporte, Cédula Profesional o Cartilla del Servicio Militar Nacional). (Original y copia para cotejo)',
                    'Documento de identificación oficial de la persona que ejercerá la actividad comercial a nombre del titular de la cédula de empadronamiento (Credencial para votar, Pasaporte, Cédula Profesional o Cartilla del Servicio Militar Nacional). (Original y copia para cotejo)',
                    'Cédula de empadronamiento. (Original y copia para cotejo)',
                    'Clave Única de Registro de Población (CURP) (1 copia simple)',
                    'Documento que acredita el carácter de representante o apoderado (Carta poder firmada ante dos testigos con ratificación de las firmas ante Notario Público, Carta Poder firmada ante dos testigos e identificación oficial del interesado y de quien realiza el trámite o Poder Notarial e identificación oficial del representante o apoderado). (Original y copia para cotejo)',
                    'Refrendo correspondiente al año en que se realiza la solicitud. (Original y copia para cotejo)',
                    'Comprobante de pago de derechos por el uso y utilización de Locales de Mercados Públicos del Distrito Federal, correspondiente al año en que se realiza la solicitud. (1 copia simple)',
                    'Comprobante de pago de la contribución que proceda conforme a lo establecido en el Código Fiscal del Distrito Federal o en los ordenamientos legales que se emitan, por el periodo correspondiente, en su caso. (1 copia simple)',
                    'Comprobante de no adeudo al Fideicomiso del Mercado, en su caso, correspondiente al año en que se realiza la solicitud (tratándose de los Mercados Públicos en Auto Administración). (1 copia simple)'
                    ],
                    'detalles' => [
                        'observaciones' => 'Trámite mediante el cual la o el titular de la cédula de empadronamiento refrenda las causas que dieron origen al empadronamiento',
                        'costo' => 'Sin costo',
                        'materia' => '10 Mercados',
                        'tiempo' => '15 Días hábiles',
                        'en_linea' => 'Sí. en: pagina.cdmx.gob.mx ',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],
                'Solicitud de exención del pago de derechos por ejercer el comercio en la vía pública' => [
                    'requisitos' => [
                    'Formato TTLALPAN_SED_1, debidamente requisitado. (Original y copia)',
                    'Identificación Oficial (Credencial para votar, Pasaporte, Cédula Profesional o Cartilla del Servicio Militar Nacional) (Original y copia para cotejo)',
                    'Comprobante de domicilio (Agua, Predio, Teléfono o Luz). (Original y copia para cotejo)',
                    'Documento(s) públicos fehaciente y vigente con que se acredite alguno de los supuestos para ser exento de pago. (Original y copia para cotejo)',
                    'Último recibo de pago que acredite estar al corriente con los derechos por el uso y explotación de la vía pública. (Original y copia para cotejo)',
                    'Documento que acredita el carácter de representante o apoderado, en su caso (Carta poder firmada ante dos testigos con ratificación de las firmas ante Notario Público, Carta Poder firmada ante dos testigos e identificación oficial del interesado y de quien realiza el trámite o Poder Notarial e identificación oficial del representante o apoderado). (Original y copia para cotejo)'
                    ],
                    'detalles' => [
                        'observaciones' => 'a)	Trámite que tiene como objetivo que los comerciantes que pertenecen a grupos vulnerables puedan obtener una exención o reducción en el pago por el uso y aprovechamiento de las vías y áreas públicas. 
                                            b)	Esta solicitud es necesaria para que los comerciantes puedan ejercer su actividad comercial en la vía pública sin tener que cubrir los costos asociados a la ocupación del espacio público.
                                            ',
                        'costo' => 'Sí. Articulo 248',
                        'materia' => '10 Mercados',
                        'tiempo' => '15 Días hábiles',
                        'en_linea' => 'No',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],
                'Permiso para ejercer el comercio en la vía pública personalísimo, temporal, revocable e intransferible y su renovación' => [
                    'requisitos' => [
                    'Formato TTLALPAN_PEC_1 debidamente requisitado. (Original y copia)',
                    'Identificación Oficial (credencial para votar, pasaporte, cartilla militar o para extranjeros FM-2 O FM-3).  (Original y copia para cotejo)',
                    'Clave Única de Registro de Población (C.U.R.P.) (1 copia simple)',
                    'Comprobante de pago de derechos (una vez aceptada la solicitud). (Original y copia para cotejo)',
                    'Comprobante de domicilio (agua, predio, teléfono o luz) con una antigüedad menor a 3 meses desde la fecha de expedición. (Original y copia para cotejo)',
                    'Dos fotografías tamaño credencial blanco y negro o a color'
                    ],
                    'detalles' => [
                        'observaciones' => 'a)	Autorización que permite a un ciudadano vender o ofrecer bienes o servicios en la vía pública, dentro de una demarcación territorial específica.
                                            b)	La renovación de este permiso es necesaria para mantener la autorización vigente y seguir ejerciendo el comercio legalmente
                                            ',
                        'costo' => 'Sí. Código Fiscal Vigente',
                        'materia' => '10 Mercados',
                        'tiempo' => '15 Días hábiles',
                        'en_linea' => 'No',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]

                ]
            ],
            'Servicios Legales' => [
                'Expedición de copias certificadas que obren en los archivos de la Delegación' => [
                    'requisitos' => [
                    'Formato TTLALPAN_ECC_1 debidamente requisitado. (Original y copia)',
                    'Documentos de identificación oficial. (Original y copia para cotejo)',
                    'Documentos de acreditación de personalidad jurídica. (Original y copia para cotejo)',
                    'Documentos con los que se acredite interés jurídico, copia certificada y/o copia simple (ejemplo: Sentencia Judicial). (Original y copia para cotejo)',
                    '5.	Comprobante de pago de derechos, una vez que la autoridad señale el monto a pagar por las copias solicitadas. (1 copia simple)'
                    ],
                    'detalles' => [
                        'observaciones' => 'Trámite mediante el cual se solicita la expedición de copias simples o certificadas de los documentos que obran en los archivos de las dependencias, órganos desconcentrados y Alcaldías de la Administración Pública de la Ciudad de México.',
                        'costo' => 'Sí. Articulo 248',
                        'materia' => '15 Servicios Legales',
                        'tiempo' => '7 Días hábiles',
                        'en_linea' => 'No',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],
                'Expedición de Certificado de Residencia' => [
                    'requisitos' => [
                    'Formato de solicitud TTLALPAN_ECR_1, debidamente requisitado. (Original y copia)',
                    'Documentos de identificación oficial. (Original y copia para cotejo)',
                    'Comprobantes de domicilio. (Original y copia para cotejo)',
                    'Comprobante de pago de derechos. (Original y copia para cotejo)',
                    'Dos fotografías tamaño infantil (blanco y negro o color).',
                    'En caso de que el interesado sea extranjero, documento vigente que acredite la legal estancia en el país. (Original y copia para cotejo)',
                    'En caso de que los comprobantes de domicilio no se encuentren a nombre del interesado: Manifestación por escrito del titular del inmueble, y copia de su identificación oficial, de que el solicitante reside en el domicilio señalado desde hace más de 6 meses; o dos cartas testimoniales de dos vecinos y sus respectivas identificaciones oficiales y comprobantes de domicilio a nombre de los mismos, manifestando bajo protesta de decir verdad que conocen y reside el solicitante en el domicilio señalado o cualquier otra prueba que lo acredite.',
                    'En caso de ser menor de edad: a) Acta de nacimiento. (Original y copia para cotejo)',
                    'b) Documento escolar (Certificado de estudios, credencial escolar, constancia con fotografía).',
                    'c) Identificación oficial del padre o tutor. (Original y copia para cotejo)',
                    'd) Comprobante de domicilio del padre o tutor. (Original y copia para cotejo)'
                    ],
                    'detalles' => [ 
                        'observaciones' => 'Trámite para obtener el documento que permite a las y los habitantes de la demarcación territorial acreditar que su domicilio legal se encuentra dentro de la misma con una antigüedad de al menos de seis meses un día.',
                        'costo' => 'Sí. Articulo 248',
                        'materia' => '15 Servicios Legales',
                        'tiempo' => '15 Días hábiles',
                        'en_linea' => 'No',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],

                'Solicitud de Visita de Verificación Administrativa' => [
                    'requisitos' => [
                        'Formato TTLALPAN_SVV_1 debidamente requisitado. (Original y copia)',
                        'Identificación Oficial Persona Física: Credencial para votar o Cédula profesional o Cartilla del Servicio Militar Nacional o Pasaporte o Carta de Naturalización. (Original y copia para cotejo)',
                        'Identificación Oficial Persona Moral: Acta constitutiva, Poder Notarial e   Identificación Oficial del representante o apoderado. (Original y copia para cotejo)',
                        'En su caso fotografías.'
                    ],
                    'detalles' => [
                        'observaciones' => 'Servicio mediante el cual se da atención a las quejas o inconformidades sobre Protección Civil, estacionamientos públicos, establecimientos mercantiles, construcciones, mercados, protección a la salud de los no fumadores, y espectáculos públicos en los que se hayan detectado posibles irregularidades.',
                        'costo' => 'Sí. Articulo 248',
                        'materia' => '15 Servicios Legales',
                        'tiempo' => '40 Días hábiles',
                        'en_linea' => 'Sí. 311.locatel.cdmx.gob.mx',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],

            ],
            'Obras y Desarrollo Urbano' => [
                'Expedición de Constancia de Alineamiento y/o Número Oficial' => [
                    'requisitos' => [
                    'Formato TTLALPAN_CAY_1, en dos tantos originales debidamente requisitado, con firmas autógrafas.',
                    'Tratándose de persona física, identificación oficial vigente con fotografía (credencial para votar, pasaporte, licencia de conducir, Cartilla del Servicio Militar Nacional o cédula profesional). (Copia simple y original para cotejo).',
                    'Podrá realizar el trámite una persona acreditada con carta poder firmada ante dos testigos, presentando su identificación oficial vigente con fotografía (cualquiera de las señaladas) y de la persona interesada. Copia simple y original para cotejo. En caso de que se acredite a una persona distinta para oír y recibir notificaciones, también deberá presentar identificación oficial vigente con fotografía (cualquiera de las señaladas). Copia simple y original para cotejo. Título de Propiedad o Documento con el que se acredite la legal posesión. Original y copia simple para cotejo. adicionalmente una copia simple del documento que acredite la propiedad o posesión del predio',
                    'Tratándose de persona moral, Acta Constitutiva y Poder Notarial que acredite la personalidad de representante legal e identificación oficial vigente con fotografía de esta persona (credencial para votar, pasaporte, licencia de conducir, Cartilla del Servicio Militar Nacional o cédula profesional). (Copia simple y original para cotejo).',
                    'Comprobante de pago de derechos correspondiente. (Copia simple y original para cotejo).',
                    'Documento que acredite la propiedad o posesión del predio. (Copia simple y original para cotejo).'
                    ],
                    'detalles' => [
                        'observaciones' => 'El trámite de número oficial mediante el cual la Secretaria de Planeación, Ordenamiento Territorial y Coordinación Metropolitana o la Alcaldía asigna un número oficial para cada predio que tenga frente a la vía pública, solo el número oficial deberá colocarse en la parte visible de la entrada de cada predio y ser claramente legible a una distancia mínima de 20 metros. El trámite de alineamiento es la traza sobre el terreno que limita el predio respectivo con la vía pública en uso, determinada en los planos debidamente aprobados. El alineamiento contendrá las afectaciones y las restricciones de carácter urbano que señale la Ley y su Reglamento.',
                        'costo' => 'Sí. Art. 233 y 234 del Código Fiscal Vigente',
                        'materia' => '5 Obras',
                        'tiempo' => '6 Días hábiles',
                        'en_linea' => 'Si. en: https://ventanilla.construccion.cdmx.gob.mx',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],
                'Registro de Manifestación de Construcción Tipo A' => [
                    'requisitos' => [
                    'Formato TTLALPAN_RMC_3 debidamente requisitado',
                    'Identificación oficial con fotografía',
                    'Constancia de alineamiento y número oficial vigente',
                    'Plano o croquis con ubicación y superficie',
                    'Constancia de no adeudo de Predial y Agua'
                    ],
                    'detalles' => [
                        'observaciones' => 'Trámite que permite realizar la construcción de hasta una vivienda unifamiliar de hasta 120 m2 construidos, ampliación de vivienda unifamiliar que no rebase 120 m2 construidos, reparación o modificación de vivienda unifamiliar, cambio de techos o entrepisos, construcción de bardas de hasta 5.5 m de altura, apertura de claros no mayores a 4 m e instalación de cisternas, fosas sépticas o albañales, en suelo urbano, en su caso, Revalidación o autorización de uso y ocupación',
                        'costo' => 'Si en base al Art 185 A Fracc. I inciso a y b Código Fiscal Vigente',
                        'materia' => '5 Obras',
                        'tiempo' => 'Inmediata (se envía a la DGDOU para su revisión derivado que en la VUT el trámite cumple con los requisitos para su registro, falta el análisis del contenido del mismo), y el área resolverá en 3 días hábiles en caso necesario se dará Revalidación.',
                        'en_linea' => 'Sí. en: https://ventanilla.construccion.cdmx.gob.mx',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],
                'Licencia de Anuncios Denominativos en Inmuebles ubicados en Vías Secundarias' => [
                    'requisitos' => [
                        'Formato TTLALPAN_LAD_1 debidamente requisitado. Original y copia Tratándose de persona física, identificación oficial vigente con fotografía (credencial para votar, pasaporte, licencia de conducir, Cartilla del Servicio Militar Nacional o cédula profesional). Original y copia para cotejo.',
                        'Podrá realizar el trámite una persona acreditada con carta poder firmada ante dos testigos, presentando su identificación oficial vigente con fotografía (cualquiera de las señaladas) y de la persona interesada. Original y copia para cotejo En caso de que se acredite a una persona distinta para oír y recibir notificaciones, también deberá presentar identificación oficial vigente con fotografía (cualquiera de las señaladas). Original y copia para cotejo',
                        'Tratándose de persona moral, Acta Constitutiva y Poder Notarial que acredite la personalidad de representante legal e identificación oficial vigente con fotografía de esta persona (credencial para votar, pasaporte, licencia de conducir, Cartilla del Servicio Militar Nacional o cédula profesional). Original y copia para cotejo En caso de que se acredite a una persona distinta para oír y recibir notificaciones, también deberá presentar identificación oficial vigente (cualquiera de las señaladas). Original y copia para cotejo',
                        'Comprobante de pago de derechos correspondiente. Original y copia para cotejo',
                        'Escritura del inmueble donde se pretende instalar el anuncio. 1 copia simple',
                        'Contrato de arrendamiento entre el poseedor o propietario del inmueble y el solicitante, en su caso. 1 copia simple',
                        'Carnet del Director o Directora Responsable de Obra y en su caso de cada Corresponsable. 1 copia simple',
                        'Constancia de no adeudo de impuesto predial emitida por la Administración Tributaria. Original y copia para cotejo',
                        'Constancia de no adeudo de agua, emitida por el Sistema de Aguas de la Ciudad de México. Original y copia para cotejo',
                        'Declaración bajo protesta de decir verdad de la persona responsable de la obra, en la que señale que no se afectarán árboles con motivo de las obras que pudieran llevarse a cabo con motivo de la instalación del anuncio.',
                        'Perspectiva o render de la edificación, en la que se considere también el anuncio de que se trate.',
                        'Cálculos estructurales y memoria estructural, tratándose de autosoportados y pantallas electrónicas.',
                        'Opinión técnica favorable de la Secretaría de Gestión Integral de Riesgos y Protección Civil de que el anuncio no representa un riesgo para la integridad física o patrimonial de las personas, salvo que se trate de anuncios pintados directamente en la fachada.',
                        'Póliza de seguro de responsabilidad civil por daños a terceros.',
                        'Tratándose de anuncios denominativos auto soportados, dictamen emitido por Director o Directora de Obra Responsable y, en su caso, por Corresponsable en Seguridad Estructural, en el que se señale que el diseño para la instalación del anuncio cumple con los criterios que en materia de riesgos establezca la Secretaría de Gestión Integral de Riesgos y Protección Civil.',
                        'Planos acotados y a escala:',
                        'a. De plantas y alzados;',
                        'b. Estructurales, tratándose de auto soportados;',
                        'c. De instalación eléctrica, en su caso, y',
                        'd. De iluminación, en su caso.',
                        'Los planos deberán incluir diseño, materiales estructurales, acabados, color, texturas, dimensiones y demás especificaciones técnicas del anuncio, así como una fotografía del inmueble. A su vez, los pies de plano correspondientes contendrán croquis de ubicación del anuncio, escala gráfica, fecha, nombre del plano y su número, nombres y firmas de la persona solicitante, Director o Directora Responsable de Obra, y en su caso, Corresponsable.',
                        'Los documentos previstos para este trámite deben entregarse tanto en versión digital como impresa y contener la firma de la persona solicitante y del Director o Directora Responsable de Obra.'
                    ],
                    'detalles' => [
                        'observaciones' => 'La licencia de anuncio denominativo permite a una persona física o moral instalar un anuncio que contenga una denominación, logotipo o emblema y eslogan con los que se identifica la edificación o local comercial donde se desarrolla la actividad que corresponda al anuncio.',
                        'costo' => 'Sí. Art 193 Fracc. V inciso b) del Código Fiscal Vigente',
                        'materia' => '5 Obras',
                        'tiempo' => '30 Días hábiles',
                        'en_linea' => 'No',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],
                'Autorización para romper el pavimento o hacer cortes en las banquetas y guarniciones en la vía pública para llevar a cabo su mantenimiento'    => [
                    'tipo_captura' => 'predio',
                    'requisitos' => [
                    'Formato TTLALPAN_ARP_1; debidamente requisitado. (Original y copia)',
                    'a) Copia de la identificación oficial del interesado y/o del representante legal',
                    'b) Copia del documento con el que acredite la personalidad del representante legal, en su caso',
                    'Tratándose de persona física con identificación oficial vigente con fotografía Tratándose de persona física, identificación oficial vigente con fotografía (credencial persona para votar, pasaporte, licencia de conducir, Cartilla del Servicio Militar Nacional o cédula profesional). (Copia simple y original para cotejo).',
                    'Podrá realizar el trámite una persona acreditada con carta poder firmada ante dos testigos, presentando su identificación oficial vigente con fotografía (cualquiera de las señaladas) y de la persona interesada. (Copia simple y original para cotejo). En caso de que se acredite a una persona distinta para oír y recibir notificaciones, también deberá presentar identificación oficial vigente con fotografía (cualquiera de las señaladas). (Copia simple y original para cotejo).',
                    'Tratándose de persona moral, Acta Constitutiva y Poder Notarial que acredite la personalidad de representante legal e identificación oficial vigente con fotografía de esta persona (credencial para votar, pasaporte, licencia de conducir, Cartilla del| Servicio Militar Nacional o cédula profesional). (Copia simple y original para cotejo).',
                    'En caso de que se acredite a una persona distinta para oír y recibir notificaciones, también deberá presentar identificación oficial vigente (cualquiera de las señaladas para cotejo y original Copia simple)',
                    'Reporte Fotográfico del antes y después de la obra.'
                    ],
                    'detalles' => [
                        'observaciones' => 'Trámite mediante el cual se obtiene la autorización, para que las personas físicas o morales puedan romper el pavimento, o llevar a cabo la reconstrucción de banquetas y guarniciones del o los inmuebles de los cuales son propietarios, bajo su propio costo.',
                        'costo' => 'Sin costo',
                        'materia' => '5 Obras',
                        'tiempo' => '40 Días hábiles',
                        'en_linea' => 'No',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],
                'Visto Bueno de Seguridad y Operación de las Instalaciones y su Renovación' => [
                    'requisitos' => [
                    'Formato TTLALPAN_RVB_1, en dos tantos originales debidamente requisitado, con firmas autógrafas.',
                    'Identificación oficial con fotografía (carta de naturalización o cartilla de servicio militar nacional o cédula profesional o pasaporte o certificado de nacionalidad mexicana o credencial para votar o licencia para conducir) (Original y copia para cotejo, persona física)',
                    'Documento con el que se acredite la personalidad, en los casos de representante legal (Acta Constitutiva). (Original y copia para cotejo).',
                    'Carnets Vigentes',
                    'Constancia de Seguridad Estructural sólo cuando el inmueble pertenezca al Grupo A o Subgrupo B1, de conformidad con el Artículo 139 fracciones I y II inciso a) del Reglamento de Construcciones para el Distrito Federal, Delegación C.P. Domicilio para oír y recibir notificaciones y documentos en la Ciudad De México * Los datos solicitados en este bloque son obligatorios. No. Exterior No. Interior Colonia Entidad Federativa Nombre del Notario, Corredor Público o Juez Inscripción en el Registro Público de la Propiedad y de Comercio Instrumento o documento con el que acredita la representación. (Original y copia)',
                    'La declaración bajo protesta de decir verdad del Director Responsable de Obra y el Corresponsable en Instalaciones, en su caso, de que la edificación e instalaciones correspondientes reúnen las condiciones de seguridad previstas por el Reglamento de Construcciones para el Distrito Federal para su operación y funcionamiento.',
                    'En el caso de giros industriales, debe acompañarse de la responsiva de un Corresponsable en Instalaciones, asimismo, la declaración del propietario y del Director Responsable de Obra de que en la construcción que se trate se cuenta con los equipos y sistemas de seguridad para situaciones de emergencia, cumpliendo con las Normas y las Normas Oficiales Mexicanas correspondientes, tanto la responsiva como las manifestaciones correspondientes se encuentran incluidas en este formato.',
                    'Declaración de la persona propietaria y el Director o Directora Responsable de Obra que la construcción cuenta con los equipos y sistemas de seguridad para situaciones de emergencia por lo que se cumple con las obligaciones derivadas de las Normas Oficiales Mexicanas y demás normatividad aplicable.',
                    'En su caso, los resultados de las pruebas a las que se refieren los artículos 185 y 186 del Reglamento de Construcciones para el Distrito Federal, cuando sea necesario comprobar la seguridad de una estructura por medio de pruebas de carga en los siguientes casos:',
                    'a) En las obras provisionales o de recreación que puedan albergar a más de 100 personas; determinado por el dictamen técnico de estabilidad o seguridad estructural expedido por un Corresponsable en Seguridad Estructural',
                    'b) Cuando no exista suficiente evidencia teórica o experimental para juzgar en forma confiable la seguridad de la estructura en cuestión, y *Cuando la Delegación previa opinión de la Secretaría de Obras y Servicios lo determine conveniente en razón de duda en la calidad y resistencia de los materiales o en cuanto al proyecto estructural y a los procedimientos constructivos',
                    'c) Cuando la Alcaldía previa opinión de la Secretaria de Obras y Servicios, lo determine conveniente en razón de duda en la calidad y resistencia de los materiales o en cuanto al proyecto estructural y a los procedimientos constructivos.'
                    ],
                    'detalles' => [
                        'observaciones' => 'Trámite que se realiza para las edificaciones e instalaciones como: escuelas públicas o privadas y cualquier otra edificación destinadas a la enseñanza; centros de reunión, como cines, teatros, salas de conciertos, salas de conferencias, auditorios, cabarets, discotecas y cualquier otro con una capacidad de ocupación superior a las 50 personas con uso distinto al habitacional, instalaciones deportivas o recreativas que sean objeto de explotación mercantil, con una capacidad de ocupación superior a las 50 personas, ferias con aparatos mecánicos, circos, carpas y cualesquier otro con usos semejantes, ascensores para personas, montacargas, escaleras mecánicas o cualquier otro mecanismo de transporte electromecánico, y edificaciones o locales donde se realicen actividades de algún giro industrial en las que excedan la ocupación de 40 m2, hospitales y clínicas, albercas con iluminación subacuática, estaciones de servicio para expendio de combustible y carburantes, así como plataformas de aterrizaje y despegue de helicópteros.',
                        'costo' => 'Sin costo',
                        'materia' => '5 Obras',
                        'tiempo' => 'Inmediata (se envía a la DGDOU para su revisión derivado que en la VUT el trámite cumple con los requisitos para su registro, falta el análisis del contenido del mismo).',
                        'en_linea' => 'Sí. en: https://ventanilla.construccion.cdmx.gob.mx  ',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],
                'Expedición de Licencia de Construcción Especial. Revalidación de la Licencia de Construcción Especial y Aviso de Terminación de Obra o Autorización de Uso y Ocupación, en su caso' => [
                    'requisitos' => [
                    'Formato TTLALPAN_LCE_1, en dos tantos originales debidamente requisitados, con firmas autógrafas.',
                    'Identificación oficial con fotografía (credencial para votar o licencia para conducir o cartilla del servicio militar nacional o pasaporte o cédula profesional o carta de naturalización o certificado de nacionalidad mexicana) Original y copia.',
                    'Documento que acredite la personalidad del representante legal, de resultar aplicable. (Acta Constitutiva, Poder Notarial, Carta Poder). Original y copia',
                    'Comprobante de pago de derechos, el cual debe presentarse posterior al ingreso de la solicitud una vez que la autoridad informe al interesado el monto a pagar. (original y copia)',
                    'Constancia de Adeudos de Predial emitida por la Administración Tributaria y el Sistema de Aguas de la Ciudad de México en la que se acredite que se encuentran al corriente de sus obligaciones. (original y copia)',
                    'Constancia de Adeudos de Agua emitida por la Administración Tributaria y el Sistema de Aguas de la Ciudad de México en la que se acredite que se encuentran al corriente de sus obligaciones. (original y copia)',
                    'a) En caso, de que la Licencia de Construcción Especial se solicite para que la obra se realice en la vía pública no serán necesarias presentar las Constancias de Adeudos.',
                    'Constancia de alineamiento y número oficial vigente. (original y copia)',
                    'Certificado único de zonificación de uso de suelo o certificado único de zonificación del suelo digital o certificado de acreditación de uso del suelo por derechos adquiridos, los cuales deberán ser verificados y firmados por el Director Responsable de Obra y/o Corresponsable en Diseño Urbano y Arquitectónico, en su caso. (original y copia)',
                    'Proyecto alternativo de captación y aprovechamiento de aguas pluviales y de tratamiento de aguas residuales aprobados por el Sistema de Aguas de la Ciudad de México. (original y copia)',
                    'Dos tantos del proyecto arquitectónico de la obra en planos a escala, de acuerdo a lo establecido en el formato TTLALPAN_LCE_1.',
                    'Memoria descriptiva de proyecto, de acuerdo a lo establecido en el formato TTLALPAN_LCE_1',
                    'Dos tantos de los proyectos de las instalaciones hidráulicas incluyendo el uso de sistemas para calentamiento de agua por medio del aprovechamiento de la energía solar, conforme a los artículos 82, 83 y 89 del Reglamento de Construcciones para el Distrito Federal, sanitarias, eléctricas, de gas e instalaciones especiales y otras que se requieran, en los que se debe incluir como mínimo: plantas, cortes e isométricos en su caso, mostrando las trayectorias de tuberías, alimentaciones, así como el diseño y memorias correspondientes; incluyendo la descripción de los dispositivos que cumplan con los requerimientos establecidos por el Reglamento y sus Normas en cuanto a salidas y muebles hidráulicos y sanitarios, equipos de extinción de fuego, sistema de captación y aprovechamiento de aguas pluviales en azotea y otras que considere el proyecto. Deberán estar firmados por el propietario o poseedor, por el proyectista indicando su número de cédula profesional, por el Director Responsable de Obra y el Corresponsable en Instalaciones, en su caso.',
                    'Dos tantos del proyecto estructural de la obra en planos debidamente acotados, con las especificaciones señaladas en el formato TTLALPAN_LCE_1.',
                    'Memoria de cálculo en la cual se describirán con el nivel de detalle suficiente para que puedan ser evaluados por un especialista externo al proyecto, debiéndose respetar los contenidos señalados en lo dispuesto en la memoria estructural consignada en el artículo 53 fracción I, inciso e) del Reglamento. Copia simple y original para cotejo.',
                    'Proyecto de protección a colindancias y estar firmados por el proyectista indicando su número de cédula profesional, así como el Director Responsable de Obra y el Corresponsable en Seguridad Estructural, en su caso. Copia simple y original para cotejo.',
                    'Dos tantos del estudio de mecánica de suelos del predio de acuerdo con los alcances y lo establecido en las Normas Técnicas Complementarias para Diseño y Construcción de Cimentaciones del Reglamento, incluyendo los procedimientos constructivos de la excavación, muros de contención y cimentación, así como las recomendaciones de protección a colindancias. Deberá estar firmado por el especialista indicando su número de cédula profesional, así como por el Director Responsable de Obra y por el Corresponsable en Seguridad Estructural, en su caso.',
                    'Para el caso, de las edificaciones que pertenezcan al grupo A o subgrupo B1, según el artículo 139 del Reglamento, o para las edificaciones del subgrupo B2, acuse de ingreso de la orden de revisión del proyecto estructural emitido por el Instituto para la Seguridad de las Construcciones en la Ciudad de México. Copia simple y original para cotejo.',
                    'Libro de bitácora de obra foliado, para ser sellado por la Secretaría de Desarrollo Urbano y Vivienda o la Delegación, el cual debe conservarse en la obra, realizando su apertura en el sitio con la presencia de los autorizados para usarla, quienes lo firmarán en ese momento. Original.',
                    'Responsiva del Director Responsable de Obra del proyecto de la obra, así como de los Corresponsables en Seguridad Estructural, en Diseño Urbano y Arquitectónico e Instalaciones, las cuales se encuentran incluidas en este formato.',
                    'Dictamen favorable del estudio de impacto ambiental, en su caso. Copia simple y original para cotejo.',
                    'Póliza vigente del seguro de responsabilidad civil por daños a terceros en las obras clasificadas en el Grupo A y Subgrupo B1, según el artículo 139 de este Reglamento. Por un monto asegurado no menor del 10% del costo total de la obra construida por el tiempo de vigencia de la licencia de construcción especial. Copia simple y original para cotejo.',
                    'Dictamen técnico favorable de la Secretaría de Desarrollo Urbano y Vivienda cuando se trate de Áreas de Conservación Patrimonial y/o inmuebles afectos al patrimonio cultural urbano o sus colindantes; y/o visto bueno del Instituto Nacional de Bellas Artes y/o la licencia del Instituto Nacional de Antropología e Historia para el caso de un monumento histórico, artístico o arqueológico, según sea su ámbito de competencia de acuerdo con lo establecido en la Ley Federal en la materia. Copia simple y original para cotejo.'
                    ],
                    'detalles' => [
                        'observaciones' => 'La licencia de construcción especial es el documento que expide la Secretaría de Desarrollo Urbano y Vivienda o la Alcaldía a las y los solicitantes para poder construir, ampliar, modificar, reparar, instalar, demoler, desmantelar una obra o instalación, colocar tapial, excavar cuando no sea parte del proceso de construcción de un edificio, así como para realizar estas actividades en suelo de conservación.',
                        'costo' => 'Sí. Art 186 del Código Fiscal Vigente depende de la modalidad',
                        'materia' => '5 Obras',
                        'tiempo' => '1 o hasta 30 días hábiles',
                        'en_linea' => 'No',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],
                'Expedición de Licencia de Relotificación y su Revalidación' => [
                    'requisitos' => [
                    'Formato TTLALPAN_ELR_1, en dos tantos originales debidamente requisitados, con firmas autógrafas.',
                    'Boleta predial del último bimestre de cada inmueble involucrado. (Original y copia para cotejo)',
                    'Certificado Único de Zonificación de Uso del Suelo o Certificado Único de Zonificación del Suelo Digital o Certificado de Acreditación de Uso del Suelo por Derechos Adquiridos. (Original y copia para cotejo)',
                    'Constancia de alineamiento y número oficial. (Original y copia para cotejo)',
                    'Escritura de propiedad del o de los inmuebles. (Original y copia simple)',
                    'Croquis en original y dos tantos que contengan, de acuerdo a lo establecido en el formato TTLALPAN_ELR_1',
                    'Constancia de Adeudos de Predial y Agua emitida por la Administración Tributaria y el Sistema de Aguas de la Ciudad de México en la que se acredite que se encuentran al corriente de sus obligaciones. (Original y copia)',
                    'Avalúo vigente de los terrenos, para el cálculo de los derechos. Original y copia',
                    'Comprobante de pago del 1% del valor del Avaluó para la Licencia de Relotificación en original y copia, el cual debe presentar posterior al ingreso de la solicitud una vez que la autoridad informe al interesado el monto a pagar',
                    'Identificación oficial con fotografía. (Original y copia)',
                    'Documento con el que se acredite la personalidad, en los casos de representante legal. (Original y copia)',
                    'b) Tratándose de licencias de relotificación, para predios mayores a 10 veces el lote tipo que marquen los Programas Delegacionales:',
                    'En el caso de que requiera estudio de impacto urbano o urbano-ambiental, dictamen aprobatorio de la Secretaría. (Original y copia)',
                    'Registros de declaración de apertura o licencias de funcionamiento, en su caso. Original y copia',
                    'Registro de manifestación de construcción, en su caso. (Original y copia)',
                    'Licencia de Construcción Especial, en su caso. (Original y copia)',
                    'Croquis de localización del polígono a relotificar, a escala de 1:500 a 1:5000, según sea su dimensión. (Original y copia)',
                    'Memoria descriptiva, impresa y en medio magnético. (Original y copia)',
                    'La relación de propietarios e interés, con expresión de la naturaleza y cuantía de su derecho; impresa y en medio magnético. (Original y copia)',
                    'La propuesta de adjudicación de inmuebles resultantes, con determinación de su uso y designación nominal de los adjudicatarios; impresa y en medio magnético. (Original y copia)',
                    'El avalúo de los inmuebles que se adjudicarán; impreso y en medio magnético. (Original y copia)',
                    'El avalúo de los derechos, edificaciones, construcciones o plantaciones que deben extinguirse o destruirse para la ejecución del proyecto de relotificación, impreso y en medio magnético. (Original y copia)',
                    'La cuenta de liquidación provisional, impresa y en medio magnético. (Original y copia)',
                    'Los planos catastrales con división de predios, impreso y en medio magnético. (Original y copia)',
                    'El plano de situación y relación con el entorno urbano, impreso y en medio magnético. (Original y copia)',
                    'El plano de delimitación del polígono a relotificar, de acuerdo a lo establecido en el formato TTLALPAN_ELR_1',
                    'Los planos de zonificación que contengan la expresión gráfica de las normas de ordenación a que se refieren los Programas, impreso y en medio magnético. (Original y copia)',
                    'El plano de clasificación y avalúo de las superficies adjudicadas, impreso y en medio magnético. (Original y copia)',
                    'El plano de adjudicación con expresión de los linderos de los inmuebles adjudicados, impreso y en medio magnético. (Original y copia)',
                    'Los planos impresos que se entregarán en una escala comprendida entre 1:500 y 1:5000, con la calidad suficiente para que puedan percibirse los linderos y la simbología utilizada, impreso y en medio magnético. (Original y copia)',
                    'Proyecto de Relotificación. (Original y copia)'
                    ],
                    'detalles' => [
                        'observaciones' => 'Trámite mediante el cual dos o más propietarias y/o de un inmueble pretendan, agrupar varios inmuebles comprendidos en un polígono de actuación sujeto a mejoramiento, para su nueva división, o rectificar los linderos de dos o más predios colindantes.',
                        'costo' => 'Sí. Art 188 del Código Fiscal Vigente 1% del valor del avalúo',
                        'materia' => '13 Obras',
                        'tiempo' => '60 días hábiles, para emitir resolución, una vez que se tenga favorable, realiza el pago y contará con 3 días hábiles, presenta pago y tendrá 15 días hábiles de Revalidación.',
                        'en_linea' => 'No',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],
                'Expedición de Licencia de Subdivisión, Fusión y Revalidación.' => [
                    'requisitos' => [
                    'Formato TTLALPAN_ELS_1 en dos tantos originales debidamente requisitados, con firmas autógrafas.',
                    'Documento con el que se acredite la personalidad, en los casos de representante legal. (Original y copia para cotejo)',
                    'Identificación oficial con fotografía (carta de naturalización o cartilla de servicio militar nacional o cédula profesional o pasaporte o certificado de nacionalidad mexicana o credencial para votar o licencia para conducir). (Original y copia para cotejo)',
                    'Boleta predial del último bimestre. (Original y copia para cotejo)',
                    '5.Certificado Único de Zonificación de Usos del Suelo. (Original y copia)',
                    'Constancia de alineamiento y/o número oficial. (Original y copia para cotejo)',
                    'Croquis en original y dos tantos que contengan, en la parte superior, la situación actual del o de los inmuebles, consignando las calles colindantes, la superficie y linderos reales del predio y, en la parte inferior, el anteproyecto de fusión o subdivisión, consignando también las calles colindantes, la superficie y linderos del predio o predios resultantes.',
                    'Escritura de propiedad del o de los inmuebles que pretende subdividir. (Original y copia simple para cotejo)',
                    'Constancia de Adeudos de Predial y Agua emitida por la Administración Tributaria y el Sistema de Aguas de la Ciudad de México en la que se acredite que se encuentran al corriente de sus obligaciones. (Original y copia)',
                    'Avalúos del o de los terrenos. (Original y copia) En caso de ser aprobada la solicitud y una vez que el interesado reciba la notificación por la autoridad correspondiente se presentará el o los avalúos del o de los terrenos, elaborados de conformidad con el Manual de Procedimientos Técnicos de Evaluación Inmobiliaria, así como de Autorización y Registro de Personas para practicar Avalúos expedido por la Secretaría de Finanzas de la Ciudad de México',
                    'Comprobante de pago de los derechos de la Licencia de Subdivisión o Fusión en original y copia, el cual debe presentar posterior al ingreso de la solicitud una vez que la autoridad informe al interesado el monto a pagar'
                    ],
                    'detalles' => [
                        'observaciones' => 'Trámite mediante el cual las y los propietarios de los predios podrán obtener la licencia que les permita llevar a cabo la subdivisión o fusión de un predio.',
                        'costo' => 'Sí. Art 188 del Código Fiscal Vigente 1% del valor del avalúo',
                        'materia' => '13 Obras',
                        'tiempo' => '30 días hábiles para emitir resolución en caso de ser negativo, y contara, 5 días para subsanar y nuevamente emiten resolución positiva concluye el trámite.',
                        'en_linea' => 'No',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],
                'Registro de Constancia de Verificación de Seguridad Estructural y su Renovación' => [
                    'requisitos' => [
                    'Formato TTLALPAN_CSE_1, en dos tantos originales debidamente requisitados, con firmas autógrafas.',
                    'Tratándose de persona física, identificación oficial vigente con fotografía (credencial para votar, pasaporte, licencia de conducir, Cartilla del Servicio Militar Nacional o cédula profesional). Copia simple y original para cotejo. Podrá realizar el trámite una persona acreditada con carta poder firmada ante dos testigos, presentando su identificación oficial vigente con fotografía (cualquiera de las señaladas) y de la persona interesada. Copia simple y original para cotejo. En caso de que se acredite a una persona distinta para oír y recibir notificaciones, también deberá presentar identificación oficial vigente con fotografía (cualquiera de las señaladas). Copia simple y original para cotejo.',
                    'Tratándose de persona moral, Acta Constitutiva y Poder Notarial que acredite la personalidad de representante legal e identificación oficial vigente con fotografía de esta persona (credencial para votar, pasaporte, licencia de conducir, Cartilla del Servicio Militar Nacional o cédula profesional). Copia simple y original para cotejo. En caso de que se acredite a una persona distinta para oír y recibir notificaciones, también deberá presentar identificación oficial vigente (cualquiera de las señaladas). Copia simple y original para cotejo.',
                    'Carnets vigentes del Director o Directora Responsable de Obra y del Corresponsable de Instalaciones vigentes. Copia simple y original para cotejo. Carnet Vigente.'
                    ],
                    'detalles' => [
                        'observaciones' => 'a)	El Registro de la Constancia de Seguridad Estructural se debe solicitar cuando el inmueble pertenezca al grupo A (edificaciones cuya falla estructural podría causar un número elevado de pérdidas de vidas humanas, o constituir un peligro significativo por contener sustancias tóxicas o explosivas, y edificaciones cuyo funcionamiento es esencial ante una emergencia urbana) o subgrupo B1 (edificaciones de más de 30 m de altura o con más de 6,000 m2 de área total construida, ubicadas en las zonas I y II a que se aluden en el artículo 170 del Reglamento de Construcciones para el Distrito Federal, y construcciones de más de 15 m de altura o más de 3,000 m2 de área total construida, en zona III; en ambos casos las áreas se refieren a un solo cuerpo de edificio que cuente con medios propios de desalojo: acceso y escaleras, incluyendo las áreas de anexos, como pueden ser los propios cuerpos de escaleras. El área de un cuerpo que no cuente con medios propios de desalojo se adicionará a la de aquel otro a través del cual se desaloje.)',
                        'costo' => 'Sin costo',
                        'materia' => '5 Obras',
                        'tiempo' => 'Inmediata (se envía a la DGDOU para su revisión derivado que en la VUT el trámite cumple con los requisitos para su registro, falta el análisis del contenido del mismo).',
                        'en_linea' => 'Sí. en: https://ventanilla.construccion.cdmx.gob.mx  ',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],
                'Registro de Manifestación de Construcción Tipo A, revalidación del registro y aviso de terminación de obra' => [
                    'requisitos' => [
                    'Formato TTLALPAN_RMC_3, en dos tantos originales, debidamente requisitados, con firmas autógrafas.',
                    'Identificación oficial con fotografía (carta de naturalización o cartilla de servicio militar nacional o cédula profesional o pasaporte o certificado de nacionalidad mexicana o credencial para votar o licencia para conducir) (Original y copia para cotejo). Persona física',
                    'Documento con el que se acredite la personalidad, en los casos de representante legal. (Original y copia)',
                    'Comprobante de pago de los derechos establecidos en el Código Fiscal de la Ciudad de México. (Original y copia)',
                    'Constancia de alineamiento y número oficial vigente, excepto para apertura de claros de 1.5 m como máximo en construcciones hasta de dos niveles, si no se afectan elementos estructurales y no se cambia total o parcialmente el uso o destino del inmueble; e instalación o construcción de cisternas, fosas sépticas o albañales. (Original y copia)',
                    'Plano o croquis que contenga la ubicación, superficie del predio, metros cuadrados por construir, distribución y dimensiones de los espacios, área libre, y en su caso, número de cajones de estacionamiento. (Original y copia)',
                    'Aviso de intervención registrado por la Secretaría de Desarrollo Urbano y Vivienda, cuando el inmueble se encuentre en área de conservación patrimonial de la Ciudad de México. (Original y copia)',
                    'Para el caso de construcciones que requieran la instalación de tomas de agua y conexión a la red de drenaje, la solicitud y comprobante del pago de derechos. (Original y copia)',
                    'Autorización emitida por autoridad competente (INHA, INBAL, SPOTMET) cuando la obra se realice en inmuebles afectos al patrimonio cultural, urbano o que este ubicada en áreas de conservación patrimonial, incluyendo las zonas de monumentos declaradas por la federación. (Original y copia simple para cotejo)',
                    'Constancia de no adeudo de impuesto predial emitida por la Administración Tributaria. (Original y copia simple para cotejo)',
                    'Constancia de no adeudo de agua emitida por el Secretaria de Gestión Integral del Agua de la Ciudad de México. (Original y copia simple para cotejo)',
                    'En caso de ampliación, se debe presentar Licencia de Construcción, Registro de Manifestación de Construcción o Registro de Obra Ejecutada de la edificación original e identificar en el plano o croquis la edificación original y área de ampliación. (Original y copia simple para cotejo)'
                    ],
                    'detalles' => [
                        'observaciones' => 'Trámite mediante el cual se obtiene la autorización para que las personas físicas o morales puedan construir, ampliar, modificar, reparar, instalar, demoler o desmantelar una obra o instalación, colocar tapial, excavar cuando no sea parte del proceso de construcción de un edificio, así como para realizar estas actividades en suelo de conservación.',
                        'costo' => 'Sí. Art 186 del Código Fiscal Vigente depende de la modalidad',
                        'materia' => '5 Obras',
                        'tiempo' => '1 o hasta 30 días hábiles',
                        'en_linea' => 'No',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],
                'Registro de Manifestación de Construcción Tipo B, C, Revalidación del registro y Aviso de terminación de obra, Uso y Ocupación' => [
                    'requisitos' => [
                    'Formato de solicitud TTLAPAN_RMC_1 en dos tantos originales debidamente requisitados, con firmas autógrafas.',
                    'Tratándose de persona física, identificación oficial vigente con fotografía (credencial para votar, pasaporte, licencia de conducir, Cartilla del Servicio Militar Nacional o cédula profesional). Copia simple y original para cotejo. Podrá realizar el trámite una persona acreditada con carta poder firmada ante dos testigos, presentando su identificación oficial vigente con fotografía (cualquiera de las señaladas) y de la persona interesada. Copia simple y original para cotejo. En caso de que se acredite a una persona distinta para oír y recibir notificaciones, también deberá presentar identificación oficial vigente con fotografía (cualquiera de las señaladas). Copia simple y original para cotejo.',
                    'Tratándose de persona moral, Acta Constitutiva y Poder Notarial que acredite la personalidad de representante legal e identificación oficial vigente con fotografía de esta persona (credencial para votar, pasaporte, licencia de conducir, Cartilla del Servicio Militar Nacional o cédula profesional). Copia simple y original para cotejo. En caso de que se acredite a una persona distinta para oír y recibir notificaciones, también deberá presentar identificación oficial vigente (cualquiera de las señaladas). Copia simple y original para cotejo.',
                    'Comprobante de Pago de los derechos establecidos en el Código Fiscal de la Ciudad de México. Original y copia',
                    'Constancia de no adeudo de impuesto predial emitida por la Administración Tributaria. Copia simple y original para cotejo.',
                    'Constancia de no adeudo de agua, emitida por el Sistema de Aguas de la Ciudad de México. Copia simple y original para cotejo.',
                    'Constancia de alineamiento y número oficial vigente. Original y copia',
                    'Certificado Único de Zonificación de Uso del Suelo o Certificado Único de Zonificación del Suelo Digital o Certificado de Acreditación de Uso del Suelo por Derechos Adquiridos, los cuales deberán ser verificados y firmados por el Director Responsable de Obra y/o Corresponsable en Diseño Urbano y Arquitectónico, en su caso. Original y copia',
                    'Dos tantos del proyecto arquitectónico de la obra en planos a escala, de acuerdo a lo establecido en el formato TTLAPAN_RMC_1.',
                    'Memoria Descriptiva del proyecto de acuerdo a lo establecido en el formato TTLAPAN_RMC_1.',
                    'Dos tantos de los proyectos de las instalaciones hidráulicas incluyendo el uso de sistemas para calentamiento de agua por medio del aprovechamiento de la energía solar, de acuerdo a los requerimientos del formato TTLAPAN_RMC_1.',
                    'Dos tantos del proyecto estructural de la obra en planos debidamente acotados, de acuerdo a lo establecido en el formato TTLAPAN_RMC_1.',
                    'Memoria de Cálculo Estructural, será expedida en papel membretado de la Empresa o del proyectista, en donde conste su número de cédula profesional y firma, así como, la descripción del proyecto, localización, número de niveles subterráneos y uso conforme a lo establecido en el artículo 53 inciso e), séptimo párrafo del Reglamento de Construcciones para el Distrito Federal. Original y copia',
                    'Proyecto de protección a colindancias firmados por el proyectista indicando su número de cédula profesional, así como el Director Responsable de Obra y el Corresponsable en Seguridad Estructural, en su caso. Original y copia',
                    'Estudio de mecánica de suelos del predio de acuerdo con los alcances y lo establecido en las Normas Técnicas Complementarias para Diseño y Construcción de Cimentaciones del Reglamento, incluyendo los procedimientos constructivos de la excavación, muros de contención y cimentación, así como las recomendaciones de protección a colindancias. El estudio debe estar firmado por el especialista indicando su número de cédula profesional, así como por el Director Responsable de Obra y por el Corresponsable en Seguridad Estructural, en su caso. (por duplicado) 12. Para el caso de las edificaciones que pertenezcan al grupo A o subgrupo B1, según el artículo 139 del Reglamento, o para las edificaciones del subgrupo B2, acuse de ingreso de la orden de revisión del proyecto estructural emitido por el Instituto para la Seguridad de las Construcciones de la Ciudad de México. Original y copia',
                    'Para el caso de las edificaciones que pertenezcan al grupo A o subgrupo B1, según el artículo 139 del Reglamento, o para las edificaciones del subgrupo B2, acuse de ingreso de la solicitud de la constancia de “Registro de la Revisión por parte del Corresponsable de seguridad Estructural, del Proyecto Estructural, emitido por el Instituto para la Seguridad de las Construcciones de la Ciudad de México. Original y copia.',
                    'Libro de bitácora de obra foliado, para ser sellado por la Secretaría de Desarrollo Urbano y Vivienda o la Delegación, el cual debe conservarse en la obra, realizando su apertura en el sitio con la presencia de los autorizados para usarla, quienes lo firmarán en ese momento. Original',
                    'Responsiva del Director Responsable de Obra del proyecto de la obra, así como de los Corresponsables en los supuestos señalados en el artículo 36 del Reglamento de Construcciones para el Distrito Federal. Se encuentra en este formato de solicitud.',
                    'Póliza vigente del seguro de responsabilidad civil por daños a terceros en las obras clasificadas en el grupo A y subgrupo B1, según el artículo 139 del Reglamento de Construcciones para el Distrito Federal, por un monto asegurado no menor del 10% del costo total de la obra construida por el tiempo de vigencia de la Manifestación de Construcción. Copia simple y original para cotejo.',
                    'Para el caso de construcciones que requieran la instalación de tomas de agua y conexión a la red de drenaje, la solicitud y comprobante del pago de derechos. Copia simple y original para cotejo. Aviso ante el Instituto, cuando se trate de trabajos para la rehabilitación sísmica de edificios dañados.',
                    'Dictamen de Factibilidad de Servicios Hidráulicos. Copia simple y original para cotejo',
                    'Dictamen favorable del estudio del impacto urbano o impacto urbano ambiental, en su caso. Copia simple y original para cotejo.',
                    'Presentar acuse de recibo de la Declaratoria Ambiental ante la Secretaría del Medio Ambiente, cuando se trate de proyectos habitacionales de más de 20 viviendas. Copia simple y original para cotejo.',
                    'En zonas de conservación patrimonial con valor histórico, artístico o arqueológico, licencia del Instituto Nacional de Antropología e Historia, visto bueno del Instituto Nacional de Bellas Artes o dictamen de la Secretaría de Desarrollo Urbano y Vivienda, en su caso. Copia simple y original para cotejo.',
                    'En caso de trabajos para la rehabilitación sísmica de edificios dañados, aviso ante el Instituto para la Seguridad de las Construcciones en la Ciudad de México. Copia simple y original para cotejo.'
                    ],
                    'detalles' => [
                        'observaciones' => 'El Registro de manifestación de construcción tipo B es el trámite que se realiza para construir, ampliar, reparar o modificar una obra o instalación de uso no habitacional o mixto de hasta 5,000 m2 o hasta 10,000 m2 con uso habitacional. El Registro de manifestación de construcción tipo C es el trámite que se realiza para construir, ampliar, reparar o modificar una obra o instalación de uso no habitacional o mixto de más de 5,000 m2 o más de 10,000 m2 con uso habitacional, o construcciones que requieran de dictamen de impacto urbano o impacto urbano-ambiental.',
                        'costo' => 'Sí. Art 185 A Fracc. II inciso a y b, Fracc. III inciso a y b, Art 185 B Fracc. I inciso a y b, Fracc. II inciso a y b Código Fiscal Vigente 181, 182, 300, 301',
                        'materia' => '5 Obras',
                        'tiempo' => 'Inmediata (se envía a la DGDOU para su revisión derivado que en la VUT el trámite cumple con los requisitos para su registro, falta el análisis del contenido del mismo), 5 días hábiles para el Trámite de Uso y Ocupación.',
                        'en_linea' => 'Sí. en: https://ventanilla.construccion.cdmx.gob.mx',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],
                'Registro de Obra Ejecutada' => [
                    'requisitos' => [
                    'Formato TTLALPAN_ROE_1, en dos tantos originales debidamente requisitados y con firmas autógrafas.',
                    'Identificación oficial con fotografía (carta de naturalización o cartilla de servicio militar nacional o cédula profesional o pasaporte o certificado de nacionalidad mexicana o credencial para votar o licencia para conducir) (Original y copia).',
                    'Documento con el que se acredite la personalidad, en los casos de representante legal. (Original y copia).',
                    'Comprobante de pago de derechos, de acuerdo al tipo de obra, el cual debe presentarse posterior al ingreso de la solicitud una vez que la autoridad informe al interesado el monto a pagar. Original y copia y Comprobante de pago de la sanción equivalente del 5 al 10% del valor de las construcciones en proceso o terminadas, el cual debe presentar posterior al ingreso de la solicitud una vez que la autoridad informe al interesado el monto a pagar.',
                    'Constancia de alineamiento y número oficial vigente. (Original y copia)',
                    'Para el caso de construcciones que requieran la instalación de tomas de agua y conexión a la red de drenaje, la solicitud y comprobante del pago de derechos. (Original y copia)',
                    'Constancia de Adeudos de predial por la Administración Tributaria de la Ciudad de México en la que se acredite que se encuentra al corriente de sus contribuciones. (Original y copia). En caso, de que la obra se haya ejecutado en la vía pública no serán necesarias presentar las Constancias de Adeudos',
                    'Constancias del pago de Adeudos del agua emitida y el Sistema de Aguas de la Ciudad de México en la que se acredite que se encuentra al corriente de sus contribuciones. (Original y copia). En caso, de que la obra se haya ejecutado en la vía pública no serán necesarias presentar las Constancias de Adeudos',
                    'Avalúo emitido por un valuador registrado ante la Secretaría de Finanzas. (Original y copia, conforme al formato)',
                    'Demás documentos que el Reglamento de Construcciones para el Distrito Federal y otras disposiciones exijan para el registro de manifestación de construcción o para expedición de licencia de construcción especial, con las responsivas de un Director de Obra, y de los Corresponsables (se encuentran dentro de este formato), en su caso. De acuerdo al artículo 72 del Reglamento de Construcciones para el Distrito Federal.'
                    ],
                    'detalles' => [
                        'observaciones' => 'Trámite que otorga el registro a una obra ejecutada cuando esta se haya realizado sin contar con el registro de manifestación de construcción o licencia de construcción especial, y se demuestre que cumple con las disposiciones normativas y los Programas Delegacionales de Desarrollo Urbano.',
                        'costo' => 'Sí. Art. 253 del Reglamento de Construcciones 10% del valor del avalúo',
                        'materia' => '5 Obras',
                        'tiempo' => '20 días hábiles',
                        'en_linea' => 'No',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],
                'Expedición de copias certificadas que obren en los archivos de la Delegación' => [
                    'requisitos' => [
                    'Formato TTLALPAN_ ECS_2 debidamente requisitado. (Original y copia)',
                    'Documentos de identificación oficial. (Original y copia para cotejo)',
                    'Documentos de acreditación de personalidad jurídica. (Original y copia para cotejo)',
                    'Documentos con los que se acredite interés jurídico, en original o copia certificada, y copia simple (ejemplo: Sentencia Judicial).',
                    'Comprobante de pago de derechos por búsqueda y una vez que la autoridad señale el monto a pagar por las copias solicitadas'
                    ],
                    'detalles' => [
                        'observaciones' => 'Trámite mediante el cual se solicita la expedición de copias simples o certificadas de los documentos que obran en los archivos de las dependencias, órganos desconcentrados y Alcaldías de la Administración Pública de la Ciudad de México.',
                        'costo' => 'Sí. Art 248 Fracc V Código Fiscal Vigente',
                        'materia' => '15 Obras',
                        'tiempo' => '15 días hábiles',
                        'en_linea' => 'No',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
                ],
                'Constancia de Publicitación Vecinal para Construcciones que Requieren Registro de Manifestación Tipo B o C, Licencias Especiales' => [
                    'requisitos' => [
                    'Formato TTLALPAN_CPV_1 debidamente requisitado. (Original y copia)',
                    'Identificación oficial vigente (credencial para votar, pasaporte, licencia de conducir, Cartilla del Servicio Militar Nacional o cédula profesional) de las personas: solicitante, su representante legal y acreditada para oír y recibir notificaciones, en su caso. (Original y copia para cotejo)',
                    'Tratándose de persona moral, acta constitutiva y documento que acredite la personalidad de representante legal. (Original y copia para cotejo)',
                    'Constancia de alineamiento y número oficial vigente, (Original y copia para cotejo)',
                    'Certificado Único de Zonificación de Uso del Suelo o certificado de acreditación de uso del suelo por derechos adquiridos o el resultado de la consulta del Sistema de Información Geográfica relativo al uso y factibilidades del predio. (Original y copia para cotejo)',
                    'Un tanto del proyecto arquitectónico de la obra en planos a escala, debidamente acotados y con las especificaciones de los materiales, acabados y equipos a utilizar, en los que se debe incluir, como mínimo: croquis de localización del predio, levantamiento del estado actual, indicando las construcciones y árboles existentes; planta de conjunto, mostrando los límites del predio y la localización y uso de las diferentes partes edificadas y áreas exteriores; plantas arquitectónicas, indicando el uso de los distintos locales y las circulaciones, con el mobiliario fijo que se requiera; cortes y fachadas; cortes por fachada, cuando colinden en vía pública y detalles arquitectónicos interiores y de obra exterior; plantas, cortes e isométricos en su caso, de las instalaciones hidrosanitarias, eléctricas, gas, instalaciones especiales y otras, mostrando las trayectorias de tuberías, alimentaciones y las memorias correspondientes.',
                    'Memoria descriptiva, la cual contendrá como mínimo: el listado de locales construidos y áreas libres de que consta la obra, con la superficie y el número de ocupantes o usuarios de cada uno; los requerimientos mínimos de acceso y desplazamiento de personas con discapacidad, cumpliendo con las Normas correspondientes; coeficientes de ocupación y de utilización del suelo, de acuerdo a los Programas General, Delegacionales y/o Parciales, en su caso. (Original)',
                    'La descripción de los dispositivos que provean el cumplimiento de los requerimientos establecidos por la Ley en cuanto a salidas y muebles hidrosanitarios, niveles de iluminación y superficies de ventilación de cada local, visibilidad en salas de espectáculos, resistencia de los materiales al fuego, circulaciones y salidas de emergencia, equiposde extinciónde fuego, y diseño де las instalaciones hidrosanitarias, eléctricas, де gas и otras que se requieran. Estos documentos deben estar firmados por el propietario o poseedor, por el Director Responsable де Obra и los Corresponsables en Diseño Urbano и Arquitectónico и en Instalaciones. (Original).',
                    'Un tanto del proyecto estructural	de la obra	en planos debidamente acotados, con especificaciones que contengan una descripción completa	y detallada	de las características	de la estructura incluyendo su cimentación. Se especificarán en ellos los datos esenciales	del diseño como las cargas vivas y los coeficientes sísmicos considerados y las calidades de materiales. Se indicarán los procedimientos de construcción recomendados, cuando éstos difieran de los tradicionales.',
                    'Responsiva del Director Responsable de Obra del proyecto de la obra, así como de los Corresponsables en los supuestos señalados en el artículo 36 del Reglamento, las cuales se encuentran incluidas en este formato.',
                    'Presentar dictamen favorable del estudio de impacto urbano o impacto urbano ambiental, para los casos señalados en la fracción III del artículo 51 del Reglamento de Construcciones para el Distrito Federal. Es decir, para el caso de usos no habitacionales o mixtos de más de 5,000m2 o más de 10,000m2 con uso habitacional, o construcciones que lo requieran. (Original)',
                    'Acuse de recibo del aviso de ejecución de obras ante la Secretaría del Medio Ambiente, cuando se trate de proyectos habitacionales de más de 20 viviendas. (Original)',
                    'Cuando se trate de zonas de conservación del Patrimonio Histórico, Artístico y Arqueológico de la Federación o área de conservación patrimonial de la Ciudad de México, se requiere, además, cuando corresponda, el dictamen técnico de la Secretaría de Desarrollo Urbano y Vivienda, el visto bueno del Instituto Nacional de Bellas Artes y/o la licencia del Instituto Nacional de Antropología e Historia, así como la responsiva de un Corresponsable en Diseño Urbano y Arquitectónico. (Original)',
                    'En el caso de ampliaciones, modificaciones o reparaciones en edificaciones existentes, se debe presentar, de la obra original, la licencia de construcción especial o el registro de manifestación de construcción o el registro de obra ejecutada, así como indicar en planos la edificación original y el área donde se realizarán estos trabajos. (Original)',
                    'Manifestación de Construcción de que se trate y sus requisitos establecidos en el Reglamento de Construcciones para el Distrito Federal.',
                    'Deberán mostrarse en planos los detalles de conexiones, cambios de nivel y aberturas para ducto, en original. Los planos anteriores deben incluir el proyecto de protección a colindancias y el estudio de mecánica de suelos cuando proceda, de acuerdo con lo establecido en el Reglamento de Construcciones para el Distrito Federal. Estos documentos deben estar firmados por el Director Responsable de Obra y el Corresponsable en Seguridad Estructural, en su caso. (Original)'
                    ],
                    'detalles' => [
                        'observaciones' => 'Constancia que se emite para las Construcciones de Manifestación tipo “B” o “C”, del impacto vecinal',
                        'costo' => 'Sin costo',
                        'materia' => '5 Obras',
                        'tiempo' => '40 días hábiles',
                        'en_linea' => 'Sí. en: https://ventanilla.construccion.cdmx.gob.mx',
                        'ubicacion' => 'Ventanilla Única de Trámites: Plaza de la Constitución # 1, Tlalpan Centro, C.P. 14000 de lunes a viernes de 9:00 a 14:00 h.'
                    ]
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