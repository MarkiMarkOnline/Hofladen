// Administration
// Extrahiert aus admin.php (vorher inline)

// ─── Modal-Verwaltung ─────────────────────────────────────

const buttons = document.querySelectorAll('[data-modal]');

buttons.forEach(btn => {
    btn.addEventListener('click', () => {
        const modalId = btn.getAttribute('data-modal');
        const noLogin = btn.getAttribute('data-no-login');
        const modal = document.getElementById(modalId);

        if (modal) {
            modal.style.display = 'block';

            if (noLogin) {
                const loginBox = modal.querySelector('.login-box');
                const contentForm = modal.querySelector('.content-form');
                if (loginBox) loginBox.style.display = 'none';
                if (contentForm) contentForm.style.display = 'block';
            }
        }
    });
});

// ─── Login-Funktionalität ─────────────────────────────────

document.querySelectorAll('.login-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const modal = this.closest('.modal-overlay');
        const passwordInput = modal.querySelector('.admin-password-input');
        const loginBox = modal.querySelector('.login-box');
        const contentForm = modal.querySelector('.content-form');
        const adminStatus = modal.querySelector('.admin-status');
        const loginFehler = modal.querySelector('.login-fehler');

        const pw = passwordInput.value;

        // ✅ NEU: Router-URL statt leerer fetch('')
        fetch('?action=check_admin', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'password=' + encodeURIComponent(pw)
        })
        .then(res => res.text())
        .then(result => {
            if (result === 'OK') {
                loginBox.style.display = 'none';
                contentForm.style.display = 'block';
                if (adminStatus) adminStatus.style.display = 'block';
                alert('Sie sind als Admin eingeloggt');
            } else {
                loginFehler.style.display = 'block';
            }
        });
    });
});

// ─── Abbrechen & Schließen ────────────────────────────────

document.querySelectorAll('.cancel-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        this.closest('.modal-overlay').style.display = 'none';
    });
});

document.querySelectorAll('.close-without-save').forEach(btn => {
    btn.addEventListener('click', function() {
        this.closest('.modal-overlay').style.display = 'none';
    });
});

// ─── Protokoll-Funktionen ─────────────────────────────────

function zeigeProtokoll() {
    const auswahl = document.getElementById('protokollAuswahl').value;
    const anzeige = document.getElementById('protokollAnzeige');
    const bild = document.getElementById('protokollBild');

    if (auswahl) {
        bild.src = '/pfad/zu/protokollen/' + auswahl;
        anzeige.style.display = 'block';
    } else {
        alert('Bitte wählen Sie ein Protokoll aus');
    }
}

function speichereProtokoll() {
    const auswahl = document.getElementById('protokollAuswahl').value;
    if (auswahl) {
        alert('Protokoll wird gespeichert: ' + auswahl);
    } else {
        alert('Kein Protokoll zum Speichern ausgewählt');
    }
}

// ─── Backup-Funktionen ───────────────────────────────────

function erstelleBackup() {
    const verzeichnis = document.getElementById('backupVerzeichnis').value;
    if (verzeichnis) {
        alert('Backup wird erstellt im Verzeichnis: ' + verzeichnis);
    } else {
        alert('Bitte geben Sie ein Verzeichnis an');
    }
}

function wiederherstellenBackup() {
    const verzeichnis = document.getElementById('backupVerzeichnis').value;
    if (verzeichnis) {
        if (confirm('Möchten Sie wirklich das Backup wiederherstellen? Alle aktuellen Daten werden überschrieben!')) {
            alert('Backup wird wiederhergestellt aus: ' + verzeichnis);
        }
    } else {
        alert('Bitte geben Sie ein Verzeichnis an');
    }
}
