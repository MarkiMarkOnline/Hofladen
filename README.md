# Hofladen GbR - POS & Lagerverwaltung (MVC)

## Ordnerstruktur

```
PROJEKT_KUNDENAUFTRAG/
│
├── config/                         ← Konfiguration
│   ├── app.php                     ← App-Konstanten (BASE_URL, Pfade)
│   └── database.php                ← PDO-Datenbankverbindung
│
├── models/                         ← MODEL: Datenbanklogik
│   ├── ArtikelModel.php            ← Alle Artikel-Queries (Suche, Bestand, Filter)
│   └── AdminModel.php              ← Admin-Auth, Mitarbeiter, Steuersätze
│
├── controllers/                    ← CONTROLLER: Request-Verarbeitung
│   ├── ArtikelController.php       ← AJAX-Endpunkte für Artikel
│   └── AdminController.php         ← Admin-Login & Formulare
│
├── views/                          ← VIEW: HTML-Templates (kein PHP-Logik!)
│   ├── layout/
│   │   ├── header.php              ← <head>, <header>
│   │   ├── sidebar.php             ← Navigation
│   │   └── footer.php              ← <footer>
│   ├── home/index.php              ← Startseite
│   ├── pos/index.php               ← Point of Sale
│   ├── lager/
│   │   ├── berichte.php            ← Lagerberichte (vorher lagerverwaltung.php)
│   │   └── bestandspflege.php      ← Bestandspflege (vorher lagerverwaltung2.php)
│   ├── admin/index.php             ← Administration + Modals
│   └── einstellungen/index.php     ← Einstellungen
│
├── public/                         ← ÖFFENTLICH: Webserver zeigt hierhin
│   ├── index.php                   ← ★ FRONT CONTROLLER / ROUTER ★
│   ├── css/
│   │   ├── styles.css              ← Hauptstyles
│   │   ├── pos.css                 ← POS-spezifisch
│   │   └── components.css          ← Tabellen, Modals (vorher inline)
│   ├── js/
│   │   ├── pos.js                  ← POS-Logik
│   │   ├── lager_berichte.js       ← Lagerberichte JS (vorher inline)
│   │   ├── lager_bestand.js        ← Bestandspflege JS (vorher inline)
│   │   └── admin.js                ← Admin-Modals JS (vorher inline)
│   └── img/                        ← Bilder
│
└── DB/
    └── db_hofladen.sql             ← Datenbank-Dump
```

## Was hat sich geändert?

### 1. Kein duplizierter Code mehr
Der Block zum Laden von Einheit, MwSt, Warengruppen, Herkunft und Lieferanten
war **4× identisch kopiert** in den alten ajax-Dateien. Jetzt existiert er **1×**
in `ArtikelModel::enrich()`.

### 2. Trennung von Logik und Darstellung
- **Models** → nur Datenbankabfragen
- **Controllers** → nur Request/Response Handling
- **Views** → nur HTML-Templates

### 3. Zentraler Router (Front Controller)
Alle Requests laufen über `public/index.php`:

**Seiten:**
- `?page=home` → Startseite
- `?page=pos` → Point of Sale
- `?page=lager_berichte` → Lagerberichte
- `?page=lager_bestand` → Bestandspflege
- `?page=admin` → Administration
- `?page=einstellungen` → Einstellungen

**AJAX-Endpunkte:**
- `?action=artikel_suche` (POST)
- `?action=artikel_suche_id` (POST)
- `?action=bestand_update` (POST)
- `?action=erweiterte_suche` (POST)
- `?action=pos_search` (GET)
- `?action=check_admin` (POST)

### 4. JavaScript extrahiert
Alle inline `<script>` Blöcke wurden in eigene `.js` Dateien verschoben.

### 5. CSS extrahiert
Alle inline `<style>` Blöcke wurden in `components.css` zusammengefasst.

## Setup

1. SQL-Dump importieren: `DB/db_hofladen.sql`
2. Datenbank-Zugangsdaten anpassen: `config/database.php`
3. BASE_URL anpassen: `config/app.php`
4. Webserver DocumentRoot auf `public/` zeigen lassen

## Autoren
Mark, Natalia, Emmanouil, Bude
