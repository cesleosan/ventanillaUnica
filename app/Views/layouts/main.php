<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['pageTitle'] ?? 'Ventanilla Única - Alcaldía Tlalpan' ?></title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F9FAFB;
        }
        .bg-tlalpan-vino { background-color: #773357; }
        .text-tlalpan-vino { color: #773357; }
        .bg-tlalpan-oro { background-color: #988053; }
        .border-tlalpan-vino { border-color: #773357; }
        .input-tlalpan {
            background-color: #FCF7F9;
            border: 1px solid #E6D4DD;
            transition: all 0.3s ease;
            border-radius: 0.75rem;
        }
        .input-tlalpan:focus {
            background-color: #ffffff;
            border-color: #773357;
            box-shadow: 0 0 0 4px rgba(119, 51, 87, 0.1);
            outline: none;
        }
        .view-fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<?php
$userLayout = $data['user'] ?? ($_SESSION['user'] ?? null);
$nombreUsuarioLayout = $userLayout['nombre'] ?? $userLayout['name'] ?? 'USUARIO';
$rolUsuarioLayout = strtolower((string)($userLayout['rol'] ?? $userLayout['role'] ?? $_SESSION['rol'] ?? ''));
$moduloUsuarioLayout = strtoupper((string)($userLayout['modulo'] ?? $_SESSION['modulo'] ?? ''));
$puedeUsuariosLayout = isset($userLayout) && in_array($rolUsuarioLayout, ['root', 'supervisor'], true) && $moduloUsuarioLayout === 'VUT';
?>

<body class="flex flex-col min-h-screen">

    <header class="bg-white border-b border-gray-100 shadow-sm relative z-20">
        <div class="max-w-7xl mx-auto px-4 min-h-24 py-4 flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">

            <div class="flex items-center gap-6">
                <img src="/logos/Logo AT Vertical guinda 100 PX.png" alt="Logo Tlalpan" class="h-16 w-auto object-contain">

                <div class="hidden sm:block h-10 w-px bg-gray-200"></div>

                <div class="flex flex-col">
                    <h2 class="text-xl font-black text-tlalpan-vino tracking-tight leading-none uppercase">Ciudad De México</h2>
                    <p class="text-[10px] text-gray-400 font-bold tracking-[0.2em] uppercase mt-1">Alcaldía Tlalpan · Ventanilla Única</p>
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:items-center gap-3">
                <?php if (isset($userLayout)): ?>
                    <nav class="flex flex-wrap items-center gap-2 justify-start md:justify-end">
                        <a href="/" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest bg-gray-50 text-gray-600 hover:bg-[#FCF7F9] hover:text-[#773357] transition-all">Inicio</a>
                        <a href="index.php?route=ventanilla" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest bg-gray-50 text-gray-600 hover:bg-[#FCF7F9] hover:text-[#773357] transition-all">Dashboard VUT</a>
                        <a href="index.php?route=ventanilla/nueva" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest bg-[#773357] text-white hover:bg-[#5b2743] transition-all">Nueva captura</a>

                        <?php if ($puedeUsuariosLayout): ?>
                            <a href="index.php?route=usuarios" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest bg-[#FCF7F9] text-[#773357] border border-[#E6D4DD] hover:bg-[#773357] hover:text-white transition-all">👥 Usuarios</a>
                        <?php endif; ?>
                    </nav>

                    <div class="flex items-center gap-3 bg-gray-50 p-2 pr-4 rounded-2xl border border-gray-100">
                        <div class="w-10 h-10 bg-tlalpan-vino rounded-xl flex items-center justify-center text-white font-bold shadow-sm">
                            <?= htmlspecialchars(substr($nombreUsuarioLayout, 0, 1), ENT_QUOTES, 'UTF-8') ?>
                        </div>
                        <div class="hidden md:flex flex-col">
                            <span class="text-xs font-black text-gray-700 leading-none uppercase"><?= htmlspecialchars($nombreUsuarioLayout, ENT_QUOTES, 'UTF-8') ?></span>
                            <span class="text-[10px] text-tlalpan-vino font-bold uppercase mt-1 tracking-wider">
                                <?= htmlspecialchars($rolUsuarioLayout, ENT_QUOTES, 'UTF-8') ?><?= $moduloUsuarioLayout ? ' · ' . htmlspecialchars($moduloUsuarioLayout, ENT_QUOTES, 'UTF-8') : '' ?>
                            </span>
                        </div>
                        <form method="POST" action="/" class="ml-2">
                            <input type="hidden" name="action" value="logout">
                            <button type="submit" class="p-2 hover:bg-red-50 text-red-400 hover:text-red-600 rounded-lg transition-colors" title="Cerrar Sesión">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest border border-gray-200 px-3 py-2 rounded-full">
                        Portal Oficial de Trámites
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <div class="h-1 bg-tlalpan-oro w-full opacity-80"></div>
    </header>

    <main class="flex-grow flex flex-col justify-center py-12 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full pointer-events-none opacity-[0.03] z-0">
            <svg width="100%" height="100%"><rect width="100%" height="100%" fill="url(#grid)" /><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="#773357" stroke-width="1"/></pattern></defs></svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10 view-fade-in">
            <?php
                if (isset($viewContent) && file_exists($viewContent)) {
                    include $viewContent;
                }
                elseif (isset($userLayout)) {
                    if (file_exists('../app/Views/dashboard/home.php')) {
                        include '../app/Views/dashboard/home.php';
                    } else {
                        echo "<div class='bg-white p-8 rounded-3xl shadow-xl text-center border-2 border-dashed border-red-100'>
                                <p class='text-red-500 font-bold'>Error: El dashboard no está disponible.</p>
                              </div>";
                    }
                }
                else {
                    include '../app/Views/auth/login.php';
                }
            ?>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <img src="/logos/Logo AT Vertical guinda 100 PX.png" alt="Tlalpan Logo" class="h-10 opacity-50 grayscale hover:grayscale-0 transition-all">
                <div class="text-left">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-tighter">Alcaldía Tlalpan</p>
                    <p class="text-[9px] text-gray-300 uppercase tracking-[0.3em]">Ventanilla Única</p>
                </div>
            </div>

            <div class="text-center md:text-right">
                <p class="text-xs font-bold text-gray-400">© <?= date('Y') ?> - Todos los derechos reservados</p>
                <p class="text-[10px] text-tlalpan-vino font-black uppercase tracking-widest mt-1">Gobierno de la Ciudad de México</p>
            </div>
        </div>
    </footer>

</body>
</html>
