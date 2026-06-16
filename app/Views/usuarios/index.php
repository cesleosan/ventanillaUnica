<?php
if (!isset($data) || !is_array($data)) $data = [];
$usuarios = $data['usuarios'] ?? [];
$roles = $data['roles'] ?? ['root','supervisor','consulta','capturista'];
$q = $data['q'] ?? '';
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="max-w-7xl mx-auto p-4 sm:p-6 font-[Inter,Arial,sans-serif]">
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-xl p-6 mb-6 overflow-hidden relative">
        <div class="absolute -right-20 -top-20 w-56 h-56 rounded-full bg-[#773357]/10"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-[10px] font-black text-[#988053] uppercase tracking-[0.25em]">Ventanilla Única de Trámites</p>
                <h1 class="text-3xl font-black text-[#773357] tracking-tight uppercase">Usuarios VUT</h1>
                <p class="text-sm text-gray-500 font-bold mt-1">Administra únicamente usuarios del módulo <b>VUT</b>. Los usuarios de TIERRA quedan separados.</p>
            </div>
            <button onclick="abrirModalUsuario()" class="bg-[#773357] hover:bg-[#5b2743] text-white px-5 py-3 rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg transition-all active:scale-95">
                + Nuevo usuario VUT
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Total VUT</p>
            <p class="text-3xl font-black text-[#773357] mt-2"><?= count($usuarios) ?></p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Activos</p>
            <p class="text-3xl font-black text-green-700 mt-2"><?= count(array_filter($usuarios, fn($u) => !empty($u['activo']))) ?></p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Admins</p>
            <p class="text-3xl font-black text-purple-700 mt-2"><?= count(array_filter($usuarios, fn($u) => in_array($u['rol'] ?? '', ['root','supervisor'], true))) ?></p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Capturistas</p>
            <p class="text-3xl font-black text-blue-700 mt-2"><?= count(array_filter($usuarios, fn($u) => ($u['rol'] ?? '') === 'capturista')) ?></p>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-xl overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
            <form method="GET" action="index.php" class="flex gap-2 w-full lg:max-w-xl">
                <input type="hidden" name="route" value="usuarios">
                <input name="q" value="<?= htmlspecialchars($q, ENT_QUOTES, 'UTF-8') ?>" placeholder="Buscar usuario VUT, nombre, teléfono o rol"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold outline-none focus:border-[#773357] bg-[#FCF7F9]">
                <button class="px-4 py-3 rounded-xl bg-gray-100 text-gray-700 text-xs font-black uppercase">Buscar</button>
            </form>
            <div class="text-xs font-black text-gray-400 uppercase tracking-widest">
                Filtro fijo: <span class="text-[#773357]">modulo = VUT</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[980px]">
                <thead>
                    <tr class="bg-gray-50 text-left text-[10px] text-gray-500 uppercase tracking-widest">
                        <th class="p-4">Usuario</th>
                        <th class="p-4">Nombre</th>
                        <th class="p-4">Teléfono</th>
                        <th class="p-4">Rol</th>
                        <th class="p-4">Módulo</th>
                        <th class="p-4">Estado</th>
                        <th class="p-4">Último acceso</th>
                        <th class="p-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <?php if (empty($usuarios)): ?>
                        <tr><td colspan="8" class="p-10 text-center text-gray-400 font-black uppercase">Sin usuarios VUT registrados</td></tr>
                    <?php endif; ?>

                    <?php foreach ($usuarios as $u): ?>
                        <tr class="border-t border-gray-100 hover:bg-[#FCF7F9] transition-colors">
                            <td class="p-4 font-black text-[#773357]"><code><?= htmlspecialchars($u['usuario'] ?? '', ENT_QUOTES, 'UTF-8') ?></code></td>
                            <td class="p-4 font-bold text-gray-800"><?= htmlspecialchars($u['nombre_completo'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="p-4 font-bold text-gray-600"><?= htmlspecialchars($u['telefono'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full bg-purple-50 text-purple-700 text-[10px] font-black uppercase tracking-wider">
                                    <?= htmlspecialchars($u['rol'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full bg-[#FCF7F9] text-[#773357] border border-[#E6D4DD] text-[10px] font-black uppercase tracking-wider">
                                    <?= htmlspecialchars($u['modulo'] ?? 'VUT', ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </td>
                            <td class="p-4">
                                <?php if (!empty($u['activo'])): ?>
                                    <span class="px-3 py-1 rounded-full bg-green-50 text-green-700 text-[10px] font-black uppercase">Activo</span>
                                <?php else: ?>
                                    <span class="px-3 py-1 rounded-full bg-red-50 text-red-700 text-[10px] font-black uppercase">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-xs font-bold text-gray-500"><?= htmlspecialchars($u['ultimo_acceso'] ?? 'SIN ACCESO', ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="p-4 text-right whitespace-nowrap">
                                <button onclick='abrirModalUsuario(<?= json_encode($u, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>)' class="px-3 py-2 rounded-xl bg-blue-50 text-blue-700 text-xs font-black uppercase">Editar</button>
                                <button onclick="toggleUsuario(<?= (int)$u['id'] ?>, <?= empty($u['activo']) ? 1 : 0 ?>)" class="px-3 py-2 rounded-xl <?= empty($u['activo']) ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' ?> text-xs font-black uppercase">
                                    <?= empty($u['activo']) ? 'Activar' : 'Desactivar' ?>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function sinAcentosMayus(value) {
    return String(value || '')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/Ñ/g, 'N')
        .replace(/ñ/g, 'n')
        .toUpperCase();
}

function normalizarUsuarioVUT(value) {
    value = String(value || '')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase()
        .replace(/[^a-z0-9._-]/g, '')
        .replace(/\.+/g, '.')
        .replace(/^[._-]+|[._-]+$/g, '');

    if (value && !value.startsWith('vut.')) {
        value = 'vut.' + value;
    }

    return value;
}

function rolesOptions(selected = 'capturista') {
    const roles = <?= json_encode($roles, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    return roles.map(r => `<option value="${r}" ${r === selected ? 'selected' : ''}>${r.toUpperCase()}</option>`).join('');
}

async function abrirModalUsuario(usuario = null) {
    const editando = !!usuario;

    const res = await Swal.fire({
        title: editando ? 'Editar usuario VUT' : 'Nuevo usuario VUT',
        width: 760,
        html: `
            <div style="text-align:left;display:grid;gap:12px;">
                <input id="usr-id" type="hidden" value="${usuario?.id || ''}">

                <div style="padding:12px;border-radius:14px;background:#FCF7F9;border:1px solid #E6D4DD;color:#773357;font-size:12px;font-weight:800;">
                    Todos los usuarios se guardan con prefijo <b>vut.</b> y módulo <b>VUT</b>. Así no chocan con TIERRA.
                </div>

                <label style="font-size:11px;font-weight:900;color:#773357;text-transform:uppercase;">Usuario</label>
                <input id="usr-usuario" class="swal2-input" style="width:100%;margin:0;" value="${usuario?.usuario || ''}" placeholder="vut.nombre.apellido">

                <label style="font-size:11px;font-weight:900;color:#773357;text-transform:uppercase;">Nombre completo</label>
                <input id="usr-nombre" class="swal2-input" style="width:100%;margin:0;" value="${usuario?.nombre_completo || ''}" placeholder="NOMBRE COMPLETO">

                <label style="font-size:11px;font-weight:900;color:#773357;text-transform:uppercase;">Teléfono</label>
                <input id="usr-telefono" class="swal2-input" style="width:100%;margin:0;" value="${usuario?.telefono || ''}" placeholder="10 DÍGITOS">

                <label style="font-size:11px;font-weight:900;color:#773357;text-transform:uppercase;">Rol</label>
                <select id="usr-rol" class="swal2-input" style="width:100%;margin:0;">${rolesOptions(usuario?.rol || 'capturista')}</select>

                <label style="font-size:11px;font-weight:900;color:#773357;text-transform:uppercase;">Contraseña ${editando ? '(opcional)' : ''}</label>
                <input id="usr-password" type="password" class="swal2-input" style="width:100%;margin:0;" placeholder="MÍNIMO 6 CARACTERES">

                <label style="display:flex;align-items:center;gap:8px;font-size:12px;font-weight:800;color:#374151;">
                    <input id="usr-activo" type="checkbox" ${!usuario || Number(usuario.activo) === 1 ? 'checked' : ''}> Usuario activo
                </label>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        didOpen: () => {
            const nombre = document.getElementById('usr-nombre');
            const tel = document.getElementById('usr-telefono');
            const usr = document.getElementById('usr-usuario');
            nombre.addEventListener('input', () => nombre.value = sinAcentosMayus(nombre.value));
            tel.addEventListener('input', () => tel.value = tel.value.replace(/\D/g, '').slice(0, 10));
            usr.addEventListener('blur', () => usr.value = normalizarUsuarioVUT(usr.value));
        },
        preConfirm: () => {
            const data = {
                id: Number(document.getElementById('usr-id').value || 0),
                usuario: normalizarUsuarioVUT(document.getElementById('usr-usuario').value.trim()),
                nombre_completo: sinAcentosMayus(document.getElementById('usr-nombre').value.trim()),
                telefono: document.getElementById('usr-telefono').value.trim(),
                rol: document.getElementById('usr-rol').value,
                password: document.getElementById('usr-password').value,
                activo: document.getElementById('usr-activo').checked ? 1 : 0,
                modulo: 'VUT'
            };

            if (!data.usuario || data.usuario.length < 7) {
                Swal.showValidationMessage('Captura un usuario válido, ejemplo: vut.nombre.apellido');
                return false;
            }

            if (data.nombre_completo.length < 3) {
                Swal.showValidationMessage('Captura el nombre completo.');
                return false;
            }

            if (!editando && data.password.length < 6) {
                Swal.showValidationMessage('La contraseña inicial debe tener mínimo 6 caracteres.');
                return false;
            }

            return data;
        }
    });

    if (!res.isConfirmed) return;

    const response = await fetch('index.php?route=usuarios/guardar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify(res.value)
    });

    const data = await response.json();

    if (!response.ok || !data.success) {
        Swal.fire('Error', data.error || 'No se pudo guardar el usuario.', 'error');
        return;
    }

    await Swal.fire('Listo', 'Usuario VUT guardado correctamente.', 'success');
    window.location.reload();
}

async function toggleUsuario(id, activo) {
    const ok = await Swal.fire({
        icon: 'question',
        title: activo ? '¿Activar usuario VUT?' : '¿Desactivar usuario VUT?',
        text: activo ? 'El usuario podrá ingresar nuevamente.' : 'El usuario ya no podrá ingresar al VUT.',
        showCancelButton: true,
        confirmButtonText: activo ? 'Activar' : 'Desactivar',
        cancelButtonText: 'Cancelar'
    });

    if (!ok.isConfirmed) return;

    const response = await fetch('index.php?route=usuarios/toggle', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ id, activo })
    });

    const data = await response.json();

    if (!response.ok || !data.success) {
        Swal.fire('Error', data.error || 'No se pudo cambiar el estado.', 'error');
        return;
    }

    window.location.reload();
}
</script>
