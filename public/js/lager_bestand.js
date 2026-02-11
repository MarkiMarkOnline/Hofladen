// Lagerverwaltung - Bestandspflege
// Extrahiert aus lagerverwaltung2.php (vorher inline)

document.addEventListener('DOMContentLoaded', function() {

    const artikelidInput = document.getElementById('artikelid');
    const addbestandInput = document.getElementById('addbestand');
    const tableModule = document.querySelector('.table-module');
    const bestandAddButton = document.getElementById('btn-bestandsadd');

    if (bestandAddButton) {
        bestandAddButton.addEventListener('click', intelligenterButton);
    }

    if (artikelidInput) {
        artikelidInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') intelligenterButton();
        });
    }

    if (addbestandInput) {
        addbestandInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') intelligenterButton();
        });
    }

    // Entscheidet automatisch: nur Suche oder Update
    function intelligenterButton() {
        const artikelid = artikelidInput.value.trim();
        const bestandsaenderung = addbestandInput.value.trim();

        if (!artikelid) {
            zeigeNachricht('Bitte geben Sie eine Artikel-ID ein.', 'warning');
            return;
        }

        if (!bestandsaenderung || bestandsaenderung === '') {
            artikelSuchenById();
        } else {
            bestandAktualisieren();
        }
    }

    function artikelSuchenById() {
        const artikelid = artikelidInput.value.trim();
        tableModule.innerHTML = '<h2>Artikelliste</h2><p class="loading">Suche läuft...</p>';

        // ✅ NEU: Router-URL
        fetch('?action=artikel_suche_id', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'artikelid=' + encodeURIComponent(artikelid)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                zeigeErgebnisse(data.data, data.count, 'ID: ' + artikelid);
            } else {
                zeigeNachricht(data.message, 'info');
            }
        })
        .catch(error => {
            console.error('Fehler:', error);
            zeigeNachricht('Ein Fehler ist aufgetreten.', 'error');
        });
    }

    function bestandAktualisieren() {
        const artikelid = artikelidInput.value.trim();
        const bestandsaenderung = addbestandInput.value.trim();
        tableModule.innerHTML = '<h2>Artikelliste</h2><p class="loading">Bestand wird aktualisiert...</p>';

        // ✅ NEU: Router-URL
        fetch('?action=bestand_update', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'artikelid=' + encodeURIComponent(artikelid) + '&bestandsaenderung=' + encodeURIComponent(bestandsaenderung)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const aenderung = data.aenderung;
                const meldung = `✓ ${aenderung.artikel}: ${aenderung.alter_bestand} → ${aenderung.neuer_bestand} (${aenderung.differenz > 0 ? '+' : ''}${aenderung.differenz})`;
                zeigeErgebnisse(data.data, data.count, meldung);
                addbestandInput.value = '';
            } else {
                zeigeNachricht(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Fehler:', error);
            zeigeNachricht('Ein Fehler ist aufgetreten.', 'error');
        });
    }

    // ─── Shared Hilfsfunktionen ───────────────────────────

    function zeigeErgebnisse(artikel, anzahl, beschreibung) {
        let html = `
            <h2>Artikelliste</h2>
            <p><strong>${anzahl}</strong> Artikel: <em>${beschreibung}</em></p>
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
                    <td><strong>${bestand}</strong></td>
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
        return d.toLocaleDateString('de-DE');
    }
});
