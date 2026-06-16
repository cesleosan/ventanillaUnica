<?php
/**
 * DASHBOARD VUT - BANDEJA DE SEGUIMIENTO
 * Versión UI/UX para usuario final/capturista: paginación visible, tabla limpia, modal de detalle y edición.
 *
 * Requiere endpoints:
 * - index.php?route=ventanilla/dashboardData
 * - index.php?route=ventanilla/detalle&id=ID
 * - index.php?route=ventanilla/cambiarEstado
 * - index.php?route=ventanilla/generarComprobante&id=ID
 * - index.php?route=ventanilla/nueva
 * - index.php?route=ventanilla/editar&id=ID
 */

if (!isset($data) || !is_array($data)) {
    $data = [];
}

$estados = $data['estados'] ?? [
    'NUEVO' => 'Nuevo',
    'INGRESADO' => 'Ingresado',
    'EN_VALIDACION' => 'En validación',
    'PREVENIDO' => 'Prevenido',
    'EN_REVISION' => 'En revisión',
    'APROBADO' => 'Aprobado',
    'RECHAZADO' => 'Rechazado',
    'TERMINADO' => 'Terminado',
    'CANCELADO' => 'Cancelado'
];

$materias = $data['materias'] ?? [];
$tramites = $data['tramites'] ?? [];
$puedeAprobar = !empty($data['puede_aprobar']);
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    :root {
        --vut-guinda: #773357;
        --vut-guinda-dark: #5b2743;
        --vut-guinda-soft: #fcf7f9;
        --vut-border: #ead9e2;
        --vut-bg: #f6f7fb;
        --vut-card: #ffffff;
        --vut-ink: #111827;
        --vut-muted: #6b7280;
        --vut-soft-gray: #f9fafb;
        --vut-line: #eef2f7;
        --vut-shadow: 0 18px 48px rgba(17, 24, 39, .075);
    }

    body {
        background: var(--vut-bg);
    }

    .vut-dashboard {
        font-family: 'Montserrat', Arial, sans-serif;
        color: var(--vut-ink);
        padding: 24px;
        max-width: 1680px;
        margin: 0 auto;
    }

    .vut-dashboard * {
        box-sizing: border-box;
    }

    .vut-hero {
        position: relative;
        overflow: hidden;
        background:
            radial-gradient(circle at top right, rgba(119, 51, 87, .16), transparent 35%),
            linear-gradient(135deg, #fff 0%, #fcf7f9 100%);
        border: 1px solid var(--vut-border);
        border-radius: 30px;
        padding: 26px;
        box-shadow: var(--vut-shadow);
        margin-bottom: 22px;
    }

    .vut-hero::after {
        content: "";
        position: absolute;
        right: -90px;
        bottom: -120px;
        width: 260px;
        height: 260px;
        border-radius: 999px;
        background: rgba(119, 51, 87, .10);
    }

    .vut-hero-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
    }

    .vut-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,.85);
        border: 1px solid var(--vut-border);
        color: var(--vut-guinda);
        border-radius: 999px;
        padding: 7px 12px;
        font-size: 10px;
        font-weight: 900;
        letter-spacing: .20em;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .vut-title {
        margin: 0;
        color: var(--vut-guinda);
        font-size: clamp(28px, 3vw, 42px);
        line-height: 1;
        font-weight: 950;
        letter-spacing: -.035em;
    }

    .vut-subtitle {
        margin: 8px 0 0;
        color: var(--vut-muted);
        font-size: 14px;
        font-weight: 700;
        max-width: 820px;
    }

    .vut-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: flex-end;
    }

    .vut-btn {
        border: 0;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 40px;
        border-radius: 14px;
        padding: 10px 14px;
        font-size: 11px;
        font-weight: 950;
        letter-spacing: .08em;
        text-transform: uppercase;
        transition: transform .18s ease, box-shadow .18s ease, background .18s ease, border-color .18s ease, color .18s ease;
        white-space: nowrap;
    }

    .vut-btn:hover {
        transform: translateY(-1px);
    }

    .vut-btn-primary {
        background: var(--vut-guinda);
        color: #fff;
        box-shadow: 0 12px 24px rgba(119, 51, 87, .18);
    }

    .vut-btn-primary:hover {
        background: var(--vut-guinda-dark);
        color: #fff;
    }

    .vut-btn-light {
        background: #fff;
        color: #374151;
        border: 1px solid #e5e7eb;
    }

    .vut-btn-light:hover {
        color: var(--vut-guinda);
        border-color: var(--vut-border);
        background: #fff;
    }

    .vut-btn-danger {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .vut-btn-icon {
        width: 38px;
        min-width: 38px;
        height: 38px;
        padding: 0;
        border-radius: 12px;
        font-size: 13px;
    }

    .vut-kpi-grid {
        display: grid;
        grid-template-columns: repeat(8, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 20px;
    }

    .vut-kpi {
        background: #fff;
        border: 1px solid #f0e7ec;
        border-radius: 22px;
        box-shadow: 0 12px 30px rgba(17, 24, 39, .055);
        padding: 18px;
        min-height: 112px;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    }

    .vut-kpi:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 44px rgba(17, 24, 39, .10);
        border-color: rgba(119,51,87,.30);
    }

    .vut-kpi::after {
        content: "";
        width: 86px;
        height: 86px;
        border-radius: 999px;
        background: rgba(119,51,87,.075);
        position: absolute;
        top: -26px;
        right: -26px;
    }

    .vut-kpi-label {
        margin: 0;
        font-size: 9px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: .18em;
        color: #9ca3af;
    }

    .vut-kpi-value {
        margin: 8px 0 2px;
        font-size: 31px;
        line-height: 1;
        font-weight: 950;
        color: #111827;
    }

    .vut-kpi-help {
        margin: 0;
        font-size: 11px;
        font-weight: 800;
        color: #9ca3af;
    }

    .vut-kpi-blue .vut-kpi-value { color:#1d4ed8; }
    .vut-kpi-sky .vut-kpi-value { color:#0369a1; }
    .vut-kpi-purple .vut-kpi-value { color:#6d28d9; }
    .vut-kpi-amber .vut-kpi-value { color:#92400e; }
    .vut-kpi-green .vut-kpi-value { color:#166534; }
    .vut-kpi-red .vut-kpi-value { color:#991b1b; }
    .vut-kpi-teal .vut-kpi-value { color:#0f766e; }

    .vut-card {
        background: #fff;
        border: 1px solid #f0e7ec;
        border-radius: 26px;
        box-shadow: var(--vut-shadow);
        margin-bottom: 20px;
    }

    .vut-card-body {
        padding: 20px;
    }

    .vut-chips {
        display: flex;
        flex-wrap: nowrap;
        gap: 8px;
        overflow-x: auto;
        padding: 2px 0 14px;
        margin-bottom: 16px;
        scrollbar-width: thin;
    }

    .vut-chip {
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #4b5563;
        border-radius: 999px;
        padding: 9px 13px;
        font-size: 10px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: .08em;
        white-space: nowrap;
        cursor: pointer;
        transition: all .18s ease;
    }

    .vut-chip:hover,
    .vut-chip.active {
        background: var(--vut-guinda);
        color: #fff;
        border-color: var(--vut-guinda);
        transform: translateY(-1px);
    }

    .vut-filters-grid {
        display: grid;
        grid-template-columns: 2fr 1.2fr 1.7fr 1.4fr .8fr auto;
        gap: 12px;
        align-items: end;
    }

    .vut-field label {
        display: block;
        margin-bottom: 7px;
        font-size: 10px;
        color: #9ca3af;
        font-weight: 950;
        letter-spacing: .16em;
        text-transform: uppercase;
    }

    .vut-control {
        width: 100%;
        min-height: 43px;
        border: 1px solid #e5e7eb;
        border-radius: 15px;
        background: #fff;
        color: #111827;
        padding: 10px 12px;
        font-size: 13px;
        font-weight: 800;
        outline: none;
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .vut-control:focus {
        border-color: var(--vut-guinda);
        box-shadow: 0 0 0 4px rgba(119, 51, 87, .10);
    }

    .vut-date-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .vut-table-card {
        overflow: hidden;
    }

    .vut-table-header {
        padding: 18px 20px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
    }

    .vut-table-title {
        margin: 0;
        color: var(--vut-guinda);
        font-size: 16px;
        font-weight: 950;
        letter-spacing: -.01em;
        text-transform: uppercase;
    }

    .vut-table-info {
        margin: 4px 0 0;
        color: #9ca3af;
        font-size: 12px;
        font-weight: 800;
    }

    .vut-table-tools {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .vut-table-wrap {
        width: 100%;
        overflow-x: auto;
        min-height: 360px;
        background:
            linear-gradient(to right, #fff 30%, rgba(255,255,255,0)),
            linear-gradient(to right, rgba(255,255,255,0), #fff 70%) 100% 0,
            radial-gradient(farthest-side at 0 50%, rgba(119,51,87,.10), rgba(119,51,87,0)),
            radial-gradient(farthest-side at 100% 50%, rgba(119,51,87,.10), rgba(119,51,87,0)) 100% 0;
        background-repeat: no-repeat;
        background-size: 40px 100%, 40px 100%, 14px 100%, 14px 100%;
        background-attachment: local, local, scroll, scroll;
    }

    .vut-table {
        width: 100%;
        min-width: 1180px;
        border-collapse: separate;
        border-spacing: 0;
    }

    .vut-table thead th {
        background: #fbfbfd;
        color: #6b7280;
        font-size: 10px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: .13em;
        padding: 14px 14px;
        border-bottom: 1px solid #eef2f7;
        position: sticky;
        top: 0;
        z-index: 2;
        text-align: left;
    }

    .vut-table thead th:last-child {
        text-align: right;
    }

    .vut-table tbody td {
        padding: 14px;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
        background: #fff;
    }

    .vut-table tbody tr {
        transition: background .16s ease;
    }

    .vut-table tbody tr:hover td {
        background: #fcf7f9;
    }

    .vut-folio-btn {
        background: transparent;
        border: 0;
        padding: 0;
        color: var(--vut-guinda);
        font-weight: 950;
        font-size: 13px;
        cursor: pointer;
        text-align: left;
    }

    .vut-folio-btn:hover {
        text-decoration: underline;
    }

    .vut-mini {
        font-size: 10px;
        color: #9ca3af;
        font-weight: 800;
        margin-top: 3px;
    }

    .vut-strong {
        color: #111827;
        font-size: 13px;
        font-weight: 900;
    }

    .vut-truncate {
        display: block;
        max-width: 340px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .estado-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        padding: 8px 11px;
        font-size: 10px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: .07em;
        white-space: nowrap;
    }

    .estado-NUEVO { background:#f3f4f6; color:#374151; }
    .estado-INGRESADO { background:#dbeafe; color:#1d4ed8; }
    .estado-EN_VALIDACION { background:#e0f2fe; color:#0369a1; }
    .estado-PREVENIDO { background:#fef3c7; color:#92400e; }
    .estado-EN_REVISION { background:#ede9fe; color:#6d28d9; }
    .estado-APROBADO { background:#dcfce7; color:#166534; }
    .estado-RECHAZADO { background:#fee2e2; color:#991b1b; }
    .estado-TERMINADO { background:#ccfbf1; color:#0f766e; }
    .estado-CANCELADO { background:#f3f4f6; color:#6b7280; }

    .vut-row-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        flex-wrap: nowrap;
    }

    .vut-action {
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        color: #374151;
        width: 38px;
        height: 38px;
        border-radius: 13px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 15px;
        transition: all .18s ease;
    }

    .vut-action:hover {
        background: #fff;
        color: var(--vut-guinda);
        border-color: var(--vut-border);
        transform: translateY(-1px);
    }

    .vut-action.pdf {
        color: #991b1b;
        background: #fff7f7;
        border-color: #fee2e2;
    }

    .vut-action.state {
        color: #0369a1;
        background: #f0f9ff;
        border-color: #dbeafe;
    }

    .vut-empty {
        padding: 52px 20px !important;
        text-align: center !important;
        color: #9ca3af !important;
        font-weight: 900 !important;
        background: #fff !important;
    }

    .vut-skeleton {
        display: inline-block;
        height: 12px;
        border-radius: 999px;
        background: linear-gradient(90deg,#f3f4f6,#e5e7eb,#f3f4f6);
        background-size: 200% 100%;
        animation: vutShimmer 1.2s infinite;
    }

    @keyframes vutShimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .vut-pagination-bar {
        padding: 14px 18px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        background: #fff;
    }

    .vut-pagination-info {
        color: #6b7280;
        font-size: 12px;
        font-weight: 850;
    }

    .vut-pagination {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 7px;
    }

    .vut-page-btn {
        min-width: 38px;
        height: 38px;
        padding: 0 12px;
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #374151;
        border-radius: 12px;
        cursor: pointer;
        font-size: 11px;
        font-weight: 950;
        text-transform: uppercase;
        transition: all .18s ease;
    }

    .vut-page-btn:hover:not(:disabled) {
        color: var(--vut-guinda);
        border-color: var(--vut-border);
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(17,24,39,.06);
    }

    .vut-page-btn.active {
        background: var(--vut-guinda);
        color: #fff;
        border-color: var(--vut-guinda);
    }

    .vut-page-btn:disabled {
        opacity: .42;
        cursor: not-allowed;
    }

    .vut-page-dots {
        min-width: 32px;
        text-align: center;
        color: #9ca3af;
        font-weight: 950;
    }

    .vut-page-jump {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #6b7280;
        font-size: 11px;
        font-weight: 900;
    }

    .vut-page-jump input {
        width: 72px;
        height: 38px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        text-align: center;
        font-weight: 900;
        outline: none;
    }

    .vut-page-jump input:focus {
        border-color: var(--vut-guinda);
        box-shadow: 0 0 0 4px rgba(119, 51, 87, .10);
    }

    .swal2-popup.vut-swal-popup {
        border-radius: 28px !important;
        font-family: 'Montserrat', Arial, sans-serif !important;
    }

    .vut-swal-confirm {
        background: var(--vut-guinda) !important;
        color: #fff !important;
        border-radius: 14px !important;
        padding: .78rem 1.28rem !important;
        font-weight: 950 !important;
        text-transform: uppercase !important;
        font-size: 11px !important;
        letter-spacing: .08em !important;
    }

    .vut-swal-cancel {
        background: #f3f4f6 !important;
        color: #4b5563 !important;
        border-radius: 14px !important;
        padding: .78rem 1.28rem !important;
        font-weight: 950 !important;
        text-transform: uppercase !important;
        font-size: 11px !important;
        letter-spacing: .08em !important;
    }

    .vut-swal-deny {
        background: #f0f9ff !important;
        color: #0369a1 !important;
        border-radius: 14px !important;
        padding: .78rem 1.28rem !important;
        font-weight: 950 !important;
        text-transform: uppercase !important;
        font-size: 11px !important;
        letter-spacing: .08em !important;
    }

    .vut-detail-tabs {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        margin-bottom: 16px;
        padding-bottom: 4px;
    }

    .vut-detail-tab {
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #4b5563;
        border-radius: 999px;
        padding: 9px 12px;
        font-size: 10px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: .08em;
        white-space: nowrap;
        cursor: pointer;
    }

    .vut-detail-tab.active {
        background: var(--vut-guinda);
        color: #fff;
        border-color: var(--vut-guinda);
    }

    .vut-detail-panel { display: none; }
    .vut-detail-panel.active { display: block; }

    .vut-detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .vut-detail-field {
        border: 1px solid #eef2f7;
        background: #fff;
        border-radius: 15px;
        padding: 11px 12px;
        min-height: 64px;
    }

    .vut-detail-field .label {
        display:block;
        font-size: 9px;
        font-weight: 950;
        color: #9ca3af;
        letter-spacing: .12em;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .vut-detail-field .value {
        color: #111827;
        font-size: 12px;
        font-weight: 850;
        word-break: break-word;
        text-transform: uppercase;
        line-height: 1.35;
    }

    .vut-detail-section-title {
        font-size: 11px;
        font-weight: 950;
        color: var(--vut-guinda);
        text-transform: uppercase;
        letter-spacing: .10em;
        margin: 14px 0 8px;
    }


    @media (max-width: 1440px) {
        .vut-kpi-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .vut-filters-grid { grid-template-columns: 1.6fr 1fr 1.4fr 1.2fr .8fr; }
        .vut-filters-grid .vut-filter-action { grid-column: 1 / -1; }
    }

    @media (max-width: 900px) {
        .vut-dashboard { padding: 14px; }
        .vut-hero-inner { flex-direction: column; align-items: flex-start; }
        .vut-actions { justify-content: flex-start; width: 100%; }
        .vut-kpi-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .vut-filters-grid { grid-template-columns: 1fr; }
        .vut-pagination-bar { flex-direction: column; align-items: stretch; }
        .vut-pagination { justify-content: flex-start; }
        .vut-detail-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 520px) {
        .vut-kpi-grid { grid-template-columns: 1fr; }
        .vut-date-grid { grid-template-columns: 1fr; }
        .vut-table-header { flex-direction: column; align-items: flex-start; }
    }
</style>

<div class="vut-dashboard">
    <section class="vut-hero">
        <div class="vut-hero-inner">
            <div>
                <div class="vut-eyebrow">Ventanilla Única de Trámites</div>
                <h1 class="vut-title">Bandeja de seguimiento</h1>
                <p class="vut-subtitle">
                    Control operativo de solicitudes, estados, aprobaciones, prevenciones, acuses PDF e historial de movimientos.
                </p>
            </div>

            <div class="vut-actions">
                <button type="button" onclick="vutDashboardReload()" class="vut-btn vut-btn-light">↻ Sincronizar</button>
                <a href="index.php?route=ventanilla/nueva" class="vut-btn vut-btn-primary">+ Nueva solicitud</a>
            </div>
        </div>
    </section>

    <section class="vut-kpi-grid">
        <div onclick="vutSetEstadoFiltro('')" class="vut-kpi">
            <p class="vut-kpi-label">Total</p>
            <h3 id="kpi-total" class="vut-kpi-value">0</h3>
            <p class="vut-kpi-help">Solicitudes registradas</p>
        </div>
        <div onclick="vutSetEstadoFiltro('INGRESADO')" class="vut-kpi vut-kpi-blue">
            <p class="vut-kpi-label">Ingresadas</p>
            <h3 id="kpi-ingresado" class="vut-kpi-value">0</h3>
            <p class="vut-kpi-help">Acuse generado</p>
        </div>
        <div onclick="vutSetEstadoFiltro('EN_VALIDACION')" class="vut-kpi vut-kpi-sky">
            <p class="vut-kpi-label">Validación</p>
            <h3 id="kpi-validacion" class="vut-kpi-value">0</h3>
            <p class="vut-kpi-help">Revisión documental</p>
        </div>
        <div onclick="vutSetEstadoFiltro('EN_REVISION')" class="vut-kpi vut-kpi-purple">
            <p class="vut-kpi-label">En revisión</p>
            <h3 id="kpi-revision" class="vut-kpi-value">0</h3>
            <p class="vut-kpi-help">Área técnica / jurídica</p>
        </div>
        <div onclick="vutSetEstadoFiltro('PREVENIDO')" class="vut-kpi vut-kpi-amber">
            <p class="vut-kpi-label">Prevenidas</p>
            <h3 id="kpi-prevenido" class="vut-kpi-value">0</h3>
            <p class="vut-kpi-help">Requieren corrección</p>
        </div>
        <div onclick="vutSetEstadoFiltro('APROBADO')" class="vut-kpi vut-kpi-green">
            <p class="vut-kpi-label">Aprobadas</p>
            <h3 id="kpi-aprobado" class="vut-kpi-value">0</h3>
            <p class="vut-kpi-help">Con visto bueno</p>
        </div>
        <div onclick="vutSetEstadoFiltro('RECHAZADO')" class="vut-kpi vut-kpi-red">
            <p class="vut-kpi-label">Rechazadas</p>
            <h3 id="kpi-rechazado" class="vut-kpi-value">0</h3>
            <p class="vut-kpi-help">No procedentes</p>
        </div>
        <div onclick="vutSetEstadoFiltro('TERMINADO')" class="vut-kpi vut-kpi-teal">
            <p class="vut-kpi-label">Terminadas</p>
            <h3 id="kpi-terminado" class="vut-kpi-value">0</h3>
            <p class="vut-kpi-help">Cerradas</p>
        </div>
    </section>

    <section class="vut-card">
        <div class="vut-card-body">
            <div class="vut-chips" id="vut-state-chips">
                <button type="button" class="vut-chip active" data-estado="" onclick="vutSetEstadoFiltro('')">Todos</button>
                <?php foreach ($estados as $key => $label): ?>
                    <button
                        type="button"
                        class="vut-chip"
                        data-estado="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"
                        onclick="vutSetEstadoFiltro('<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>')"
                    >
                        <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="vut-filters-grid">
                <div class="vut-field">
                    <label>Buscar</label>
                    <input id="filtro-q" class="vut-control" placeholder="Folio, interesado, RFC o trámite">
                </div>

                <div class="vut-field">
                    <label>Materia</label>
                    <select id="filtro-materia" class="vut-control">
                        <option value="">Todas</option>
                        <?php foreach ($materias as $materia): ?>
                            <option value="<?= htmlspecialchars($materia, ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($materia, ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="vut-field">
                    <label>Trámite</label>
                    <select id="filtro-tramite" class="vut-control">
                        <option value="">Todos</option>
                        <?php foreach ($tramites as $tramite): ?>
                            <option value="<?= htmlspecialchars($tramite, ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($tramite, ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="vut-field">
                    <label>Rango de fechas</label>
                    <div class="vut-date-grid">
                        <input id="filtro-fecha-inicio" type="date" class="vut-control" title="Fecha inicial">
                        <input id="filtro-fecha-fin" type="date" class="vut-control" title="Fecha final">
                    </div>
                </div>

                <div class="vut-field">
                    <label>Mostrar</label>
                    <select id="filtro-limit" class="vut-control">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>

                <div class="vut-filter-action">
                    <button type="button" onclick="vutDashboardBuscar()" class="vut-btn vut-btn-primary" style="width:100%;">Filtrar</button>
                </div>
            </div>
        </div>
    </section>

    <section class="vut-card vut-table-card">
        <div class="vut-table-header">
            <div>
                <h2 class="vut-table-title">Trámites registrados</h2>
                <p id="table-info" class="vut-table-info">Cargando solicitudes...</p>
            </div>

            <div class="vut-table-tools">
                <button type="button" onclick="vutLimpiarFiltros()" class="vut-btn vut-btn-light">Limpiar filtros</button>
                <button type="button" onclick="vutDashboardReload()" class="vut-btn vut-btn-light">Actualizar</button>
            </div>
        </div>

        <div class="vut-table-wrap">
            <table class="vut-table">
                <thead>
                    <tr>
                        <th style="width:150px;">Folio</th>
                        <th style="width:260px;">Interesado</th>
                        <th>Materia / trámite</th>
                        <th style="width:250px;">Modalidad</th>
                        <th style="width:180px;">Estado</th>
                        <th style="width:120px;">Fecha</th>
                        <th style="width:215px;">Acciones</th>
                    </tr>
                </thead>
                <tbody id="vut-dashboard-tbody">
                    <tr>
                        <td colspan="7" class="vut-empty">Cargando...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="vut-pagination-bar">
            <div id="vut-pagination-info" class="vut-pagination-info"></div>
            <div id="vut-pagination" class="vut-pagination"></div>
        </div>
    </section>
</div>

<script>
    window.VUT_DASHBOARD = {
        page: 1,
        limit: 10,
        total: 0,
        pages: 1,
        rows: [],
        allRows: [],
        loading: false,
        clientMode: false,
        estadoFiltro: '',
        puedeAprobar: <?= $puedeAprobar ? 'true' : 'false' ?>,
        estados: <?= json_encode($estados, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
    };

    function vutDashEsc(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function vutSwalConfig(extra = {}) {
        return {
            buttonsStyling: false,
            reverseButtons: true,
            customClass: {
                popup: 'vut-swal-popup',
                confirmButton: 'vut-swal-confirm',
                cancelButton: 'vut-swal-cancel',
                denyButton: 'vut-swal-deny'
            },
            ...extra
        };
    }

    function estadoLabel(estado) {
        return window.VUT_DASHBOARD.estados[estado] || estado || 'NUEVO';
    }

    function formatFecha(fecha, withTime = false) {
        if (!fecha) return 'S/F';

        const str = String(fecha).replace(' ', 'T');
        const d = new Date(str);

        if (Number.isNaN(d.getTime())) {
            return fecha;
        }

        return d.toLocaleDateString('es-MX') + (
            withTime
                ? ' ' + d.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' })
                : ''
        );
    }

    function getLimit() {
        return Math.max(1, parseInt(document.getElementById('filtro-limit')?.value || window.VUT_DASHBOARD.limit || 10, 10));
    }

    function getEstadoFiltro() {
        return window.VUT_DASHBOARD.estadoFiltro || '';
    }

    function buildQueryParams() {
        const limit = getLimit();
        window.VUT_DASHBOARD.limit = limit;

        const params = new URLSearchParams();
        params.set('route', 'ventanilla/dashboardData');
        params.set('page', window.VUT_DASHBOARD.page);
        params.set('limit', limit);
        params.set('q', document.getElementById('filtro-q')?.value || '');
        params.set('materia', document.getElementById('filtro-materia')?.value || '');
        params.set('tramite', document.getElementById('filtro-tramite')?.value || '');
        params.set('estado', getEstadoFiltro());
        params.set('fecha_inicio', document.getElementById('filtro-fecha-inicio')?.value || '');
        params.set('fecha_fin', document.getElementById('filtro-fecha-fin')?.value || '');

        return params.toString();
    }

    function renderLoadingRows() {
        const tbody = document.getElementById('vut-dashboard-tbody');
        const rows = Array.from({ length: 7 }).map(() => `
            <tr>
                <td><span class="vut-skeleton" style="width:110px"></span><div><span class="vut-skeleton" style="width:46px;margin-top:8px"></span></div></td>
                <td><span class="vut-skeleton" style="width:190px"></span><div><span class="vut-skeleton" style="width:105px;margin-top:8px"></span></div></td>
                <td><span class="vut-skeleton" style="width:220px"></span><div><span class="vut-skeleton" style="width:360px;margin-top:8px"></span></div></td>
                <td><span class="vut-skeleton" style="width:160px"></span></td>
                <td><span class="vut-skeleton" style="width:120px"></span></td>
                <td><span class="vut-skeleton" style="width:82px"></span></td>
                <td style="text-align:right;"><span class="vut-skeleton" style="width:150px"></span></td>
            </tr>
        `).join('');

        tbody.innerHTML = rows;
    }

    function normalizarRespuestaDashboard(data) {
        const rows = Array.isArray(data.rows)
            ? data.rows
            : (Array.isArray(data.data) ? data.data : []);

        const limit = Number(data.limit || window.VUT_DASHBOARD.limit || getLimit());
        const page = Number(data.page || window.VUT_DASHBOARD.page || 1);

        let total = Number(data.total || 0);
        let pages = Number(data.pages || data.total_pages || 0);

        /**
         * Modo compatible:
         * Si el backend no manda total/pages pero manda muchas filas, paginamos del lado del front.
         */
        let clientMode = false;

        if ((!total || !pages) && rows.length > limit) {
            clientMode = true;
            total = rows.length;
            pages = Math.max(1, Math.ceil(total / limit));
        } else {
            total = total || rows.length;
            pages = pages || Math.max(1, Math.ceil(total / limit));
        }

        return {
            rows,
            page: Math.max(1, page),
            limit,
            total,
            pages: Math.max(1, pages),
            summary: data.summary || {},
            clientMode
        };
    }

    function cortarRowsCliente(rows, page, limit) {
        const start = (page - 1) * limit;
        return rows.slice(start, start + limit);
    }

    async function cargarDashboardVUT() {
        if (window.VUT_DASHBOARD.loading) return;

        window.VUT_DASHBOARD.loading = true;
        renderLoadingRows();

        try {
            const response = await fetch('index.php?' + buildQueryParams(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.error || 'No se pudo cargar el dashboard.');
            }

            const normalized = normalizarRespuestaDashboard(data);

            window.VUT_DASHBOARD.clientMode = normalized.clientMode;
            window.VUT_DASHBOARD.allRows = normalized.clientMode ? normalized.rows : [];
            window.VUT_DASHBOARD.page = normalized.page;
            window.VUT_DASHBOARD.limit = normalized.limit;
            window.VUT_DASHBOARD.total = normalized.total;
            window.VUT_DASHBOARD.pages = normalized.pages;

            const rowsToRender = normalized.clientMode
                ? cortarRowsCliente(normalized.rows, normalized.page, normalized.limit)
                : normalized.rows;

            window.VUT_DASHBOARD.rows = rowsToRender;

            renderKPIs(normalized.summary);
            renderRows(rowsToRender);
            renderPagination(normalized.page, normalized.pages, normalized.total, normalized.limit);
        } catch (error) {
            console.error(error);
            document.getElementById('vut-dashboard-tbody').innerHTML = `
                <tr>
                    <td colspan="7" class="vut-empty" style="color:#991b1b !important;">
                        ${vutDashEsc(error.message)}
                    </td>
                </tr>
            `;
            renderPagination(1, 1, 0, getLimit());
        } finally {
            window.VUT_DASHBOARD.loading = false;
        }
    }

    function renderKPIs(summary) {
        document.getElementById('kpi-total').innerText = summary.TOTAL || 0;
        document.getElementById('kpi-ingresado').innerText = summary.INGRESADO || 0;
        document.getElementById('kpi-validacion').innerText = summary.EN_VALIDACION || 0;
        document.getElementById('kpi-revision').innerText = summary.EN_REVISION || 0;
        document.getElementById('kpi-prevenido').innerText = summary.PREVENIDO || 0;
        document.getElementById('kpi-aprobado').innerText = summary.APROBADO || 0;
        document.getElementById('kpi-rechazado').innerText = summary.RECHAZADO || 0;
        document.getElementById('kpi-terminado').innerText = summary.TERMINADO || 0;
    }

    function renderRows(rows) {
        const tbody = document.getElementById('vut-dashboard-tbody');

        if (!Array.isArray(rows) || !rows.length) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="vut-empty">
                        No hay trámites con los filtros seleccionados.
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = rows.map(row => {
            const id = Number(row.id_solicitud || row.id || 0);
            const estado = row.estado_proceso || 'NUEVO';
            const modalidad = [row.modalidad_texto, row.detalle_texto].filter(Boolean).join(' / ') || 'SIN MODALIDAD';
            const fecha = formatFecha(row.fecha_ingreso || row.fecha_creacion || row.created_at);
            const titular = row.titular && String(row.titular).trim() !== '' ? row.titular : 'SIN INTERESADO';
            const telefono = row.telefono ? `<div class="vut-mini">TEL. ${vutDashEsc(row.telefono)}</div>` : '';
            const prioridad = row.prioridad && row.prioridad !== 'NORMAL'
                ? `<div class="vut-mini" style="color:#92400e;">PRIORIDAD: ${vutDashEsc(row.prioridad)}</div>`
                : '';

            return `
                <tr>
                    <td>
                        <button type="button" onclick="verDetalleSolicitud(${id})" class="vut-folio-btn">
                            ${vutDashEsc(row.folio || 'S/F')}
                        </button>
                        <div class="vut-mini">ID ${vutDashEsc(id)}</div>
                    </td>

                    <td>
                        <div class="vut-strong vut-truncate" title="${vutDashEsc(titular)}">${vutDashEsc(titular)}</div>
                        <div class="vut-mini">${vutDashEsc(row.rfc || 'SIN RFC')}</div>
                        ${telefono}
                    </td>

                    <td>
                        <div class="vut-strong vut-truncate" title="${vutDashEsc(row.materia || 'SIN MATERIA')}">
                            ${vutDashEsc(row.materia || 'SIN MATERIA')}
                        </div>
                        <div class="vut-mini vut-truncate" style="max-width:420px;" title="${vutDashEsc(row.nombre_tramite || 'SIN TRÁMITE')}">
                            ${vutDashEsc(row.nombre_tramite || 'SIN TRÁMITE')}
                        </div>
                        ${prioridad}
                    </td>

                    <td>
                        <div class="vut-mini vut-truncate" style="max-width:240px;" title="${vutDashEsc(modalidad)}">
                            ${vutDashEsc(modalidad)}
                        </div>
                    </td>

                    <td>
                        <span class="estado-pill estado-${vutDashEsc(estado)}">● ${vutDashEsc(estadoLabel(estado))}</span>
                    </td>

                    <td>
                        <div class="vut-strong" style="font-size:12px;">${vutDashEsc(fecha)}</div>
                    </td>

                    <td>
                        <div class="vut-row-actions">
                            <a
                                class="vut-action"
                                title="Editar / continuar captura"
                                href="index.php?route=ventanilla/editar&id=${encodeURIComponent(id)}"
                            >✏️</a>

                            <a
                                class="vut-action pdf"
                                target="_blank"
                                title="Abrir PDF"
                                href="index.php?route=ventanilla/generarComprobante&id=${encodeURIComponent(id)}"
                            >📄</a>

                            <button
                                type="button"
                                class="vut-action"
                                title="Ver detalle completo"
                                onclick="verDetalleSolicitud(${id})"
                            >👁</button>

                            <button
                                type="button"
                                class="vut-action state"
                                title="Cambiar estado"
                                onclick="abrirCambioEstado(${id}, '${vutDashEsc(estado)}')"
                            >↺</button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function paginationNumbers(page, pages) {
        const nums = [];
        const add = value => {
            if (value >= 1 && value <= pages && !nums.includes(value)) {
                nums.push(value);
            }
        };

        add(1);
        add(2);

        for (let i = page - 2; i <= page + 2; i++) {
            add(i);
        }

        add(pages - 1);
        add(pages);

        nums.sort((a, b) => a - b);

        const output = [];

        nums.forEach((num, idx) => {
            if (idx > 0 && num - nums[idx - 1] > 1) {
                output.push('...');
            }

            output.push(num);
        });

        return output;
    }

    function renderPagination(page, pages, total, limit) {
        page = Math.max(1, Number(page || 1));
        pages = Math.max(1, Number(pages || 1));
        total = Math.max(0, Number(total || 0));
        limit = Math.max(1, Number(limit || 10));

        if (page > pages) {
            page = pages;
        }

        window.VUT_DASHBOARD.page = page;
        window.VUT_DASHBOARD.pages = pages;
        window.VUT_DASHBOARD.total = total;
        window.VUT_DASHBOARD.limit = limit;

        const start = total === 0 ? 0 : ((page - 1) * limit) + 1;
        const end = Math.min(total, page * limit);

        document.getElementById('table-info').innerText = `Mostrando ${start}-${end} de ${total} solicitud(es)`;
        document.getElementById('vut-pagination-info').innerText = `Página ${page} de ${pages} · ${limit} por página`;

        const container = document.getElementById('vut-pagination');

        let html = '';

        html += `<button type="button" class="vut-page-btn" ${page <= 1 ? 'disabled' : ''} onclick="vutDashboardGo(1)">Primera</button>`;
        html += `<button type="button" class="vut-page-btn" ${page <= 1 ? 'disabled' : ''} onclick="vutDashboardGo(${page - 1})">Anterior</button>`;

        paginationNumbers(page, pages).forEach(item => {
            if (item === '...') {
                html += `<span class="vut-page-dots">...</span>`;
            } else {
                html += `<button type="button" class="vut-page-btn ${item === page ? 'active' : ''}" onclick="vutDashboardGo(${item})">${item}</button>`;
            }
        });

        html += `<button type="button" class="vut-page-btn" ${page >= pages ? 'disabled' : ''} onclick="vutDashboardGo(${page + 1})">Siguiente</button>`;
        html += `<button type="button" class="vut-page-btn" ${page >= pages ? 'disabled' : ''} onclick="vutDashboardGo(${pages})">Última</button>`;

        html += `
            <div class="vut-page-jump">
                <span>Ir a</span>
                <input id="vut-page-jump-input" type="number" min="1" max="${pages}" value="${page}" onkeydown="vutPageJumpKey(event)">
            </div>
        `;

        container.innerHTML = html;
    }

    function vutPageJumpKey(event) {
        if (event.key !== 'Enter') return;

        const value = Number(event.target.value || 1);
        const pages = window.VUT_DASHBOARD.pages || 1;

        vutDashboardGo(Math.min(Math.max(1, value), pages));
    }

    function vutDashboardGo(page) {
        page = Number(page || 1);

        if (page < 1 || page > window.VUT_DASHBOARD.pages) {
            return;
        }

        window.VUT_DASHBOARD.page = page;

        if (window.VUT_DASHBOARD.clientMode && window.VUT_DASHBOARD.allRows.length) {
            const rows = cortarRowsCliente(window.VUT_DASHBOARD.allRows, page, window.VUT_DASHBOARD.limit);
            window.VUT_DASHBOARD.rows = rows;
            renderRows(rows);
            renderPagination(page, window.VUT_DASHBOARD.pages, window.VUT_DASHBOARD.total, window.VUT_DASHBOARD.limit);
            return;
        }

        cargarDashboardVUT();
    }

    function cortarRowsCliente(rows, page, limit) {
        const start = (page - 1) * limit;
        return rows.slice(start, start + limit);
    }

    function vutDashboardBuscar() {
        window.VUT_DASHBOARD.page = 1;
        cargarDashboardVUT();
    }

    function vutDashboardReload() {
        cargarDashboardVUT();
    }

    function vutLimpiarFiltros() {
        ['filtro-q', 'filtro-materia', 'filtro-tramite', 'filtro-fecha-inicio', 'filtro-fecha-fin'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });

        const limit = document.getElementById('filtro-limit');
        if (limit) limit.value = '10';

        vutSetEstadoFiltro('', false);

        window.VUT_DASHBOARD.page = 1;
        window.VUT_DASHBOARD.limit = 10;

        cargarDashboardVUT();
    }

    function vutSetEstadoFiltro(estado, reload = true) {
        window.VUT_DASHBOARD.estadoFiltro = estado || '';

        document.querySelectorAll('.vut-chip').forEach(chip => {
            chip.classList.toggle('active', (chip.dataset.estado || '') === (estado || ''));
        });

        if (reload) {
            vutDashboardBuscar();
        }
    }

    async function abrirCambioEstado(id, estadoActual) {
        const puedeAprobar = window.VUT_DASHBOARD.puedeAprobar;

        const estados = Object.entries(window.VUT_DASHBOARD.estados)
            .filter(([key]) => puedeAprobar || !['APROBADO', 'RECHAZADO', 'TERMINADO', 'CANCELADO'].includes(key));

        const options = estados.map(([key, label]) => `
            <option value="${vutDashEsc(key)}" ${key === estadoActual ? 'selected' : ''}>
                ${vutDashEsc(key === 'APROBADO' ? 'Autorizado' : label)}
            </option>
        `).join('');

        const estadoRequiereFirmaRecibido = (estado) => ['APROBADO', 'AUTORIZADO'].includes(String(estado || '').toUpperCase());
        let firmaCanvas = null;
        let firmaCtx = null;
        let firmaDibujando = false;
        let firmaTieneTrazo = false;

        const prepararCanvasFirmaRecibido = () => {
            firmaCanvas = document.getElementById('swal-firma-recibido-canvas');
            if (!firmaCanvas) return;

            const rect = firmaCanvas.getBoundingClientRect();
            const ratio = Math.max(window.devicePixelRatio || 1, 1);

            firmaCanvas.width = Math.floor(rect.width * ratio);
            firmaCanvas.height = Math.floor(rect.height * ratio);

            firmaCtx = firmaCanvas.getContext('2d');
            firmaCtx.setTransform(ratio, 0, 0, ratio, 0, 0);
            firmaCtx.lineWidth = 2.2;
            firmaCtx.lineCap = 'round';
            firmaCtx.lineJoin = 'round';
            firmaCtx.strokeStyle = '#111827';
            firmaCtx.fillStyle = '#ffffff';
            firmaCtx.fillRect(0, 0, rect.width, rect.height);

            const getPos = (evt) => {
                const r = firmaCanvas.getBoundingClientRect();
                const p = evt.touches && evt.touches.length ? evt.touches[0] : evt;
                return {
                    x: p.clientX - r.left,
                    y: p.clientY - r.top
                };
            };

            const iniciar = (evt) => {
                evt.preventDefault();
                firmaDibujando = true;
                const pos = getPos(evt);
                firmaCtx.beginPath();
                firmaCtx.moveTo(pos.x, pos.y);
            };

            const dibujar = (evt) => {
                if (!firmaDibujando) return;
                evt.preventDefault();
                const pos = getPos(evt);
                firmaCtx.lineTo(pos.x, pos.y);
                firmaCtx.stroke();
                firmaTieneTrazo = true;
            };

            const terminar = (evt) => {
                if (evt) evt.preventDefault();
                firmaDibujando = false;
            };

            firmaCanvas.addEventListener('mousedown', iniciar);
            firmaCanvas.addEventListener('mousemove', dibujar);
            firmaCanvas.addEventListener('mouseup', terminar);
            firmaCanvas.addEventListener('mouseleave', terminar);
            firmaCanvas.addEventListener('touchstart', iniciar, { passive: false });
            firmaCanvas.addEventListener('touchmove', dibujar, { passive: false });
            firmaCanvas.addEventListener('touchend', terminar, { passive: false });

            const limpiarBtn = document.getElementById('swal-limpiar-firma-recibido');
            if (limpiarBtn) {
                limpiarBtn.addEventListener('click', () => {
                    const r = firmaCanvas.getBoundingClientRect();
                    firmaCtx.fillStyle = '#ffffff';
                    firmaCtx.fillRect(0, 0, r.width, r.height);
                    firmaTieneTrazo = false;
                });
            }
        };

        const actualizarBloqueFirmaRecibido = () => {
            const estado = document.getElementById('swal-estado')?.value || '';
            const bloque = document.getElementById('bloque-firma-recibido');
            const ayuda = document.getElementById('swal-firma-recibido-ayuda');

            if (!bloque) return;

            const mostrar = estadoRequiereFirmaRecibido(estado);
            bloque.style.display = mostrar ? 'block' : 'none';

            if (ayuda) {
                ayuda.textContent = mostrar
                    ? 'Para autorizar el trámite es obligatorio capturar la firma de recibido. Esta firma se anexará al acuse.'
                    : '';
            }
        };

        const res = await Swal.fire(vutSwalConfig({
            icon: 'question',
            title: 'Cambiar estado',
            width: 780,
            html: `
                <div style="text-align:left;">
                    <p style="font-size:13px;color:#6b7280;font-weight:700;margin-bottom:14px;">
                        El movimiento quedará registrado en el historial del trámite.
                    </p>

                    <label style="font-size:11px;font-weight:900;color:#773357;text-transform:uppercase;">
                        Nuevo estado
                    </label>
                    <select id="swal-estado" class="swal2-input" style="width:100%;margin:8px 0 14px 0;">
                        ${options}
                    </select>

                    <label style="font-size:11px;font-weight:900;color:#773357;text-transform:uppercase;">
                        Observaciones
                    </label>
                    <textarea
                        id="swal-observaciones"
                        class="swal2-textarea"
                        style="width:100%;margin:8px 0 14px 0;"
                        placeholder="Motivo, nota o comentario para historial"
                    ></textarea>

                    <div id="bloque-firma-recibido" style="display:none;margin-top:10px;padding:14px;border:1px solid #ead9e2;border-radius:16px;background:#fcf7f9;">
                        <div style="font-size:12px;font-weight:900;color:#773357;text-transform:uppercase;margin-bottom:8px;">
                            Firma de recibido para autorización
                        </div>
                        <p id="swal-firma-recibido-ayuda" style="margin:0 0 12px 0;font-size:12px;color:#6b7280;font-weight:700;line-height:1.45;"></p>

                        <label style="font-size:11px;font-weight:900;color:#773357;text-transform:uppercase;">
                            Nombre de quien recibe
                        </label>
                        <input
                            id="swal-firma-recibido-nombre"
                            class="swal2-input"
                            style="width:100%;margin:8px 0 12px 0;"
                            placeholder="Nombre completo"
                        >

                        <label style="font-size:11px;font-weight:900;color:#773357;text-transform:uppercase;">
                            Firma de recibido
                        </label>
                        <div style="background:#fff;border:1px dashed #c9a9bb;border-radius:14px;padding:8px;margin-top:8px;">
                            <canvas
                                id="swal-firma-recibido-canvas"
                                style="display:block;width:100%;height:170px;touch-action:none;border-radius:10px;background:#fff;"
                            ></canvas>
                        </div>

                        <button
                            type="button"
                            id="swal-limpiar-firma-recibido"
                            class="swal2-styled"
                            style="margin:10px 0 0 0;background:#6b7280;font-size:11px;padding:8px 14px;"
                        >
                            Limpiar firma
                        </button>

                        <label style="display:block;margin-top:12px;font-size:11px;font-weight:900;color:#773357;text-transform:uppercase;">
                            Observaciones de recibido opcionales
                        </label>
                        <textarea
                            id="swal-firma-recibido-observaciones"
                            class="swal2-textarea"
                            style="width:100%;margin:8px 0 0 0;min-height:72px;"
                            placeholder="Ej. Recibe resolución/autorización del trámite"
                        ></textarea>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Guardar estado',
            cancelButtonText: 'Cancelar',
            didOpen: () => {
                prepararCanvasFirmaRecibido();
                actualizarBloqueFirmaRecibido();

                const estadoSelect = document.getElementById('swal-estado');
                if (estadoSelect) {
                    estadoSelect.addEventListener('change', actualizarBloqueFirmaRecibido);
                }
            },
            preConfirm: () => {
                const estado = document.getElementById('swal-estado').value;
                const observaciones = document.getElementById('swal-observaciones').value.trim();

                if (['RECHAZADO', 'PREVENIDO', 'CANCELADO'].includes(estado) && observaciones.length < 5) {
                    Swal.showValidationMessage('Agrega una observación o motivo para este estado.');
                    return false;
                }

                let firmaRecibido = null;

                if (estadoRequiereFirmaRecibido(estado)) {
                    const nombre = document.getElementById('swal-firma-recibido-nombre').value.trim();
                    const observacionesFirma = document.getElementById('swal-firma-recibido-observaciones').value.trim();

                    if (nombre.length < 3) {
                        Swal.showValidationMessage('Captura el nombre de quien recibe.');
                        return false;
                    }

                    if (!firmaCanvas || !firmaTieneTrazo) {
                        Swal.showValidationMessage('Captura la firma de recibido para autorizar el trámite.');
                        return false;
                    }

                    firmaRecibido = {
                        imagen: firmaCanvas.toDataURL('image/png'),
                        nombre: nombre,
                        fecha: new Date().toISOString(),
                        observaciones: observacionesFirma
                    };
                }

                return { estado, observaciones, firma_recibido: firmaRecibido };
            }
        }));

        if (!res.isConfirmed) return;

        try {
            Swal.fire(vutSwalConfig({
                title: 'Actualizando...',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            }));

            const response = await fetch('index.php?route=ventanilla/cambiarEstado', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    id_solicitud: id,
                    estado: res.value.estado,
                    observaciones: res.value.observaciones,
                    firma_recibido: res.value.firma_recibido || null
                })
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.error || 'No se pudo actualizar el estado.');
            }

            await Swal.fire(vutSwalConfig({
                icon: 'success',
                title: 'Estado actualizado',
                text: res.value.firma_recibido
                    ? 'El trámite fue autorizado y la firma de recibido fue anexada al acuse.'
                    : 'El trámite fue actualizado correctamente.',
                confirmButtonText: 'Entendido'
            }));

            cargarDashboardVUT();
        } catch (error) {
            Swal.fire(vutSwalConfig({
                icon: 'error',
                title: 'Error',
                text: error.message,
                confirmButtonText: 'Revisar'
            }));
        }
    }

    function labelFromKey(key) {
        return String(key || '')
            .replace(/^INTERESADO_/i, '')
            .replace(/^MORAL_/i, '')
            .replace(/^PREDIO_/i, '')
            .replace(/^MERCADO_/i, '')
            .replace(/^PROPIETARIO_/i, 'PROPIETARIO ')
            .replace(/^LEGAL_/i, '')
            .replace(/^LEG_/i, '')
            .replace(/^AUTORIZADA_/i, '')
            .replace(/^AUT_/i, '')
            .replace(/^BIFURCACION_/i, 'BIFURCACIÓN ')
            .replace(/_/g, ' ')
            .replace(/\s+/g, ' ')
            .trim();
    }

    function asObject(value) {
        return value && typeof value === 'object' && !Array.isArray(value) ? value : {};
    }

    function objectFieldsHtml(obj, empty = 'Sin datos registrados.', omitKeys = []) {
        obj = asObject(obj);

        const omit = omitKeys.map(k => String(k).toUpperCase());

        const rows = Object.entries(obj).filter(([key, value]) => {
            if (omit.includes(String(key).toUpperCase())) return false;
            if (value === null || value === undefined || value === '') return false;
            if (typeof value === 'object') return false;
            return true;
        });

        if (!rows.length) {
            return `<div class="vut-empty" style="padding:24px !important;">${vutDashEsc(empty)}</div>`;
        }

        return `
            <div class="vut-detail-grid">
                ${rows.map(([key, value]) => `
                    <div class="vut-detail-field">
                        <span class="label">${vutDashEsc(labelFromKey(key))}</span>
                        <span class="value">${vutDashEsc(value)}</span>
                    </div>
                `).join('')}
            </div>
        `;
    }

    function detailValue(sources, keys, fallback = '') {
        for (const src of sources) {
            if (!src || typeof src !== 'object') continue;

            for (const key of keys) {
                if (src[key] !== undefined && src[key] !== null && String(src[key]).trim() !== '') {
                    return src[key];
                }
            }
        }

        return fallback;
    }

    function section(title, content) {
        return `
            <div class="vut-detail-section-title">${vutDashEsc(title)}</div>
            ${content}
        `;
    }

    function renderRequisitos(reqs) {
        if (!Array.isArray(reqs) || !reqs.length) {
            return '<div class="vut-empty" style="padding:24px !important;">Sin requisitos registrados.</div>';
        }

        return `
            <div style="display:grid;gap:8px;">
                ${reqs.map((req, index) => `
                    <div class="vut-detail-field" style="min-height:auto;">
                        <span class="label">REQUISITO ${index + 1}</span>
                        <span class="value">${vutDashEsc(req)}</span>
                    </div>
                `).join('')}
            </div>
        `;
    }

    function renderRecibos(recibos) {
        recibos = asObject(recibos);

        const items = [];

        for (let i = 1; i <= 10; i++) {
            const folio = recibos[`FOLIO_RECIBO_${i}`] || recibos[`folio_recibo_${i}`] || '';
            const monto = recibos[`MONTO_RECIBO_${i}`] || recibos[`monto_recibo_${i}`] || '';

            if (String(folio).trim() !== '' || String(monto).trim() !== '') {
                items.push({ i, folio, monto });
            }
        }

        if (!items.length) {
            return '<div class="vut-empty" style="padding:24px !important;">Sin recibos registrados.</div>';
        }

        return `
            <div class="vut-detail-grid">
                ${items.map(recibo => `
                    <div class="vut-detail-field">
                        <span class="label">RECIBO ${recibo.i}</span>
                        <span class="value">
                            FOLIO: ${vutDashEsc(recibo.folio || 'N/A')}<br>
                            MONTO: ${vutDashEsc(recibo.monto || 'N/A')}
                        </span>
                    </div>
                `).join('')}
            </div>
        `;
    }

    function renderFirmas(firmas) {
        firmas = asObject(firmas);

        const capturista = asObject(firmas.capturista);
        const interesado = asObject(firmas.interesado);

        const imgCapturista = capturista.imagen || '';
        const imgInteresado = interesado.imagen || '';

        return `
            <div class="vut-detail-grid">
                <div class="vut-detail-field" style="min-height:145px;">
                    <span class="label">FIRMA DEL CAPTURISTA</span>
                    ${
                        imgCapturista
                            ? `<img src="${vutDashEsc(imgCapturista)}" style="max-width:100%;max-height:92px;object-fit:contain;border:1px solid #e5e7eb;border-radius:12px;background:white;">`
                            : `<span class="value">Sin firma registrada.</span>`
                    }
                    ${capturista.fecha ? `<div class="vut-mini">${vutDashEsc(formatFecha(capturista.fecha, true))}</div>` : ''}
                </div>

                <div class="vut-detail-field" style="min-height:145px;">
                    <span class="label">FIRMA DEL INTERESADO</span>
                    ${
                        imgInteresado
                            ? `<img src="${vutDashEsc(imgInteresado)}" style="max-width:100%;max-height:92px;object-fit:contain;border:1px solid #e5e7eb;border-radius:12px;background:white;">`
                            : `<span class="value">${interesado.no_presente ? 'INTERESADO NO PRESENTE / NO FIRMA' : 'Sin firma registrada.'}</span>`
                    }
                    ${interesado.motivo_no_firma ? `<div class="vut-mini">MOTIVO: ${vutDashEsc(interesado.motivo_no_firma)}</div>` : ''}
                    ${interesado.fecha ? `<div class="vut-mini">${vutDashEsc(formatFecha(interesado.fecha, true))}</div>` : ''}
                </div>
            </div>
        `;
    }

    function renderHistorial(historial) {
        if (!Array.isArray(historial) || !historial.length) {
            return '<div class="vut-empty" style="padding:24px !important;">Sin historial registrado.</div>';
        }

        return `
            <div style="display:grid;gap:9px;">
                ${historial.map(item => `
                    <div class="vut-detail-field" style="min-height:auto;">
                        <span class="label">${vutDashEsc(formatFecha(item.fecha_movimiento || '', true))}</span>
                        <span class="value">${vutDashEsc(item.estado_anterior || 'N/A')} → ${vutDashEsc(item.estado_nuevo || 'N/A')}</span>
                        <div style="font-size:11px;color:#6b7280;font-weight:750;margin-top:4px;">
                            ${vutDashEsc(item.observaciones || 'Sin observaciones.')}
                        </div>
                        ${
                            item.usuario_nombre
                                ? `<div style="font-size:10px;color:#9ca3af;font-weight:900;margin-top:4px;">USUARIO: ${vutDashEsc(item.usuario_nombre)}</div>`
                                : ''
                        }
                    </div>
                `).join('')}
            </div>
        `;
    }

    function activarTabsDetalle() {
        document.querySelectorAll('.vut-detail-tab').forEach(button => {
            button.addEventListener('click', () => {
                const tab = button.dataset.tab;

                document.querySelectorAll('.vut-detail-tab').forEach(btn => {
                    btn.classList.toggle('active', btn.dataset.tab === tab);
                });

                document.querySelectorAll('.vut-detail-panel').forEach(panel => {
                    panel.classList.toggle('active', panel.dataset.panel === tab);
                });
            });
        });
    }

    function buildDetalleHtml(data) {
        const interesado = asObject(data.interesado);
        const base = asObject(interesado.datos);
        const din = asObject(interesado.datos_dinamicos);
        const legal = asObject(data.representante_legal);
        const autorizada = asObject(data.persona_autorizada);
        const especificos = asObject(data.especificos);
        const bif = asObject(data.bifurcacion);
        const recibos = asObject(data.recibos);
        const firmas = asObject(data.firmas);
        const historial = Array.isArray(data._historial_estados) ? data._historial_estados : [];

        const titular = detailValue(
            [din, base, data],
            ['MORAL_RAZON_SOCIAL', 'INTERESADO_NOMBRES', 'NOMBRES', 'titular'],
            data.folio || 'SIN DATO'
        );

        const modalidad = [
            bif.modalidad_texto || data.modalidad_texto,
            bif.detalle_texto || data.detalle_texto
        ].filter(Boolean).join(' / ') || 'SIN MODALIDAD';

        const general = section('Datos generales', `
            <div class="vut-detail-grid">
                <div class="vut-detail-field"><span class="label">Folio</span><span class="value">${vutDashEsc(data.folio || 'S/F')}</span></div>
                <div class="vut-detail-field"><span class="label">Estado</span><span class="value">${vutDashEsc(estadoLabel(data.estado_proceso || 'NUEVO'))}</span></div>
                <div class="vut-detail-field"><span class="label">Fecha de ingreso</span><span class="value">${vutDashEsc(formatFecha(data.fecha_ingreso || data.fecha_creacion || '', true))}</span></div>
                <div class="vut-detail-field"><span class="label">Estatus interno</span><span class="value">${vutDashEsc(data.estatus || 'N/A')}</span></div>
                <div class="vut-detail-field"><span class="label">Materia</span><span class="value">${vutDashEsc(data.materia || '')}</span></div>
                <div class="vut-detail-field"><span class="label">Modalidad / detalle</span><span class="value">${vutDashEsc(modalidad)}</span></div>
                <div class="vut-detail-field" style="grid-column:1/-1;"><span class="label">Trámite</span><span class="value">${vutDashEsc(data.nombre_tramite || data.solicitud?.tramite || '')}</span></div>
                <div class="vut-detail-field" style="grid-column:1/-1;"><span class="label">Observaciones</span><span class="value">${vutDashEsc(data.OBSERVACIONES || data.observaciones || 'SIN OBSERVACIONES')}</span></div>
            </div>
        `);

        const personas =
            section('Interesado', `
                <div class="vut-detail-field" style="margin-bottom:10px;">
                    <span class="label">Titular detectado</span>
                    <span class="value">${vutDashEsc(titular)}</span>
                </div>
                ${objectFieldsHtml({ ...base, ...din }, 'Sin datos del interesado.')}
            `) +
            section('Representante legal', objectFieldsHtml(legal, 'Sin representante legal registrado.')) +
            section('Persona autorizada', objectFieldsHtml(autorizada, 'Sin persona autorizada registrada.'));

        const especificosHtml =
            section('Bifurcación / modalidad', objectFieldsHtml(bif, 'Sin bifurcación registrada.')) +
            section('Datos específicos capturados', objectFieldsHtml(especificos, 'Sin datos específicos registrados.'));

        const docsHtml =
            section('Requisitos presentados', renderRequisitos(data.requisitos_validados || [])) +
            section('Recibos / pagos', renderRecibos(recibos)) +
            section('Firmas digitales', renderFirmas(firmas));

        const historialHtml = section('Historial de estados', renderHistorial(historial));

        return `
            <div style="text-align:left;">
                <div style="display:flex;justify-content:space-between;gap:12px;align-items:flex-start;margin-bottom:16px;">
                    <div>
                        <div style="font-size:11px;font-weight:950;color:#773357;text-transform:uppercase;letter-spacing:.12em;">
                            ${vutDashEsc(data.folio || 'S/F')}
                        </div>
                        <div style="font-size:18px;font-weight:950;color:#111827;line-height:1.2;">
                            ${vutDashEsc(titular)}
                        </div>
                    </div>

                    <span class="estado-pill estado-${vutDashEsc(data.estado_proceso || 'NUEVO')}">
                        ● ${vutDashEsc(estadoLabel(data.estado_proceso || 'NUEVO'))}
                    </span>
                </div>

                <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:14px;">
                    <a class="vut-btn vut-btn-light" href="index.php?route=ventanilla/editar&id=${encodeURIComponent(data.id_solicitud || data.id || '')}" style="text-decoration:none;">
                        ✏️ Editar captura
                    </a>
                    <a class="vut-btn vut-btn-light" target="_blank" href="index.php?route=ventanilla/generarComprobante&id=${encodeURIComponent(data.id_solicitud || data.id || '')}" style="text-decoration:none;">
                        📄 Abrir acuse PDF
                    </a>
                    <button type="button" class="vut-btn vut-btn-light" onclick="Swal.close(); abrirCambioEstado(${Number(data.id_solicitud || data.id || 0)}, '${vutDashEsc(data.estado_proceso || 'NUEVO')}')">
                        ↺ Cambiar estado
                    </button>
                </div>

                <div class="vut-detail-tabs">
                    <button type="button" class="vut-detail-tab active" data-tab="general">General</button>
                    <button type="button" class="vut-detail-tab" data-tab="personas">Personas</button>
                    <button type="button" class="vut-detail-tab" data-tab="especificos">Captura</button>
                    <button type="button" class="vut-detail-tab" data-tab="docs">Documentos / pagos</button>
                    <button type="button" class="vut-detail-tab" data-tab="historial">Historial</button>
                    
                </div>

                <div class="vut-detail-panel active" data-panel="general">${general}</div>
                <div class="vut-detail-panel" data-panel="personas">${personas}</div>
                <div class="vut-detail-panel" data-panel="especificos">${especificosHtml}</div>
                <div class="vut-detail-panel" data-panel="docs">${docsHtml}</div>
                <div class="vut-detail-panel" data-panel="historial">${historialHtml}</div>
            </div>
        `;
    }

    async function verDetalleSolicitud(id) {
        try {
            Swal.fire(vutSwalConfig({
                title: 'Cargando detalle...',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            }));

            const response = await fetch(`index.php?route=ventanilla/detalle&id=${encodeURIComponent(id)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.error || 'No se pudo cargar el detalle.');
            }

            const detalle = data.data || {};

            await Swal.fire(vutSwalConfig({
                icon: 'info',
                title: 'Detalle del trámite',
                width: 'min(1120px, 96vw)',
                html: buildDetalleHtml(detalle),
                showCancelButton: false,
                showDenyButton: false,
                confirmButtonText: 'Cerrar',
                didOpen: activarTabsDetalle
            }));
        } catch (error) {
            Swal.fire(vutSwalConfig({
                icon: 'error',
                title: 'Error',
                text: error.message,
                confirmButtonText: 'Revisar'
            }));
        }
    }

    let vutSearchTimer = null;

    document.addEventListener('DOMContentLoaded', () => {
        cargarDashboardVUT();

        document.getElementById('filtro-q')?.addEventListener('input', () => {
            clearTimeout(vutSearchTimer);
            vutSearchTimer = setTimeout(vutDashboardBuscar, 420);
        });

        ['filtro-materia', 'filtro-tramite', 'filtro-fecha-inicio', 'filtro-fecha-fin', 'filtro-limit'].forEach(id => {
            document.getElementById(id)?.addEventListener('change', () => {
                window.VUT_DASHBOARD.page = 1;
                cargarDashboardVUT();
            });
        });
    });
</script>
