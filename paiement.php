<?php
// Cette page est privee : redirection vers connexion.php si pas connecte
require_once 'includes/auth_check.php';
require_once 'includes/db.php';

$message = '';
$eleve = null;

$tarifs = [
    'mat' => 450,
    'pri' => 500,
    'eb' => 550,
    'sci_1' => 650,
    'sci_2' => 650,
    'sci_3' => 650,
    'sci_4' => 750,
    'tech_1' => 700,
    'tech_2' => 700,
    'tech_3' => 700,
    'tech_4' => 800,
    'com_1' => 600,
    'com_2' => 600,
    'com_3' => 600,
    'com_4' => 650
];

try {
    $stmt = $pdo->prepare(
        "SELECT nom, prenom, classe_choisie
         FROM inscriptions
         WHERE parent_id = ?
         ORDER BY id DESC
         LIMIT 1"
    );
    $stmt->execute([$_SESSION['parent_id']]);
    $eleve = $stmt->fetch();
} catch (PDOException $e) {
    $message = "<div class='payment-alert error'>Impossible de charger les informations de l'eleve.</div>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $eleve) {
    $classKey = $_POST['classe'] ?? '';
    $periode = $_POST['periode'] ?? 'trim';
    $modePaiement = $_POST['moyen'] ?? 'Mobile Money';

    if (!isset($tarifs[$classKey])) {
        $message = "<div class='payment-alert error'>Classe invalide pour le paiement.</div>";
    } else {
        $base = $tarifs[$classKey];
        $montantScolarite = ($periode === 'annuel') ? $base : ($base / 3);
        $remise = ($periode === 'annuel') ? ($montantScolarite * 0.05) : 0;
        $fraisService = ($montantScolarite - $remise) * 0.02;
        $montantTotal = ($montantScolarite - $remise) + $fraisService;
        $motif = ($periode === 'annuel') ? 'Scolarite annuelle' : 'Scolarite trimestrielle';
        $reference = 'ASC-' . date('YmdHis') . '-' . random_int(100, 999);

        try {
            $stmt = $pdo->prepare(
                "INSERT INTO paiements
                    (parent_id, eleve_nom, eleve_prenom, motif, montant, devise, mode_paiement, reference_transaction, date_paiement)
                 VALUES
                    (?, ?, ?, ?, ?, 'USD', ?, ?, CURDATE())"
            );
            $stmt->execute([
                $_SESSION['parent_id'],
                $eleve['nom'],
                $eleve['prenom'],
                $motif,
                round($montantTotal, 2),
                $modePaiement,
                $reference
            ]);

            $message = "<div class='payment-alert success'>Paiement enregistre avec succes. Reference : " . htmlspecialchars($reference) . "</div>";
        } catch (PDOException $e) {
            $message = "<div class='payment-alert error'>Erreur lors de l'enregistrement du paiement.</div>";
        }
    }
}

$pageCss = 'style.css/paiement.css';
include 'includes/header.php';
?>

