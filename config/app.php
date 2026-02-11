<?php
/**
 * Anwendungs-Konfiguration
 * 
 * Zentrale Konstanten für Pfade und URLs.
 */

// Absoluter Pfad zum Projektroot
define('ROOT_PATH', dirname(__DIR__));

// Basis-URL relativ zur Webroot (anpassen falls nötig)
define('BASE_URL', '/Hofladen/public');

// Asset-Version für Cache-Busting
define('ASSET_VERSION', '1.0');

// Datenbank einbinden
require_once ROOT_PATH . '/config/database.php';
