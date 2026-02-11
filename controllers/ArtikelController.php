<?php
/**
 * ArtikelController
 * 
 * Verarbeitet AJAX-Anfragen für Artikelsuche, Bestandspflege
 * und erweiterte Suche. Ersetzt die 4 einzelnen ajax_*.php Dateien.
 */
class ArtikelController
{
    private ArtikelModel $model;

    public function __construct(ArtikelModel $model)
    {
        $this->model = $model;
    }

    // ─── Artikelsuche nach Name ───────────────────────────────
    // Vorher: ajax_artikel_suche.php

    public function sucheNachName(): void
    {
        header('Content-Type: application/json');

        $name = trim($_POST['artikelname'] ?? '');
        if ($name === '') {
            echo json_encode(['success' => false, 'message' => 'Bitte geben Sie einen Suchbegriff ein.']);
            return;
        }

        try {
            $ergebnisse = $this->model->sucheNachName($name);

            if (count($ergebnisse) > 0) {
                echo json_encode(['success' => true, 'data' => $ergebnisse, 'count' => count($ergebnisse)]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Keine Artikel gefunden für: ' . htmlspecialchars($name)]);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Datenbankfehler: ' . $e->getMessage()]);
        }
    }

    // ─── Artikelsuche nach ID ─────────────────────────────────
    // Vorher: ajax_artikel_suche_id.php

    public function sucheNachId(): void
    {
        header('Content-Type: application/json');

        $id = trim($_POST['artikelid'] ?? '');
        if ($id === '') {
            echo json_encode(['success' => false, 'message' => 'Bitte geben Sie eine Artikel-ID ein.']);
            return;
        }

        try {
            $artikel = $this->model->sucheNachId((int) $id);

            if ($artikel) {
                echo json_encode(['success' => true, 'data' => [$artikel], 'count' => 1]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Kein Artikel mit ID ' . (int) $id . ' gefunden.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Datenbankfehler: ' . $e->getMessage()]);
        }
    }

    // ─── Bestand aktualisieren ────────────────────────────────
    // Vorher: ajax_bestand_update.php

    public function bestandUpdate(): void
    {
        header('Content-Type: application/json');

        $id       = trim($_POST['artikelid'] ?? '');
        $aenderung = $_POST['bestandsaenderung'] ?? '';

        if ($id === '') {
            echo json_encode(['success' => false, 'message' => 'Bitte geben Sie eine Artikel-ID ein.']);
            return;
        }
        if ($aenderung === '') {
            echo json_encode(['success' => false, 'message' => 'Bitte geben Sie eine Bestandsänderung ein.']);
            return;
        }

        try {
            $result = $this->model->bestandAktualisieren((int) $id, (float) $aenderung);

            if ($result === false) {
                echo json_encode(['success' => false, 'message' => 'Artikel mit ID ' . (int) $id . ' nicht gefunden.']);
                return;
            }

            echo json_encode([
                'success'   => true,
                'message'   => 'Bestand erfolgreich aktualisiert',
                'data'      => [$result['artikel']],
                'count'     => 1,
                'aenderung' => [
                    'artikel'       => $result['name'],
                    'alter_bestand' => $result['alter_bestand'],
                    'neuer_bestand' => $result['neuer_bestand'],
                    'differenz'     => $result['differenz'],
                ],
            ]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Datenbankfehler: ' . $e->getMessage()]);
        }
    }

    // ─── Erweiterte Suche ─────────────────────────────────────
    // Vorher: ajax_erweiterte_suche.php

    public function erweitertesSuchen(): void
    {
        header('Content-Type: application/json');

        try {
            $filter = [
                'kategorie' => $_POST['kategorie'] ?? 'alle',
                'gruppe'    => $_POST['gruppe']    ?? 'alle',
                'art'       => $_POST['art']       ?? '',
                'vergleich' => $_POST['vergleich'] ?? 'gleich',
                'anzahl'    => $_POST['anzahl']    ?? '',
            ];

            $ergebnisse = $this->model->erweitertesSuchen($filter);

            if (count($ergebnisse) > 0) {
                echo json_encode(['success' => true, 'data' => $ergebnisse, 'count' => count($ergebnisse)]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Keine Artikel mit den gewählten Filtern gefunden.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Fehler: ' . $e->getMessage()]);
        }
    }

    // ─── POS-Schnellsuche ─────────────────────────────────────
    // Vorher: search.php

    public function posSearch(): void
    {
        header('Content-Type: application/json');

        $q = $_GET['q'] ?? '';
        echo json_encode($this->model->schnellsuche($q));
    }
}
