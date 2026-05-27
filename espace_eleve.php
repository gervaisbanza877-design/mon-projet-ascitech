<?php
require_once 'includes/auth_check.php';
require_once 'includes/db.php';

$parentNom = $_SESSION['parent_nom'] ?? 'Parent';
$parentEmail = $_SESSION['parent_email'] ?? '';
$eleve = null;
$messageErreur = '';
$pageCss = 'style.css/espace_eleve.css';

try {
    $stmt = $pdo->prepare(
        "SELECT id, nom, postnom, prenom, date_naissance, genre, adresse,
                nom_pere, tel_pere, nom_mere, tel_mere, tuteur, classe_choisie
         FROM inscriptions
         WHERE parent_id = ?
         ORDER BY id DESC
         LIMIT 1"
    );
    $stmt->execute([$_SESSION['parent_id']]);
    $eleve = $stmt->fetch();
} catch (PDOException $e) {
    $messageErreur = "Impossible de charger les informations de l'eleve pour le moment.";
}

$nomCompletEleve = '';
if ($eleve) {
    $nomCompletEleve = trim($eleve['prenom'] . ' ' . $eleve['nom'] . ' ' . $eleve['postnom']);
}

$tarifsScolarite = [
    'mat' => 450,
    'pri' => 500,
    'eb' => 550,
    'sci' => 650,
    'sci_final' => 750,
    'tech' => 700,
    'tech_final' => 800,
    'com' => 600,
    'com_final' => 650
];

function fraisAnnuelClasse($classe, $tarifs)
{
    $classe = strtolower($classe ?? '');
    $estFinaliste = strpos($classe, '4') !== false || strpos($classe, 'final') !== false;

    if (strpos($classe, 'primaire') !== false) {
        return $tarifs['pri'];
    }

    if (strpos($classe, 'eb') !== false) {
        return $tarifs['eb'];
    }

    if (strpos($classe, 'science') !== false || strpos($classe, 'scient') !== false) {
        return $estFinaliste ? $tarifs['sci_final'] : $tarifs['sci'];
    }

    if (strpos($classe, 'electron') !== false || strpos($classe, 'electric') !== false || strpos($classe, 'mecanique') !== false) {
        return $estFinaliste ? $tarifs['tech_final'] : $tarifs['tech'];
    }

    if (strpos($classe, 'informatique') !== false || strpos($classe, 'commercial') !== false || strpos($classe, 'gestion') !== false) {
        return $estFinaliste ? $tarifs['com_final'] : $tarifs['com'];
    }

    return $tarifs['pri'];
}

$totalFrais = 0;
$totalPaye = 0;

if ($eleve) {
    $totalFrais = fraisAnnuelClasse($eleve['classe_choisie'], $tarifsScolarite);

    try {
        $stmt = $pdo->prepare(
            "SELECT COALESCE(SUM(montant), 0) AS total_paye
             FROM paiements
             WHERE parent_id = ?
               AND eleve_nom = ?
               AND eleve_prenom = ?"
        );
        $stmt->execute([$_SESSION['parent_id'], $eleve['nom'], $eleve['prenom']]);
        $paiement = $stmt->fetch();
        $totalPaye = (float) ($paiement['total_paye'] ?? 0);
    } catch (PDOException $e) {
        $messageErreur = "Impossible de charger la situation financiere pour le moment.";
    }
}

$resteAPayer = max($totalFrais - $totalPaye, 0);
$situationFinanciere = [
    'total' => number_format($totalFrais, 2, ',', ' ') . ' $',
    'paye' => number_format($totalPaye, 2, ',', ' ') . ' $',
    'reste' => number_format($resteAPayer, 2, ',', ' ') . ' $',
    'statut' => $resteAPayer <= 0 && $totalFrais > 0 ? 'Frais soldes' : 'Paiement en cours'
];

$resultatsCours = [];

if ($eleve) {
    try {
        $stmt = $pdo->prepare(
            "SELECT cours, note, appreciation
             FROM resultats
             WHERE inscription_id = ?
             ORDER BY date_evaluation DESC, id DESC"
        );
        $stmt->execute([$eleve['id']]);
        $resultatsCours = $stmt->fetchAll();
    } catch (PDOException $e) {
        $resultatsCours = [];
    }
}

