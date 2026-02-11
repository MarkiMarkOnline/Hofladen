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
        <h2>Bericht über:</h2>

        <label for="artikelname">Suche nach Artikel: </label>
        <input type="text" id="artikelname" name="artikelname" placeholder="Artikelname eingeben...">
        <button type="button" id="btn-artikel-suche">OK</button><br><br>

        <label for="art">Zeige alle Artikel mit einem:</label>
        <select id="art" name="art">
            <option value="bestand"> - </option>
            <option value="bestand">Bestand</option>
            <option value="preis">Preis</option>
            <option value="umsatz">Umsatz</option>
        </select>

        <select id="vergleich" name="vergleich">
            <option value="gleich">gleich</option>
            <option value="groesser">größer</option>
            <option value="kleiner">kleiner</option>
        </select>

        <input type="number" id="anzahl" name="anzahl" placeholder="Wert">

        <label for="kategorie">aus der Kategorie: </label>
        <select id="kategorie" name="kategorie">
            <option value="alle">Alle</option>
            <option value="Obst">Obst</option>
            <option value="Gemüse">Gemüse</option>
            <option value="Milchprodukt">Milchprodukt</option>
            <option value="Fleisch">Fleisch</option>
            <option value="Nüsse">Nüsse</option>
            <option value="Marmelade">Marmelade</option>
            <option value="Öl">Öl</option>
        </select>

        <select id="gruppe" name="gruppe">
            <option value="alle">Alle</option>
            <option value="Eigenerzeugnis">Eigenerzeugnis</option>
            <option value="Eigenproduktion">Eigenproduktion</option>
            <option value="Zulieferung">Zulieferung</option>
        </select>
        <button type="button" id="btn-erweiterte-suche">OK</button>
    </div>

    <div class="module table-module">
        <h2>Artikelliste</h2>
        <p>Geben Sie einen Suchbegriff ein oder wählen Sie Filter aus...</p>
    </div>
</main>

<script src="<?= BASE_URL ?>/js/lager_berichte.js" defer></script>
