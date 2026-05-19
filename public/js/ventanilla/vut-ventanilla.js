
(function () {
    'use strict';

    const VUT_CONFIG = window.VUT_CONFIG || {};
    const catalogo = VUT_CONFIG.catalogo || {};
    const VUT_ROUTES = {
        guardar: 'index.php?route=guardar_tramite',
        home: 'index.php?route=home',
        pdf: 'index.php?route=ventanilla/generarComprobante&id=',
        ...(VUT_CONFIG.routes || {})
    };
    const VUT_COLONIAS_URL = VUT_CONFIG.coloniasUrl || 'assets/data/colonias_cdmx.json';

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
    let selMateria = null;
    let selTramite = null;
    let divReq = null;
    let divPres = null;
    let placeholder = null;

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
async function cancelarProceso() {
    const confirmado = await vutConfirm({
        icon: 'warning',
        title: '¿Cancelar captura?',
        html: `
            <div style="text-align:left;">
                <p>Se abandonará el formulario actual.</p>
                <p style="font-size:12px;color:#6b7280;">
                    Los datos no guardados se perderán.
                </p>
            </div>
        `,
        confirmButtonText: 'Sí, cancelar',
        cancelButtonText: 'Seguir capturando'
    });

    if (!confirmado) return;

    window.location.href = VUT_ROUTES.home;
}
async function limpiarTodo() {
    const confirmado = await vutConfirm({
        icon: 'warning',
        title: '¿Limpiar formulario?',
        html: `
            <div style="text-align:left;">
                <p>Se borrará la captura actual del formulario.</p>
                <p style="font-size:12px;color:#6b7280;">
                    Esta acción no afectará solicitudes ya guardadas.
                </p>
            </div>
        `,
        confirmButtonText: 'Sí, limpiar',
        cancelButtonText: 'Conservar captura'
    });

    if (!confirmado) return;

    const selMateria = document.getElementById('select-materia');

    if (selMateria) {
        selMateria.selectedIndex = 0;
    }

    if (typeof actualizarTramites === 'function') {
        actualizarTramites();
    }

    const inputs = document.querySelectorAll(
        '.tab-content input:not([type="checkbox"]), .tab-content textarea'
    );

    inputs.forEach(input => {
        input.value = '';
        if (typeof vutClearInputError === 'function') {
            vutClearInputError(input);
        }
    });

    const checks = document.querySelectorAll('.tab-content input[type="checkbox"]');
    checks.forEach(chk => {
        chk.checked = false;
    });

    if (typeof switchTab === 'function') {
        switchTab('requisitos');
    }

    vutToast({
        icon: 'success',
        title: 'Formulario limpiado correctamente'
    });
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
/**
 * SWEETALERT2 / UI UX VUT
 */

function vutSwalDisponible() {
    return typeof Swal !== 'undefined' && typeof Swal.fire === 'function';
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
        alert(text || html || title);
        return;
    }

    return Swal.fire(vutSwalConfig({
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
    cancelButtonText = 'Cancelar',
    confirmButtonColor = '#773357'
} = {}) {
    if (!vutSwalDisponible()) {
        return confirm(text || html || title);
    }

    const result = await Swal.fire(vutSwalConfig({
        icon,
        title,
        text: html ? undefined : text,
        html: html || undefined,
        showCancelButton: true,
        confirmButtonText,
        cancelButtonText,
        confirmButtonColor
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

    Swal.fire({
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
    if (!vutSwalDisponible()) {
        return;
    }

    Swal.fire(vutSwalConfig({
        title,
        html,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    }));
}

function vutCloseLoading() {
    if (vutSwalDisponible()) {
        Swal.close();
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
                <p style="margin-bottom:10px;">
                    Hay campos que necesitan corregirse antes de finalizar.
                </p>
                <ul style="padding-left:18px; margin:0;">
                    ${lista}
                </ul>
                ${extra}
            </div>
        `,
        confirmButtonText: 'Corregir datos'
    });
}
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

    fetch(VUT_COLONIAS_URL)
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

        if (!vutEstaVisible(el)) return;

        if (el.type === 'checkbox') {
            data[key] = el.checked ? 'SÍ' : 'NO';
            return;
        }

        const value = (el.value || '').trim();

        if (value !== '') {
            data[key] = vutNormalizarValorPayload(el, value);
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
/**
 * VALIDACIONES UNIVERSALES VUT
 * Funciona con inputs estáticos y dinámicos.
 */

function vutCampoKey(el) {
    return ((el.name || el.id || '') + '').toLowerCase();
}

function vutEstaVisible(el) {
    if (!el) return false;

    let node = el;

    while (node && node !== document.body) {
        if (node.classList && node.classList.contains('hidden')) {
            return false;
        }

        const style = window.getComputedStyle(node);

        if (style.display === 'none' || style.visibility === 'hidden') {
            return false;
        }

        node = node.parentElement;
    }

    return true;
}

function vutGetLabel(el) {
    if (!el) return '';

    const id = el.id;

    if (id) {
        const labelFor = document.querySelector(`label[for="${id}"]`);
        if (labelFor) return labelFor.innerText || '';
    }

    const parent = el.closest('div');
    const label = parent ? parent.querySelector('label') : null;

    return label ? label.innerText || '' : '';
}

function vutEsRequerido(el) {
    if (!el || !vutEstaVisible(el)) return false;

    if (el.required) return true;

    const label = vutGetLabel(el);

    return label.includes('*');
}

function vutTipoCampo(el) {
    const key = vutCampoKey(el);

    if (key.includes('email') || key.includes('correo')) return 'email';

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
        key.includes('paterno') ||
        key.includes('materno') ||
        key.includes('nombre_notario')
    ) return 'nombre';

    return 'texto';
}

function vutNormalizarInput(el) {
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
        el.value = value
            .toUpperCase()
            .replace(/[^A-ZÑ&0-9]/g, '')
            .slice(0, 13);
        return;
    }

    if (tipo === 'curp') {
        el.value = value
            .toUpperCase()
            .replace(/[^A-Z0-9]/g, '')
            .slice(0, 18);
        return;
    }

    if (tipo === 'email') {
        el.value = value
            .toLowerCase()
            .replace(/\s/g, '')
            .slice(0, 150);
        return;
    }

    if (tipo === 'monto') {
        value = value.replace(/[^0-9.]/g, '');

        const parts = value.split('.');

        if (parts.length > 2) {
            value = parts[0] + '.' + parts.slice(1).join('');
        }

        if (value.includes('.')) {
            const [entero, decimal] = value.split('.');
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
        el.value = value
            .toUpperCase()
            .replace(/[^A-Z0-9ÁÉÍÓÚÜÑ#\-\s/]/g, '')
            .slice(0, 20);
        return;
    }

    if (tipo === 'nombre') {
        el.value = value
            .toUpperCase()
            .replace(/[^A-ZÁÉÍÓÚÜÑ\s.'-]/g, '')
            .replace(/\s{2,}/g, ' ')
            .slice(0, 150);
        return;
    }

    if (tipo === 'folio_recibo') {
        el.value = value
            .toUpperCase()
            .replace(/[^A-Z0-9\-\/]/g, '')
            .slice(0, 100);
        return;
    }

    if (el.tagName !== 'TEXTAREA') {
        el.value = value.toUpperCase().slice(0, 255);
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

function vutClearInputError(el) {
    if (!el) return;

    el.classList.remove('vut-input-error');

    const next = el.nextElementSibling;

    if (next && next.classList.contains('vut-error-msg')) {
        next.remove();
    }
}

function vutValidarRFC(value) {
    value = (value || '').toUpperCase().trim();

    if (value === '') return true;

    return /^([A-ZÑ&]{3,4})(\d{2})(0[1-9]|1[0-2])(0[1-9]|[12]\d|3[01])([A-Z0-9]{3})$/.test(value);
}

function vutValidarCURP(value) {
    value = (value || '').toUpperCase().trim();

    if (value === '') return true;

    return /^[A-Z][AEIOUX][A-Z]{2}\d{2}(0[1-9]|1[0-2])(0[1-9]|[12]\d|3[01])[HM][A-Z]{5}[A-Z0-9]\d$/.test(value);
}

function vutValidarEmail(value) {
    value = (value || '').trim();

    if (value === '') return true;

    return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(value);
}

function vutValidarCampo(el, mostrarError = true) {
    if (!el || !vutEstaVisible(el)) {
        return {
            ok: true,
            mensaje: ''
        };
    }

    if (el.disabled || el.readOnly) {
        return {
            ok: true,
            mensaje: ''
        };
    }

    const tipo = vutTipoCampo(el);
    const value = (el.value || '').trim();
    const requerido = vutEsRequerido(el);

    vutClearInputError(el);

    if (requerido && value === '') {
        const result = {
            ok: false,
            mensaje: 'Este campo es obligatorio.'
        };

        if (mostrarError) vutSetInputError(el, result.mensaje);

        return result;
    }

    if (value === '') {
        return {
            ok: true,
            mensaje: ''
        };
    }

    let result = {
        ok: true,
        mensaje: ''
    };

    if (tipo === 'telefono' && !/^\d{10}$/.test(value)) {
        result = {
            ok: false,
            mensaje: 'El teléfono debe tener exactamente 10 dígitos.'
        };
    }

    if (tipo === 'cp' && !/^\d{5}$/.test(value)) {
        result = {
            ok: false,
            mensaje: 'El código postal debe tener exactamente 5 dígitos.'
        };
    }

    if (tipo === 'email' && !vutValidarEmail(value)) {
        result = {
            ok: false,
            mensaje: 'Ingresa un correo electrónico válido.'
        };
    }

    if (tipo === 'rfc' && !vutValidarRFC(value)) {
        result = {
            ok: false,
            mensaje: 'El RFC debe tener formato válido de 12 o 13 caracteres.'
        };
    }

    if (tipo === 'curp' && !vutValidarCURP(value)) {
        result = {
            ok: false,
            mensaje: 'La CURP debe tener formato válido de 18 caracteres.'
        };
    }

    if (tipo === 'monto') {
        const monto = Number(value);

        if (isNaN(monto) || monto <= 0) {
            result = {
                ok: false,
                mensaje: 'El monto debe ser mayor a 0.'
            };
        }
    }

    if (tipo === 'solo_numeros' && !/^\d+$/.test(value)) {
        result = {
            ok: false,
            mensaje: 'Este campo solo permite números.'
        };
    }

    if (tipo === 'numero_exterior' && value.length > 20) {
        result = {
            ok: false,
            mensaje: 'El número exterior no debe exceder 20 caracteres.'
        };
    }

    if (tipo === 'nombre' && value.length < 2) {
        result = {
            ok: false,
            mensaje: 'Ingresa al menos 2 caracteres.'
        };
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

    value = (value || '').trim();

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
        }

        if (tipo === 'cp') {
            el.type = 'text';
            el.inputMode = 'numeric';
            el.maxLength = 5;
            el.placeholder = el.placeholder || '5 dígitos';
        }

        if (tipo === 'email') {
            el.type = 'email';
            el.maxLength = 150;
            el.placeholder = el.placeholder || 'correo@ejemplo.com';
        }

        if (tipo === 'rfc') {
            el.type = 'text';
            el.maxLength = 13;
            el.placeholder = el.placeholder || 'RFC';
        }

        if (tipo === 'curp') {
            el.type = 'text';
            el.maxLength = 18;
            el.placeholder = el.placeholder || 'CURP';
        }

        if (tipo === 'monto') {
            el.type = 'text';
            el.inputMode = 'decimal';
            el.placeholder = el.placeholder || '0.00';
        }

        if (tipo === 'solo_numeros') {
            el.type = 'text';
            el.inputMode = 'numeric';
        }

        if (tipo === 'numero_exterior') {
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

    if (typeof switchTab === 'function') {
        switchTab(tabName);
    }
}

async function validarFormularioVUT() {
    aplicarRestriccionesVUT();

    const containers = [
        'tab-interesado',
        'tab-legal',
        'tab-autorizada',
        'contenedor-dinamico-captura',
        'contenedor-recibos-dinamico',
        'observaciones'
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
                const label = vutGetLabel(el).replace('*', '').trim() || el.name || el.id || 'Campo';

                errores.push(`${label}: ${result.mensaje}`);

                if (!primerCampoError) {
                    primerCampoError = el;
                }
            }
        });
    });

    if (errores.length > 0) {
        if (primerCampoError) {
            vutIrATabDeCampo(primerCampoError);

            setTimeout(() => {
                primerCampoError.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });

                primerCampoError.focus();
            }, 250);
        }

        await vutValidationAlert(errores);

        return false;
    }

    return true;
}

/**
 * Observa cambios dinámicos.
 * Esto es clave porque plantillasCaptura y plantillasInteresado inyectan HTML después.
 */
document.addEventListener('DOMContentLoaded', () => {
    aplicarRestriccionesVUT();

    const observer = new MutationObserver(() => {
        aplicarRestriccionesVUT();
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});
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
        await vutAlert({
            icon: 'warning',
            title: 'Trámite requerido',
            text: 'Por favor, selecciona un trámite específico para continuar.',
            confirmButtonText: 'Seleccionar trámite'
        });

        return;
    }

    if (typeof validarFormularioVUT === 'function') {
        const formularioValido = await validarFormularioVUT();

        if (!formularioValido) {
            return;
        }
    }

    const confirmado = await vutConfirm({
        icon: 'question',
        title: '¿Finalizar captura?',
        html: `
            <div style="text-align:left;">
                <p>Se registrará la solicitud y se generará el acuse oficial.</p>
                <p style="font-size:12px;color:#6b7280;">
                    Revisa que los requisitos, datos del interesado, predio/mercado y observaciones estén correctos.
                </p>
            </div>
        `,
        confirmButtonText: 'Sí, finalizar',
        cancelButtonText: 'Revisar captura'
    });

    if (!confirmado) return;

    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner"></span> Procesando...';
    }

    vutLoading(
        'Guardando solicitud',
        'Estamos registrando la captura y preparando el acuse oficial.'
    );

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

            recibos: typeof recolectarRecibosValidos === 'function'
                ? recolectarRecibosValidos()
                : recolectarInputs('contenedor-recibos-dinamico'),

            requisitos_validados: Array.from(document.querySelectorAll('#lista-presentados .item-requisito'))
                .map(div => div.innerText.trim().toUpperCase()),

            observaciones: document.getElementById('observaciones')?.value.toUpperCase() || ""
        };

        console.log("🚀 Enviando Payload Estructurado:", payload);

        const response = await fetch(VUT_ROUTES.guardar, {
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

        if (!result.success) {
            throw new Error(result.error || "Error desconocido al guardar.");
        }

        vutCloseLoading();

        const urlPdf = `${VUT_ROUTES.pdf}${result.id}`;

        const decision = vutSwalDisponible()
            ? await Swal.fire(vutSwalConfig({
                icon: 'success',
                title: 'Solicitud registrada',
                html: `
                    <div style="text-align:center;">
                        <p>La solicitud fue registrada correctamente.</p>
                        <div style="
                            margin:14px auto 4px;
                            padding:12px 14px;
                            background:#FCF7F9;
                            border:1px solid #E6D4DD;
                            border-radius:14px;
                            color:#773357;
                            font-weight:900;
                            display:inline-block;
                        ">
                            FOLIO: ${vutHtmlEscape(result.folio || 'SIN FOLIO')}
                        </div>
                        <p style="font-size:12px;color:#6b7280;margin-top:10px;">
                            Puedes abrir el acuse PDF en una nueva pestaña.
                        </p>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Abrir acuse PDF',
                cancelButtonText: 'Ir al inicio'
            }))
            : { isConfirmed: true };

        if (decision.isConfirmed) {
            window.open(urlPdf, '_blank');
        }

        window.location.href = VUT_ROUTES.home;

    } catch (error) {
        vutCloseLoading();

        console.error("❌ Error en el proceso:", error);

        await vutAlert({
            icon: 'error',
            title: 'No se pudo guardar',
            html: `
                <div style="text-align:left;">
                    <p>Ocurrió un error al procesar la solicitud.</p>
                    <div style="
                        margin-top:10px;
                        padding:10px;
                        background:#fff7f7;
                        border:1px solid #fecaca;
                        border-radius:12px;
                        color:#991b1b;
                        font-size:12px;
                        font-weight:800;
                        word-break:break-word;
                    ">
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
    selMateria = document.getElementById('select-materia');
    selTramite = document.getElementById('select-tramite');
    divReq = document.getElementById('lista-requisitos');
    divPres = document.getElementById('lista-presentados');
    placeholder = document.getElementById('placeholder-presentados');

    if (selMateria && selTramite && typeof actualizarTramites === 'function') {
        actualizarTramites();
    }

    if (typeof cambiarOpcionesRepresentante === 'function') {
        cambiarOpcionesRepresentante();
    }

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

    /**
     * Exponer funciones usadas por onclick="" en la vista.
     */
    window.switchTab = switchTab;
    window.actualizarTramites = actualizarTramites;
    window.actualizarRequisitos = actualizarRequisitos;
    window.actualizarDetalles = actualizarDetalles;
    window.moverDerecha = moverDerecha;
    window.moverIzquierda = moverIzquierda;
    window.cancelarProceso = cancelarProceso;
    window.limpiarTodo = limpiarTodo;
    window.actualizarPlantillaInteresado = actualizarPlantillaInteresado;
    window.cambiarOpcionesRepresentante = cambiarOpcionesRepresentante;
    window.inspeccionarData = inspeccionarData;
    window.finalizarCaptura = finalizarCaptura;
    window.actualizarResumenBifurcacion = actualizarResumenBifurcacion;
    window.recolectarInputs = recolectarInputs;
    window.recolectarBifurcacion = recolectarBifurcacion;
    window.recolectarRecibosValidos = recolectarRecibosValidos;
    window.aplicarRestriccionesVUT = aplicarRestriccionesVUT;
    window.validarFormularioVUT = validarFormularioVUT;
})();
