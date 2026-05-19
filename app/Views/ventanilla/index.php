<?php
if (!isset($data)) {
    $data = [
        'materias' => [],
        'catalogo_json' => [],
    ];
}

$modoEdicionVUT = !empty($data['modo_edicion']);
$idSolicitudEdicionVUT = (int)($data['id_solicitud'] ?? 0);
$solicitudEdicionVUT = (isset($data['solicitud_edit']) && is_array($data['solicitud_edit'])) ? $data['solicitud_edit'] : [];
?>
<style>
    /* Scrollbar Institucional */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #E6D4DD;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #773357;
    }

    /* Clases de estado para los requisitos */
    .item-requisito.seleccionado {
        border-color: #773357 !important;
        background-color: #FCF7F9 !important;
        box-shadow: 0 0 0 2px rgba(119, 51, 87, 0.2);
    }
    .item-requisito.listo-para-volver {
        border-color: #10B981 !important;
        background-color: #F0FDF4 !important;
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
    }

    /* Colores institucionales extras */
    .text-tlalpan-vino { color: #773357; }
    .border-tlalpan-vino { border-color: #773357; }
    .bg-tlalpan-vino { background-color: #773357; }
</style>
<style>
    .vut-input-error {
        border-color: #dc2626 !important;
        background-color: #fff7f7 !important;
        box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.12) !important;
    }

    .vut-error-msg {
        display: block;
        margin-top: 4px;
        font-size: 10px;
        line-height: 1.2;
        color: #dc2626;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .vut-input-ok {
        border-color: #10b981 !important;
    }
</style>

<div class="mb-8 animate-fade-in-up">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-3xl font-black text-tlalpan-vino"><?= $modoEdicionVUT ? 'Editar solicitud' : 'Ventanilla Única' ?></h2>
            <p class="text-gray-500 font-medium tracking-tight">
                <?= $modoEdicionVUT ? 'Modificación de datos capturados y actualización del expediente' : 'Gestión y captura de trámites ciudadanos' ?>
            </p>
            <?php if ($modoEdicionVUT): ?>
                <div class="mt-3 inline-flex items-center gap-2 bg-amber-50 text-amber-800 border border-amber-200 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider">
                    ✏️ Modo edición · Folio <?= htmlspecialchars($solicitudEdicionVUT['folio'] ?? ('ID ' . $idSolicitudEdicionVUT), ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="text-right">
            <span class="bg-blue-50 text-blue-700 text-xs font-bold px-4 py-1.5 rounded-full border border-blue-100 shadow-sm uppercase tracking-wider">
                <?= $modoEdicionVUT ? 'EDITANDO EXPEDIENTE' : ('FECHA INGRESO: ' . date('d/m/y')) ?>
            </span>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 border-b border-gray-50 pb-2">Filtros de Trámite</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-[10px] font-black text-gray-500 mb-2 uppercase tracking-wider">Materia</label>
                <select id="select-materia" onchange="actualizarTramites()" class="input-tlalpan block w-full py-2.5 px-4 rounded-xl shadow-sm sm:text-sm">
                    <?php foreach($data['materias'] as $materia): ?>
                        <option value="<?= $materia ?>"><?= $materia ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-[10px] font-black text-gray-500 mb-2 uppercase tracking-wider">Trámite Específico</label>
                <select id="select-tramite" onchange="actualizarRequisitos()" class="input-tlalpan block w-full py-2.5 px-4 rounded-xl shadow-sm sm:text-sm">
                    </select>
            </div>
        </div>
    </div>

    <div id="seccion-observaciones" class="mt-6 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hidden">
        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 border-b border-gray-50 pb-2">Detalles del Trámite</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div>
                <label class="block text-[10px] font-black text-tlalpan-vino mb-1 uppercase tracking-wider">Costo</label>
                <p id="obs-costo" class="text-sm font-bold text-gray-700"></p>
            </div>
            <div>
                <label class="block text-[10px] font-black text-tlalpan-vino mb-1 uppercase tracking-wider">Tiempo de Respuesta</label>
                <p id="obs-tiempo" class="text-sm font-bold text-gray-700"></p>
            </div>
            <div>
                <label class="block text-[10px] font-black text-tlalpan-vino mb-1 uppercase tracking-wider">¿Disponible en Línea?</label>
                <p id="obs-linea" class="text-sm font-bold text-gray-700"></p>
            </div>
            <div>
                <label class="block text-[10px] font-black text-tlalpan-vino mb-1 uppercase tracking-wider">Materia</label>
                <p id="obs-materia" class="text-sm font-bold text-gray-700"></p>
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <label class="block text-[10px] font-black text-tlalpan-vino mb-1 uppercase tracking-wider">Observaciones y Comentarios</label>
                <div class="bg-[#FCF7F9] p-4 rounded-xl border-l-4 border-[#773357]">
                    <p id="obs-descripcion" class="text-[11px] text-gray-600 font-bold leading-relaxed italic"></p>
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-black text-tlalpan-vino mb-1 uppercase tracking-wider">¿Dónde se realiza el Trámite?</label>
                <div class="flex items-start gap-2 text-[11px] text-gray-600 bg-gray-50 p-3 rounded-xl border border-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#773357] mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <p id="obs-ubicacion" class="font-bold"></p>
                </div>
            </div>
        </div>
    </div>

<div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden animate-fade-in-up mb-10">
    
    <div class="bg-gray-50/80 px-8 py-4 border-b border-gray-100 flex flex-wrap items-center gap-x-8 gap-y-4">
    <h3 class="font-black text-tlalpan-vino text-lg uppercase tracking-tight">Datos de Captura</h3>

    <div class="flex items-center gap-3">
        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Persona:</span>
        <select id="select-tipo-persona" onchange="cambiarOpcionesRepresentante(); actualizarPlantillaInteresado();" class="input-tlalpan text-xs font-bold rounded-lg py-1.5 px-4">
            <option value="fisica">FISICA</option>
            <option value="moral">MORAL</option>
        </select>
    </div>

    <div class="flex items-center gap-3">
        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Representante:</span>
        <select id="select-tipo-rep" onchange="actualizarPlantillaInteresado()" class="input-tlalpan text-xs font-bold rounded-lg py-1.5 px-4"></select>
    </div>

    <div id="contenedor-tipo-obra" class="flex items-center gap-3 hidden animate-fade-in">
        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Modalidad:</span>
        <select id="select-modalidad-obra" class="input-tlalpan text-xs font-bold rounded-lg py-1.5 px-4 border-blue-200 text-blue-700 bg-blue-50/50">
            <option value="TIPO B">Tipo B</option>
            <option value="TIPO C">Tipo C</option>
            <option value="DEMOLICION">Demolición</option>
            <option value="FUSION_SUBDIVISION">Fusión y/o subdivisión</option>
            <option value="SUELO_CONSERVACION">Suelo de conservación</option>
        </select>
    </div>

    <div id="contenedor-tipo-licencia" class="flex flex-wrap items-center gap-4 hidden animate-fade-in">
        <div class="flex items-center gap-2">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest whitespace-nowrap">Modalidad:</span>
            <select id="select-modalidad-licencia" class="input-tlalpan text-[11px] font-black rounded-lg py-1.5 px-4 border-amber-200 text-amber-800 bg-amber-50">
                <option value="EXPEDICION">Expedición de Licencia de Construcción Especial</option>
                <option value="REVALIDACION">Revalidación de la Licencia de Construcción Especial</option>
                <option value="AVISO_TERMINACION">Aviso de Terminación de Obra</option>
                <option value="AUTORIZACION_USO_OCUPACION">Autorización de Uso y Ocupación</option>
            </select>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest whitespace-nowrap">Obra:</span>
            <select id="select-obra-especial" class="input-tlalpan text-[11px] font-bold rounded-lg py-1.5 px-3 border-amber-300 text-amber-900 bg-white shadow-sm max-w-[400px]">
                <option value="-1">Seleccione tipo de obra...</option>
                <option value="SUELO_CONSERVACION">Suelo de conservación</option>
                <option value="INSTALACIONES">Instalaciones subterráneas, aéreas, cortes en banquetas</option>
                <option value="ANTENAS">Estaciones repetidoras (Antenas)</option>
                <option value="DEMOLICIONES">Demoliciones mayores a 60m2</option>
                <option value="EXCAVACIONES_OTROS">Excavaciones, tapiales, instalaciones temporales, ferias o aparatos mecánicos, circos, carpas, modificaciones en edificaciones existentes, montacargas o cualquier aparato mecánico, equipos contra incendio, tanques de almacenamiento, instalación de máquina con o sin plataforma</option>
            </select>
        </div>
    </div>

    <div id="contenedor-tipo-manifestacion-a" class="flex flex-wrap items-center gap-4 hidden animate-fade-in">
        <div class="flex items-center gap-2">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Modalidad:</span>
            <select id="select-modalidad-manifestacion-a" class="input-tlalpan text-[11px] font-black rounded-lg py-1.5 px-4 border-emerald-200 text-emerald-800 bg-emerald-50">
                <option value="REGISTRO">Registro de Manifestación de Construcción Tipo A</option>
                <option value="REVALIDACION">Revalidación del registro</option>
                <option value="AVISO_TERMINACION">Aviso de terminación de obra</option>
            </select>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tipo:</span>
            <select id="select-detalle-manifestacion-a" class="input-tlalpan text-[11px] font-black rounded-lg py-1.5 px-4 border-emerald-300 text-emerald-900 bg-white shadow-sm">
                <option value="-1">Tipo de manifestación</option>
                <option value="1">Obra nueva</option>
                <option value="2">Ampliación</option>
                <option value="3">Reparación</option>
                <option value="4">Bardas</option>
                <option value="5">Fosas Séptica o Cisterna</option>
                <option value="6">Modificación</option>
            </select>
        </div>
    </div>

<div id="contenedor-tipo-manifestacion-bc" class="flex flex-wrap items-center gap-4 hidden animate-fade-in">
    <div class="flex items-center gap-2">
        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Modalidad:</span>
        <select id="select-modalidad-manifestacion-bc" class="input-tlalpan text-[11px] font-black rounded-lg py-1.5 px-4 border-indigo-200 text-indigo-800 bg-indigo-50">
            <option value="REGISTROB">Registro de Manifestación de Construcción Tipo B</option>
            <option value="REGISTROC">Registro de Manifestación de Construcción Tipo C</option>
            <option value="REVALIDACION">Revalidación del registro</option>
            <option value="AVISO_TERMINACION">Aviso de terminación de obra</option>
            <option value="USO_OCUPACION">Uso y Ocupación</option>
        </select>
    </div>

    <div class="flex items-center gap-2">
        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tipo:</span>
        <select id="select-detalle-manifestacion-bc" class="input-tlalpan text-[11px] font-black rounded-lg py-1.5 px-4 border-indigo-300 text-indigo-900 bg-white shadow-sm">
            <option value="OBRA_NUEVA">Obra nueva</option>
            <option value="AMPLIACION">Ampliación</option>
            <option value="REPARACION">Reparación</option>
            <option value="MODIFICACION">Modificación</option>
        </select>
    </div>
</div>

    <div id="contenedor-tipo-espectaculos-publicos" class="flex items-center gap-3 hidden animate-fade-in">
        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Modalidad:</span>
        <select id="select-modalidad-espectaculos-publicos" class="input-tlalpan text-[11px] font-black rounded-lg py-1.5 px-4 border-indigo-200 text-indigo-800 bg-indigo-50">
            <option value="AUTORIZACION">Autorización</option>
            <option value="AVISO">Aviso</option>
            <option value="PERMISO">Permiso</option>
        </select>
    </div>
    <div id="contenedor-tipo-alineamientos" class="flex items-center gap-3 hidden animate-fade-in">
        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Modalidad:</span>
        <select id="select-modalidad-alineamientos" class="input-tlalpan text-[11px] font-black rounded-lg py-1.5 px-4 border-indigo-200 text-indigo-800 bg-indigo-50">
            <option value="ALINEAMIENTO">Alineamiento</option>
            <option value="NUMERO">Número oficial</option>
        </select>
    </div>
    <div id="contenedor-tipo-seguridad" class="flex items-center gap-3 hidden animate-fade-in">
        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Modalidad:</span>
        <select id="select-modalidad-seguridad" class="input-tlalpan text-[11px] font-black rounded-lg py-1.5 px-4 border-indigo-200 text-indigo-800 bg-indigo-50">
            <option value="NUEVO">Nuevo</option>
            <option value="RENOVACION">Renovación</option>
        </select>
    </div>
    <div id="contenedor-tipo-retolificacion" class="flex items-center gap-3 hidden animate-fade-in">
        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Modalidad:</span>
        <select id="select-modalidad-retolificacion" class="input-tlalpan text-[11px] font-black rounded-lg py-1.5 px-4 border-indigo-200 text-indigo-800 bg-indigo-50">
            <option value="NUEVO">Nuevo</option>
            <option value="RENOVACION">Renovación</option>
        </select>
    </div>
    <div id="contenedor-tipo-subdivision" class="flex items-center gap-3 hidden animate-fade-in">
        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Modalidad:</span>
        <select id="select-modalidad-subdivision" class="input-tlalpan text-[11px] font-black rounded-lg py-1.5 px-4 border-indigo-200 text-indigo-800 bg-indigo-50">
            <option value="NUEVO">Nuevo</option>
            <option value="FUSION">Fusión</option>
            <option value="REVALIDACION">Revalidación</option>
        </select>
    </div>
    <div id="contenedor-tipo-estructural" class="flex items-center gap-3 hidden animate-fade-in">
        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Modalidad:</span>
        <select id="select-modalidad-estructural" class="input-tlalpan text-[11px] font-black rounded-lg py-1.5 px-4 border-indigo-200 text-indigo-800 bg-indigo-50">
            <option value="NUEVO">Nuevo</option>
            <option value="RENOVACION">Renovación</option>
        </select>
    </div>
</div>
<div id="resumen-bifurcacion" class="hidden w-full bg-[#FCF7F9] border border-[#E6D4DD] rounded-2xl px-5 py-4 mt-3">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Resumen del trámite</p>
            <p id="resumen-bifurcacion-texto" class="text-sm font-black text-tlalpan-vino mt-1"></p>
        </div>
        <div class="text-[10px] font-bold text-gray-500 uppercase">
            Esto se imprimirá en el acuse
        </div>
    </div>
</div>

    <div class="border-b border-gray-100">
        <nav class="-mb-px flex space-x-8 px-8 overflow-x-auto custom-scrollbar" aria-label="Tabs">
            <button onclick="switchTab('requisitos')" id="btn-requisitos" class="tab-btn border-tlalpan-vino text-tlalpan-vino whitespace-nowrap py-5 px-1 border-b-2 font-black text-xs uppercase tracking-widest transition-all">
                Requisitos
            </button>
            <button onclick="switchTab('interesado')" id="btn-interesado" class="tab-btn border-transparent text-gray-400 hover:text-gray-600 whitespace-nowrap py-5 px-1 border-b-2 font-bold text-xs uppercase tracking-widest transition-all">
                Interesado
            </button>
            <button onclick="switchTab('legal')" id="btn-legal" class="tab-btn hidden border-transparent text-purple-600 hover:text-purple-800 whitespace-nowrap py-5 px-1 border-b-2 font-black text-xs uppercase tracking-widest transition-all italic">
                Representante Legal +
            </button>
            <button onclick="switchTab('autorizada')" id="btn-autorizada" class="tab-btn hidden border-transparent text-blue-500 hover:text-blue-700 whitespace-nowrap py-5 px-1 border-b-2 font-black text-xs uppercase tracking-widest transition-all italic">
                Persona Autorizada +
            </button>
            <button onclick="switchTab('predio')" id="btn-predio" class="tab-btn border-transparent text-gray-400 hover:text-gray-600 whitespace-nowrap py-5 px-1 border-b-2 font-bold text-xs uppercase tracking-widest transition-all">
                Predio
            </button>
            <button onclick="switchTab('observaciones')" id="btn-observaciones" class="tab-btn border-transparent text-gray-400 hover:text-gray-600 whitespace-nowrap py-5 px-1 border-b-2 font-bold text-xs uppercase tracking-widest transition-all">
                Observaciones
            </button>
        </nav>
    </div>

    <div class="p-8 min-h-[500px]">
        
        <div id="tab-requisitos" class="tab-content block">
            <p class="mb-6 text-sm text-gray-500 font-medium italic">Seleccione los documentos entregados por el ciudadano para validarlos.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-stretch">
                <div class="md:col-span-5 flex flex-col">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 ml-2">Documentos Pendientes</label>
                    <div id="lista-requisitos" 
                         class="border border-[#E6D4DD] rounded-2xl bg-[#FCF7F9]/30 p-3 space-y-2 shadow-inner overflow-y-auto custom-scrollbar h-[450px]">
                        </div>
                </div>

                <div class="md:col-span-2 flex md:flex-col justify-center items-center gap-4 py-4">
                   <button onclick="moverDerecha()" class="p-4 bg-tlalpan-vino text-white rounded-2xl hover:bg-[#5a2540] shadow-lg transform active:scale-90 transition-all group" title="Mover a Presentados">
                        <svg class="w-6 h-6 md:rotate-0 rotate-90 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </button>

                    <button onclick="moverIzquierda()" class="p-4 bg-gray-100 text-gray-400 rounded-2xl hover:bg-gray-200 shadow hover:text-gray-600 transition-all transform active:scale-90 group" title="Devolver a Pendientes">
                        <svg class="w-6 h-6 md:rotate-0 rotate-90 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                        </svg>
                    </button>
                </div>

                <div class="md:col-span-5 flex flex-col">
                    <label class="text-[10px] font-black text-tlalpan-vino uppercase tracking-[0.2em] mb-3 ml-2">Documentos Validados</label>
                    <div id="lista-presentados" 
                         class="border-2 border-dashed border-gray-200 rounded-2xl bg-white p-3 space-y-2 shadow-sm overflow-y-auto custom-scrollbar h-[450px] flex flex-col items-center justify-center text-center">
                        <div id="placeholder-presentados" class="opacity-30">
                            <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-xs font-bold uppercase tracking-widest">Arrastre o mueva aquí los documentos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="tab-interesado" class="tab-content hidden">
    <h4 class="text-lg font-black text-gray-800 mb-6 flex items-center gap-3">
        <span class="w-1.5 h-6 bg-tlalpan-vino rounded-full"></span> DATOS DEL SOLICITANTE
    </h4>
    <div id="contenedor-dinamico-interesado">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <div>
            <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Nombres</label>
            <input id="interesado_nombres" type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold">
        </div>
        <div>
            <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Ape. Paterno</label>
            <input id="interesado_paterno" type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold">
        </div>
        <div>
            <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">Ape. Materno</label>
            <input id="interesado_materno" type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold">
        </div>
        <div>
            <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* RFC</label>
            <input id="interesado_rfc" type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-black uppercase tracking-widest">
        </div>
    </div>
</div>

<div class="bg-[#FCF7F9] border border-[#E6D4DD] rounded-2xl p-5 mb-8">
    <h4 class="text-[10px] font-black text-tlalpan-vino uppercase tracking-[0.3em] mb-4 border-b border-[#E6D4DD] pb-2">
        Datos de contacto del solicitante
    </h4>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">Teléfono de contacto</label>
            <input id="interesado_telefono" name="interesado_telefono" type="tel" placeholder="Teléfono" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm">
        </div>
        <div class="md:col-span-2">
            <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">Correo electrónico</label>
            <input id="interesado_email" name="interesado_email" type="email" placeholder="correo@ejemplo.com" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm">
        </div>
    </div>
    <p class="text-[10px] text-gray-500 font-bold mt-3 uppercase tracking-wider">
        Este dato aplica para cualquier trámite y se imprimirá en el acuse para seguimiento.
    </p>
</div>
    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mb-6 border-b border-gray-100 pb-2">Domicilio del Interesado</h4>
    
    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
        <div class="md:col-span-3">
                <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Alcaldía:</label>
                <select id="select-alcaldia" name="interesado_alcaldia" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-bold" name="alcaldia">
                    <option value="">Seleccione...</option>
                    <option value="AZCAPOTZALCO">AZCAPOTZALCO</option>
                    <option value="COYOACAN">COYOACÁN</option>
                    <option value="CUAJIMALPA DE MORELOS">CUAJIMALPA DE MORELOS</option>
                    <option value="GUSTAVO A MADERO">GUSTAVO A. MADERO</option>
                    <option value="IZTACALCO">IZTACALCO</option>
                    <option value="IZTAPALAPA">IZTAPALAPA</option>
                    <option value="LA MAGDALENA CONTRERAS">LA MAGDALENA CONTRERAS</option>
                    <option value="MILPA ALTA">MILPA ALTA</option>
                    <option value="ALVARO OBREGON">ÁLVARO OBREGÓN</option>
                    <option value="TLAHUAC">TLÁHUAC</option>
                    <option value="TLALPAN">TLALPAN</option>
                    <option value="XOCHIMILCO">XOCHIMILCO</option>
                    <option value="BENITO JUAREZ">BENITO JUÁREZ</option>
                    <option value="CUAUHTEMOC">CUAUHTÉMOC</option>
                    <option value="MIGUEL HIDALGO">MIGUEL HIDALGO</option>
                    <option value="VENUSTIANO CARRANZA">VENUSTIANO CARRANZA</option>
                </select>
            </div>

            <div class="md:col-span-6">
                <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Colonia</label>
                <input type="hidden" id="colonia_nombre" name="interesado_colonia">
                <select id="select-colonia" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm" disabled>
                    <option value="">Selecciona primero una alcaldía</option>
                </select>
            </div>
        <div class="md:col-span-3">
            <label class="block text-[10px] font-black text-gray-400 mb-1 uppercase tracking-wider">Col. no Listada</label>
        <input type="text" id="colonia-no-listada" name="interesado_colonia_no_listada" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm bg-gray-50/50">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <div class="md:col-span-3">
            <label class="block text-[10px] font-black text-gray-400 mb-1 uppercase tracking-wider">* Calle</label>
            <input type="text" id="calle" name="interesado_calle" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm bg-gray-50/50">
        </div>
        <div class="md:col-span-3">
            <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Número Exterior</label>
            <input type="text" id="numero-exterior" name="interesado_numero_exterior" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-bold text-center">
        </div>
        <div class="md:col-span-2">
                <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">C.P.</label>
                <input type="text" id="cp" name="interesado_cp" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-black text-center" readonly>
            </div>
    </div>
</div>

        <div id="tab-autorizada" class="tab-content hidden animate-fade-in">
            <h4 class="text-lg font-black text-blue-600 mb-6 flex items-center gap-3">
                <span class="w-1.5 h-6 bg-blue-600 rounded-full"></span> PERSONA AUTORIZADA PARA OÍR Y RECIBIR NOTIFICACIONES
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Nombres</label>
                    <input type="text" id="aut_nombres" name="autorizada_nombres" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold border-gray-200">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Ape. Paterno</label>
                    <input type="text" id="aut_paterno" name="autorizada_apellido_paterno" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold border-gray-200">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">Ape. Materno</label>
                    <input type="text" id="aut_materno" name="autorizada_apellido_materno" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold border-gray-200">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* RFC</label>
                    <input type="text" id="aut_rfc" name="autorizada_rfc" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-black uppercase tracking-widest border-gray-200">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="md:col-span-1">
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">Teléfono</label>
                    <input type="tel" id="aut_telefono" name="autorizada_telefono" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">e-mail</label>
                    <input type="email" id="aut_email" name="autorizada_email" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200">
                </div>
            </div>

            <div class="bg-gray-50/50 p-5 rounded-2xl mb-8 border border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Del.:</label>
                        <select id="aut_dom_del" name="aut_dom_del" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-bold border-gray-200">
                            <option value="tlalpan">TLALPAN</option>
                        </select>
                    </div>
                    <div class="md:col-span-6">
                        <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Colonia</label>
                        <select id="aut_dom_colonia" name="aut_dom_colonia" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200">
                            <option value="">Seleccione...</option>
                        </select>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-black text-gray-400 mb-1 uppercase tracking-wider">Col. no Listada</label>
                        <input type="text" id="aut_dom_col_manual" name="aut_dom_col_manual" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-7">
                        <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Calle</label>
                        <input 
                            type="text" 
                            id="aut_dom_calle" 
                            name="aut_dom_calle" 
                            placeholder="Calle / Avenida" 
                            class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200"
                        >
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Número Exterior</label>
                        <input type="text" id="aut_dom_num_ext" name="autorizada_domicilio_numero_exterior" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-bold border-gray-200">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* C.P.</label>
                        <input type="text" id="aut_dom_cp" name="aut_dom_cp" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-black text-center border-gray-200">
                    </div>
                </div>
            </div>

            <div class="space-y-4 border-t border-gray-100 pt-6">
                <div class="grid grid-cols-1 md:grid-cols-12 items-center gap-4">
                    <div class="md:col-span-5 text-right pr-4">
                        <label class="text-[11px] font-bold text-gray-600 uppercase tracking-tight">Documento con que se acredita la personalidad</label>
                    </div>
                    <div class="md:col-span-7">
                        <input type="text" id="aut_doc_personalidad" name="autorizada_documento_personalidad" class="input-tlalpan w-full rounded-xl py-2.5 px-4 text-sm font-semibold border-gray-200">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-12 items-center gap-4">
                    <div class="md:col-span-5 text-right pr-4">
                        <label class="text-[11px] font-bold text-gray-600 uppercase tracking-tight">Domicilio para oír y recibir notificaciones *</label>
                    </div>
                    <div class="md:col-span-7">
                        <input type="text" id="aut_domicilio_procesal" name="autorizada_domicilio_procesal" class="input-tlalpan w-full rounded-xl py-2.5 px-4 text-sm font-semibold border-gray-200">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 items-center gap-4">
                    <div class="md:col-span-5 text-right pr-4">
                        <label class="text-[11px] font-bold text-gray-600 uppercase tracking-tight">Persona autorizada para oír y recibir notificaciones</label>
                    </div>
                    <div class="md:col-span-7">
                        <input type="text" id="aut_persona_nombre_extra" name="autorizada_persona_nombre_extra" class="input-tlalpan w-full rounded-xl py-2.5 px-4 text-sm font-semibold border-gray-200">
                    </div>
                </div>
            </div>
        </div>

        <div id="tab-legal" class="tab-content hidden animate-fade-in">
            <h4 class="text-lg font-black text-purple-600 mb-6 flex items-center gap-3">
                <span class="w-1.5 h-6 bg-purple-600 rounded-full"></span> DATOS DEL REPRESENTANTE LEGAL
            </h4>

            <!-- Fila 1: Nombres, Apellidos y RFC -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Nombres</label>
                    <input type="text" id="leg_nombres" name="legal_nombres" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold border-gray-200">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Ape. Paterno</label>
                    <input type="text" id="leg_paterno" name="legal_paterno" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold border-gray-200">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">Ape. Materno</label>
                    <input type="text" id="leg_materno" name="legal_materno" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold border-gray-200">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* RFC</label>
                    <input type="text" id="leg_rfc" name="legal_rfc" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-black uppercase tracking-widest border-gray-200">
                </div>
            </div>

            <!-- Fila 2: Teléfono, Email y Dirección -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-8">
                <div class="md:col-span-3">
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">Teléfono</label>
                    <input type="tel" id="leg_telefono" name="legal_telefono" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200">
                </div>
                <div class="md:col-span-4">
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">e-mail</label>
                    <input type="email" id="leg_email" name="legal_email" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200">
                </div>
                <div class="md:col-span-5">
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">Dirección</label>
                    <input type="text" id="leg_direccion_simple" name="legal_direccion_simple" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200">
                </div>
            </div>

            <!-- Documento de Personalidad -->
            <div class="grid grid-cols-1 md:grid-cols-12 items-center gap-4 mb-8">
                <div class="md:col-span-5 text-left">
                    <label class="text-[11px] font-bold text-gray-600 uppercase tracking-tight">Documento con que se acredita la personalidad</label>
                </div>
                <div class="md:col-span-7">
                    <input type="text" id="leg_doc_personalidad" name="legal_doc_personalidad"   class="input-tlalpan w-full rounded-xl py-2 px-4 text-sm font-semibold border-gray-200">
                </div>
            </div>

            <div class="h-px bg-gray-100 my-8"></div>

            <!-- Sección de Domicilio Detallado -->
            <div class="bg-gray-50/50 p-5 rounded-2xl border border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Alcaldía:</label>
                        <select id="leg_dom_del" name="leg_alcaldia" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-bold border-gray-200">
                            <option value="">Seleccione...</option>
                            <option value="AZCAPOTZALCO">AZCAPOTZALCO</option>
                            <option value="COYOACAN">COYOACÁN</option>
                            <option value="CUAJIMALPA DE MORELOS">CUAJIMALPA DE MORELOS</option>
                            <option value="GUSTAVO A MADERO">GUSTAVO A. MADERO</option>
                            <option value="IZTACALCO">IZTACALCO</option>
                            <option value="IZTAPALAPA">IZTAPALAPA</option>
                            <option value="LA MAGDALENA CONTRERAS">LA MAGDALENA CONTRERAS</option>
                            <option value="MILPA ALTA">MILPA ALTA</option>
                            <option value="ALVARO OBREGON">ÁLVARO OBREGÓN</option>
                            <option value="TLAHUAC">TLÁHUAC</option>
                            <option value="TLALPAN">TLALPAN</option>
                            <option value="XOCHIMILCO">XOCHIMILCO</option>
                            <option value="BENITO JUAREZ">BENITO JUÁREZ</option>
                            <option value="CUAUHTEMOC">CUAUHTÉMOC</option>
                            <option value="MIGUEL HIDALGO">MIGUEL HIDALGO</option>
                            <option value="VENUSTIANO CARRANZA">VENUSTIANO CARRANZA</option>
                        </select>
                    </div>
                    <div class="md:col-span-6">
                        <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Colonia</label>
                        <input type="hidden" id="leg_colonia_nombre" name="leg_colonia">
                        <select id="leg_dom_colonia" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200" disabled>
                            <option value="">Selecciona primero una alcaldía</option>
                        </select>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-black text-gray-400 mb-1 uppercase tracking-wider">Col. no Listada</label>
                        <input type="text" id="leg_dom_col_manual" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200 bg-gray-50/50">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-7">
                        <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Calle</label>
                        <input type="text" id="leg_dom_calle" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Número Exterior</label>
                        <input type="text" id="leg_dom_num_ext" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-bold border-gray-200">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* C.P.</label>
                        <input type="text" id="leg_dom_cp" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-black text-center border-gray-200" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div id="tab-predio" class="tab-content hidden">
            <div id="contenedor-dinamico-captura" class="animate-fade-in">
                </div>
        </div>

        <div id="tab-observaciones" class="tab-content hidden">
    <div class="max-w-4xl mx-auto py-4 space-y-8">
        
        <div id="contenedor-recibos-dinamico" class="bg-gray-50/50 p-6 rounded-[2rem] border border-gray-100 shadow-sm animate-fade-in hidden">
            <div class="mb-4 flex items-center gap-3">
                <div class="p-2 bg-emerald-100 text-emerald-700 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="font-black text-gray-800 text-sm uppercase tracking-tight">Registro de Recibos</h3>
                    <p class="text-[9px] text-gray-500 font-bold uppercase tracking-widest">Ingrese los folios y montos correspondientes</p>
                </div>
            </div>

           <div class="grid grid-cols-1 gap-4">
                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-8">
                        <label for="folio_recibo_1" class="block text-[9px] font-black text-gray-400 uppercase mb-1 ml-2">Folio del Recibo</label>
                        <input type="text" id="folio_recibo_1" name="folio_recibo_1" placeholder="No. Recibo" 
                            class="input-tlalpan w-full rounded-xl py-2.5 px-4 text-xs font-bold border-gray-200 focus:border-emerald-500">
                    </div>
                    <div class="col-span-4">
                        <label for="monto_recibo_1" class="block text-[9px] font-black text-gray-400 uppercase mb-1 ml-2">Cantidad</label>
                        <input type="number" id="monto_recibo_1" name="monto_recibo_1" step="0.01" 
                            class="input-tlalpan w-full rounded-xl py-2.5 px-4 text-xs font-black text-right border-gray-200 text-emerald-700">
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-8">
                        <label for="folio_recibo_2" class="block text-[9px] font-black text-gray-400 uppercase mb-1 ml-2">Folio del Recibo</label>
                        <input type="text" id="folio_recibo_2" name="folio_recibo_2" placeholder="No. Recibo" 
                            class="input-tlalpan w-full rounded-xl py-2.5 px-4 text-xs font-bold border-gray-200 focus:border-emerald-500">
                    </div>
                    <div class="col-span-4">
                        <label for="monto_recibo_2" class="block text-[9px] font-black text-gray-400 uppercase mb-1 ml-2">Cantidad</label>
                        <input type="number" id="monto_recibo_2" name="monto_recibo_2" step="0.01"  
                            class="input-tlalpan w-full rounded-xl py-2.5 px-4 text-xs font-black text-right border-gray-200 text-emerald-700">
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-8">
                        <label for="folio_recibo_3" class="block text-[9px] font-black text-gray-400 uppercase mb-1 ml-2">Folio del Recibo</label>
                        <input type="text" id="folio_recibo_3" name="folio_recibo_3" placeholder="No. Recibo" 
                            class="input-tlalpan w-full rounded-xl py-2.5 px-4 text-xs font-bold border-gray-200 focus:border-emerald-500">
                    </div>
                    <div class="col-span-4">
                        <label for="monto_recibo_3" class="block text-[9px] font-black text-gray-400 uppercase mb-1 ml-2">Cantidad</label>
                        <input type="number" id="monto_recibo_3" name="monto_recibo_3" step="0.01" 
                            class="input-tlalpan w-full rounded-xl py-2.5 px-4 text-xs font-black text-right border-gray-200 text-emerald-700">
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-tlalpan-vino/10 rounded-xl text-tlalpan-vino">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <div>
                    <h3 class="font-black text-gray-800 text-xl uppercase tracking-tight">Bitácora de Observaciones</h3>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Notas adicionales del capturista</p>
                </div>
            </div>
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-tlalpan-vino/20 to-transparent rounded-2xl blur opacity-25 group-focus-within:opacity-50 transition"></div>
                <textarea id="observaciones" rows="8" class="input-tlalpan relative w-full rounded-2xl p-6 text-gray-700 text-sm leading-relaxed resize-none shadow-sm outline-none" placeholder="Escriba aquí cualquier detalle relevante..."></textarea>
            </div>
        </div>
    </div>
</div>

    </div>

    <div class="bg-gray-50/80 px-8 py-6 border-t border-gray-100 flex flex-col sm:flex-row justify-end gap-4">
        <button class="px-6 py-3 bg-white border border-gray-200 text-gray-500 font-black rounded-xl hover:bg-gray-100 transition-all shadow-sm text-[10px] uppercase tracking-[0.2em] active:scale-95" onclick="limpiarTodo()">Limpiar Formulario</button>
        <button class="px-6 py-3 bg-red-50 text-red-700 border border-red-100 font-black rounded-xl hover:bg-red-100 transition-all shadow-sm text-[10px] uppercase tracking-[0.2em] active:scale-95" onclick="cancelarProceso()">Cancelar Proceso</button>
        <button onclick="finalizarCaptura()" class="px-10 py-3 bg-green-600 text-white font-black rounded-xl hover:bg-green-700 transition-all shadow-lg flex items-center justify-center text-[10px] uppercase tracking-[0.2em] transform hover:-translate-y-0.5 active:scale-95">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            Validar y Finalizar Captura
        </button>
    </div>
</div>

<script>
    // 1. Plantillas de Captura Dinámica (Mercado, Predio, etc.)
    const plantillasCaptura = {
        'mercado': `
            <div class="animate-fade-in">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-8 pt-2">
                    <div class="md:col-span-5">
                        <label class="block text-[10px] font-black text-gray-500 mb-2 uppercase tracking-wider">Nombre del Mercado Público <span class="text-red-500">*</span></label>
                        <input type="text" id="mercado_nombre" name="mercado_nombre" placeholder="MERCADO" class="input-tlalpan w-full rounded-xl py-3 px-4 text-sm font-bold border-gray-200">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-gray-500 mb-2 uppercase tracking-wider">Local <span class="text-red-500">*</span></label>
                        <input type="text" id="mercado_local" name="mercado_local" placeholder="LOCAL" class="input-tlalpan w-full rounded-xl py-3 px-4 text-sm font-black text-center border-gray-200">
                    </div>
                    <div class="md:col-span-5">
                        <label class="block text-[10px] font-black text-gray-500 mb-2 uppercase tracking-wider">Giro Solicitado <span class="text-red-500">*</span></label>
                        <input type="text" id="mercado_giro" name="mercado_giro" placeholder="GIRO" class="input-tlalpan w-full rounded-xl py-3 px-4 text-sm font-bold border-gray-200">
                    </div>
                </div>
                <div class="h-px bg-gray-100 my-8"></div>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-6">
                    <div class="md:col-span-3 text-center md:text-left">
                        <label class="block text-[10px] font-black text-gray-500 mb-2 uppercase tracking-wider">Alcaldía / Demarcación <span class="text-red-500">*</span></label>
                        <select id="mercado_alcaldia" name="mercado_alcaldia" class="input-tlalpan w-full rounded-xl py-3 px-4 text-sm font-black text-tlalpan-vino border-tlalpan-vino/20 bg-white">
                            <option value="">Seleccione una alcaldía...</option>
                            <option value="AZCAPOTZALCO">AZCAPOTZALCO</option>
                            <option value="COYOACAN">COYOACÁN</option>
                            <option value="CUAJIMALPA DE MORELOS">CUAJIMALPA DE MORELOS</option>
                            <option value="GUSTAVO A MADERO">GUSTAVO A. MADERO</option>
                            <option value="IZTACALCO">IZTACALCO</option>
                            <option value="IZTAPALAPA">IZTAPALAPA</option>
                            <option value="LA MAGDALENA CONTRERAS">LA MAGDALENA CONTRERAS</option>
                            <option value="MILPA ALTA">MILPA ALTA</option>
                            <option value="ALVARO OBREGON">ÁLVARO OBREGÓN</option>
                            <option value="TLAHUAC">TLÁHUAC</option>
                            <option value="TLALPAN">TLALPAN</option>
                            <option value="XOCHIMILCO">XOCHIMILCO</option>
                            <option value="BENITO JUAREZ">BENITO JUÁREZ</option>
                            <option value="CUAUHTEMOC">CUAUHTÉMOC</option>
                            <option value="MIGUEL HIDALGO">MIGUEL HIDALGO</option>
                            <option value="VENUSTIANO CARRANZA">VENUSTIANO CARRANZA</option>
                        </select>
                    </div>

                    <div class="md:col-span-5">
                        <label class="block text-[10px] font-black text-gray-500 mb-2 uppercase tracking-wider">Colonia <span class="text-red-500">*</span></label>
                        <!-- Este hidden almacenará el nombre real de la colonia -->
                        <input type="hidden" id="mercado_colonia_nombre" name="mercado_colonia_nombre">
                        <select id="mercado_colonia" name="mercado_colonia" class="input-tlalpan w-full rounded-xl py-3 px-4 text-sm font-semibold">
                            <option value="">Seleccione una colonia...</option>
                        </select>
                    </div>
                    <div class="md:col-span-4">
                        <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-wider italic">Colonia no listada</label>
                        <input type="text" id="mercado_colonia_manual" name="mercado_colonia_manual" class="input-tlalpan w-full rounded-xl py-3 px-4 text-sm bg-gray-50/50 border-dashed border-gray-200">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-6">
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-black text-gray-500 mb-2 uppercase tracking-wider">Calle / Avenida <span class="text-red-500">*</span></label>
                        <input type="text" id="mercado_calle" name="mercado_calle" placeholder="Calle / Avenida" class="input-tlalpan w-full rounded-xl py-3 px-4 text-sm text-center font-bold border-gray-200">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-black text-gray-500 mb-2 uppercase tracking-wider">Número Exterior <span class="text-red-500">*</span></label>
                        <input type="text" id="mercado_num_ext" name="mercado_num_ext" placeholder="S/N, Mz, Lt o #" class="input-tlalpan w-full rounded-xl py-3 px-4 text-sm text-center font-bold border-gray-200">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-black text-gray-500 mb-2 uppercase tracking-wider">Código Postal <span class="text-red-500">*</span></label>
                        <input type="text" id="mercado_cp" name="mercado_cp" placeholder="C.P." class="input-tlalpan w-full rounded-xl py-3 px-4 text-sm text-center font-black border-gray-200">
                    </div>
                </div>
            </div>
        `,
'predio': `
    <div class="animate-fade-in">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8 pt-2">
            <div>
                <label class="block text-[10px] font-black text-gray-500 mb-2 uppercase tracking-wider">* Uso actual:</label>
                <select id="predio_uso_actual" name="predio_uso_actual" class="input-tlalpan w-full rounded-xl py-3 px-4 text-sm font-bold border-gray-200">
                <option value="">Seleccione...</option>
                        <option value="BALDIO">BALDÍO</option>
                        <option value="COMERCIO">COMERCIO</option>
                        <option value="HABITACION">HABITACION</option>
                        <option value="INDUSTRIA">INDUSTRIA</option>
                        <option value="INFRAESTRUCTURA">INFRAESTRUCTURA</option>
                        <option value="SERVICIOS">SERVICIOS</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-500 mb-2 uppercase tracking-wider">Uso solicitado:</label>
                <select id="predio_uso_solicitado" name="predio_uso_solicitado" class="input-tlalpan w-full rounded-xl py-3 px-4 text-sm font-bold border-gray-200">
                        <option value="">Seleccione...</option>
                        <option value="BALDIO">BALDÍO</option>
                        <option value="COMERCIO">COMERCIO</option>
                        <option value="HABITACION">HABITACION</option>
                        <option value="INDUSTRIA">INDUSTRIA</option>
                        <option value="INFRAESTRUCTURA">INFRAESTRUCTURA</option>
                        <option value="SERVICIOS">SERVICIOS</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-500 mb-2 uppercase tracking-wider">* Dirección:</label>
                <select id="predio_direccion" name="predio_direccion" class="input-tlalpan w-full rounded-xl py-3 px-4 text-sm font-black border-tlalpan-vino text-tlalpan-vino">
                <option value="">Seleccione...</option>
                    <option value="NUEVA">NUEVA</option>
                    <option value="INTERESADO">INTERESADO</option>
                </select>
            </div>
        </div>

        <div class="h-px bg-gray-100 my-8"></div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
            <div class="md:col-span-3">
                <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Del.:</label>
                <select id="predio_alcaldia" name="predio_alcaldia" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-bold border-gray-200">
                    <option value="">Seleccione...</option>
                    <option value="AZCAPOTZALCO">AZCAPOTZALCO</option>
                    <option value="COYOACAN">COYOACÁN</option>
                    <option value="CUAJIMALPA DE MORELOS">CUAJIMALPA DE MORELOS</option>
                    <option value="GUSTAVO A MADERO">GUSTAVO A. MADERO</option>
                    <option value="IZTACALCO">IZTACALCO</option>
                    <option value="IZTAPALAPA">IZTAPALAPA</option>
                    <option value="LA MAGDALENA CONTRERAS">LA MAGDALENA CONTRERAS</option>
                    <option value="MILPA ALTA">MILPA ALTA</option>
                    <option value="ALVARO OBREGON">ÁLVARO OBREGÓN</option>
                    <option value="TLAHUAC">TLÁHUAC</option>
                    <option value="TLALPAN">TLALPAN</option>
                    <option value="XOCHIMILCO">XOCHIMILCO</option>
                    <option value="BENITO JUAREZ">BENITO JUÁREZ</option>
                    <option value="CUAUHTEMOC">CUAUHTÉMOC</option>
                    <option value="MIGUEL HIDALGO">MIGUEL HIDALGO</option>
                    <option value="VENUSTIANO CARRANZA">VENUSTIANO CARRANZA</option>
                </select>
            </div>
            <div class="md:col-span-6">
                <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Colonia</label>
                <input type="hidden" id="predio-colonia-nolista" name="predio-colonia-nombre">
                <select id="predio_colonia" name="predio_colonia" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200">
                    <option value="">Seleccione una colonia...</option>
                </select>
            </div>
            <div class="md:col-span-3">
                <label class="block text-[10px] font-black text-gray-400 mb-1 uppercase tracking-wider text-right">Col. no Listada</label>
                <input id="predio_colonia_nolista" name="predio_colonia_nolista" type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm bg-gray-50/50 border-gray-200">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-8">
            <div class="md:col-span-3">
                <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Calle</label>
                <input id="predio_calle" name="predio_calle" type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200">
            </div>
            <div class="md:col-span-3">
                <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Número Exterior</label>
                <input id="predio_numero_exterior" name="predio_numero_exterior" type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200">
            </div>
            <div class="md:col-span-3">
                <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Código Postal</label>
                <input id="predio_codigo_postal" name="predio_codigo_postal" type="text" value="14049" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-black text-center border-gray-200">
            </div>
        </div>

        <div class="flex items-center gap-3 mb-6 p-3 bg-gray-50 rounded-xl border border-gray-100">
            <input type="checkbox" id="check_agregar_propietario" class="w-4 h-4 text-tlalpan-vino rounded focus:ring-tlalpan-vino">
            <label for="check_agregar_propietario" class="text-[11px] font-black text-gray-700 uppercase tracking-widest cursor-pointer">Agregar datos de propietario</label>
        </div>

        <div id="seccion_propietario_predio" class="space-y-4 border-t border-gray-100 pt-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Nombres</label>
                    <input id="propietario_nombres" type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold border-gray-200">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Ape. Paterno</label>
                    <input id="propietario_ape_paterno" type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold border-gray-200">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">Ape. Materno</label>
                    <input id="propietario_ape_materno" type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold border-gray-200">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* RFC</label>
                    <input id="propietario_rfc" type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-black uppercase border-gray-200">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">Teléfono</label>
                    <input id="propietario_telefono" type="tel" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">e-mail</label>
                    <input id="propietario_email" type="email" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200">
                </div>
            </div>
        </div>
    </div>
`
    };

    // 2. Plantillas para Datos del Interesado (Física / Moral)
    const plantillasInteresado = {
        'fisica': `
            <div class="animate-fade-in grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Nombres</label>
                    <input id="interesado_nombres" type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold border-gray-200">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Ape. Paterno</label>
                    <input id="interesado_ape_paterno" type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold border-gray-200">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">Ape. Materno</label>
                    <input id="interesado_ape_materno" type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold border-gray-200">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* RFC</label>
                    <input id="interesado_rfc" type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-black uppercase tracking-widest border-gray-200">
                </div>
            </div>
        `,
        'moral': `
    <div class="animate-fade-in">
        <!-- Primera Fila: Razón Social y RFC -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
            <div class="md:col-span-8">
                <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Denominación o Razón Social</label>
                <input type="text" id="moral_razon_social" placeholder="Razón Social" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-bold uppercase border-gray-200">
            </div>
            <div class="md:col-span-4">
                <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* RFC</label>
                <input type="text" id="moral_rfc" placeholder="RFC" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-black uppercase tracking-widest border-gray-200">
            </div>
        </div>

        <!-- Segunda Fila: Datos Notariales -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
            <div class="md:col-span-2">
                <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">No. Escritura</label>
                <input type="text" id="moral_no_escritura" placeholder="No. Escritura" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-bold border-gray-200">
            </div>
            <div class="md:col-span-2">
                <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">No. Notario</label>
                <input type="text" id="moral_no_notario" placeholder="No. Notario" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-bold border-gray-200">
            </div>
            <div class="md:col-span-5">
                <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">Nombre del Notario</label>
                <input type="text" id="moral_nombre_notario" placeholder="Nombre Notario" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-bold uppercase border-gray-200">
            </div>
            <div class="md:col-span-3">
                <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Dirección</label>
                <input type="text" id="moral_direccion" placeholder="Dirección" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-bold border-gray-200">
            </div>
        </div>
    </div>
`
    };

    // 3. Variables de Control
    const catalogo = <?= json_encode($data['catalogo_json'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    window.VUT_EDIT_MODE = <?= $modoEdicionVUT ? 'true' : 'false' ?>;
    window.VUT_EDIT_ID = <?= (int)$idSolicitudEdicionVUT ?>;
    window.VUT_EDIT_DATA = <?= json_encode($solicitudEdicionVUT, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    const selMateria = document.getElementById('select-materia');
    const selTramite = document.getElementById('select-tramite');
    const divReq = document.getElementById('lista-requisitos');
    const divPres = document.getElementById('lista-presentados');
    const placeholder = document.getElementById('placeholder-presentados');

    // Inicialización
    window.onload = function() { 
        actualizarTramites(); 
        cambiarOpcionesRepresentante();
    };

    function switchTab(tabId) {
        const contents = document.querySelectorAll('.tab-content');
        contents.forEach(content => content.classList.add('hidden'));
        
        const buttons = document.querySelectorAll('.tab-btn');
        buttons.forEach(btn => {
            btn.classList.remove('border-tlalpan-vino', 'text-tlalpan-vino', 'font-black');
            btn.classList.add('border-transparent', 'text-gray-400', 'font-bold');
        });

        document.getElementById('tab-' + tabId).classList.remove('hidden');
        const activeBtn = document.getElementById('btn-' + tabId);
        activeBtn.classList.remove('border-transparent', 'text-gray-400', 'font-bold');
        activeBtn.classList.add('border-tlalpan-vino', 'text-tlalpan-vino', 'font-black');
    }

    function actualizarTramites() {
        const materia = selMateria.value;
        const tramites = catalogo[materia] || {};
        
        selTramite.innerHTML = '';
        for (const t in tramites) {
            let opt = document.createElement('option');
            opt.value = t;
            opt.innerHTML = t;
            selTramite.appendChild(opt);
        }
        actualizarRequisitos();
    }

function actualizarRequisitos() {
    // 1. DEFINIR REFERENCIAS
    const conObra = document.getElementById('contenedor-tipo-obra'); 
    const conLic = document.getElementById('contenedor-tipo-licencia'); 
    const conManA = document.getElementById('contenedor-tipo-manifestacion-a');
    const conManBC = document.getElementById('contenedor-tipo-manifestacion-bc');
    const conRecibos = document.getElementById('contenedor-recibos-dinamico');
    const conEspectaculos = document.getElementById('contenedor-tipo-espectaculos-publicos');
    const conAlineamientos = document.getElementById('contenedor-tipo-alineamientos');
    const conSeguirdad = document.getElementById('contenedor-tipo-seguridad');
    const conReteolificacion = document.getElementById('contenedor-tipo-retolificacion');
    const conSubdivision = document.getElementById('contenedor-tipo-subdivision');
    const conEstructural = document.getElementById('contenedor-tipo-estructural');

    // 2. RESET TOTAL (Limpieza Quirúrgica)
    // Ocultamos todos los combos y la sección de recibos antes de evaluar nada
    [conObra, conLic, conManA, conManBC, conRecibos, conEspectaculos, conAlineamientos, conSeguirdad, conReteolificacion, conSubdivision, conEstructural].forEach(el => {
        if (el) el.classList.add('hidden');
    });

    const materia = selMateria.value;
    const tramite = selTramite.value;

    // Si no hay trámite, salimos (ya está todo oculto por el paso anterior)
    if (!tramite) return;

    // Buscamos la data en el catálogo
    const dataTramite = catalogo[materia] ? catalogo[materia][tramite] : null;
    if (!dataTramite) return;

    let esTramiteObra = false;
    // Normalizamos el nombre para una búsqueda más segura (sin acentos/minúsculas)
    const tramiteNormalizado = tramite.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

    // 3. ACTIVACIÓN DE COMBOS ESPECÍFICOS
    if (tramite.includes("Publicitación Vecinal")) {
        if (conObra) conObra.classList.remove('hidden');
        esTramiteObra = true;
    } 
    else if (tramite.includes("Licencia de Construcción Especial")) {
        if (conLic) conLic.classList.remove('hidden');
        esTramiteObra = true;
    }
    else if (tramite.includes("Manifestación de Construcción Tipo A")) {
        if (conManA) conManA.classList.remove('hidden');
        esTramiteObra = true;
    }
    else if (tramite.includes("Manifestación de Construcción Tipo B") || 
             tramite.includes("Manifestación de Construcción Tipo C")) {
        if (conManBC) conManBC.classList.remove('hidden');
        esTramiteObra = true;
    }
    // CORRECCIÓN: Detección flexible para Espectáculos Públicos
    else if (tramiteNormalizado.includes("espectaculos publicos")) {
        if (conEspectaculos) conEspectaculos.classList.remove('hidden');
    }
    else if (tramiteNormalizado.includes("constancia de alineamiento y/o")) {
        if (conAlineamientos) conAlineamientos.classList.remove('hidden');
        esTramiteObra = true;
    }
    else if (tramiteNormalizado.includes("visto bueno de seguridad y")) {
        if (conSeguirdad) conSeguirdad.classList.remove('hidden');
        esTramiteObra = true;
    }
    else if (tramiteNormalizado.includes("licencia de relotificacion")) {
        if (conReteolificacion) conReteolificacion.classList.remove('hidden');
        esTramiteObra = true; // Activa recibos ya que es una Licencia con costo
    }
    else if (tramiteNormalizado.includes("licencia de subdivision, fusion y revalidacion")) {
        if (conSubdivision) conSubdivision.classList.remove('hidden');
        esTramiteObra = true; // Activa recibos ya que es una Licencia con costo
    }
    else if (tramiteNormalizado.includes("verificacion de seguridad estructural y su renovacion")) {
        if (conEstructural) conEstructural.classList.remove('hidden');
        esTramiteObra = true; // Activa recibos ya que es una Licencia con costo
    }

    // 4. MOSTRAR RECIBOS SOLO SI ES OBRA
    if (esTramiteObra && conRecibos) {
        conRecibos.classList.remove('hidden');
    }

    // --- 5. RENDERIZADO DE REQUISITOS ---
    const requisitos = dataTramite.requisitos ? dataTramite.requisitos : dataTramite;
    divReq.innerHTML = '';
    divPres.innerHTML = ''; 
    divPres.appendChild(placeholder); 
    placeholder.style.display = 'block';

    if (Array.isArray(requisitos)) {
        requisitos.forEach((req) => {
            let div = document.createElement('div');
            div.className = 'item-requisito flex items-start p-3 bg-white border border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition-all shadow-sm mb-2';
            div.innerHTML = `
                <div class="mr-3 mt-0.5 text-gray-300 transition-colors flex-shrink-0 icono-req">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <span class="text-[11px] text-gray-600 font-bold leading-tight select-none">${req}</span>
            `;
            div.onclick = function() { 
                this.classList.toggle('seleccionado');
                this.querySelector('.icono-req').classList.toggle('text-tlalpan-vino');
            };
            divReq.appendChild(div);
        });
    }

    // --- 6. GESTIÓN DE TABS (CERO DEFAULT PREDIO) ---
    const tipo = dataTramite.tipo_captura; // Ya no hay || 'predio'
    const contenedorCaptura = document.getElementById('contenedor-dinamico-captura');
    const btnTabCaptura = document.getElementById('btn-predio'); 

    // Solo habilitamos si el catálogo pide explícitamente predio o mercado
    if (tipo === 'predio' || tipo === 'mercado') {
        if (btnTabCaptura) {
            btnTabCaptura.classList.remove('hidden'); 
            btnTabCaptura.innerText = (tipo === 'mercado') ? 'DATOS DEL MERCADO' : 'DATOS DEL PREDIO';
        }
        if (contenedorCaptura && plantillasCaptura[tipo]) {
            contenedorCaptura.innerHTML = plantillasCaptura[tipo];
        }
    } else {
        // Para Protección Civil y otros que no tengan tipo_captura: OCULTAR TODO
        if (btnTabCaptura) btnTabCaptura.classList.add('hidden'); 
        if (contenedorCaptura) contenedorCaptura.innerHTML = ''; 

        // Si el usuario estaba en la pestaña de ubicación, lo devolvemos a requisitos
        const tabUbicacion = document.getElementById('tab-predio');
        if (tabUbicacion && !tabUbicacion.classList.contains('hidden')) {
            switchTab('requisitos');
        }
    }

    // --- 7. ACTUALIZACIÓN FINAL ---
    actualizarDetalles(dataTramite.detalles);
    actualizarPlantillaInteresado();

    actualizarResumenBifurcacion();

    document.querySelectorAll('[id^="select-modalidad-"], #select-obra-especial, #select-detalle-manifestacion-a, #select-detalle-manifestacion-bc')
        .forEach(el => {
            el.removeEventListener('change', actualizarResumenBifurcacion);
            el.addEventListener('change', actualizarResumenBifurcacion);
        });
}
    function actualizarDetalles(detalles) {
        const seccion = document.getElementById('seccion-observaciones');
        if (!detalles) {
            if(seccion) seccion.classList.add('hidden');
            return;
        }
        if(seccion) {
            seccion.classList.remove('hidden');
            document.getElementById('obs-costo').innerText = detalles.costo || 'N/A';
            document.getElementById('obs-tiempo').innerText = detalles.tiempo || 'N/A';
            document.getElementById('obs-linea').innerText = detalles.en_linea || 'N/A';
            document.getElementById('obs-materia').innerText = detalles.materia || 'N/A';
            document.getElementById('obs-descripcion').innerText = detalles.observaciones || '';
            document.getElementById('obs-ubicacion').innerText = detalles.ubicacion || '';
        }
    }

    function moverDerecha() {
        const items = divReq.querySelectorAll('.item-requisito.seleccionado');
        if(items.length > 0) placeholder.style.display = 'none';
        items.forEach(item => {
            item.classList.remove('seleccionado');
            item.querySelector('.icono-req').classList.remove('text-tlalpan-vino');
            item.querySelector('.icono-req').classList.add('text-green-500');
            item.classList.add('border-green-100', 'bg-green-50/30');
            item.onclick = function() { this.classList.toggle('listo-para-volver'); };
            divPres.appendChild(item);
        });
    }

    function moverIzquierda() {
        const items = divPres.querySelectorAll('.item-requisito.listo-para-volver');
        items.forEach(item => {
            item.classList.remove('listo-para-volver', 'border-green-100', 'bg-green-50/30');
            item.querySelector('.icono-req').classList.remove('text-green-500');
            item.onclick = function() { 
                this.classList.toggle('seleccionado');
                this.querySelector('.icono-req').classList.toggle('text-tlalpan-vino');
            };
            divReq.appendChild(item);
        });
        if(divPres.querySelectorAll('.item-requisito').length === 0) {
            placeholder.style.display = 'block';
        }
    }

    function limpiarTodo() {
        if(!confirm("¿Está seguro de limpiar todo el formulario?")) return;
        selMateria.selectedIndex = 0;
        actualizarTramites();
        const inputs = document.querySelectorAll('.tab-content input:not([type="checkbox"]), .tab-content textarea');
        inputs.forEach(input => input.value = '');
        switchTab('requisitos');
    }

   function actualizarPlantillaInteresado() {
    const tipoPersona = document.getElementById('select-tipo-persona').value;
    const tipoRep = document.getElementById('select-tipo-rep').value;
    const btnAutorizada = document.getElementById('btn-autorizada');
    const btnLegal = document.getElementById('btn-legal'); // Referencia al nuevo botón

    // Inyectar contenido dinámico en Interesado (Física/Moral)
    const contenedor = document.getElementById('contenedor-dinamico-interesado');
    if (contenedor && plantillasInteresado[tipoPersona]) {
        contenedor.innerHTML = plantillasInteresado[tipoPersona];
    }

    // Control de pestañas adicionales
    // 1. Mostrar Representante Legal si se elige en Moral
    if (tipoPersona === 'moral' && tipoRep === 'legal') {
        btnLegal.classList.remove('hidden');
    } else {
        btnLegal.classList.add('hidden');
        if (!document.getElementById('tab-legal').classList.contains('hidden')) {
            switchTab('interesado');
        }
    }

    // 2. Mostrar Persona Autorizada
    if (tipoRep === 'autorizada') {
        btnAutorizada.classList.remove('hidden');
    } else {
        btnAutorizada.classList.add('hidden');
        if (!document.getElementById('tab-autorizada').classList.contains('hidden')) {
            switchTab('interesado');
        }
    }
    setTimeout(() => {
        if (typeof inicializarEventos === 'function') {
            inicializarEventos();
        }
    }, 100);
}

// Asegúrate de que tus funciones de cambio llamen a esta nueva lógica
function cambiarOpcionesRepresentante() {
    const tipoPersona = document.getElementById('select-tipo-persona').value;
    const selectRep = document.getElementById('select-tipo-rep');
    
    selectRep.innerHTML = '';

    if (tipoPersona === 'fisica') {
        const opciones = [
            { val: 'representante', text: 'TIPO DE REPRESENTANTE' },
            { val: 'autorizada', text: 'PERSONA AUTORIZADA' }
        ];
        opciones.forEach(opt => {
            let o = document.createElement('option');
            o.value = opt.val; o.text = opt.text;
            selectRep.appendChild(o);
        });
    } else {
        const opciones = [
            { val: 'autorizada', text: 'PERSONA AUTORIZADA' },
            { val: 'legal', text: 'REPRESENTANTE LEGAL' }
        ];
        opciones.forEach(opt => {
            let o = document.createElement('option');
            o.value = opt.val; o.text = opt.text;
            selectRep.appendChild(o);
        });
    }
    
    // Al cambiar las opciones, revisamos si se debe mostrar la pestaña extra
    actualizarPlantillaInteresado();
}
</script>
<script>
window.inicializarEventos = function inicializarEventos() {
    window.dataCDMX = window.dataCDMX || {};

    function vincularEventosDireccion() {
        const configuraciones = [
            { alc: 'select-alcaldia', col: 'select-colonia', cp: 'cp', hidden: 'colonia_nombre' },
            { alc: 'predio_alcaldia', col: 'predio_colonia', cp: 'predio_codigo_postal', hidden: 'predio-colonia-nolista' },
            { alc: 'mercado_alcaldia', col: 'mercado_colonia', cp: 'mercado_cp', hidden: 'mercado_colonia_nombre' },
            { alc: 'leg_dom_del', col: 'leg_dom_colonia', cp: 'leg_dom_cp', hidden: 'leg_colonia_nombre' },
            { alc: 'aut_dom_del', col: 'aut_dom_colonia', cp: 'aut_dom_cp', hidden: null }
        ];

        configuraciones.forEach(config => {
            const selectAlc = document.getElementById(config.alc);
            const selectCol = document.getElementById(config.col);
            const inputCP = document.getElementById(config.cp);
            const inputHidden = config.hidden ? document.getElementById(config.hidden) : null;

            if (!selectAlc || !selectCol) return;

            if (!selectAlc.dataset.eventActive) {
                selectAlc.dataset.eventActive = "true";

                selectAlc.addEventListener('change', () => {
                    actualizarColonias(selectAlc, selectCol, inputCP, inputHidden);
                });

                selectCol.addEventListener('change', () => {
                    actualizarCP(selectCol, inputCP, inputHidden);
                });
            }

            if (selectAlc.value && selectCol.options.length <= 1) {
                actualizarColonias(selectAlc, selectCol, inputCP, inputHidden);
            }
        });
    }

    fetch('assets/data/colonias_cdmx.json')
        .then(res => res.json())
        .then(data => {
            window.dataCDMX = data;
            vincularEventosDireccion();

            document.addEventListener('click', (e) => {
                if (e.target.closest('.tab-btn')) {
                    setTimeout(vincularEventosDireccion, 200);
                }
            });
        })
        .catch(err => console.error("Error cargando el catálogo de colonias:", err));
};

function normalizarTexto(txt) {
    return (txt || '')
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .trim()
        .toUpperCase();
}

function buscarAlcaldia(valorAlcaldia) {
    const normalizada = normalizarTexto(valorAlcaldia);
    const dataCDMX = window.dataCDMX || {};

    return Object.keys(dataCDMX).find(k => {
        return normalizarTexto(k) === normalizada;
    });
}

function actualizarColonias(selectAlc, selectCol, inputCP, inputHidden) {
    const valorAlcaldia = selectAlc.value.trim();
    const dataCDMX = window.dataCDMX || {};

    selectCol.innerHTML = '<option value="">Seleccione una colonia...</option>';
    selectCol.disabled = true;

    if (inputCP) inputCP.value = '';
    if (inputHidden) inputHidden.value = '';

    if (!valorAlcaldia) return;

    const llaveEncontrada = buscarAlcaldia(valorAlcaldia);

    if (!llaveEncontrada || !Array.isArray(dataCDMX[llaveEncontrada])) {
        console.warn('No se encontraron colonias para:', valorAlcaldia);
        return;
    }

    selectCol.disabled = false;

    const fragment = document.createDocumentFragment();

    dataCDMX[llaveEncontrada].forEach(col => {
        const opt = document.createElement('option');

        opt.value = col.nombre || '';
        opt.textContent = col.cp ? `${col.nombre} - C.P. ${col.cp}` : col.nombre;
        opt.dataset.cp = col.cp || '';

        fragment.appendChild(opt);
    });

    selectCol.appendChild(fragment);
}

function actualizarCP(selectCol, inputCP, inputHidden) {
    const option = selectCol.options[selectCol.selectedIndex];

    if (!option || !option.value) {
        if (inputCP) inputCP.value = '';
        if (inputHidden) inputHidden.value = '';
        return;
    }

    if (inputCP) {
        inputCP.value = option.dataset.cp || '';
    }

    if (inputHidden) {
        inputHidden.value = option.value || '';
    }
}

/**
 * RECOLECTOR UNIVERSAL
 * Convierte guiones en guiones bajos y normaliza llaves a mayúsculas.
 */
function recolectarInputs(containerId) {
    const container = document.getElementById(containerId);
    const data = {};

    if (!container) return {};

    const elements = container.querySelectorAll('input, select, textarea');

    elements.forEach(el => {
        const key = (el.name || el.id || '').toUpperCase().replace(/-/g, '_');

        if (!key) return;

        if (el.type === 'checkbox') {
            data[key] = el.checked ? 'SÍ' : 'NO';
            return;
        }

        const value = (el.value || '').trim();

        if (value !== '') {
            data[key] = value.toUpperCase();
        }
    });

    return data;
}

/**
 * Limpia recibos cuando no aplican o cuando se cambia de trámite.
 */
function limpiarRecibos() {
    const contenedor = document.getElementById('contenedor-recibos-dinamico');

    if (!contenedor) return;

    contenedor.querySelectorAll('input').forEach(input => {
        input.value = '';
    });
}

/**
 * Convierte texto de monto en número real.
 */
function normalizarMontoRecibo(valor) {
    if (valor === null || valor === undefined) return 0;

    const limpio = String(valor)
        .replace(/,/g, '')
        .replace(/\$/g, '')
        .trim();

    const numero = Number(limpio);

    return isNaN(numero) ? 0 : numero;
}

/**
 * Detecta si un folio de recibo es realmente válido.
 */
function folioReciboValido(folio) {
    folio = String(folio || '').trim().toUpperCase();

    if (folio === '') return false;
    if (folio === 'N/A') return false;
    if (folio === 'NA') return false;
    if (folio === 'S/N') return false;
    if (folio === 'SN') return false;
    if (folio === 'SIN FOLIO') return false;

    return true;
}

/**
 * Recolecta SOLO recibos reales.
 * No manda:
 * - N/A
 * - vacío
 * - 0
 * - 0.00
 * - recibos de secciones ocultas
 */
function recolectarRecibosValidos() {
    const contenedor = document.getElementById('contenedor-recibos-dinamico');

    if (!contenedor) return {};

    if (contenedor.classList.contains('hidden')) {
        return {};
    }

    const recibos = {};

    for (let i = 1; i <= 10; i++) {
        const inputFolio = document.getElementById(`folio_recibo_${i}`);
        const inputMonto = document.getElementById(`monto_recibo_${i}`);

        if (!inputFolio && !inputMonto) continue;

        const folio = (inputFolio?.value || '').trim().toUpperCase();
        const montoRaw = (inputMonto?.value || '').trim();
        const montoNum = normalizarMontoRecibo(montoRaw);

        const tieneFolio = folioReciboValido(folio);
        const tieneMonto = montoNum > 0;

        if (tieneFolio || tieneMonto) {
            if (tieneFolio) {
                recibos[`FOLIO_RECIBO_${i}`] = folio;
            }

            if (tieneMonto) {
                recibos[`MONTO_RECIBO_${i}`] = montoNum.toFixed(2);
            }
        }
    }

    return recibos;
}

/**
 * MODO INSPECTOR
 */
function inspeccionarData() {
    console.clear();

    const dataPreview = {
        solicitud: {
            tramite: document.getElementById('select-tramite')?.value || '',
            persona: document.getElementById('select-tipo-persona')?.value || ''
        },
        interesado_base: recolectarInputs('tab-interesado'),
        interesado_dinamico: recolectarInputs('contenedor-dinamico-interesado'),
        representante_legal: recolectarInputs('tab-legal'),
        persona_autorizada: recolectarInputs('tab-autorizada'),
        ubicacion_objeto: recolectarInputs('contenedor-dinamico-captura'),
        bifurcacion: recolectarBifurcacion(),
        recibos_validos: recolectarRecibosValidos(),
        requisitos: Array.from(document.querySelectorAll('#lista-presentados .item-requisito'))
            .map(div => div.innerText.trim())
    };

    console.group("%c 🔍 INSPECCIÓN DE PAYLOAD NORMALIZADO ", "background: #773357; color: white; font-size: 14px; padding: 5px;");
    console.log("%cResumen de Solicitud:", "font-weight: bold;", dataPreview.solicitud);
    console.log("%cInteresado Base:", "color: blue;");
    console.table(dataPreview.interesado_base);
    console.log("%cInteresado Dinámico:", "color: purple;");
    console.table(dataPreview.interesado_dinamico);
    console.log("%cDatos Específicos:", "color: green;");
    console.table(dataPreview.ubicacion_objeto);
    console.log("%cBifurcación:", "color: #773357;");
    console.table(dataPreview.bifurcacion);
    console.log("%cRecibos Válidos:", "color: #059669;");
    console.table(dataPreview.recibos_validos);
    console.log("OBJETO COMPLETO PARA ENVÍO:", dataPreview);
    console.groupEnd();

    alert("Revisa la consola F12. Ya solo se mostrarán recibos válidos si realmente fueron capturados.");
}

function textoSelect(id) {
    const el = document.getElementById(id);

    if (!el) return '';

    const contenedor = el.closest('[id^="contenedor-tipo-"]');

    if (contenedor && contenedor.classList.contains('hidden')) {
        return '';
    }

    const selected = el.options?.[el.selectedIndex];

    return selected ? selected.text.trim().toUpperCase() : '';
}

function valorSelect(id) {
    const el = document.getElementById(id);

    if (!el) return '';

    const contenedor = el.closest('[id^="contenedor-tipo-"]');

    if (contenedor && contenedor.classList.contains('hidden')) {
        return '';
    }

    return el.value ? el.value.toUpperCase() : '';
}

function recolectarBifurcacion() {
    const selTramite = document.getElementById('select-tramite');
    const tramiteTexto = selTramite?.options[selTramite.selectedIndex]?.text || '';

    const tramiteNormalizado = tramiteTexto
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "");

    let data = {
        clave: '',
        modalidad: '',
        modalidad_texto: '',
        detalle: '',
        detalle_texto: '',
        requiere_predio: 'NO',
        requiere_recibos: 'NO'
    };

    if (tramiteTexto.includes("Publicitación Vecinal")) {
        data.clave = 'PUBLICITACION_VECINAL';
        data.modalidad = valorSelect('select-modalidad-obra');
        data.modalidad_texto = textoSelect('select-modalidad-obra');
        data.requiere_predio = 'SÍ';
        data.requiere_recibos = 'SÍ';
    }

    else if (tramiteTexto.includes("Licencia de Construcción Especial")) {
        data.clave = 'LICENCIA_CONSTRUCCION_ESPECIAL';
        data.modalidad = valorSelect('select-modalidad-licencia');
        data.modalidad_texto = textoSelect('select-modalidad-licencia');
        data.detalle = valorSelect('select-obra-especial');
        data.detalle_texto = textoSelect('select-obra-especial');
        data.requiere_predio = 'SÍ';
        data.requiere_recibos = 'SÍ';
    }

    else if (tramiteTexto.includes("Manifestación de Construcción Tipo A")) {
        data.clave = 'MANIFESTACION_A';
        data.modalidad = valorSelect('select-modalidad-manifestacion-a');
        data.modalidad_texto = textoSelect('select-modalidad-manifestacion-a');
        data.detalle = valorSelect('select-detalle-manifestacion-a');
        data.detalle_texto = textoSelect('select-detalle-manifestacion-a');
        data.requiere_predio = 'SÍ';
        data.requiere_recibos = 'SÍ';
    }

    else if (
        tramiteTexto.includes("Manifestación de Construcción Tipo B") ||
        tramiteTexto.includes("Manifestación de Construcción Tipo C")
    ) {
        data.clave = 'MANIFESTACION_BC';
        data.modalidad = valorSelect('select-modalidad-manifestacion-bc');
        data.modalidad_texto = textoSelect('select-modalidad-manifestacion-bc');
        data.detalle = valorSelect('select-detalle-manifestacion-bc');
        data.detalle_texto = textoSelect('select-detalle-manifestacion-bc');
        data.requiere_predio = 'SÍ';
        data.requiere_recibos = 'SÍ';
    }

    else if (tramiteNormalizado.includes("espectaculos publicos")) {
        data.clave = 'ESPECTACULOS_PUBLICOS';
        data.modalidad = valorSelect('select-modalidad-espectaculos-publicos');
        data.modalidad_texto = textoSelect('select-modalidad-espectaculos-publicos');
    }

    else if (tramiteNormalizado.includes("constancia de alineamiento")) {
        data.clave = 'ALINEAMIENTO_NUMERO_OFICIAL';
        data.modalidad = valorSelect('select-modalidad-alineamientos');
        data.modalidad_texto = textoSelect('select-modalidad-alineamientos');
        data.requiere_predio = 'SÍ';
        data.requiere_recibos = 'SÍ';
    }

    else if (tramiteNormalizado.includes("visto bueno de seguridad")) {
        data.clave = 'VISTO_BUENO_SEGURIDAD';
        data.modalidad = valorSelect('select-modalidad-seguridad');
        data.modalidad_texto = textoSelect('select-modalidad-seguridad');
        data.requiere_predio = 'SÍ';
        data.requiere_recibos = 'SÍ';
    }

    else if (tramiteNormalizado.includes("licencia de relotificacion")) {
        data.clave = 'RELOTIFICACION';
        data.modalidad = valorSelect('select-modalidad-retolificacion');
        data.modalidad_texto = textoSelect('select-modalidad-retolificacion');
        data.requiere_predio = 'SÍ';
        data.requiere_recibos = 'SÍ';
    }

    else if (tramiteNormalizado.includes("licencia de subdivision")) {
        data.clave = 'SUBDIVISION_FUSION';
        data.modalidad = valorSelect('select-modalidad-subdivision');
        data.modalidad_texto = textoSelect('select-modalidad-subdivision');
        data.requiere_predio = 'SÍ';
        data.requiere_recibos = 'SÍ';
    }

    else if (tramiteNormalizado.includes("verificacion de seguridad estructural")) {
        data.clave = 'SEGURIDAD_ESTRUCTURAL';
        data.modalidad = valorSelect('select-modalidad-estructural');
        data.modalidad_texto = textoSelect('select-modalidad-estructural');
        data.requiere_predio = 'SÍ';
        data.requiere_recibos = 'SÍ';
    }

    return data;
}

function vutCanvasEstaVacio(canvas) {
    if (!canvas) return true;

    const blank = document.createElement('canvas');
    blank.width = canvas.width;
    blank.height = canvas.height;

    return canvas.toDataURL() === blank.toDataURL();
}

function vutPrepararSignaturePad(canvas) {
    const ctx = canvas.getContext('2d');
    let dibujando = false;
    let ultimo = null;

    function resizeCanvas() {
        const rect = canvas.getBoundingClientRect();
        const ratio = window.devicePixelRatio || 1;

        const dataAnterior = canvas.toDataURL();

        canvas.width = Math.max(500, rect.width * ratio);
        canvas.height = 180 * ratio;

        ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
        ctx.lineWidth = 2.4;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        ctx.strokeStyle = '#111827';

        if (dataAnterior && dataAnterior.length > 100) {
            const img = new Image();
            img.onload = () => {
                ctx.drawImage(img, 0, 0, rect.width, 180);
            };
            img.src = dataAnterior;
        }
    }

    function getPos(e) {
        const rect = canvas.getBoundingClientRect();

        let clientX;
        let clientY;

        if (e.touches && e.touches.length > 0) {
            clientX = e.touches[0].clientX;
            clientY = e.touches[0].clientY;
        } else {
            clientX = e.clientX;
            clientY = e.clientY;
        }

        return {
            x: clientX - rect.left,
            y: clientY - rect.top
        };
    }

    function iniciar(e) {
        e.preventDefault();
        dibujando = true;
        ultimo = getPos(e);
    }

    function mover(e) {
        if (!dibujando) return;

        e.preventDefault();

        const pos = getPos(e);

        ctx.beginPath();
        ctx.moveTo(ultimo.x, ultimo.y);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();

        ultimo = pos;
    }

    function terminar(e) {
        if (e) e.preventDefault();
        dibujando = false;
        ultimo = null;
    }

    resizeCanvas();

    canvas.addEventListener('mousedown', iniciar);
    canvas.addEventListener('mousemove', mover);
    canvas.addEventListener('mouseup', terminar);
    canvas.addEventListener('mouseleave', terminar);

    canvas.addEventListener('touchstart', iniciar, { passive: false });
    canvas.addEventListener('touchmove', mover, { passive: false });
    canvas.addEventListener('touchend', terminar, { passive: false });

    return {
        limpiar() {
            const rect = canvas.getBoundingClientRect();
            ctx.clearRect(0, 0, rect.width, 180);
        },
        estaVacio() {
            return vutCanvasEstaVacio(canvas);
        },
        dataURL() {
            return canvas.toDataURL('image/png');
        }
    };
}

async function capturarFirmasVUT() {
    const html = `
        <div style="text-align:left;">
            <p style="font-size:13px;color:#4b5563;font-weight:700;margin-bottom:14px;">
                Solicita la firma del capturista y, si está presente, la firma del interesado.
            </p>

            <div style="margin-bottom:18px;">
                <div style="font-size:11px;font-weight:900;color:#773357;text-transform:uppercase;margin-bottom:6px;">
                    Firma del capturista <span style="color:#dc2626;">*</span>
                </div>
                <div style="border:2px dashed #d1d5db;border-radius:16px;background:#fff;padding:8px;">
                    <canvas id="firma_capturista_canvas" style="width:100%;height:180px;touch-action:none;display:block;"></canvas>
                </div>
                <button type="button" id="limpiar_firma_capturista" style="margin-top:8px;font-size:11px;font-weight:900;color:#773357;">
                    Limpiar firma del capturista
                </button>
            </div>

            <div style="margin-bottom:12px;">
                <div style="font-size:11px;font-weight:900;color:#773357;text-transform:uppercase;margin-bottom:6px;">
                    Firma del interesado
                </div>
                <div style="border:2px dashed #d1d5db;border-radius:16px;background:#fff;padding:8px;">
                    <canvas id="firma_interesado_canvas" style="width:100%;height:180px;touch-action:none;display:block;"></canvas>
                </div>
                <button type="button" id="limpiar_firma_interesado" style="margin-top:8px;font-size:11px;font-weight:900;color:#773357;">
                    Limpiar firma del interesado
                </button>
            </div>

            <label style="display:flex;align-items:center;gap:8px;font-size:12px;font-weight:800;color:#374151;margin-top:12px;">
                <input type="checkbox" id="interesado_no_presente">
                El interesado no se encuentra presente / no firma en este momento
            </label>

            <textarea id="motivo_sin_firma_interesado"
                placeholder="Motivo opcional cuando no firma el interesado"
                style="width:100%;margin-top:10px;border:1px solid #d1d5db;border-radius:12px;padding:10px;font-size:12px;min-height:70px;"></textarea>
        </div>
    `;

    const result = await Swal.fire(vutSwalConfig({
        icon: 'info',
        title: 'Firmas digitales',
        html,
        width: 760,
        showCancelButton: true,
        confirmButtonText: 'Guardar firmas y continuar',
        cancelButtonText: 'Regresar',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            const canvasCapturista = document.getElementById('firma_capturista_canvas');
            const canvasInteresado = document.getElementById('firma_interesado_canvas');

            window.__vutFirmaCapturistaPad = vutPrepararSignaturePad(canvasCapturista);
            window.__vutFirmaInteresadoPad = vutPrepararSignaturePad(canvasInteresado);

            document.getElementById('limpiar_firma_capturista').addEventListener('click', () => {
                window.__vutFirmaCapturistaPad.limpiar();
            });

            document.getElementById('limpiar_firma_interesado').addEventListener('click', () => {
                window.__vutFirmaInteresadoPad.limpiar();
            });
        },
        preConfirm: () => {
            const padCapturista = window.__vutFirmaCapturistaPad;
            const padInteresado = window.__vutFirmaInteresadoPad;

            const interesadoNoPresente = document.getElementById('interesado_no_presente')?.checked || false;
            const motivo = document.getElementById('motivo_sin_firma_interesado')?.value?.trim() || '';

            if (!padCapturista || padCapturista.estaVacio()) {
                Swal.showValidationMessage('La firma del capturista es obligatoria.');
                return false;
            }

            const interesadoFirmo = padInteresado && !padInteresado.estaVacio();

            if (!interesadoFirmo && !interesadoNoPresente) {
                Swal.showValidationMessage('El interesado debe firmar o debes marcar que no se encuentra presente.');
                return false;
            }

            return {
                capturista: {
                    firmo: true,
                    imagen: padCapturista.dataURL(),
                    fecha: new Date().toISOString()
                },
                interesado: {
                    firmo: interesadoFirmo,
                    no_presente: interesadoNoPresente,
                    motivo_no_firma: motivo,
                    imagen: interesadoFirmo ? padInteresado.dataURL() : '',
                    fecha: interesadoFirmo ? new Date().toISOString() : ''
                }
            };
        }
    }));

    if (!result.isConfirmed) {
        return null;
    }

    return result.value;
}

/**
 * FINALIZAR CAPTURA
 */
async function finalizarCaptura(event) {
    if (event) event.preventDefault();

    const btn = event ? event.currentTarget : null;
    const originalContent = btn ? btn.innerHTML : "";

    const selMateria = document.getElementById('select-materia');
    const selTramite = document.getElementById('select-tramite');
    const selPersona = document.getElementById('select-tipo-persona');
    const selRep = document.getElementById('select-tipo-rep');

    if (!selTramite || !selTramite.value) {
        return alert("⚠️ Por favor, seleccione un trámite específico para continuar.");
    }

    if (!confirm("¿Deseas finalizar la captura y generar el comprobante oficial?")) return;

    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner"></span> Procesando...';
    }

    try {
        const payload = {
            solicitud: {
                materia: selMateria.value.toUpperCase(),
                tramite: selTramite.options[selTramite.selectedIndex].text.toUpperCase(),
                tipo_persona: selPersona.value.toUpperCase(),
                tipo_representante: selRep.value.toUpperCase()
            },

            bifurcacion: recolectarBifurcacion(),

            interesado: {
                datos: recolectarInputs('tab-interesado'),
                datos_dinamicos: recolectarInputs('contenedor-dinamico-interesado')
            },

            representante_legal: recolectarInputs('tab-legal'),
            persona_autorizada: recolectarInputs('tab-autorizada'),

            especificos: recolectarInputs('contenedor-dinamico-captura'),

            // IMPORTANTE:
            // Ya no usamos recolectarInputs aquí porque mandaba 0.00 aunque no existiera recibo.
            recibos: recolectarRecibosValidos(),

            requisitos_validados: Array.from(document.querySelectorAll('#lista-presentados .item-requisito'))
                .map(div => div.innerText.trim().toUpperCase()),

            observaciones: document.getElementById('observaciones')?.value.toUpperCase() || "",
            firmas: firmas

            
        };

        console.log("🚀 Enviando Payload Estructurado:", payload);

        const response = await fetch('index.php?route=guardar_tramite', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(payload)
        });

        const rawText = await response.text();

        let result;

        try {
            result = JSON.parse(rawText);
        } catch (e) {
            console.error("Respuesta no JSON del servidor:", rawText);
            throw new Error("El servidor respondió algo que no es JSON. Revisa errores PHP en guardar().");
        }

        if (!response.ok) {
            throw new Error(result.error || `Error en el servidor: ${response.status} ${response.statusText}`);
        }
        const firmas = await capturarFirmasVUT();

        if (!firmas) {
            return;
        }
        if (result.success) {
            const urlPdf = `index.php?route=ventanilla/generarComprobante&id=${result.id}`;
            window.open(urlPdf, '_blank');

            alert("✅ Solicitud registrada con folio: " + result.folio);
            window.location.href = 'index.php?route=ventanilla';
        } else {
            throw new Error(result.error || "Error desconocido al guardar.");
        }

    } catch (error) {
        console.error("❌ Error en el proceso:", error);
        alert("Hubo un error al procesar la solicitud: " + error.message);

        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalContent;
        }
    }
}

function actualizarResumenBifurcacion() {
    const resumen = document.getElementById('resumen-bifurcacion');
    const texto = document.getElementById('resumen-bifurcacion-texto');

    if (!resumen || !texto) return;

    const selTramite = document.getElementById('select-tramite');
    const tramite = selTramite?.options[selTramite.selectedIndex]?.text || '';
    const bif = recolectarBifurcacion();

    if (!bif.clave) {
        resumen.classList.add('hidden');
        texto.innerText = '';
        return;
    }

    const partes = [
        tramite,
        bif.modalidad_texto,
        bif.detalle_texto
    ].filter(Boolean);

    texto.innerText = partes.join(' / ').toUpperCase();
    resumen.classList.remove('hidden');
}

/**
 * Inicialización segura.
 */
document.addEventListener('DOMContentLoaded', () => {
    if (typeof window.inicializarEventos === 'function') {
        window.inicializarEventos();
    }

    setTimeout(() => {
        actualizarResumenBifurcacion();

        document
            .querySelectorAll('[id^="select-modalidad-"], #select-obra-especial, #select-detalle-manifestacion-a, #select-detalle-manifestacion-bc')
            .forEach(el => {
                el.removeEventListener('change', actualizarResumenBifurcacion);
                el.addEventListener('change', actualizarResumenBifurcacion);
            });
    }, 300);
});
</script>

<!-- SweetAlert2 + Validaciones VUT: capa segura encima del script original funcional -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .vut-input-error {
        border-color: #dc2626 !important;
        background-color: #fff7f7 !important;
        box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.12) !important;
    }

    .vut-error-msg {
        display: block;
        margin-top: 4px;
        font-size: 10px;
        line-height: 1.2;
        color: #dc2626;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .vut-input-ok {
        border-color: #10b981 !important;
    }

    .swal2-popup.vut-swal-popup {
        border-radius: 24px !important;
        padding: 2rem !important;
        font-family: inherit !important;
    }

    .swal2-title.vut-swal-title {
        color: #773357 !important;
        font-weight: 900 !important;
        text-transform: uppercase !important;
        letter-spacing: -0.02em !important;
    }

    .swal2-html-container.vut-swal-html {
        color: #4b5563 !important;
        font-size: 0.92rem !important;
        font-weight: 600 !important;
    }

    .vut-swal-confirm {
        background: #773357 !important;
        color: white !important;
        border-radius: 14px !important;
        padding: 0.75rem 1.4rem !important;
        font-weight: 900 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.08em !important;
        font-size: 11px !important;
    }

    .vut-swal-cancel {
        background: #f3f4f6 !important;
        color: #4b5563 !important;
        border-radius: 14px !important;
        padding: 0.75rem 1.4rem !important;
        font-weight: 900 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.08em !important;
        font-size: 11px !important;
    }

    .vut-swal-deny {
        background: #dc2626 !important;
        color: white !important;
        border-radius: 14px !important;
        padding: 0.75rem 1.4rem !important;
        font-weight: 900 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.08em !important;
        font-size: 11px !important;
    }
