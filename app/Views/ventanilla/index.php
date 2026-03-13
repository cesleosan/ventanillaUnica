<div class="mb-8 animate-fade-in-up">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-3xl font-black text-tlalpan-vino">Ventanilla Única</h2>
            <p class="text-gray-500">Gestión y captura de trámites ciudadanos</p>
        </div>
        <div class="text-right">
            <span class="bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-full border border-blue-100">
                FECHA INGRESO: <?= date('d/m/y') ?>
            </span>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b pb-2">Panel de Búsqueda</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Materia</label>
                <select id="select-materia" onchange="actualizarTramites()" class="input-tlalpan block w-full py-2 px-3 rounded-lg shadow-sm sm:text-sm">
                    <?php foreach($data['materias'] as $materia): ?>
                        <option value="<?= $materia ?>"><?= $materia ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Trámite</label>
                <select id="select-tramite" onchange="actualizarRequisitos()" class="input-tlalpan block w-full py-2 px-3 rounded-lg shadow-sm sm:text-sm">

                </select>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden animate-fade-in-up mb-10">
    
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-wrap gap-6 items-center">
        <h3 class="font-bold text-tlalpan-vino text-lg">Datos de Captura</h3>
        
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-gray-500">PERSONA:</span>
            <select class="input-tlalpan text-sm rounded py-1 px-3">
                <option>Física</option>
                <option>Moral</option>
            </select>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-gray-500">REPRESENTANTE:</span>
            <select class="input-tlalpan text-sm rounded py-1 px-3">
                <option>Propietario</option>
                <option>Legal</option>
            </select>
        </div>
    </div>

    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8 px-6 overflow-x-auto" aria-label="Tabs">
            <button onclick="switchTab('requisitos')" id="btn-requisitos" class="tab-btn border-tlalpan-vino text-tlalpan-vino whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm transition-colors">
                Requisitos
            </button>
            <button onclick="switchTab('interesado')" id="btn-interesado" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Interesado
            </button>
            <button onclick="switchTab('predio')" id="btn-predio" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Predio
            </button>
            <button onclick="switchTab('observaciones')" id="btn-observaciones" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Observaciones
            </button>
        </nav>
    </div>

    <div class="p-6 min-h-[400px]">
        
        <div id="tab-requisitos" class="tab-content block">
            <p class="mb-4 text-sm text-gray-600">Seleccione los documentos que el ciudadano está presentando.</p>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 h-96">
                <div class="md:col-span-5 flex flex-col h-full">
                    <label class="text-xs font-bold text-gray-500 uppercase mb-2">Requeridos</label>
                    <div  id="lista-requisitos" class="border border-gray-200 rounded-lg bg-gray-50 flex-1 overflow-y-auto p-2 space-y-2 shadow-inner">
                        <?php foreach($data['requisitos'] as $req): ?>
                        <div class="flex items-start p-3 bg-white border border-gray-100 rounded-lg hover:border-tlalpan-vino/30 hover:bg-red-50 cursor-pointer group transition shadow-sm">
                            <div class="mr-3 mt-0.5 text-gray-300 group-hover:text-tlalpan-vino transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <p class="text-xs text-gray-600 font-medium leading-snug select-none"><?= $req ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="md:col-span-2 flex md:flex-col justify-center items-center gap-3">
                   <button onclick="moverDerecha()" class="p-3 bg-tlalpan-vino text-white rounded-full hover:bg-opacity-90 shadow-lg transform active:scale-95 transition">
                        <svg class="w-6 h-6 md:rotate-0 rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </button>

                    <button onclick="moverIzquierda()" class="p-3 bg-gray-100 text-gray-400 rounded-full hover:bg-gray-200 shadow hover:text-gray-600 transition">
                        <svg class="w-6 h-6 md:rotate-0 rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                        </svg>
                    </button>
                </div>
                <div class="md:col-span-5 flex flex-col h-full">
                    <label class="text-xs font-bold text-tlalpan-vino uppercase mb-2">Presentados</label>
                    <div id="lista-presentados" class="border-2 border-dashed border-gray-300 rounded-lg bg-white flex-1 overflow-y-auto p-2 space-y-2 h-96">
                        <div class="text-center opacity-50">
                            <svg class="w-10 h-10 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            No hay documentos aún.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="tab-interesado" class="tab-content hidden">
            <h4 class="text-lg font-bold text-gray-800 mb-6 border-b pb-2 flex items-center gap-2">
                <span class="w-2 h-6 bg-tlalpan-vino rounded-full"></span> Datos del Solicitante
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nombre(s) <span class="text-red-500">*</span></label>
                    <input type="text" class="input-tlalpan w-full rounded-lg shadow-sm sm:text-sm p-2">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Apellido Paterno <span class="text-red-500">*</span></label>
                    <input type="text" class="input-tlalpan w-full rounded-lg shadow-sm sm:text-sm p-2">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Apellido Materno</label>
                    <input type="text" class="input-tlalpan w-full rounded-lg shadow-sm sm:text-sm p-2">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-gray-700 mb-1">RFC <span class="text-red-500">*</span></label>
                    <input type="text" class="input-tlalpan w-full rounded-lg shadow-sm sm:text-sm uppercase p-2" placeholder="XXXX000000">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Teléfono</label>
                    <input type="tel" class="input-tlalpan w-full rounded-lg shadow-sm sm:text-sm p-2">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Correo Electrónico</label>
                    <input type="email" class="input-tlalpan w-full rounded-lg shadow-sm sm:text-sm p-2">
                </div>
            </div>

            <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-4 mt-8 border-b pb-1">Domicilio</h4>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Alcaldía / Municipio <span class="text-red-500">*</span></label>
                    <select class="input-tlalpan w-full rounded-lg shadow-sm sm:text-sm p-2">
                        <option>TLALPAN</option>
                        <option>COYOACÁN</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Colonia (Lista) <span class="text-red-500">*</span></label>
                    <select class="input-tlalpan w-full rounded-lg shadow-sm sm:text-sm p-2">
                        <option>Seleccione...</option>
                        <option>SAN MIGUEL AJUSCO</option>
                        <option>CENTRO DE TLALPAN</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Colonia no listada</label>
                    <input type="text" class="input-tlalpan w-full rounded-lg shadow-sm sm:text-sm bg-gray-50 p-2">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                 <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Calle (Lista) <span class="text-red-500">*</span></label>
                    <select class="input-tlalpan w-full rounded-lg shadow-sm sm:text-sm p-2">
                        <option>Calle no listada</option>
                        <option>INSURGENTES SUR</option>
                    </select>
                </div>
                 <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Calle no listada</label>
                    <input type="text" class="input-tlalpan w-full rounded-lg shadow-sm sm:text-sm p-2">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Número Exterior <span class="text-red-500">*</span></label>
                    <input type="text" class="input-tlalpan w-full rounded-lg shadow-sm sm:text-sm p-2">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-gray-700 mb-1">C.P. <span class="text-red-500">*</span></label>
                    <input type="text" class="input-tlalpan w-full rounded-lg shadow-sm sm:text-sm p-2">
                </div>
            </div>
        </div>

        <div id="tab-predio" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-6 pt-2">
                <div class="md:col-span-4">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Uso actual <span class="text-red-500">*</span></label>
                    <select class="input-tlalpan w-full rounded-lg shadow-sm sm:text-sm p-2">
                        <option>Seleccione...</option>
                        <option>HABITACIONAL</option>
                        <option>COMERCIAL</option>
                    </select>
                </div>
                <div class="md:col-span-4">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Uso solicitado</label>
                    <select class="input-tlalpan w-full rounded-lg shadow-sm sm:text-sm p-2">
                        <option>Seleccione...</option>
                        <option>HABITACIONAL MIXTO</option>
                    </select>
                </div>
                <div class="md:col-span-4">
                    <label class="block text-xs font-bold text-gray-700 mb-1 text-right">Dirección <span class="text-red-500">*</span></label>
                    <select class="input-tlalpan w-full rounded-lg shadow-sm sm:text-sm p-2">
                        <option>Nueva</option>
                        <option>Existente</option>
                    </select>
                </div>
            </div>

            <div class="border-t border-gray-200 my-6"></div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Del.: <span class="text-red-500">*</span></label>
                    <select class="input-tlalpan w-full rounded-lg shadow-sm sm:text-sm p-2">
                        <option>tlalpan</option>
                    </select>
                </div>
                <div class="md:col-span-5">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Colonia <span class="text-red-500">*</span></label>
                    <select class="input-tlalpan w-full rounded-lg shadow-sm sm:text-sm p-2">
                        <option>Seleccione una colonia...</option>
                        <option>SAN PEDRO MARTIR</option>
                    </select>
                </div>
                <div class="md:col-span-4">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Col. no Listada</label>
                    <input type="text" class="input-tlalpan w-full rounded-lg shadow-sm sm:text-sm bg-gray-50 p-2">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-6">
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Calle: <span class="text-red-500">*</span></label>
                    <select class="input-tlalpan w-full rounded-lg shadow-sm sm:text-sm p-2">
                        <option>Calle no listada</option>
                        <option>AV. INSURGENTES</option>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Calle no Listada <span class="text-red-500">*</span></label>
                    <input type="text" class="input-tlalpan w-full rounded-lg shadow-sm sm:text-sm p-2">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Número Exterior <span class="text-red-500">*</span></label>
                    <input type="text" class="input-tlalpan w-full rounded-lg shadow-sm sm:text-sm p-2">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Código Postal <span class="text-red-500">*</span></label>
                    <input type="text" class="input-tlalpan w-full rounded-lg shadow-sm sm:text-sm p-2">
                </div>
            </div>

            <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <label class="inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="w-5 h-5 rounded border-gray-300 text-tlalpan-vino shadow-sm focus:border-tlalpan-vino focus:ring focus:ring-tlalpan-vino focus:ring-opacity-50 transition">
                    <span class="ml-3 text-sm text-gray-700 font-bold select-none">Agregar datos de propietario</span>
                </label>
            </div>
        </div>

        <div id="tab-observaciones" class="tab-content hidden">
            <div class="max-w-5xl mx-auto py-4">
                <div class="mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-tlalpan-vino" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <h3 class="font-bold text-gray-700 text-lg">Observaciones Generales</h3>
                </div>
                <div class="relative">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2 ml-1">
                        Ingrese aquí notas adicionales:
                    </label>
                    <textarea 
                        rows="12" 
                        class="input-tlalpan w-full p-4 text-gray-700 text-sm leading-relaxed resize-none"
                        placeholder="Ejemplo: El ciudadano presenta documentación parcial pendiente de validación..."
                    ></textarea>
                </div>
            </div>
        </div>

    </div>

    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
        <button class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50 transition shadow-sm text-sm uppercase" onclick="limpiarTodo()">Limpiar</button>
        <button class="px-4 py-2 bg-red-50 text-red-700 border border-red-200 font-bold rounded-lg hover:bg-red-100 transition shadow-sm text-sm uppercase" onclick="window.location.href='?route=home'">Cancelar</button>
        <button class="px-6 py-2 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition shadow-md flex items-center text-sm uppercase">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Validar y Guardar
        </button>
    </div>
