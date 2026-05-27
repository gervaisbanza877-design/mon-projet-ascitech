<?php
// On inclut le fichier de connexion à la BDD
require_once 'includes/db.php';

$message = ""; // Variable pour afficher un message de succès ou d'erreur

// On vérifie si le formulaire a été soumis
if (isset($_POST['inscription_parent'])) {
    // Récupération et nettoyage rapide des données reçues
    $nom = htmlspecialchars(trim($_POST['nom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];

    // 1. Sécurité : On hache (crypte) le mot de passe
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        // 2. Préparation de la requête pour éviter les injections SQL
        $stmt = $pdo->prepare("INSERT INTO parents (nom, email, mot_de_passe) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $email, $password_hash]);

        $message = "<p style='color: green; font-weight: bold;'>Compte créé avec succès ! <a href='connexion.php'>Connectez-vous ici</a></p>";
    } catch (PDOException $e) {
        // Si l'email existe déjà (contrainte UNIQUE dans la BDD)
        if ($e->getCode() == 23000) {
            $message = "<p style='color: red; font-weight: bold;'>Cette adresse email est déjà utilisée.</p>";
        } else {
            $message = "<p style='color: red; font-weight: bold;'>Une erreur est survenue lors de l'inscription.</p>";
        }
    }
}

$pageCss = 'style.css/login.css';
?>

<?php include 'includes/header.php'; ?>

<section class="register-section">
    <div class="register-container">
        <h2>Créer un compte Parent</h2>
        <p>Inscrivez-vous pour gérer les inscriptions et paiements de vos enfants.</p>

        <?php echo $message; ?>

        <form action="creer_compte.php" method="POST">
            <div class="form-group">
                <label for="nom">Nom complet :</label>
                <input type="text" name="nom" id="nom" required placeholder="Ex: Jean Mukendi">
            </div>

            <div class="form-group">
                <label for="email">Adresse Email :</label>
                <input type="email" name="email" id="email" required placeholder="parent@example.com">
            </div>

            <div class="form-group">
                <label for="password">Créer un mot de passe :</label>
                <input type="password" name="password" id="password" required placeholder="Choisissez un mot de passe sécurisé">
            </div>

            <button type="submit" name="inscription_parent" class="btn-submit">Créer mon compte</button>
            
            <div class="form-footer">
                <p>Vous avez déjà un compte ? <a href="connexion.php" class="btn-link">Se connecter</a></p>
            </div>
        </form>
    </div>
</section>

<?php include 'includes/footer.php'; ?>