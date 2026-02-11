// Lagerverwaltung - Berichte
// Extrahiert aus lagerverwaltung.php (vorher inline)

document.addEventListener('DOMContentLoaded', function() {

    const artikelnameInput = document.getElementById('artikelname');
    const tableModule = document.querySelector('.table-module');
    const suchButton = document.getElementById('btn-artikel-suche');
    const erweiterterSuchButton = document.getElementById('btn-erweiterte-suche');

    if (suchButton) {
        suchButton.addEventListener('click', artikelSuchen);
    }

    if (erweiterterSuchButton) {
        erweiterterSuchButton.addEventListener('click', erweiterteSuche);
    }

    if (artikelnameInput) {
        artikelnameInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                artikelSuchen();
            }
        });
    }

    function artikelSuchen() {
        const suchbegriff = artikelnameInput.value.trim();

        if (!suchbegriff) {
            zeigeNachricht('Bitte geben Sie einen Suchbegriff ein.', 'warning');
            return;
        }

        tableModule.innerHTML = '<h2>Artikelliste</h2><p class="loading">Suche läuft...</p>';

        // ✅ NEU: Router-URL statt direkter Datei
        fetch('?action=artikel_suche', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'artikelname=' + encodeURIComponent(suchbegriff)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                zeigeErgebnisse(data.data, data.count, suchbegriff);
            } else {
                zeigeNachricht(data.message, 'info');
            }
        })
        .catch(error => {
            console.error('Fehler:', error);
            zeigeNachricht('Ein Fehler ist aufgetreten. Bitte versuchen Sie es erneut.', 'error');
        });
    }

    function erweiterteSuche() {
        const art = document.getElementById('art').value;
        const vergleich = document.getElementById('vergleich').value;
        const anzahl = document.getElementById('anzahl').value;
        const kategorie = document.getElementById('kategorie').value;
        const gruppe = document.getElementById('gruppe').value;

        tableModule.innerHTML = '<h2>Artikelliste</h2><p class="loading">Suche läuft...</p>';

        const formData = new URLSearchParams();
        formData.append('art', art);
        formData.append('vergleich', vergleich);
        formData.append('anzahl', anzahl);
        formData.append('kategorie', kategorie);
        formData.append('gruppe', gruppe);

        // ✅ NEU: Router-URL
        fetch('?action=erweiterte_suche', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: formData.toString()
        })
        .then(response => {
            if (!response.ok) throw new Error('HTTP error ' + response.status);
            return response.text();
        })
        .then(text => {
            try {
                const data = JSON.parse(text);
                if (data.success) {
                    let filterBeschreibung = erstelleFilterBeschreibung(art, vergleich, anzahl, kategorie, gruppe);
                    zeigeErgebnisse(data.data, data.count, filterBeschreibung);
                } else {
                    zeigeNachricht(data.message, 'info');
                }
            } catch(e) {
                console.error('JSON Parse Error:', e);
                zeigeNachricht('Serverfehler: Ungültige Antwort.', 'error');
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            zeigeNachricht('Ein Fehler ist aufgetreten: ' + error.message, 'error');
        });
    }

    // ─── Shared Hilfsfunktionen ───────────────────────────

    function zeigeErgebnisse(artikel, anzahl, beschreibung) {
        let html = `
            <h2>Artikelliste</h2>
            <p><strong>${anzahl}</strong> Artikel gefunden für: "<em>${beschreibung}</em>"</p>
            <div class="table-wrapper">
                <table class="artikel-tabelle">
                    <thead>
                        <tr>
                            <th>ID</th><th>Artikelbezeichnung</th><th>Preis (€)</th>
                            <th>Einheit</th><th>Lagerbestand</th><th>MwSt (%)</th>
                            <th>Warengruppe</th><th>Herkunft</th><th>Lieferant</th>
                            <th>Saisonware</th><th>Saison</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        artikel.forEach(a => {
            const preis = parseFloat(a.preis).toFixed(2);
            const bestand = parseFloat(a.lagerbestand).toFixed(2);
            const mwst = a.mwst_satz ? parseFloat(a.mwst_satz).toFixed(2) : '-';
            const saisonware = a.saisonware == 1 ? 'Ja' : 'Nein';
            const saison = a.saisonware == 1
                ? `${formatDatum(a.saisonstart)} - ${formatDatum(a.saisonende)}`
                : '-';

            html += `
                <tr>
                    <td>${a.id_artikel}</td>
                    <td>${a.artikelbezeichnung}</td>
                    <td>${preis}</td>
                    <td>${a.einheit || '-'}</td>
                    <td>${bestand}</td>
                    <td>${mwst}</td>
                    <td>${a.warengruppen || '-'}</td>
                    <td>${a.herkuenfte || '-'}</td>
                    <td>${a.lieferanten || '-'}</td>
                    <td>${saisonware}</td>
                    <td>${saison}</td>
                </tr>
            `;
        });

        html += '</tbody></table></div>';
        tableModule.innerHTML = html;
    }

    function zeigeNachricht(nachricht, typ) {
        const cssClass = typ === 'error' ? 'error' : typ === 'warning' ? 'warning' : 'info';
        tableModule.innerHTML = `<h2>Artikelliste</h2><p class="${cssClass}">${nachricht}</p>`;
    }

    function formatDatum(datum) {
        if (!datum) return '-';
        const d = new Date(datum);
        const tag = String(d.getDate()).padStart(2, '0');
        const monat = String(d.getMonth() + 1).padStart(2, '0');
        return `${tag}.${monat}`;
    }

    function erstelleFilterBeschreibung(art, vergleich, anzahl, kategorie, gruppe) {
        let teile = [];

        if (art && art !== '' && art !== '-' && anzahl) {
            const artText = art === 'bestand' ? 'Bestand' : art === 'preis' ? 'Preis' : 'Umsatz';
            const vergleichText = vergleich === 'groesser' ? 'größer als' : vergleich === 'kleiner' ? 'kleiner als' : 'gleich';
            teile.push(`${artText} ${vergleichText} ${anzahl}`);
        }

        if (kategorie && kategorie !== 'alle') teile.push(`Kategorie: ${kategorie}`);
        if (gruppe && gruppe !== 'alle') teile.push(`Herkunft: ${gruppe}`);

        return teile.length > 0 ? teile.join(' | ') : 'Alle Artikel';
    }
});
