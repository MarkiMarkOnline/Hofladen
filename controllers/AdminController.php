<?php
/**
 * AdminController
 * 
 * Verarbeitet Admin-Anmeldung, Mitarbeiter- und Steuersatz-Speicherung.
 */
class AdminController
{
    private AdminModel $model;

    public function __construct(AdminModel $model)
    {
        $this->model = $model;
    }

    // ─── Admin-Passwort prüfen (AJAX) ─────────────────────────

    public function checkAdmin(): void
    {
        $pw = $_POST['password'] ?? '';
        echo $this->model->pruefePasswort($pw) ? 'OK' : 'FAIL';
    }

    // ─── Mitarbeiter speichern (POST) ─────────────────────────

    public function mitarbeiterSpeichern(): void
    {
        $vorname  = $_POST['vorname']  ?? '';
        $nachname = $_POST['nachname'] ?? '';
        $rolle    = (isset($_POST['rolle']) && $_POST['rolle'] === 'admin') ? 1 : 0;

        $this->model->mitarbeiterSpeichern($vorname, $nachname, $rolle);

        $closeModal = isset($_POST['speichern_schliessen']);
        $modalId    = 'benutzerModal';

        if ($closeModal) {
            echo "<script>alert('Mitarbeiter gespeichert'); document.getElementById('$modalId').style.display = 'none';</script>";
        } else {
            echo "<script>alert('Mitarbeiter gespeichert');</script>";
        }
    }

    // ─── Steuersatz speichern (POST) ──────────────────────────

    public function steuersatzSpeichern(): void
    {
        $satz = (float) ($_POST['steuersatz'] ?? 0);

        $this->model->steuersatzSpeichern($satz);

        $closeModal = isset($_POST['speichern_schliessen']);
        $modalId    = 'buchhaltungModal';

        if ($closeModal) {
            echo "<script>alert('Steuersatz gespeichert'); document.getElementById('$modalId').style.display = 'none';</script>";
        } else {
            echo "<script>alert('Steuersatz gespeichert');</script>";
        }
    }
}
