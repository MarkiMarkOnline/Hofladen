<?php
/**
 * ArtikelModel
 * 
 * Alle Datenbankabfragen rund um Artikel.
 * Ersetzt den duplizierten Code aus ajax_artikel_suche.php,
 * ajax_artikel_suche_id.php, ajax_bestand_update.php und
 * ajax_erweiterte_suche.php.
 */
class ArtikelModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // ─── Suche nach Name (LIKE) ───────────────────────────────

    public function sucheNachName(string $suchbegriff): array
    {
        $stmt = $this->pdo->prepare("
            SELECT a.id_artikel, a.artikelbezeichnung, a.preis, a.lagerbestand,
                   a.saisonware, a.saisonstart, a.saisonende, a.fk_einheit, a.fk_mwst
            FROM t_artikel a
            WHERE a.artikelbezeichnung LIKE :suchbegriff
            ORDER BY a.artikelbezeichnung ASC
        ");
        $stmt->execute(['suchbegriff' => '%' . $suchbegriff . '%']);
        $ergebnisse = $stmt->fetchAll();

        return $this->enrichAll($ergebnisse);
    }

    // ─── Suche nach ID ────────────────────────────────────────

    public function sucheNachId(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT a.id_artikel, a.artikelbezeichnung, a.preis, a.lagerbestand,
                   a.saisonware, a.saisonstart, a.saisonende, a.fk_einheit, a.fk_mwst
            FROM t_artikel a
            WHERE a.id_artikel = :id
        ");
        $stmt->execute(['id' => $id]);
        $artikel = $stmt->fetch();

        if (!$artikel) {
            return null;
        }

        return $this->enrich($artikel);
    }

    // ─── Bestand aktualisieren ────────────────────────────────

    public function bestandAktualisieren(int $id, float $aenderung): array|false
    {
        // Artikel prüfen
        $stmt = $this->pdo->prepare(
            "SELECT id_artikel, artikelbezeichnung, lagerbestand FROM t_artikel WHERE id_artikel = ?"
        );
        $stmt->execute([$id]);
        $artikel = $stmt->fetch();

        if (!$artikel) {
            return false;
        }

        $alterBestand = (float) $artikel['lagerbestand'];
        $neuerBestand = $alterBestand + $aenderung;

        // Update
        $this->pdo->prepare("UPDATE t_artikel SET lagerbestand = ? WHERE id_artikel = ?")
                   ->execute([$neuerBestand, $id]);

        // Aktualisierte Daten holen
        $aktualisiert = $this->sucheNachId($id);

        return [
            'artikel'       => $aktualisiert,
            'alter_bestand' => $alterBestand,
            'neuer_bestand' => $neuerBestand,
            'differenz'     => $aenderung,
            'name'          => $artikel['artikelbezeichnung'],
        ];
    }

    // ─── Erweiterte Suche mit Filtern ─────────────────────────

    public function erweitertesSuchen(array $filter): array
    {
        $sql    = "SELECT DISTINCT a.id_artikel, a.artikelbezeichnung, a.preis, a.lagerbestand,
                          a.saisonware, a.saisonstart, a.saisonende, a.fk_einheit, a.fk_mwst
                   FROM t_artikel a";
        $joins  = [];
        $where  = [];
        $params = [];

        // Kategorie (Warengruppe)
        $kategorie = $filter['kategorie'] ?? 'alle';
        if ($kategorie !== 'alle') {
            $joins[] = "INNER JOIN vt_artikel_warengruppe vw ON a.id_artikel = vw.fk_artikel";
            $joins[] = "INNER JOIN t_warengruppe w ON vw.fk_warengruppe = w.id_warengruppe";
            $where[] = "w.warengruppe = :kategorie";
            $params[':kategorie'] = $kategorie;
        }

        // Gruppe (Herkunft)
        $gruppe = $filter['gruppe'] ?? 'alle';
        if ($gruppe !== 'alle') {
            $joins[] = "INNER JOIN vt_artikel_herkunft vh ON a.id_artikel = vh.fk_artikel";
            $joins[] = "INNER JOIN t_herkunft h ON vh.fk_herkunft = h.id_herkunft";
            $where[] = "h.herkunft = :gruppe";
            $params[':gruppe'] = $gruppe;
        }

        // Vergleichsfilter (Bestand / Preis / Umsatz)
        $art      = $filter['art'] ?? '';
        $vergl    = $filter['vergleich'] ?? 'gleich';
        $anzahl   = $filter['anzahl'] ?? '';

        if ($art !== '' && $art !== '-' && $anzahl !== '') {
            $spalten   = ['bestand' => 'a.lagerbestand', 'preis' => 'a.preis', 'umsatz' => 'a.preis'];
            $operatoren = ['groesser' => '>', 'kleiner' => '<', 'gleich' => '='];

            $spalte   = $spalten[$art]    ?? null;
            $operator = $operatoren[$vergl] ?? '=';

            if ($spalte) {
                $where[]           = "$spalte $operator :anzahl";
                $params[':anzahl'] = (float) $anzahl;
            }
        }

        // Query zusammenbauen
        if (!empty($joins)) {
            $sql .= ' ' . implode(' ', array_unique($joins));
        }
        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY a.artikelbezeichnung ASC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $ergebnisse = $stmt->fetchAll();

        return $this->enrichAll($ergebnisse);
    }

    // ─── Einfache Schnellsuche (für POS) ──────────────────────

    public function schnellsuche(string $query): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id_artikel, artikelbezeichnung, preis FROM t_artikel WHERE artikelbezeichnung LIKE ?"
        );
        $stmt->execute(["%$query%"]);
        return $stmt->fetchAll();
    }

    // ═══ PRIVATE HILFSMETHODEN ════════════════════════════════
    // Diese ersetzen den 4× duplizierten Code!

    /**
     * Einen einzelnen Artikel mit allen Relationen anreichern.
     */
    private function enrich(array $artikel): array
    {
        $id = $artikel['id_artikel'];

        // Einheit
        $artikel['einheit'] = $this->fetchSingle(
            "SELECT einheit FROM t_einheit WHERE id_einheit = ?",
            $artikel['fk_einheit']
        );

        // MwSt
        $artikel['mwst_satz'] = $this->fetchSingle(
            "SELECT mwst FROM t_mwst WHERE id_mwst = ?",
            $artikel['fk_mwst']
        );

        // Warengruppen (M:N)
        $artikel['warengruppen'] = $this->fetchList(
            "SELECT w.warengruppe FROM vt_artikel_warengruppe vw
             JOIN t_warengruppe w ON vw.fk_warengruppe = w.id_warengruppe
             WHERE vw.fk_artikel = ?",
            $id
        );

        // Herkunft (M:N)
        $artikel['herkuenfte'] = $this->fetchList(
            "SELECT h.herkunft FROM vt_artikel_herkunft vh
             JOIN t_herkunft h ON vh.fk_herkunft = h.id_herkunft
             WHERE vh.fk_artikel = ?",
            $id
        );

        // Lieferanten (M:N)
        $artikel['lieferanten'] = $this->fetchList(
            "SELECT l.firma FROM vt_artikel_lieferant vl
             JOIN t_lieferant l ON vl.fk_lieferant = l.id_lieferant
             WHERE vl.fk_artikel = ?",
            $id
        );

        return $artikel;
    }

    /**
     * Alle Artikel in einem Array anreichern.
     */
    private function enrichAll(array $artikelListe): array
    {
        foreach ($artikelListe as &$artikel) {
            $artikel = $this->enrich($artikel);
        }
        unset($artikel);
        return $artikelListe;
    }

    /**
     * Einen einzelnen Wert aus einer Tabelle holen.
     */
    private function fetchSingle(string $sql, $fk): ?string
    {
        if (!$fk) return null;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$fk]);
        $row = $stmt->fetch();
        return $row ? reset($row) : null;
    }

    /**
     * Eine Liste von Werten (M:N) als kommaseparierten String holen.
     */
    private function fetchList(string $sql, int $artikelId): string
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$artikelId]);
        $values = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $values ? implode(', ', $values) : '-';
    }
}
