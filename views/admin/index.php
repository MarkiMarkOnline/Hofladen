<main class="grid-administration" id="admin">
    <div class="module">
        <h3>Benutzerverwaltung</h3>
        <button class="admin-btn" data-modal="benutzerModal">Mitarbeiter hinzufügen</button>
    </div>

    <div class="module">
        <h3>Systemverwaltung</h3>
        <button class="admin-btn" data-modal="systemModal">Systemeinstellungen</button>
    </div>

    <div class="module">
        <h3>Einstellungen</h3>
        <button class="admin-btn" data-modal="einstellungenModal" data-no-login="true">Einstellungen ändern</button>
    </div>

    <div class="module">
        <h3>Buchhalterische Einstellungen</h3>
        <button class="admin-btn" data-modal="buchhaltungModal">Buchhalterische Einstellungen</button>
    </div>

    <div class="module">
        <h3>Protokolle einsehen</h3>
        <button class="admin-btn" data-modal="protokolleModal">Protokolle einsehen</button>
    </div>

    <div class="module">
        <h3>Backup und Wiederherstellung</h3>
        <button class="admin-btn" data-modal="backupModal">Backup und Wiederherstellung</button>
    </div>
</main>

<!-- ═══════════════════════════════════════════════════════ -->
<!-- MODALS                                                  -->
<!-- ═══════════════════════════════════════════════════════ -->

<!-- Modal: Benutzerverwaltung -->
<div id="benutzerModal" class="modal-overlay">
    <div class="module modal-content">
        <div id="benutzerAdminStatus" class="admin-status">Sie sind als Admin eingeloggt</div>

        <div class="login-box">
            <h3>Admin Anmeldung</h3>
            <p>Geben Sie Admin Kennwort ein</p>
            <input type="password" class="admin-password-input" style="width:100%; padding:8px;">
            <button class="admin-btn login-btn">Anmelden</button>
            <button class="admin-btn admin-btn-secondary cancel-btn">Abbrechen</button>
            <p class="login-fehler">Falsches Kennwort</p>
        </div>

        <form class="content-form" method="post">
            <h3>Neuen Mitarbeiter hinzufügen</h3>

            <label>Geben Sie den Vornamen ein</label><br>
            <input type="text" name="vorname" required style="width:100%; padding:8px; margin-bottom:15px;"><br>

            <label>Geben Sie den Nachnamen ein</label><br>
            <input type="text" name="nachname" required style="width:100%; padding:8px; margin-bottom:15px;"><br>

            <label>Wählen Sie die Rolle</label><br>
            <div style="margin: 10px 0;">
                <input type="radio" id="rolle_mitarbeiter" name="rolle" value="mitarbeiter" checked>
                <label for="rolle_mitarbeiter" style="margin-left: 5px;">Mitarbeiter</label><br>
                <input type="radio" id="rolle_admin" name="rolle" value="admin">
                <label for="rolle_admin" style="margin-left: 5px;">Admin</label>
            </div><br>

            <button type="submit" name="save_mitarbeiter" class="admin-btn">Speichern</button>
            <button type="submit" name="save_mitarbeiter" onclick="this.form.speichern_schliessen.value='1'" class="admin-btn">Speichern und Schließen</button>
            <input type="hidden" name="speichern_schliessen" value="0">
            <button type="button" class="admin-btn admin-btn-secondary close-without-save">Schließen ohne Speichern</button>
        </form>
    </div>
</div>

<!-- Modal: Systemverwaltung -->
<div id="systemModal" class="modal-overlay">
    <div class="module modal-content">
        <div id="systemAdminStatus" class="admin-status">Sie sind als Admin eingeloggt</div>

        <div class="login-box">
            <h3>Admin Anmeldung</h3>
            <p>Geben Sie Admin Kennwort ein</p>
            <input type="password" class="admin-password-input" style="width:100%; padding:8px;">
            <button class="admin-btn login-btn">Anmelden</button>
            <button class="admin-btn admin-btn-secondary cancel-btn">Abbrechen</button>
            <p class="login-fehler">Falsches Kennwort</p>
        </div>

        <div class="content-form">
            <h3>Systemverwaltung</h3>
            <p>Systemverwaltungsfunktionen werden hier angezeigt.</p>

            <button type="button" class="admin-btn">Systemupdates prüfen</button>
            <button type="button" class="admin-btn">Systemdiagnose</button>
            <button type="button" class="admin-btn admin-btn-secondary close-without-save">Schließen</button>
        </div>
    </div>
</div>

<!-- Modal: Einstellungen (ohne Login) -->
<div id="einstellungenModal" class="modal-overlay">
    <div class="module modal-content">
        <h3>Einstellungen</h3>

        <div style="margin: 20px 0;">
            <label style="font-weight: bold;">Theme-Auswahl</label><br>
            <div style="margin: 10px 0;">
                <input type="radio" id="theme_hofladen" name="theme" value="hofladen" checked>
                <label for="theme_hofladen" style="margin-left: 5px;">Hofladen Theme</label><br>
                <input type="radio" id="theme_dunkel" name="theme" value="dunkel">
                <label for="theme_dunkel" style="margin-left: 5px;">Dunkles Theme</label><br>
                <input type="radio" id="theme_hell" name="theme" value="hell">
                <label for="theme_hell" style="margin-left: 5px;">Helles Theme</label>
            </div>
        </div>

        <div style="margin: 20px 0;">
            <label style="font-weight: bold;">Sprache</label><br>
            <div style="margin: 10px 0;">
                <input type="radio" id="sprache_de" name="sprache" value="deutsch" checked>
                <label for="sprache_de" style="margin-left: 5px;">Deutsch</label><br>
                <input type="radio" id="sprache_en" name="sprache" value="englisch">
                <label for="sprache_en" style="margin-left: 5px;">Englisch</label>
            </div>
        </div>

        <div style="margin: 20px 0;">
            <label style="font-weight: bold;">Benachrichtigungen</label><br>
            <div style="margin: 10px 0;">
                <input type="radio" id="notif_an" name="notifications" value="an" checked>
                <label for="notif_an" style="margin-left: 5px;">An</label><br>
                <input type="radio" id="notif_aus" name="notifications" value="aus">
                <label for="notif_aus" style="margin-left: 5px;">Aus</label>
            </div>
        </div>

        <button class="admin-btn" onclick="alert('Einstellungen gespeichert');">Speichern</button>
        <button class="admin-btn admin-btn-secondary" onclick="document.getElementById('einstellungenModal').style.display='none'">Schließen</button>
    </div>