</div>

<script>
function switchTab(tabId) {
    const contents = document.querySelectorAll('.tab-content');
    contents.forEach(content => content.classList.add('hidden'));
    
    const buttons = document.querySelectorAll('.tab-btn');
    buttons.forEach(btn => {
        btn.classList.remove('border-tlalpan-vino', 'text-tlalpan-vino', 'font-bold');
        btn.classList.add('border-transparent', 'text-gray-500', 'font-medium');
    });

    document.getElementById('tab-' + tabId).classList.remove('hidden');

    const activeBtn = document.getElementById('btn-' + tabId);
    activeBtn.classList.remove('border-transparent', 'text-gray-500', 'font-medium');
    activeBtn.classList.add('border-tlalpan-vino', 'text-tlalpan-vino', 'font-bold');
}
</script>

<script>
    // 1. Recibimos los datos del Controlador (PHP -> JS)
    const catalogo = <?= json_encode($data['catalogo_json']) ?>;

    const selMateria = document.getElementById('select-materia');
    const selTramite = document.getElementById('select-tramite');
    const divReq = document.getElementById('lista-requisitos');
    const divPres = document.getElementById('lista-presentados');

    // Al cargar la página, llenamos el primer trámite
    window.onload = function() { actualizarTramites(); };

    function actualizarTramites() {
        const materia = selMateria.value;
        const tramites = catalogo[materia];
        
        selTramite.innerHTML = ''; // Limpiar
        for (const t in tramites) {
            let opt = document.createElement('option');
            opt.value = t;
            opt.innerHTML = t;
            selTramite.appendChild(opt);
        }
        actualizarRequisitos(); // Actualizar lista de abajo
    }

    function actualizarRequisitos() {
        const materia = selMateria.value;
        const tramite = selTramite.value;
        const requisitos = catalogo[materia][tramite] || [];

        divReq.innerHTML = '';
        divPres.innerHTML = ''; // Limpiar presentados al cambiar trámite

        requisitos.forEach((req, i) => {
            // Creamos el cuadrito del requisito
            let div = document.createElement('div');
            div.className = 'flex items-center p-2 border border-gray-200 rounded mb-2 cursor-pointer hover:bg-gray-50 transition';
            div.innerHTML = `<span class="text-xs text-gray-600">${req}</span>`;
            
            // Al hacer clic se selecciona (borde vino)
            div.onclick = function() { 
                this.classList.toggle('ring-2'); 
                this.classList.toggle('ring-tlalpan-vino'); 
                this.classList.toggle('seleccionado');
            };
            divReq.appendChild(div);
        });
    }

    function moverDerecha() {
        // Mover de Izquierda -> Derecha (Validar)
        const items = divReq.querySelectorAll('.seleccionado');
        items.forEach(item => {
            item.classList.remove('seleccionado', 'ring-2', 'ring-tlalpan-vino');
            item.classList.add('bg-green-50', 'border-green-200'); // Poner verde
            // Cambiar evento onclick para deseleccionar en el otro lado
            item.onclick = function() { this.classList.toggle('ring-2'); this.classList.toggle('ring-green-500'); this.classList.toggle('listo-para-volver'); };
            divPres.appendChild(item);
        });
    }

    function moverIzquierda() {
        // Mover de Derecha -> Izquierda (Corregir)
        const items = divPres.querySelectorAll('.listo-para-volver');
        items.forEach(item => {
            item.classList.remove('listo-para-volver', 'ring-2', 'ring-green-500', 'bg-green-50', 'border-green-200');
            item.onclick = function() { this.classList.toggle('ring-2'); this.classList.toggle('ring-tlalpan-vino'); this.classList.toggle('seleccionado'); };
            divReq.appendChild(item);
        });
    }
    
    function limpiarTodo() {
    // 1. Resetear el Panel de Búsqueda
    const selMateria = document.getElementById('select-materia');
    selMateria.selectedIndex = 0; // Vuelve a la primera opción
    actualizarTramites(); // Esto regenera la lista de trámites y limpia los requisitos automáticamente

    // 2. Limpiar todos los Inputs de Texto, Email y Teléfono en las pestañas
    const inputs = document.querySelectorAll('.tab-content input:not([type="checkbox"]), .tab-content textarea');
    inputs.forEach(input => input.value = '');

    // 3. Resetear todos los Selects de los formularios (Interesado, Predio...)
    const selectsFormulario = document.querySelectorAll('.tab-content select');
    selectsFormulario.forEach(select => select.selectedIndex = 0);

    // 4. Desmarcar todos los checkboxes
    const checkboxes = document.querySelectorAll('.tab-content input[type="checkbox"]');
    checkboxes.forEach(box => box.checked = false);

    // 5. Regresar visualmente a la primera pestaña
    switchTab('requisitos');
}
</script>