</style>

<script>
(function () {
    'use strict';

    function vutSwalDisponible() {
        return typeof window.Swal !== 'undefined' && typeof window.Swal.fire === 'function';
    }

    function vutSwalConfig(extra = {}) {
        return {
            buttonsStyling: false,
            reverseButtons: true,
            customClass: {
                popup: 'vut-swal-popup',
                title: 'vut-swal-title',
                htmlContainer: 'vut-swal-html',
                confirmButton: 'vut-swal-confirm',
                cancelButton: 'vut-swal-cancel',
                denyButton: 'vut-swal-deny'
            },
            ...extra
        };
    }

    async function vutAlert({
        icon = 'info',
        title = 'Aviso',
        text = '',
        html = '',
        confirmButtonText = 'Entendido',
        timer = null
    } = {}) {
        if (!vutSwalDisponible()) {
            window.alert(text || html.replace(/<[^>]+>/g, '') || title);
            return { isConfirmed: true };
        }

        return window.Swal.fire(vutSwalConfig({
            icon,
            title,
            text: html ? undefined : text,
            html: html || undefined,
            confirmButtonText,
            timer,
            timerProgressBar: !!timer
        }));
    }

    async function vutConfirm({
        icon = 'question',
        title = 'Confirmar acción',
        text = '',
        html = '',
        confirmButtonText = 'Sí, continuar',
        cancelButtonText = 'Cancelar'
    } = {}) {
        if (!vutSwalDisponible()) {
            return window.confirm(text || html.replace(/<[^>]+>/g, '') || title);
        }

        const result = await window.Swal.fire(vutSwalConfig({
            icon,
            title,
            text: html ? undefined : text,
            html: html || undefined,
            showCancelButton: true,
            confirmButtonText,
            cancelButtonText
        }));

        return result.isConfirmed;
    }

    function vutToast({
        icon = 'success',
        title = 'Listo',
        position = 'top-end',
        timer = 2800
    } = {}) {
        if (!vutSwalDisponible()) {
            console.log(title);
            return;
        }

        window.Swal.fire({
            toast: true,
            icon,
            title,
            position,
            showConfirmButton: false,
            timer,
            timerProgressBar: true,
            background: '#ffffff',
            color: '#374151'
        });
    }

    function vutLoading(title = 'Procesando...', html = 'Por favor espera un momento.') {
        if (!vutSwalDisponible()) return;

        window.Swal.fire(vutSwalConfig({
            title,
            html,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => window.Swal.showLoading()
        }));
    }

    function vutCloseLoading() {
        if (vutSwalDisponible()) {
            window.Swal.close();
        }
    }

    function vutHtmlEscape(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function vutCampoKey(el) {
        return String(el?.name || el?.id || '').toLowerCase();
    }

    function vutTabEstaHabilitado(tabContent) {
        if (!tabContent || !tabContent.id || !tabContent.id.startsWith('tab-')) return true;

        const tabName = tabContent.id.replace('tab-', '');
        const btn = document.getElementById('btn-' + tabName);

        return !btn || !btn.classList.contains('hidden');
    }

    function vutCampoParticipa(el) {
        if (!el || el.disabled) return false;

        let node = el;

        while (node && node !== document.body) {
            if (node.classList && node.classList.contains('hidden')) {
                if (node.classList.contains('tab-content')) {
                    if (!vutTabEstaHabilitado(node)) return false;
                } else {
                    return false;
                }
            }

            node = node.parentElement;
        }

        return true;
    }

    function vutGetLabel(el) {
        if (!el) return '';

        if (el.id) {
            const labelFor = document.querySelector(`label[for="${CSS.escape(el.id)}"]`);
            if (labelFor) return labelFor.innerText || '';
        }

        const parent = el.closest('div');
        const label = parent ? parent.querySelector('label') : null;

        return label ? label.innerText || '' : '';
    }

    function vutEsRequerido(el) {
        if (!el || !vutCampoParticipa(el)) return false;
        if (el.type === 'hidden') return false;
        if (el.required) return true;

        const label = vutGetLabel(el);
        return label.includes('*') || label.includes('＊');
    }

    function vutTipoCampo(el) {
        const key = vutCampoKey(el);

        if (key.includes('email') || key.includes('correo') || key.includes('e_mail')) return 'email';

        if (
            key.includes('telefono') ||
            key.includes('tel_') ||
            key.endsWith('_tel') ||
            key === 'telefono'
        ) return 'telefono';

        if (key.includes('rfc')) return 'rfc';
        if (key.includes('curp')) return 'curp';

        if (
            key === 'cp' ||
            key.endsWith('_cp') ||
            key.includes('codigo_postal') ||
            key.includes('código_postal')
        ) return 'cp';

        if (key.includes('monto')) return 'monto';
        if (key.includes('folio_recibo')) return 'folio_recibo';

        if (
            key.includes('no_escritura') ||
            key.includes('num_escritura') ||
            key.includes('no_notario') ||
            key.includes('num_notario')
        ) return 'solo_numeros';

        if (
            key.includes('numero_exterior') ||
            key.includes('num_ext') ||
            key.includes('dom_num_ext')
        ) return 'numero_exterior';

        if (
            key.includes('nombres') ||
            key.includes('ape_paterno') ||
            key.includes('ape_materno') ||
            key.endsWith('_paterno') ||
            key.endsWith('_materno') ||
            key.includes('nombre_notario')
        ) return 'nombre_persona';

        return 'texto';
    }

    function vutNormalizarInput(el) {
        if (!el || el.tagName === 'SELECT') return;

        const tipo = vutTipoCampo(el);
        let value = el.value || '';

        if (tipo === 'telefono') {
            el.value = value.replace(/\D/g, '').slice(0, 10);
            return;
        }

        if (tipo === 'cp') {
            el.value = value.replace(/\D/g, '').slice(0, 5);
            return;
        }

        if (tipo === 'rfc') {
            el.value = value.toUpperCase().replace(/[^A-ZÑ&0-9]/g, '').slice(0, 13);
            return;
        }

        if (tipo === 'curp') {
            el.value = value.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 18);
            return;
        }

        if (tipo === 'email') {
            el.value = value.toLowerCase().replace(/\s/g, '').slice(0, 150);
            return;
        }

        if (tipo === 'monto') {
            value = value.replace(/[^0-9.]/g, '');
            const parts = value.split('.');

            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }

            if (value.includes('.')) {
                const [entero, decimal = ''] = value.split('.');
                value = entero.slice(0, 10) + '.' + decimal.slice(0, 2);
            } else {
                value = value.slice(0, 10);
            }

            el.value = value;
            return;
        }

        if (tipo === 'solo_numeros') {
            el.value = value.replace(/\D/g, '').slice(0, 20);
            return;
        }

        if (tipo === 'numero_exterior') {
            el.value = value.toUpperCase().replace(/[^A-Z0-9ÁÉÍÓÚÜÑ#\-\s/]/g, '').slice(0, 20);
            return;
        }

        if (tipo === 'nombre_persona') {
            el.value = value.toUpperCase().replace(/[^A-ZÁÉÍÓÚÜÑ\s.'-]/g, '').replace(/\s{2,}/g, ' ').slice(0, 150);
            return;
        }

        if (tipo === 'folio_recibo') {
            el.value = value.toUpperCase().replace(/[^A-Z0-9\-\/]/g, '').slice(0, 100);
            return;
        }

        if (el.tagName !== 'TEXTAREA') {
            el.value = value.toUpperCase().slice(0, 255);
        }
    }

    function vutClearInputError(el) {
        if (!el) return;

        el.classList.remove('vut-input-error');

        const next = el.nextElementSibling;
        if (next && next.classList.contains('vut-error-msg')) {
            next.remove();
        }
    }

    function vutSetInputError(el, mensaje) {
        vutClearInputError(el);
        el.classList.add('vut-input-error');
        el.classList.remove('vut-input-ok');

        const msg = document.createElement('span');
        msg.className = 'vut-error-msg';
        msg.innerText = mensaje;
        el.insertAdjacentElement('afterend', msg);
    }

    function vutValidarRFC(value) {
        value = String(value || '').toUpperCase().trim();
        if (value === '') return true;
        return /^([A-ZÑ&]{3,4})(\d{2})(0[1-9]|1[0-2])(0[1-9]|[12]\d|3[01])([A-Z0-9]{3})$/.test(value);
    }

    function vutValidarCURP(value) {
        value = String(value || '').toUpperCase().trim();
        if (value === '') return true;
        return /^[A-Z][AEIOUX][A-Z]{2}\d{2}(0[1-9]|1[0-2])(0[1-9]|[12]\d|3[01])[HM][A-Z]{5}[A-Z0-9]\d$/.test(value);
    }

    function vutValidarEmail(value) {
        value = String(value || '').trim();
        if (value === '') return true;
        return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(value);
    }

    function vutValidarCampo(el, mostrarError = true) {
        if (!el || !vutCampoParticipa(el)) return { ok: true, mensaje: '' };
        if (el.type === 'hidden') return { ok: true, mensaje: '' };
        if (el.disabled || el.readOnly) return { ok: true, mensaje: '' };

        const tipo = vutTipoCampo(el);
        const value = String(el.value || '').trim();
        const requerido = vutEsRequerido(el);

        vutClearInputError(el);

        if (requerido && (value === '' || value === '-1')) {
            const result = { ok: false, mensaje: 'Este campo es obligatorio.' };
            if (mostrarError) vutSetInputError(el, result.mensaje);
            return result;
        }

        if (value === '') return { ok: true, mensaje: '' };

        let result = { ok: true, mensaje: '' };

        if (tipo === 'telefono' && !/^\d{10}$/.test(value)) {
            result = { ok: false, mensaje: 'El teléfono debe tener exactamente 10 dígitos.' };
        } else if (tipo === 'cp' && !/^\d{5}$/.test(value)) {
            result = { ok: false, mensaje: 'El código postal debe tener exactamente 5 dígitos.' };
        } else if (tipo === 'email' && !vutValidarEmail(value)) {
            result = { ok: false, mensaje: 'Ingresa un correo electrónico válido.' };
        } else if (tipo === 'rfc' && !vutValidarRFC(value)) {
            result = { ok: false, mensaje: 'El RFC debe tener formato válido de 12 o 13 caracteres.' };
        } else if (tipo === 'curp' && !vutValidarCURP(value)) {
            result = { ok: false, mensaje: 'La CURP debe tener formato válido de 18 caracteres.' };
        } else if (tipo === 'monto') {
            const monto = Number(value);
            if (isNaN(monto) || monto <= 0) {
                result = { ok: false, mensaje: 'El monto debe ser mayor a 0.' };
            }
        } else if (tipo === 'solo_numeros' && !/^\d+$/.test(value)) {
            result = { ok: false, mensaje: 'Este campo solo permite números.' };
        } else if (tipo === 'numero_exterior' && value.length > 20) {
            result = { ok: false, mensaje: 'El número exterior no debe exceder 20 caracteres.' };
        } else if (tipo === 'nombre_persona' && value.length < 2) {
            result = { ok: false, mensaje: 'Ingresa al menos 2 caracteres.' };
        }

        if (!result.ok && mostrarError) {
            vutSetInputError(el, result.mensaje);
        }

        if (result.ok && value !== '') {
            el.classList.add('vut-input-ok');
        }

        return result;
    }

    function vutNormalizarValorPayload(el, value) {
        const tipo = vutTipoCampo(el);
        value = String(value || '').trim();

        if (tipo === 'telefono' || tipo === 'cp') {
            return value.replace(/\D/g, '');
        }

        if (tipo === 'email') {
            return value.toLowerCase();
        }

        if (tipo === 'monto') {
            const monto = Number(value);
            return isNaN(monto) ? value : monto.toFixed(2);
        }

        return value.toUpperCase();
    }

    function aplicarRestriccionesVUT(root = document) {
        const elements = root.querySelectorAll('input, select, textarea');

        elements.forEach(el => {
            if (el.dataset.vutValidationBound === 'true') return;

            const tipo = vutTipoCampo(el);

            if (tipo === 'telefono') {
                el.type = 'tel';
                el.inputMode = 'numeric';
                el.maxLength = 10;
                el.placeholder = el.placeholder || '10 dígitos';
            } else if (tipo === 'cp') {
                el.type = 'text';
                el.inputMode = 'numeric';
                el.maxLength = 5;
                el.placeholder = el.placeholder || '5 dígitos';
            } else if (tipo === 'email') {
                el.type = 'email';
                el.maxLength = 150;
                el.placeholder = el.placeholder || 'correo@ejemplo.com';
            } else if (tipo === 'rfc') {
                el.type = 'text';
                el.maxLength = 13;
                el.placeholder = el.placeholder || 'RFC';
            } else if (tipo === 'curp') {
                el.type = 'text';
                el.maxLength = 18;
                el.placeholder = el.placeholder || 'CURP';
            } else if (tipo === 'monto') {
                el.type = 'text';
                el.inputMode = 'decimal';
                el.placeholder = el.placeholder || '0.00';
            } else if (tipo === 'solo_numeros') {
                el.type = 'text';
                el.inputMode = 'numeric';
            } else if (tipo === 'numero_exterior') {
                el.maxLength = 20;
            }

            el.addEventListener('input', () => {
                vutNormalizarInput(el);
                vutValidarCampo(el, false);
            });

            el.addEventListener('blur', () => {
                vutNormalizarInput(el);
                vutValidarCampo(el, true);
            });

            el.dataset.vutValidationBound = 'true';
        });
    }

    function vutIrATabDeCampo(el) {
        const tab = el.closest('.tab-content');
        if (!tab || !tab.id) return;

        const tabName = tab.id.replace('tab-', '');
        if (typeof window.switchTab === 'function') {
            window.switchTab(tabName);
        }
    }

    function vutRecolectarInputs(containerId) {
        const container = document.getElementById(containerId);
        const data = {};
        if (!container) return {};

        const elements = container.querySelectorAll('input, select, textarea');

        elements.forEach(el => {
            const key = String(el.name || el.id || '').toUpperCase().replace(/-/g, '_');
            if (!key) return;
            if (!vutCampoParticipa(el)) return;

            if (el.type === 'checkbox') {
                data[key] = el.checked ? 'SÍ' : 'NO';
                return;
            }

            const value = String(el.value || '').trim();
            if (value !== '') {
                data[key] = vutNormalizarValorPayload(el, value);
            }
        });

        return data;
    }

    async function vutValidationAlert(errores = []) {
        const lista = errores
            .slice(0, 10)
            .map(error => `<li style="margin-bottom:6px;">${vutHtmlEscape(error)}</li>`)
            .join('');

        const extra = errores.length > 10
            ? `<p style="margin-top:10px; font-weight:800;">Y ${errores.length - 10} error(es) más.</p>`
            : '';

        return vutAlert({
            icon: 'warning',
            title: 'Revisa la captura',
            html: `
                <div style="text-align:left;">
                    <p style="margin-bottom:10px;">Hay campos que necesitan corregirse antes de finalizar.</p>
                    <ul style="padding-left:18px; margin:0;">${lista}</ul>
                    ${extra}
                </div>
            `,
            confirmButtonText: 'Corregir datos'
        });
    }

    async function validarFormularioVUT() {
        aplicarRestriccionesVUT();

        const containers = [
            'tab-interesado',
            'tab-legal',
            'tab-autorizada',
            'contenedor-dinamico-captura',
            'contenedor-recibos-dinamico',
            'tab-observaciones'
        ];

        const errores = [];
        let primerCampoError = null;

        containers.forEach(containerId => {
            const container = document.getElementById(containerId);
            if (!container) return;

            const elements = container.querySelectorAll('input, select, textarea');

            elements.forEach(el => {
                const result = vutValidarCampo(el, true);
                if (!result.ok) {
                    const label = (vutGetLabel(el) || el.name || el.id || 'Campo')
                        .replace('*', '')
                        .replace(/\s+/g, ' ')
                        .trim();

                    errores.push(`${label}: ${result.mensaje}`);

                    if (!primerCampoError) primerCampoError = el;
                }
            });
        });

        if (errores.length > 0) {
            if (primerCampoError) {
                vutIrATabDeCampo(primerCampoError);

                setTimeout(() => {
                    primerCampoError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    primerCampoError.focus({ preventScroll: true });
                }, 250);
            }

            await vutValidationAlert(errores);
            return false;
        }

        return true;
    }

    function actualizarSeccionPropietario() {
        const check = document.getElementById('check_agregar_propietario');
        const section = document.getElementById('seccion_propietario_predio');

        if (!check || !section) return;

        if (check.dataset.vutPropBound !== 'true') {
            check.addEventListener('change', actualizarSeccionPropietario);
            check.dataset.vutPropBound = 'true';
        }

        if (check.checked) {
            section.classList.remove('hidden');
        } else {
            section.classList.add('hidden');
            section.querySelectorAll('input, select, textarea').forEach(el => {
                if (el.type !== 'checkbox') el.value = '';
                vutClearInputError(el);
                el.classList.remove('vut-input-ok');
            });
        }
    }

    function vincularResumenBifurcacionVUT() {
        document
            .querySelectorAll('[id^="select-modalidad-"], #select-obra-especial, #select-detalle-manifestacion-a, #select-detalle-manifestacion-bc')
            .forEach(el => {
                if (el.dataset.vutResumenBound === 'true') return;
                el.addEventListener('change', () => {
                    if (typeof window.actualizarResumenBifurcacion === 'function') {
                        window.actualizarResumenBifurcacion();
                    }
                });
                el.dataset.vutResumenBound = 'true';
            });
    }

    function postRenderVUT() {
        aplicarRestriccionesVUT();
        actualizarSeccionPropietario();
        vincularResumenBifurcacionVUT();
    }

    async function cancelarProcesoVUT() {
        const confirmado = await vutConfirm({
            icon: 'warning',
            title: '¿Cancelar captura?',
            html: `
                <div style="text-align:left;">
                    <p>Se abandonará el formulario actual.</p>
                    <p style="font-size:12px;color:#6b7280;">Los datos no guardados se perderán.</p>
                </div>
            `,
            confirmButtonText: 'Sí, cancelar',
            cancelButtonText: 'Seguir capturando'
        });

        if (!confirmado) return;
        window.location.href = '?route=ventanilla';
    }

    async function limpiarTodoVUT() {
        const confirmado = await vutConfirm({
            icon: 'warning',
            title: '¿Limpiar formulario?',
            html: `
                <div style="text-align:left;">
                    <p>Se borrará la captura actual del formulario.</p>
                    <p style="font-size:12px;color:#6b7280;">Esta acción no afectará solicitudes ya guardadas.</p>
                </div>
            `,
            confirmButtonText: 'Sí, limpiar',
            cancelButtonText: 'Conservar captura'
        });

        if (!confirmado) return;

        const selMateria = document.getElementById('select-materia');
        if (selMateria) selMateria.selectedIndex = 0;

        if (typeof window.actualizarTramites === 'function') {
            window.actualizarTramites();
        }

        document.querySelectorAll('.tab-content input:not([type="checkbox"]), .tab-content textarea').forEach(input => {
            input.value = '';
            vutClearInputError(input);
            input.classList.remove('vut-input-ok');
        });

        document.querySelectorAll('.tab-content input[type="checkbox"]').forEach(chk => {
            chk.checked = false;
        });

        actualizarSeccionPropietario();

        if (typeof window.switchTab === 'function') {
            window.switchTab('requisitos');
        }

        vutToast({ icon: 'success', title: 'Formulario limpiado correctamente' });
    }

    function inspeccionarDataVUT() {
        console.clear();

        const dataPreview = {
            solicitud: {
                tramite: document.getElementById('select-tramite')?.value || '',
                persona: document.getElementById('select-tipo-persona')?.value || ''
            },
            interesado_base: vutRecolectarInputs('tab-interesado'),
            interesado_dinamico: vutRecolectarInputs('contenedor-dinamico-interesado'),
            representante_legal: vutRecolectarInputs('tab-legal'),
            persona_autorizada: vutRecolectarInputs('tab-autorizada'),
            ubicacion_objeto: vutRecolectarInputs('contenedor-dinamico-captura'),
            bifurcacion: typeof window.recolectarBifurcacion === 'function' ? window.recolectarBifurcacion() : {},
            recibos_validos: typeof window.recolectarRecibosValidos === 'function' ? window.recolectarRecibosValidos() : {},
            requisitos: Array.from(document.querySelectorAll('#lista-presentados .item-requisito'))
                .map(div => div.innerText.trim())
        };

        console.group('%c 🔍 INSPECCIÓN DE PAYLOAD NORMALIZADO ', 'background: #773357; color: white; font-size: 14px; padding: 5px;');
        console.log('%cResumen de Solicitud:', 'font-weight: bold;', dataPreview.solicitud);
        console.log('%cInteresado Base:', 'color: blue;');
        console.table(dataPreview.interesado_base);
        console.log('%cInteresado Dinámico:', 'color: purple;');
        console.table(dataPreview.interesado_dinamico);
        console.log('%cDatos Específicos:', 'color: green;');
        console.table(dataPreview.ubicacion_objeto);
        console.log('%cBifurcación:', 'color: #773357;');
        console.table(dataPreview.bifurcacion);
        console.log('%cRecibos Válidos:', 'color: #059669;');
        console.table(dataPreview.recibos_validos);
        console.log('OBJETO COMPLETO PARA ENVÍO:', dataPreview);
        console.groupEnd();

        vutAlert({
            icon: 'info',
            title: 'Payload inspeccionado',
            html: `
                <div style="text-align:left;">
                    <p>Abre la consola del navegador con <b>F12</b>.</p>
                    <p>Ahí verás el payload normalizado, bifurcación, datos específicos y recibos válidos.</p>
                </div>
            `,
            confirmButtonText: 'Entendido'
        });
    }

    /**
     * FIRMA DIGITAL VUT - CAPTURISTA E INTERESADO
     * Esta capa vive dentro del mismo scope de SweetAlert para no romper funciones globales.
     */
    function vutFirmaMostrarError(mensaje) {
        if (vutSwalDisponible()) {
            window.Swal.showValidationMessage(mensaje);
        }
        return false;
    }

    function vutPrepararLienzoFirma(canvas) {
        if (!canvas) return null;

        const ctx = canvas.getContext('2d');
        const anchoBase = 640;
        const altoBase = 190;
        let dibujando = false;
        let ultimo = null;
        let tieneTrazos = false;

        function inicializarLienzo() {
            const ratio = window.devicePixelRatio || 1;
            canvas.width = anchoBase * ratio;
            canvas.height = altoBase * ratio;
            canvas.style.width = '100%';
            canvas.style.height = altoBase + 'px';

            ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
            ctx.clearRect(0, 0, anchoBase, altoBase);
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, anchoBase, altoBase);
            ctx.lineWidth = 2.4;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            ctx.strokeStyle = '#111827';
        }

        function posicion(e) {
            const rect = canvas.getBoundingClientRect();
            const touch = e.touches && e.touches.length ? e.touches[0] : null;
            const clientX = touch ? touch.clientX : e.clientX;
            const clientY = touch ? touch.clientY : e.clientY;

            return {
                x: ((clientX - rect.left) / rect.width) * anchoBase,
                y: ((clientY - rect.top) / rect.height) * altoBase
            };
        }

        function iniciar(e) {
            if (e) e.preventDefault();
            dibujando = true;
            ultimo = posicion(e);
        }

        function mover(e) {
            if (!dibujando) return;
            if (e) e.preventDefault();

            const actual = posicion(e);
            ctx.beginPath();
            ctx.moveTo(ultimo.x, ultimo.y);
            ctx.lineTo(actual.x, actual.y);
            ctx.stroke();
            ultimo = actual;
            tieneTrazos = true;
        }

        function terminar(e) {
            if (e) e.preventDefault();
            dibujando = false;
            ultimo = null;
        }

        inicializarLienzo();

        canvas.addEventListener('mousedown', iniciar);
        canvas.addEventListener('mousemove', mover);
        canvas.addEventListener('mouseup', terminar);
        canvas.addEventListener('mouseleave', terminar);
        canvas.addEventListener('touchstart', iniciar, { passive: false });
        canvas.addEventListener('touchmove', mover, { passive: false });
        canvas.addEventListener('touchend', terminar, { passive: false });
        canvas.addEventListener('touchcancel', terminar, { passive: false });

        return {
            limpiar() {
                inicializarLienzo();
                tieneTrazos = false;
            },
            estaVacio() {
                return !tieneTrazos;
            },
            dataURL() {
                return canvas.toDataURL('image/png');
            }
        };
    }

    async function capturarFirmasVUT() {
        if (!vutSwalDisponible()) {
            await vutAlert({
                icon: 'warning',
                title: 'Firmas no disponibles',
                text: 'No se pudo cargar SweetAlert2 para capturar firmas. Revisa tu conexión o carga local de la librería.',
                confirmButtonText: 'Entendido'
            });
            return null;
        }

        const html = `
            <div style="text-align:left;">
                <p style="font-size:13px;color:#4b5563;font-weight:700;margin-bottom:14px;">
                    Solicita la firma del capturista y, si está presente, la firma del interesado.
                </p>

                <div style="margin-bottom:18px;">
                    <div style="font-size:11px;font-weight:900;color:#773357;text-transform:uppercase;margin-bottom:6px;">
                        Firma del capturista <span style="color:#dc2626;">*</span>
                    </div>
                    <div style="border:2px dashed #d1d5db;border-radius:16px;background:#ffffff;padding:8px;">
                        <canvas id="firma_capturista_canvas" style="width:100%;height:190px;touch-action:none;display:block;border-radius:12px;background:#ffffff;"></canvas>
                    </div>
                    <button type="button" id="limpiar_firma_capturista" style="margin-top:8px;font-size:11px;font-weight:900;color:#773357;background:transparent;border:0;cursor:pointer;">
                        Limpiar firma del capturista
                    </button>
                </div>

                <div style="margin-bottom:12px;">
                    <div style="font-size:11px;font-weight:900;color:#773357;text-transform:uppercase;margin-bottom:6px;">
                        Firma del interesado
                    </div>
                    <div style="border:2px dashed #d1d5db;border-radius:16px;background:#ffffff;padding:8px;">
                        <canvas id="firma_interesado_canvas" style="width:100%;height:190px;touch-action:none;display:block;border-radius:12px;background:#ffffff;"></canvas>
                    </div>
                    <button type="button" id="limpiar_firma_interesado" style="margin-top:8px;font-size:11px;font-weight:900;color:#773357;background:transparent;border:0;cursor:pointer;">
                        Limpiar firma del interesado
                    </button>
                </div>

                <label style="display:flex;align-items:center;gap:8px;font-size:12px;font-weight:800;color:#374151;margin-top:12px;">
                    <input type="checkbox" id="interesado_no_presente" style="width:16px;height:16px;">
                    El interesado no se encuentra presente / no firma en este momento
                </label>

                <textarea id="motivo_sin_firma_interesado"
                    placeholder="Motivo opcional cuando no firma el interesado"
                    style="width:100%;margin-top:10px;border:1px solid #d1d5db;border-radius:12px;padding:10px;font-size:12px;min-height:70px;resize:vertical;"></textarea>
            </div>
        `;

        const resultado = await window.Swal.fire(vutSwalConfig({
            icon: 'info',
            title: 'Firmas digitales',
            html,
            width: 760,
            showCancelButton: true,
            confirmButtonText: 'Guardar firmas y continuar',
            cancelButtonText: 'Regresar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                const canvasCapturista = document.getElementById('firma_capturista_canvas');
                const canvasInteresado = document.getElementById('firma_interesado_canvas');

                window.__vutFirmaCapturistaPad = vutPrepararLienzoFirma(canvasCapturista);
                window.__vutFirmaInteresadoPad = vutPrepararLienzoFirma(canvasInteresado);

                document.getElementById('limpiar_firma_capturista')?.addEventListener('click', () => {
                    window.__vutFirmaCapturistaPad?.limpiar();
                });

                document.getElementById('limpiar_firma_interesado')?.addEventListener('click', () => {
                    window.__vutFirmaInteresadoPad?.limpiar();
                });
            },
            preConfirm: () => {
                const padCapturista = window.__vutFirmaCapturistaPad;
                const padInteresado = window.__vutFirmaInteresadoPad;
                const interesadoNoPresente = document.getElementById('interesado_no_presente')?.checked || false;
                const motivo = (document.getElementById('motivo_sin_firma_interesado')?.value || '').trim();

                if (!padCapturista || padCapturista.estaVacio()) {
                    return vutFirmaMostrarError('La firma del capturista es obligatoria.');
                }

                const interesadoFirmo = !!(padInteresado && !padInteresado.estaVacio());

                if (!interesadoFirmo && !interesadoNoPresente) {
                    return vutFirmaMostrarError('El interesado debe firmar o debes marcar que no se encuentra presente.');
                }

                return {
                    capturista: {
                        firmo: true,
                        imagen: padCapturista.dataURL(),
                        fecha: new Date().toISOString()
                    },
                    interesado: {
                        firmo: interesadoFirmo,
                        no_presente: interesadoNoPresente,
                        motivo_no_firma: motivo,
                        imagen: interesadoFirmo ? padInteresado.dataURL() : '',
                        fecha: interesadoFirmo ? new Date().toISOString() : ''
                    }
                };
            }
        }));

        delete window.__vutFirmaCapturistaPad;
        delete window.__vutFirmaInteresadoPad;

        if (!resultado.isConfirmed) return null;
        return resultado.value;
    }

    async function finalizarCapturaVUT(event) {
        if (event) event.preventDefault();

        const btn = event ? event.currentTarget : null;
        const originalContent = btn ? btn.innerHTML : '';

        const selMateria = document.getElementById('select-materia');
        const selTramite = document.getElementById('select-tramite');
        const selPersona = document.getElementById('select-tipo-persona');
        const selRep = document.getElementById('select-tipo-rep');

        if (!selTramite || !selTramite.value) {
            await vutAlert({
                icon: 'warning',
                title: 'Trámite requerido',
                text: 'Por favor, selecciona un trámite específico para continuar.',
                confirmButtonText: 'Seleccionar trámite'
            });
            return;
        }

        const formularioValido = await validarFormularioVUT();
        if (!formularioValido) return;

        const confirmado = await vutConfirm({
            icon: 'question',
            title: window.VUT_EDIT_MODE ? '¿Guardar cambios?' : '¿Finalizar captura?',
            html: `
                <div style="text-align:left;">
                    <p>${window.VUT_EDIT_MODE ? 'Se actualizará la solicitud existente sin generar un folio nuevo.' : 'Se registrará la solicitud y se generará el acuse oficial.'}</p>
                    <p style="font-size:12px;color:#6b7280;">Revisa que los requisitos, datos del interesado, predio/mercado y observaciones estén correctos.</p>
                </div>
            `,
            confirmButtonText: window.VUT_EDIT_MODE ? 'Sí, actualizar' : 'Sí, finalizar',
            cancelButtonText: 'Revisar captura'
        });

        if (!confirmado) return;

        let firmas = null;

        if (window.VUT_EDIT_MODE && window.VUT_EDIT_DATA && window.VUT_EDIT_DATA.firmas && window.VUT_EDIT_DATA.firmas.capturista && window.VUT_EDIT_DATA.firmas.capturista.imagen) {
            const conservarFirmas = await vutConfirm({
                icon: 'question',
                title: 'Firmas digitales',
                html: `
                    <div style="text-align:left;">
                        <p>Esta solicitud ya tiene firmas guardadas.</p>
                        <p style="font-size:12px;color:#6b7280;">Puedes conservarlas o recapturarlas si el expediente lo requiere.</p>
                    </div>
                `,
                confirmButtonText: 'Conservar firmas',
                cancelButtonText: 'Recapturar firmas'
            });

            if (conservarFirmas) {
                firmas = window.VUT_EDIT_DATA.firmas;
            } else {
                firmas = await capturarFirmasVUT();
            }
        } else {
            firmas = await capturarFirmasVUT();
        }

        if (!firmas) return;

        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner"></span> Procesando...';
        }

        vutLoading(window.VUT_EDIT_MODE ? 'Actualizando solicitud' : 'Guardando solicitud', window.VUT_EDIT_MODE ? 'Estamos guardando los cambios del expediente.' : 'Estamos registrando la captura y preparando el acuse oficial.');

        try {
            const payload = {
                ...(window.VUT_EDIT_MODE ? { id_solicitud: window.VUT_EDIT_ID } : {}),

                solicitud: {
                    materia: (selMateria?.value || '').toUpperCase(),
                    tramite: (selTramite?.options[selTramite.selectedIndex]?.text || '').toUpperCase(),
                    tipo_persona: (selPersona?.value || '').toUpperCase(),
                    tipo_representante: (selRep?.value || '').toUpperCase()
                },

                bifurcacion: typeof window.recolectarBifurcacion === 'function'
                    ? window.recolectarBifurcacion()
                    : {},

                interesado: {
                    datos: vutRecolectarInputs('tab-interesado'),
                    datos_dinamicos: vutRecolectarInputs('contenedor-dinamico-interesado')
                },

                representante_legal: vutRecolectarInputs('tab-legal'),
                persona_autorizada: vutRecolectarInputs('tab-autorizada'),
                especificos: vutRecolectarInputs('contenedor-dinamico-captura'),

                recibos: typeof window.recolectarRecibosValidos === 'function'
                    ? window.recolectarRecibosValidos()
                    : vutRecolectarInputs('contenedor-recibos-dinamico'),

                requisitos_validados: Array.from(document.querySelectorAll('#lista-presentados .item-requisito'))
                    .map(div => div.innerText.trim().toUpperCase()),

                observaciones: (document.getElementById('observaciones')?.value || '').toUpperCase(),

                firmas: firmas
            };

            console.log('🚀 Enviando Payload Estructurado:', payload);

            const endpoint = window.VUT_EDIT_MODE
                ? 'index.php?route=ventanilla/actualizar'
                : 'index.php?route=guardar_tramite';

            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            });

            const rawText = await response.text();
            let result;

            try {
                result = JSON.parse(rawText);
            } catch (e) {
                console.error('Respuesta no JSON del servidor:', rawText);
                throw new Error('El servidor respondió algo que no es JSON. Revisa errores PHP en guardar/actualizar.');
            }

            if (!response.ok) {
                throw new Error(result.error || `Error en el servidor: ${response.status} ${response.statusText}`);
            }

            if (!result.success) {
                throw new Error(result.error || 'Error desconocido al guardar.');
            }

            vutCloseLoading();

            const urlPdf = `index.php?route=ventanilla/generarComprobante&id=${result.id}`;

            if (vutSwalDisponible()) {
                const decision = await window.Swal.fire(vutSwalConfig({
                    icon: 'success',
                    title: window.VUT_EDIT_MODE ? 'Solicitud actualizada' : 'Solicitud registrada',
                    html: `
                        <div style="text-align:center;">
                            <p>${window.VUT_EDIT_MODE ? 'La solicitud fue actualizada correctamente.' : 'La solicitud fue registrada correctamente.'}</p>
                            <div style="margin:14px auto 4px; padding:12px 14px; background:#FCF7F9; border:1px solid #E6D4DD; border-radius:14px; color:#773357; font-weight:900; display:inline-block;">
                                FOLIO: ${vutHtmlEscape(result.folio || 'SIN FOLIO')}
                            </div>
                            <p style="font-size:12px;color:#6b7280;margin-top:10px;">Puedes abrir el acuse PDF en una nueva pestaña.</p>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Abrir acuse PDF',
                    cancelButtonText: 'Ir al inicio'
                }));

                if (decision.isConfirmed) {
                    window.open(urlPdf, '_blank');
                }
            } else {
                window.open(urlPdf, '_blank');
                window.alert('Solicitud registrada con folio: ' + (result.folio || 'SIN FOLIO'));
            }

            window.location.href = 'index.php?route=ventanilla';

        } catch (error) {
            vutCloseLoading();
            console.error('❌ Error en el proceso:', error);

            await vutAlert({
                icon: 'error',
                title: 'No se pudo guardar',
                html: `
                    <div style="text-align:left;">
                        <p>Ocurrió un error al procesar la solicitud.</p>
                        <div style="margin-top:10px; padding:10px; background:#fff7f7; border:1px solid #fecaca; border-radius:12px; color:#991b1b; font-size:12px; font-weight:800; word-break:break-word;">
                            ${vutHtmlEscape(error.message)}
                        </div>
                    </div>
                `,
                confirmButtonText: 'Revisar'
            });

            if (btn) {
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }
        }
    }



    function vutNormalizarTextoEdicion(value) {
        return String(value || '')
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .trim();
    }

    function vutSeleccionarOpcionPorTextoOValor(select, valor) {
        if (!select || valor === undefined || valor === null || String(valor).trim() === '') return false;

        const buscado = vutNormalizarTextoEdicion(valor);
        let encontrado = false;

        Array.from(select.options || []).forEach(option => {
            const val = vutNormalizarTextoEdicion(option.value);
            const txt = vutNormalizarTextoEdicion(option.textContent);

            if (!encontrado && (val === buscado || txt === buscado || val.includes(buscado) || buscado.includes(val))) {
                select.value = option.value;
                encontrado = true;
            }
        });

        return encontrado;
    }

    function vutSetValorCampo(id, value) {
        const el = document.getElementById(id) || document.querySelector(`[name="${id}"]`);
        if (!el || value === undefined || value === null) return;

        if (el.type === 'checkbox') {
            const v = String(value).toUpperCase();
            el.checked = ['SÍ', 'SI', 'TRUE', '1', 'ON', 'YES'].includes(v);
        } else {
            el.value = value;
        }

        el.dispatchEvent(new Event('input', { bubbles: true }));
        el.dispatchEvent(new Event('change', { bubbles: true }));
    }

    function vutRellenarGrupoCampos(obj) {
        if (!obj || typeof obj !== 'object') return;

        Object.entries(obj).forEach(([key, value]) => {
            if (value === null || value === undefined || typeof value === 'object') return;

            const lower = String(key).toLowerCase();
            const candidates = [
                lower,
                lower.replace(/^interesado_/, ''),
                lower.replace(/^moral_/, ''),
                lower.replace(/^legal_/, 'leg_'),
                lower.replace(/^autorizada_/, 'aut_')
            ];

            for (const id of candidates) {
                const el = document.getElementById(id) || document.querySelector(`[name="${id}"]`);
                if (el) {
                    vutSetValorCampo(id, value);
                    break;
                }
            }
        });
    }

    function vutRellenarBifurcacionEdit(bif) {
        if (!bif || typeof bif !== 'object') return;

        const modalidad = bif.modalidad || bif.MODALIDAD || '';
        const detalle = bif.detalle || bif.DETALLE || '';

        document.querySelectorAll('[id^="select-modalidad-"]').forEach(select => {
            vutSeleccionarOpcionPorTextoOValor(select, modalidad);
        });

        ['select-obra-especial', 'select-detalle-manifestacion-a', 'select-detalle-manifestacion-bc'].forEach(id => {
            const select = document.getElementById(id);
            if (select) vutSeleccionarOpcionPorTextoOValor(select, detalle);
        });

        if (typeof window.actualizarResumenBifurcacion === 'function') {
            window.actualizarResumenBifurcacion();
        }
    }

    function vutRellenarRequisitosEdit(reqs) {
        if (!Array.isArray(reqs) || !reqs.length) return;

        const normalizados = new Set(reqs.map(r => vutNormalizarTextoEdicion(r)));
        const pendientes = document.getElementById('lista-requisitos');
        const presentados = document.getElementById('lista-presentados');
        const placeholder = document.getElementById('placeholder-presentados');

        if (!pendientes || !presentados) return;

        Array.from(pendientes.querySelectorAll('.item-requisito')).forEach(item => {
            const texto = vutNormalizarTextoEdicion(item.innerText || item.textContent || '');

            if (normalizados.has(texto)) {
                item.classList.remove('seleccionado');
                item.classList.add('border-green-100', 'bg-green-50/30');

                const icono = item.querySelector('.icono-req');
                if (icono) {
                    icono.classList.remove('text-tlalpan-vino');
                    icono.classList.add('text-green-500');
                }

                item.onclick = function() { this.classList.toggle('listo-para-volver'); };
                presentados.appendChild(item);
            }
        });

        if (placeholder) {
            placeholder.style.display = presentados.querySelectorAll('.item-requisito').length ? 'none' : 'block';
        }
    }

    function vutInicializarEdicion() {
        if (!window.VUT_EDIT_MODE || !window.VUT_EDIT_DATA || window.__VUT_EDIT_LOADED) return;
        window.__VUT_EDIT_LOADED = true;

        const d = window.VUT_EDIT_DATA;
        const solicitud = d.solicitud || {};

        const selMateria = document.getElementById('select-materia');
        const selTramite = document.getElementById('select-tramite');
        const selPersona = document.getElementById('select-tipo-persona');
        const selRep = document.getElementById('select-tipo-rep');

        const materia = solicitud.materia || d.materia || '';
        const tramite = solicitud.tramite || d.nombre_tramite || d.tramite || '';

        if (selMateria) {
            vutSeleccionarOpcionPorTextoOValor(selMateria, materia);
            if (typeof window.actualizarTramites === 'function') window.actualizarTramites();
        }

        setTimeout(() => {
            if (selTramite) {
                vutSeleccionarOpcionPorTextoOValor(selTramite, tramite);
                if (typeof window.actualizarRequisitos === 'function') window.actualizarRequisitos();
            }

            setTimeout(() => {
                if (selPersona) {
                    vutSeleccionarOpcionPorTextoOValor(selPersona, solicitud.tipo_persona || d.tipo_persona || '');
                    if (typeof window.cambiarOpcionesRepresentante === 'function') window.cambiarOpcionesRepresentante();
                    if (typeof window.actualizarPlantillaInteresado === 'function') window.actualizarPlantillaInteresado();
                }

                if (selRep) {
                    vutSeleccionarOpcionPorTextoOValor(selRep, solicitud.tipo_representante || d.tipo_representante || '');
                    if (typeof window.actualizarPlantillaInteresado === 'function') window.actualizarPlantillaInteresado();
                }

                setTimeout(() => {
                    const interesado = d.interesado || {};
                    vutRellenarGrupoCampos(interesado.datos || {});
                    vutRellenarGrupoCampos(interesado.datos_dinamicos || {});
                    vutRellenarGrupoCampos(d.representante_legal || {});
                    vutRellenarGrupoCampos(d.persona_autorizada || {});
                    vutRellenarGrupoCampos(d.especificos || {});
                    vutRellenarGrupoCampos(d.recibos || {});
                    vutRellenarBifurcacionEdit(d.bifurcacion || {});
                    vutRellenarRequisitosEdit(d.requisitos_validados || []);

                    const obs = document.getElementById('observaciones');
                    if (obs) obs.value = d.observaciones || d.OBSERVACIONES || '';

                    if (typeof window.aplicarRestriccionesVUT === 'function') {
                        window.aplicarRestriccionesVUT();
                    }

                    if (typeof window.vutToast === 'function') {
                        window.vutToast({ icon: 'info', title: 'Solicitud cargada en modo edición' });
                    }
                }, 300);
            }, 300);
        }, 300);
    }

    function protegerFuncionesOriginales() {
        window.vutAlert = vutAlert;
        window.vutConfirm = vutConfirm;
        window.vutToast = vutToast;
        window.vutLoading = vutLoading;
        window.vutCloseLoading = vutCloseLoading;
        window.vutClearInputError = vutClearInputError;
        window.vutValidarCampo = vutValidarCampo;
        window.aplicarRestriccionesVUT = aplicarRestriccionesVUT;
        window.validarFormularioVUT = validarFormularioVUT;
        window.capturarFirmasVUT = capturarFirmasVUT;
        window.vutInicializarEdicion = vutInicializarEdicion;

        window.recolectarInputs = vutRecolectarInputs;
        window.cancelarProceso = cancelarProcesoVUT;
        window.limpiarTodo = limpiarTodoVUT;
        window.inspeccionarData = inspeccionarDataVUT;
        window.finalizarCaptura = finalizarCapturaVUT;
    }

    function inicializarCapaVUT() {
        protegerFuncionesOriginales();
        postRenderVUT();
        setTimeout(vutInicializarEdicion, 700);

        const observer = new MutationObserver(() => {
            postRenderVUT();
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', inicializarCapaVUT);
    } else {
        inicializarCapaVUT();
    }
})();
</script>
