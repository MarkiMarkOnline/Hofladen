<?php
/**
 * Front Controller (Router)
 * 
 * Einziger Einstiegspunkt der Anwendung.
 * Alle Requests laufen über diese Datei.
 * 
 * Seiten:      ?page=home | pos | lager_berichte | lager_bestand | admin | einstellungen
 * API/AJAX:    ?action=artikel_suche | artikel_suche_id | bestand_update | erweiterte_suche | pos_search | check_admin
 */

// ─── Config laden ─────────────────────────────────────────
require_once __DIR__ . '/../config/app.php';

// ─── Models laden ─────────────────────────────────────────
require_once ROOT_PATH . '/models/ArtikelModel.php';
require_once ROOT_PATH . '/models/AdminModel.php';

// ─── Controllers laden ────────────────────────────────────
require_once ROOT_PATH . '/controllers/ArtikelController.php';
require_once ROOT_PATH . '/controllers/AdminController.php';

// ─── Controller-Instanzen ─────────────────────────────────
$artikelModel      = new ArtikelModel($pdo);
$adminModel        = new AdminModel($pdo);
$artikelController = new ArtikelController($artikelModel);
$adminController   = new AdminController($adminModel);

// ═══════════════════════════════════════════════════════════
// AJAX / API REQUESTS
// ═══════════════════════════════════════════════════════════

$action = $_GET['action'] ?? $_POST['action'] ?? null;

if ($action) {
    switch ($action) {
        case 'artikel_suche':
            $artikelController->sucheNachName();
            break;
        case 'artikel_suche_id':
            $artikelController->sucheNachId();
            break;
        case 'bestand_update':
            $artikelController->bestandUpdate();
            break;
        case 'erweiterte_suche':
            $artikelController->erweitertesSuchen();
            break;
        case 'pos_search':
            $artikelController->posSearch();
            break;
        case 'check_admin':
            $adminController->checkAdmin();
            break;
        default:
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode(['error' => 'Unbekannte Aktion: ' . $action]);
    }
    exit; // AJAX-Requests enden hier
}

// ═══════════════════════════════════════════════════════════
// SEITEN-REQUESTS (HTML)
// ═══════════════════════════════════════════════════════════

// Admin POST-Aktionen verarbeiten (vor dem Rendern)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_mitarbeiter'])) {
        $adminController->mitarbeiterSpeichern();
    }
    if (isset($_POST['steuersatz'])) {
        $adminController->steuersatzSpeichern();
    }
}

// Seite bestimmen
$page = $_GET['page'] ?? 'home';

// View-Mapping: page => view-Datei
$views = [
    'home'            => ROOT_PATH . '/views/home/index.php',
    'pos'             => ROOT_PATH . '/views/pos/index.php',
    'lager_berichte'  => ROOT_PATH . '/views/lager/berichte.php',
    'lager_bestand'   => ROOT_PATH . '/views/lager/bestandspflege.php',
    'admin'           => ROOT_PATH . '/views/admin/index.php',
    'einstellungen'   => ROOT_PATH . '/views/einstellungen/index.php',
];

// Prüfen ob Seite existiert
$viewFile = $views[$page] ?? $views['home'];

// ─── Layout rendern ───────────────────────────────────────
include ROOT_PATH . '/views/layout/header.php';
include ROOT_PATH . '/views/layout/sidebar.php';
include $viewFile;
include ROOT_PATH . '/views/layout/footer.php';