if (empty($resultatsCours)) {
    $resultatsCours = [
        ['cours' => 'Mathematiques', 'note' => '-', 'appreciation' => 'Non publie'],
        ['cours' => 'Francais', 'note' => '-', 'appreciation' => 'Non publie'],
        ['cours' => 'Sciences', 'note' => '-', 'appreciation' => 'Non publie']
    ];
}

$horaireCours = [
    ['jour' => 'Lundi', 'heure' => '08h00 - 12h00', 'cours' => 'Cours generaux'],
    ['jour' => 'Mardi', 'heure' => '08h00 - 12h00', 'cours' => 'Cours generaux'],
    ['jour' => 'Mercredi', 'heure' => '08h00 - 12h00', 'cours' => 'Cours pratiques']
];

$evaluations = [
    ['titre' => 'Interrogation', 'date' => 'A planifier', 'statut' => 'En attente'],
    ['titre' => 'Devoir surveille', 'date' => 'A planifier', 'statut' => 'En attente'],
    ['titre' => 'Examen periode', 'date' => 'A planifier', 'statut' => 'En attente']
];

include 'includes/header.php';
?>

<main class="student-space">
    <div class="student-container">
        <section class="student-hero">
            <div class="student-panel">
                <div class="eyebrow">Espace eleve</div>
                <h1>Bienvenue, <?php echo htmlspecialchars($parentNom); ?></h1>
                <p>
                    Consultez le profil de l'eleve rattache a votre compte parent et accedez
                    rapidement aux services essentiels d'ASCITECH.
                </p>
                <div class="student-actions">
                    <a class="student-btn secondary" href="paiement.php">Effectuer un paiement</a>
                    <a class="student-btn light" href="contact.php">Contacter l'ecole</a>
                </div>
            </div>

            <aside class="student-card">
                <div class="eyebrow">Compte parent</div>
                <div class="profile-name"><?php echo htmlspecialchars($parentNom); ?></div>
                <?php if (!empty($parentEmail)): ?>
                    <div class="profile-email"><?php echo htmlspecialchars($parentEmail); ?></div>
                <?php endif; ?>
            </aside>
        </section>

        <h2 class="section-title">Profil de l'eleve</h2>

        <?php if (!empty($messageErreur)): ?>
            <div class="alert-error"><?php echo htmlspecialchars($messageErreur); ?></div>
        <?php elseif (empty($eleve)): ?>
            <div class="empty-state">
                <h3>Aucun eleve rattache a ce compte</h3>
                <p>Commencez par remplir une fiche d'inscription afin d'afficher le profil de l'eleve ici.</p>
            </div>
        <?php else: ?>
            <section class="student-profile">
                <div class="profile-header">
                    <div class="avatar-circle">
                        <?php echo htmlspecialchars(strtoupper(substr($eleve['prenom'] ?: $eleve['nom'], 0, 1))); ?>
                    </div>
                    <div>
                        <div class="eyebrow">Identite</div>
                        <h3><?php echo htmlspecialchars($nomCompletEleve); ?></h3>
                        <span class="badge-class">
                            <?php echo htmlspecialchars($eleve['classe_choisie'] ?: 'Classe non definie'); ?>
                        </span>
                    </div>
                </div>

                <div class="profile-grid">
                    <div class="info-box">
                        <span class="info-label">Nom</span>
                        <strong><?php echo htmlspecialchars($eleve['nom'] ?: '-'); ?></strong>
                    </div>
                    <div class="info-box">
                        <span class="info-label">Postnom</span>
                        <strong><?php echo htmlspecialchars($eleve['postnom'] ?: '-'); ?></strong>
                    </div>
                    <div class="info-box">
                        <span class="info-label">Prenom</span>
                        <strong><?php echo htmlspecialchars($eleve['prenom'] ?: '-'); ?></strong>
                    </div>
                    <div class="info-box">
                        <span class="info-label">Genre</span>
                        <strong><?php echo htmlspecialchars($eleve['genre'] ?: '-'); ?></strong>
                    </div>
                    <div class="info-box">
                        <span class="info-label">Date de naissance</span>
                        <strong><?php echo htmlspecialchars($eleve['date_naissance'] ?: '-'); ?></strong>
                    </div>
                    <div class="info-box">
                        <span class="info-label">Classe choisie</span>
                        <strong><?php echo htmlspecialchars($eleve['classe_choisie'] ?: '-'); ?></strong>
                    </div>
                    <div class="info-box wide">
                        <span class="info-label">Adresse</span>
                        <strong><?php echo htmlspecialchars($eleve['adresse'] ?: '-'); ?></strong>
                    </div>
                </div>

                <div class="guardian-section">
                    <h3>Informations parentales</h3>
                    <div class="profile-grid">
                        <div class="info-box">
                            <span class="info-label">Pere</span>
                            <strong><?php echo htmlspecialchars($eleve['nom_pere'] ?: '-'); ?></strong>
                            <small><?php echo htmlspecialchars($eleve['tel_pere'] ?: 'Telephone non renseigne'); ?></small>
                        </div>
                        <div class="info-box">
                            <span class="info-label">Mere</span>
                            <strong><?php echo htmlspecialchars($eleve['nom_mere'] ?: '-'); ?></strong>
                            <small><?php echo htmlspecialchars($eleve['tel_mere'] ?: 'Telephone non renseigne'); ?></small>
                        </div>
                        <div class="info-box">
                            <span class="info-label">Tuteur</span>
                            <strong><?php echo htmlspecialchars($eleve['tuteur'] ?: '-'); ?></strong>
                        </div>
                    </div>
                </div>
            </section>

            <section class="dashboard-section">
                <h2 class="section-title">Tableau de bord</h2>
                <div class="dashboard-grid">
                    <article class="dashboard-card finance-card">
                        <div class="dashboard-card-head">
                            <div>
                                <span class="eyebrow">Frais scolaires</span>
                                <h3>Situation financiere</h3>
                            </div>
                            <a class="mini-link" href="paiement.php">Payer</a>
                        </div>
                        <div class="finance-summary">
                            <div>
                                <span>Total</span>
                                <strong><?php echo htmlspecialchars($situationFinanciere['total']); ?></strong>
                            </div>
                            <div>
                                <span>Paye</span>
                                <strong><?php echo htmlspecialchars($situationFinanciere['paye']); ?></strong>
                            </div>
                            <div>
                                <span>Reste</span>
                                <strong><?php echo htmlspecialchars($situationFinanciere['reste']); ?></strong>
                            </div>
                        </div>
                        <p class="status-line"><?php echo htmlspecialchars($situationFinanciere['statut']); ?></p>
                    </article>

                    <article class="dashboard-card">
                        <span class="eyebrow">Cours</span>
                        <h3>Resultats</h3>
                        <div class="list-board">
                            <?php foreach ($resultatsCours as $resultat): ?>
                                <div class="list-row">
                                    <span><?php echo htmlspecialchars($resultat['cours']); ?></span>
                                    <strong><?php echo htmlspecialchars($resultat['note']); ?></strong>
                                    <small><?php echo htmlspecialchars($resultat['appreciation']); ?></small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </article>

                    <article class="dashboard-card">
                        <span class="eyebrow">Semaine</span>
                        <h3>Horaire de cours</h3>
                        <div class="list-board">
                            <?php foreach ($horaireCours as $horaire): ?>
                                <div class="list-row schedule-row">
                                    <span><?php echo htmlspecialchars($horaire['jour']); ?></span>
                                    <strong><?php echo htmlspecialchars($horaire['heure']); ?></strong>
                                    <small><?php echo htmlspecialchars($horaire['cours']); ?></small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </article>

                    <article class="dashboard-card">
                        <span class="eyebrow">Evaluation</span>
                        <h3>Evaluations de l'ecole</h3>
                        <div class="list-board">
                            <?php foreach ($evaluations as $evaluation): ?>
                                <div class="list-row evaluation-row">
                                    <span><?php echo htmlspecialchars($evaluation['titre']); ?></span>
                                    <strong><?php echo htmlspecialchars($evaluation['date']); ?></strong>
                                    <small><?php echo htmlspecialchars($evaluation['statut']); ?></small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </article>
                </div>
            </section>

            <div class="profile-note">
                Si plusieurs inscriptions existent pour ce parent, cette page affiche la plus recente.
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
