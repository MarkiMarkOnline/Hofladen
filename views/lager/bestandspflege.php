<main class="grid-lagerverwaltung" id="lager">
    <a href="<?= BASE_URL ?>/?page=lager_bestand" class="module lager-module" id="bestand">
        <h2>Bestandspflege</h2>
        <p>Einpflegen von Bestellungen</p>
        <p>Bestände aktualisieren</p>
    </a>

    <a href="<?= BASE_URL ?>/?page=lager_berichte" class="module lager-module" id="berichte">
        <h2>Berichte</h2>
        <p>Berichte von Artikel mit geringem Bestand</p>
        <p>Berichte von Artikel mit hohem Bestand</p>
        <p>Berichte von Artikeln mit niedrigem MHD</p>
        <p>Umsatzberichte</p>
    </a>

    <div class="module lager-module" id="verwaltung">
        <h2>Verwaltung</h2>
        <p>Hinzufügen neuer Artikelvarianten</p>
        <p>Entfernen von Artikelvarianten</p>
        <p>Hinzufügen von Lieferanten</p>
        <p>Entfernen von Lieferanten</p>
    </div>

    <div class="module report-module">
        <h2>Bestand ändern:</h2>
        <label for="artikelid">ID:</label>
        <input type="number" id="artikelid" name="artikelid" placeholder="Artikel ID">
        <label for="addbestand">Bestandsänderung (Addierung):</label>
        <input type="number" step="0.01" id="addbestand" name="addbestand" placeholder="+ / - Wert">
        <button type="button" id="btn-bestandsadd">OK</button><br><br>
    </div>

    <div class="module table-module">
        <h2>Artikelliste</h2>
        <p>Gebe nur eine ID ein um den Artikel aufzurufen, gib eine ID und eine Bestandsänderung ein, um den Bestand zu aktualisieren.</p>
    </div>
</main>

<script src="<?= BASE_URL ?>/js/lager_bestand.js" defer></script>