</div>

<!-- Modal: Buchhalterische Einstellungen -->
<div id="buchhaltungModal" class="modal-overlay">
    <div class="module modal-content">
        <div id="buchhaltungAdminStatus" class="admin-status">Sie sind als Admin eingeloggt</div>

        <div class="login-box">
            <h3>Admin Anmeldung</h3>
            <p>Geben Sie Admin Kennwort ein</p>
            <input type="password" class="admin-password-input" style="width:100%; padding:8px;">
            <button class="admin-btn login-btn">Anmelden</button>
            <button class="admin-btn admin-btn-secondary cancel-btn">Abbrechen</button>
            <p class="login-fehler">Falsches Kennwort</p>
        </div>

        <form class="content-form" method="post">
            <h3>Neuen Steuersatz festlegen</h3>

            <label>Neuer Steuersatz (%)</label><br>
            <input type="number" name="steuersatz" min="0" max="100" step="0.01" style="width:100%; padding:8px;"><br><br>

            <button type="submit" name="speichern" class="admin-btn">Speichern</button>
            <button type="submit" name="speichern_schliessen" class="admin-btn">Speichern und Schließen</button>
            <button type="button" class="admin-btn admin-btn-secondary close-without-save">Schließen ohne Speichern</button>
        </form>
    </div>
</div>

<!-- Modal: Protokolle einsehen -->
<div id="protokolleModal" class="modal-overlay">
    <div class="module modal-content">
        <div id="protokolleAdminStatus" class="admin-status">Sie sind als Admin eingeloggt</div>

        <div class="login-box">
            <h3>Admin Anmeldung</h3>
            <p>Geben Sie Admin Kennwort ein</p>
            <input type="password" class="admin-password-input" style="width:100%; padding:8px;">
            <button class="admin-btn login-btn">Anmelden</button>
            <button class="admin-btn admin-btn-secondary cancel-btn">Abbrechen</button>
            <p class="login-fehler">Falsches Kennwort</p>
        </div>

        <div class="content-form">
            <h3>Protokolle einsehen</h3>

            <label>Wählen Sie ein Protokoll</label><br>
            <select id="protokollAuswahl" style="width:100%; padding:8px; margin: 10px 0;">
                <option value="">-- Bitte wählen --</option>
                <option value="protokoll1.jpg">Verkaufsprotokoll</option>
                <option value="protokoll2.jpg">Lagerprotokoll</option>
                <option value="protokoll3.jpg">Systemprotokoll</option>
            </select><br>

            <button type="button" class="admin-btn" onclick="zeigeProtokoll()">Erstellen</button>

            <div id="protokollAnzeige" style="margin-top: 20px; display: none;">
                <img id="protokollBild" src="" alt="Protokoll" style="max-width: 100%; border: 1px solid #ccc;">
            </div>

            <button type="button" class="admin-btn" onclick="speichereProtokoll()">Protokoll speichern</button>
            <button type="button" class="admin-btn admin-btn-secondary close-without-save">Schließen</button>
        </div>
    </div>
</div>

<!-- Modal: Backup und Wiederherstellung -->
<div id="backupModal" class="modal-overlay">
    <div class="module modal-content">
        <div id="backupAdminStatus" class="admin-status">Sie sind als Admin eingeloggt</div>

        <div class="login-box">
            <h3>Admin Anmeldung</h3>
            <p>Geben Sie Admin Kennwort ein</p>
            <input type="password" class="admin-password-input" style="width:100%; padding:8px;">
            <button class="admin-btn login-btn">Anmelden</button>
            <button class="admin-btn admin-btn-secondary cancel-btn">Abbrechen</button>
            <p class="login-fehler">Falsches Kennwort</p>
        </div>

        <div class="content-form">
            <h3>Backup und Wiederherstellung</h3>

            <div style="background: #fff3cd; border: 1px solid #ffc107; padding: 10px; margin: 15px 0; border-radius: 4px;">
                <strong>Achtung!</strong> Backups werden täglich erstellt.
            </div>

            <label>Verzeichnis auswählen</label><br>
            <input type="text" id="backupVerzeichnis" placeholder="z.B. /pfad/zum/backup" style="width:100%; padding:8px; margin: 10px 0;"><br>

            <button type="button" class="admin-btn" onclick="erstelleBackup()">Backup erstellen</button>
            <button type="button" class="admin-btn" onclick="wiederherstellenBackup()">Wiederherstellen</button>
            <button type="button" class="admin-btn admin-btn-secondary close-without-save">Schließen</button>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>/js/admin.js" defer></script>
