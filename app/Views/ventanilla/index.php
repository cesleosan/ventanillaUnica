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

<div class="mb-8 animate-fade-in-up">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-3xl font-black text-tlalpan-vino">Ventanilla Única</h2>
            <p class="text-gray-500 font-medium tracking-tight">Gestión y captura de trámites ciudadanos</p>
        </div>
        <div class="text-right">
            <span class="bg-blue-50 text-blue-700 text-xs font-bold px-4 py-1.5 rounded-full border border-blue-100 shadow-sm uppercase tracking-wider">
                FECHA INGRESO: <?= date('d/m/y') ?>
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
    
    <div class="bg-gray-50/80 px-8 py-5 border-b border-gray-100 flex flex-wrap gap-8 items-center">
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
            <select id="select-tipo-rep" onchange="actualizarPlantillaInteresado()" class="input-tlalpan text-xs font-bold rounded-lg py-1.5 px-4">
                </select>
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
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <div>
            <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Nombres</label>
            <input type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold">
        </div>
        <div>
            <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Ape. Paterno</label>
            <input type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold">
        </div>
        <div>
            <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">Ape. Materno</label>
            <input type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold">
        </div>
        <div>
            <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* RFC</label>
            <input type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-black uppercase tracking-widest">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div>
            <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">Teléfono</label>
            <input type="tel" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm">
        </div>
        <div class="md:col-span-2">
            <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">e-mail</label>
            <input type="email" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm">
        </div>
    </div>

    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mb-6 border-b border-gray-100 pb-2">Domicilio del Interesado</h4>
    
    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
        <div class="md:col-span-3">
            <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Del.:</label>
            <select class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-bold"><option>TLALPAN</option></select>
        </div>
        <div class="md:col-span-6">
            <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Colonia</label>
            <select class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm"><option>COMUNEROS DE SANTA URSULA</option></select>
        </div>
        <div class="md:col-span-3">
            <label class="block text-[10px] font-black text-gray-400 mb-1 uppercase tracking-wider">Col. no Listada</label>
            <input type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm bg-gray-50/50">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <div class="md:col-span-4">
            <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Calle.:</label>
            <select class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm"><option>13 ORIENTE</option></select>
        </div>
        <div class="md:col-span-3">
            <label class="block text-[10px] font-black text-gray-400 mb-1 uppercase tracking-wider">Calle no Listada</label>
            <input type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm bg-gray-50/50">
        </div>
        <div class="md:col-span-3">
            <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Número Exterior</label>
            <input type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-bold text-center">
        </div>
        <div class="md:col-span-2">
            <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">C.P.</label>
            <input type="text" value="14049" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-black text-center">
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
                    <input type="text" id="aut_nombres" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold border-gray-200">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Ape. Paterno</label>
                    <input type="text" id="aut_paterno" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold border-gray-200">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">Ape. Materno</label>
                    <input type="text" id="aut_materno" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold border-gray-200">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* RFC</label>
                    <input type="text" id="aut_rfc" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-black uppercase tracking-widest border-gray-200">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="md:col-span-1">
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">Teléfono</label>
                    <input type="tel" id="aut_telefono" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">e-mail</label>
                    <input type="email" id="aut_email" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200">
                </div>
            </div>

            <div class="bg-gray-50/50 p-5 rounded-2xl mb-8 border border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Del.:</label>
                        <select id="aut_dom_del" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-bold border-gray-200">
                            <option value="tlalpan">TLALPAN</option>
                        </select>
                    </div>
                    <div class="md:col-span-6">
                        <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Colonia</label>
                        <select id="aut_dom_colonia" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200">
                            <option value="">Seleccione...</option>
                        </select>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-black text-gray-400 mb-1 uppercase tracking-wider">Col. no Listada</label>
                        <input type="text" id="aut_dom_col_manual" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-4">
                        <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Calle.:</label>
                        <select id="aut_dom_calle" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200">
                            <option value="">Seleccione...</option>
                        </select>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-black text-gray-400 mb-1 uppercase tracking-wider">Calle no Listada</label>
                        <input type="text" id="aut_dom_calle_manual" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Número Exterior</label>
                        <input type="text" id="aut_dom_num_ext" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-bold border-gray-200">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* C.P.</label>
                        <input type="text" id="aut_dom_cp" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-black text-center border-gray-200">
                    </div>
                </div>
            </div>

            <div class="space-y-4 border-t border-gray-100 pt-6">
                <div class="grid grid-cols-1 md:grid-cols-12 items-center gap-4">
                    <div class="md:col-span-5 text-right pr-4">
                        <label class="text-[11px] font-bold text-gray-600 uppercase tracking-tight">Documento con que se acredita la personalidad</label>
                    </div>
                    <div class="md:col-span-7">
                        <input type="text" id="aut_doc_personalidad" class="input-tlalpan w-full rounded-xl py-2.5 px-4 text-sm font-semibold border-gray-200">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-12 items-center gap-4">
                    <div class="md:col-span-5 text-right pr-4">
                        <label class="text-[11px] font-bold text-gray-600 uppercase tracking-tight">Domicilio para oír y recibir notificaciones *</label>
                    </div>
                    <div class="md:col-span-7">
                        <input type="text" id="aut_domicilio_procesal" class="input-tlalpan w-full rounded-xl py-2.5 px-4 text-sm font-semibold border-gray-200">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 items-center gap-4">
                    <div class="md:col-span-5 text-right pr-4">
                        <label class="text-[11px] font-bold text-gray-600 uppercase tracking-tight">Persona autorizada para oír y recibir notificaciones</label>
                    </div>
                    <div class="md:col-span-7">
                        <input type="text" id="aut_persona_nombre_extra" class="input-tlalpan w-full rounded-xl py-2.5 px-4 text-sm font-semibold border-gray-200">
                    </div>
                </div>
            </div>
        </div>

        <div id="tab-predio" class="tab-content hidden">
            <div id="contenedor-dinamico-captura" class="animate-fade-in">
                </div>
        </div>

        <div id="tab-observaciones" class="tab-content hidden">
            <div class="max-w-4xl mx-auto py-4">
                <div class="mb-6 flex items-center gap-4">
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
                    <textarea 
                        rows="12" 
                        class="input-tlalpan relative w-full rounded-2xl p-6 text-gray-700 text-sm leading-relaxed resize-none shadow-sm outline-none"
                        placeholder="Escriba aquí cualquier detalle relevante sobre el trámite o la documentación recibida..."></textarea>
                </div>
            </div>
        </div>

    </div>

    <div class="bg-gray-50/80 px-8 py-6 border-t border-gray-100 flex flex-col sm:flex-row justify-end gap-4">
        <button class="px-6 py-3 bg-white border border-gray-200 text-gray-500 font-black rounded-xl hover:bg-gray-100 transition-all shadow-sm text-[10px] uppercase tracking-[0.2em] active:scale-95" onclick="limpiarTodo()">Limpiar Formulario</button>
        <button class="px-6 py-3 bg-red-50 text-red-700 border border-red-100 font-black rounded-xl hover:bg-red-100 transition-all shadow-sm text-[10px] uppercase tracking-[0.2em] active:scale-95" onclick="window.location.href='?route=home'">Cancelar Proceso</button>
        <button class="px-10 py-3 bg-green-600 text-white font-black rounded-xl hover:bg-green-700 transition-all shadow-lg flex items-center justify-center text-[10px] uppercase tracking-[0.2em] transform hover:-translate-y-0.5 active:scale-95">
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
                        <input type="text" id="campo_mercado_nombre" placeholder="MERCADO" class="input-tlalpan w-full rounded-xl py-3 px-4 text-sm font-bold border-gray-200">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-gray-500 mb-2 uppercase tracking-wider">Local <span class="text-red-500">*</span></label>
                        <input type="text" id="campo_mercado_local" placeholder="LOCAL" class="input-tlalpan w-full rounded-xl py-3 px-4 text-sm font-black text-center border-gray-200">
                    </div>
                    <div class="md:col-span-5">
                        <label class="block text-[10px] font-black text-gray-500 mb-2 uppercase tracking-wider">Giro Solicitado <span class="text-red-500">*</span></label>
                        <input type="text" id="campo_mercado_giro" placeholder="GIRO" class="input-tlalpan w-full rounded-xl py-3 px-4 text-sm font-bold border-gray-200">
                    </div>
                </div>
                <div class="h-px bg-gray-100 my-8"></div>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-6">
                    <div class="md:col-span-3 text-center md:text-left">
                        <label class="block text-[10px] font-black text-gray-500 mb-2 uppercase tracking-wider">Alcaldía / Demarcación <span class="text-red-500">*</span></label>
                        <select id="campo_mercado_alcaldia" class="input-tlalpan w-full rounded-xl py-3 px-4 text-sm font-black text-tlalpan-vino border-tlalpan-vino/20 bg-white">
                            <option value="TLALPAN">TLALPAN</option>
                            <option value="COYOACAN">COYOACÁN</option>
                            <option value="XOCHIMILCO">XOCHIMILCO</option>
                        </select>
                    </div>
                    <div class="md:col-span-5">
                        <label class="block text-[10px] font-black text-gray-500 mb-2 uppercase tracking-wider">Colonia <span class="text-red-500">*</span></label>
                        <select id="campo_mercado_colonia" class="input-tlalpan w-full rounded-xl py-3 px-4 text-sm font-semibold">
                            <option value="">Seleccione una colonia...</option>
                            <option value="SAN PEDRO MARTIR">SAN PEDRO MÁRTIR</option>
                            <option value="CENTRO DE TLALPAN">CENTRO DE TLALPAN</option>
                            <option value="OTRA">OTRA (ESPECIFICAR A LA DERECHA)</option>
                        </select>
                    </div>
                    <div class="md:col-span-4">
                        <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-wider italic">Colonia no listada</label>
                        <input type="text" id="campo_mercado_colonia_manual" class="input-tlalpan w-full rounded-xl py-3 px-4 text-sm bg-gray-50/50 border-dashed border-gray-200">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-6">
                    <div class="md:col-span-6">
                        <label class="block text-[10px] font-black text-gray-500 mb-2 uppercase tracking-wider">Calle / Avenida <span class="text-red-500">*</span></label>
                        <select id="campo_mercado_calle" class="input-tlalpan w-full rounded-xl py-3 px-4 text-sm font-semibold mb-2">
                            <option value="">Seleccione calle...</option>
                            <option value="CALZADA DE TLALPAN">CALZADA DE TLALPAN</option>
                            <option value="OTRA">OTRA (ESPECIFICAR ABAJO)</option>
                        </select>
                        <input type="text" id="campo_mercado_calle_manual" placeholder="Escriba el nombre de la calle manual" class="input-tlalpan w-full rounded-xl py-2 px-4 text-xs bg-gray-50/30 border-dashed">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-black text-gray-500 mb-2 uppercase tracking-wider">Número Exterior <span class="text-red-500">*</span></label>
                        <input type="text" id="campo_mercado_num_ext" placeholder="S/N, Mz, Lt o #" class="input-tlalpan w-full rounded-xl py-3 px-4 text-sm text-center font-bold border-gray-200">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-black text-gray-500 mb-2 uppercase tracking-wider">Código Postal <span class="text-red-500">*</span></label>
                        <select id="campo_mercado_cp" class="input-tlalpan w-full rounded-xl py-3 px-4 text-sm font-black tracking-widest text-center border-tlalpan-vino/20 bg-white">
                            <option value="14000">14000</option>
                            <option value="14400">14400</option>
                            <option value="14600">14600</option>
                        </select>
                    </div>
                </div>
            </div>
        `,
'predio': `
    <div class="animate-fade-in">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8 pt-2">
            <div>
                <label class="block text-[10px] font-black text-gray-500 mb-2 uppercase tracking-wider">* Uso actual:</label>
                <select class="input-tlalpan w-full rounded-xl py-3 px-4 text-sm font-bold border-gray-200">
                    <option value="HABITACIONAL">HABITACIONAL</option>
                    <option value="COMERCIAL">COMERCIAL</option>
                    <option value="EQUIPAMIENTO">EQUIPAMIENTO</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-500 mb-2 uppercase tracking-wider">Uso solicitado:</label>
                <select class="input-tlalpan w-full rounded-xl py-3 px-4 text-sm font-bold border-gray-200">
                    <option value="SIN CAMBIO">SIN CAMBIO</option>
                    <option value="HABITACIONAL MIXTO">HABITACIONAL MIXTO</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-500 mb-2 uppercase tracking-wider">* Dirección:</label>
                <select class="input-tlalpan w-full rounded-xl py-3 px-4 text-sm font-black border-tlalpan-vino text-tlalpan-vino">
                    <option value="NUEVA">NUEVA</option>
                    <option value="EXISTENTE EN PADRÓN">EXISTENTE EN PADRÓN</option>
                </select>
            </div>
        </div>

        <div class="h-px bg-gray-100 my-8"></div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
            <div class="md:col-span-3">
                <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Del.:</label>
                <select class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-bold border-gray-200">
                    <option value="tlalpan">tlalpan</option>
                </select>
            </div>
            <div class="md:col-span-6">
                <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">*</label>
                <select class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200">
                    <option value="comuneros de santa ursula">comuneros de santa ursula</option>
                </select>
            </div>
            <div class="md:col-span-3">
                <label class="block text-[10px] font-black text-gray-400 mb-1 uppercase tracking-wider text-right">Col. no Listada</label>
                <input type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm bg-gray-50/50 border-gray-200">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-8">
            <div class="md:col-span-3">
                <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Calle.:</label>
                <select class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200">
                    <option value="calle no listada">Calle no listada</option>
                </select>
            </div>
            <div class="md:col-span-3">
                <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Calle no Listada</label>
                <input type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200">
            </div>
            <div class="md:col-span-3">
                <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Número Exterior</label>
                <input type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200">
            </div>
            <div class="md:col-span-3">
                <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Código Postal</label>
                <input type="text" value="14049" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-black text-center border-gray-200">
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
                    <input type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold border-gray-200">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Ape. Paterno</label>
                    <input type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold border-gray-200">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">Ape. Materno</label>
                    <input type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold border-gray-200">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* RFC</label>
                    <input type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-black uppercase border-gray-200">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">Teléfono</label>
                    <input type="tel" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">e-mail</label>
                    <input type="email" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm border-gray-200">
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
                    <input type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold border-gray-200">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Ape. Paterno</label>
                    <input type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold border-gray-200">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">Ape. Materno</label>
                    <input type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-semibold border-gray-200">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* RFC</label>
                    <input type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-black uppercase tracking-widest border-gray-200">
                </div>
            </div>
        `,
        'moral': `
            <div class="animate-fade-in grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
                <div class="md:col-span-8">
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* Denominación o Razón Social</label>
                    <input type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-bold uppercase border-gray-200">
                </div>
                <div class="md:col-span-4">
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">* RFC de la Empresa</label>
                    <input type="text" class="input-tlalpan w-full rounded-lg py-2 px-3 text-sm font-black uppercase tracking-widest border-gray-200">
                </div>
            </div>
        `
    };

    // 3. Variables de Control
    const catalogo = <?= json_encode($data['catalogo_json']) ?>;
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
        const materia = selMateria.value;
        const tramite = selTramite.value;
        const dataTramite = catalogo[materia][tramite];
        if (!dataTramite) return;

        const requisitos = dataTramite.requisitos ? dataTramite.requisitos : dataTramite;

        divReq.innerHTML = '';
        divPres.innerHTML = ''; 
        divPres.appendChild(placeholder); 
        placeholder.style.display = 'block';

        requisitos.forEach((req) => {
            let div = document.createElement('div');
            div.className = 'item-requisito flex items-start p-3 bg-white border border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition-all shadow-sm mb-2';
            div.innerHTML = `
                <div class="mr-3 mt-0.5 text-gray-300 transition-colors flex-shrink-0 icono-req">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <span class="text-[11px] text-gray-600 font-bold leading-tight select-none">${req}</span>
            `;
            div.onclick = function() { 
                this.classList.toggle('seleccionado');
                this.querySelector('.icono-req').classList.toggle('text-tlalpan-vino');
            };
            divReq.appendChild(div);
        });

        // Inyección Dinámica de Plantilla de Captura (Tab Predio/Mercado)
        const tipo = dataTramite.tipo_captura || 'predio'; 
        const contenedorCaptura = document.getElementById('contenedor-dinamico-captura');
        const btnTabCaptura = document.getElementById('btn-predio'); 

        if (contenedorCaptura && plantillasCaptura[tipo]) {
            contenedorCaptura.innerHTML = plantillasCaptura[tipo];
            if (btnTabCaptura) {
                btnTabCaptura.innerText = (tipo === 'mercado') ? 'DATOS DEL MERCADO' : 'DATOS DEL PREDIO';
            }
        }

        actualizarDetalles(dataTramite.detalles);
        actualizarPlantillaInteresado(); // Sincroniza el tab del interesado
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

    // YA NO INYECTAMOS NADA. 
    // Solo controlamos si la pestaña de "Persona Autorizada" debe ser visible en el Nav.
    
    if (tipoPersona === 'fisica' && tipoRep === 'autorizada') {
        // Mostramos el botón en el menú
        btnAutorizada.classList.remove('hidden');
    } else {
        // Ocultamos el botón
        btnAutorizada.classList.add('hidden');
        
        // Si el usuario estaba viendo la pestaña autorizada y se oculta, lo mandamos a 'interesado'
        const tabAutorizada = document.getElementById('tab-autorizada');
        if (tabAutorizada && !tabAutorizada.classList.contains('hidden')) {
            switchTab('interesado');
        }
    }
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
            { val: 'propietario', text: 'DENOMINACIÓN SOCIAL' },
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