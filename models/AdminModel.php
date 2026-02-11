<?php
/**
 * AdminModel
 * 
 * Verwaltung von Mitarbeitern, Steuersätzen und Admin-Authentifizierung.
 */
class AdminModel
{
    private PDO $pdo;

    // Admin-Passwort-Hash (bcrypt)
    private string $adminHash = '$2y$10$tGycFsMDxO/DCcFSiLp9Bul27H0H6k0F8O2.1ulYPcDyRQrPN0IPO';

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // ─── Admin-Login prüfen ───────────────────────────────────

    public function pruefePasswort(string $passwort): bool
    {
        return password_verify($passwort, $this->adminHash);
    }

    // ─── Mitarbeiter speichern ────────────────────────────────

    public function mitarbeiterSpeichern(string $vorname, string $nachname, int $rolle): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO t_mitarbeiter (vorname, nachname, rolle) VALUES (:vorname, :nachname, :rolle)"
        );
        return $stmt->execute([
            ':vorname'  => $vorname,
            ':nachname' => $nachname,
            ':rolle'    => $rolle,
        ]);
    }

    // ─── Steuersatz speichern ─────────────────────────────────

    public function steuersatzSpeichern(float $satz): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO t_mwst (mwst) VALUES (:mwst)");
        return $stmt->execute([':mwst' => $satz]);
    }
}