<main class="payment-hero">
    <div class="container">
        <div class="payment-grid">
            <section class="payment-form-box">
                <div class="form-intro">
                    <h1>Portail de Paiement</h1>
                    <p>Reglez les frais de scolarite en toute securite.</p>
                </div>

                <?php echo $message; ?>

                <?php if (!$eleve): ?>
                    <div class="payment-alert error">Aucun eleve inscrit n'est rattache a ce compte parent.</div>
                <?php endif; ?>

                <form id="feeForm" method="POST" action="paiement.php">
                    <div class="form-section">
                        <h3>1. Information de l'eleve</h3>
                        <div class="input-grid">
                            <div class="field">
                                <label>Nom complet de l'eleve</label>
                                <input type="text" value="<?php echo htmlspecialchars($eleve ? $eleve['prenom'] . ' ' . $eleve['nom'] : ''); ?>" placeholder="Prenom et Nom" readonly required>
                            </div>
                            <div class="field">
                                <label>Classe inscrite</label>
                                <input type="text" value="<?php echo htmlspecialchars($eleve['classe_choisie'] ?? ''); ?>" placeholder="Classe" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>2. Scolarite & Niveau</h3>
                        <div class="field">
                            <label>Classe et option</label>
                            <select id="classSelector" name="classe" required>
                                <optgroup label="Cycles de Base">
                                    <option value="mat">Maternelle (Toutes sections)</option>
                                    <option value="pri">Primaire (1ere a 6eme)</option>
                                    <option value="eb">Education de Base (7eme & 8eme)</option>
                                </optgroup>
                                <optgroup label="Humanites Scientifiques">
                                    <option value="sci_1">1ere Scientifique</option>
                                    <option value="sci_2">2eme Scientifique</option>
                                    <option value="sci_3">3eme Scientifique</option>
                                    <option value="sci_4">4eme Scientifique (Finaliste)</option>
                                </optgroup>
                                <optgroup label="Humanites Techniques">
                                    <option value="tech_1">1ere Techniques Industrielles</option>
                                    <option value="tech_2">2eme Techniques Industrielles</option>
                                    <option value="tech_3">3eme Techniques Industrielles</option>
                                    <option value="tech_4">4eme Techniques Industrielles (Finaliste)</option>
                                </optgroup>
                                <optgroup label="Humanites Commerciales & Litteraires">
                                    <option value="com_1">1ere Comm / Litteraire</option>
                                    <option value="com_2">2eme Comm / Litteraire</option>
                                    <option value="com_3">3eme Comm / Litteraire</option>
                                    <option value="com_4">4eme Comm / Litteraire (Finaliste)</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="field">
                            <label>Type de paiement</label>
                            <div class="payment-type-options">
                                <label class="payment-type-option">
                                    <input type="radio" id="accompanteOption" name="periode" value="trim" checked>
                                    <div class="type-card">
                                        <span class="type-title">Acompte (1 trimestre)</span>
                                        <span class="type-desc">Paiement partiel pour le premier trimestre de l'année scolaire</span>
                                        <span class="type-amount" id="acompteAmount">0.00 $</span>
                                    </div>
                                </label>
                                <label class="payment-type-option">
                                    <input type="radio" id="totalOption" name="periode" value="annuel">
                                    <div class="type-card">
                                        <span class="type-title">Totalité des frais (Année complète)</span>
                                        <span class="type-desc">Paiement complet pour toute l'année scolaire avec 5% de remise</span>
                                        <span class="type-amount" id="totalAmount">0.00 $</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>3. Moyen de Paiement</h3>
                        <div class="payment-methods">
                            <label class="method-option">
                                <input type="radio" name="moyen" value="Mobile Money" checked>
                                <div class="method-card">Mobile Money</div>
                            </label>
                            <label class="method-option">
                                <input type="radio" name="moyen" value="Carte Bancaire">
                                <div class="method-card">Carte Bancaire</div>
                            </label>
                        </div>
                    </div>

                    <div class="form-section payment-recap">
                        <h3>4. Recapitulatif du Paiement</h3>
                        <div class="recap-box">
                            <div class="recap-line">
                                <span class="recap-label">Type de paiement:</span>
                                <span class="recap-value" id="paymentType">Acompte (1 trimestre)</span>
                            </div>
                            <div class="recap-line">
                                <span class="recap-label">Montant à payer:</span>
                                <span class="recap-value recap-amount" id="recapAmount">0.00 $</span>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="montant_total" id="amountInput" value="0">

                    <button type="submit" class="pay-button" <?php echo $eleve ? '' : 'disabled'; ?>>
                        Confirmer le paiement de <span id="btnAmount">0.00</span> $
                    </button>
                </form>
            </section>

            <aside class="payment-summary">
                <div class="summary-card">
                    <h3>Details</h3>
                    <div class="summary-line"><span>Scolarite</span><span id="baseVal">0.00 $</span></div>
                    <div class="summary-line"><span>Frais service (2%)</span><span id="taxVal">0.00 $</span></div>
                    <div class="summary-line promo" id="promoLine" style="display:none;">
                        <span>Remise</span><span id="promoVal">- 0.00 $</span>
                    </div>
                    <hr>
                    <div class="summary-total"><span>Total</span><span id="totalVal">0.00 $</span></div>
                </div>
            </aside>
        </div>
    </div>
</main>

<script src="image/script.js"></script>

<?php include 'includes/footer.php'; ?>
