<?php
// 1. CRUCIAL : On démarre la session au tout début pour que PHP s'en souvienne sur tout le site !
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// On inclut la connexion à la BDD
require_once 'includes/db.php';

$message = "";

// 2. Si le parent est DÉJÀ connecté, on le laisse aller directement à l'accueil
if (isset($_SESSION['parent_id'])) {
    header("Location: accueil.php");
    exit();
}

// On vérifie si le formulaire de connexion a été soumis
if (isset($_POST['connexion'])) {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];

    // 1. On cherche si l'email existe dans la table parents
    $stmt = $pdo->prepare("SELECT * FROM parents WHERE email = ?");
    $stmt->execute([$email]);
    $parent = $stmt->fetch();

    // 2. Si le parent existe, on vérifie si le mot de passe correspond au hash en BDD
    if ($parent && password_verify($password, $parent['mot_de_passe'])) {
        
        // 3. Connexion réussie : On stocke ses infos dans la Session globale
        $_SESSION['parent_id'] = $parent['id'];
        $_SESSION['parent_nom'] = $parent['nom'];
        $_SESSION['parent_email'] = $parent['email'];

        // On force l'écriture immédiate de la session pour éviter les pertes pendant la redirection
        session_write_close();

        // 4. Redirection vers la page d'accueil générale
        header("Location: accueil.php");
        exit();
    } else {
        // En cas d'erreur, on reste vague par sécurité
        $message = "<p style='color: red; font-weight: bold; text-align: center;'>Adresse email ou mot de passe incorrect.</p>";
    }
}

$pageCss = 'style.css/login.css';

?>

<?php include 'includes/header.php'; ?>

<section class="login-section">
    <div class="login-container">
        <h2>Espace Parent ASCITECH</h2>
        <p>Connectez-vous pour accéder au suivi, inscriptions et paiements.</p>

        <?php echo $message; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="email">Adresse Email :</label>
                <input type="email" name="email" id="email" required placeholder="exemple@email.com">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password" required placeholder="Votre mot de passe">
            </div>

            <button type="submit" name="connexion" class="btn-submit">Se connecter</button>
            
            <div class="form-footer">
                <p>Nouveau sur ASCITECH ? <a href="creer_compte.php" class="btn-link">Créer un compte parent</a></p>
            </div>
        </form>
    </div>
</section>

<?php include 'includes/footer.php'; ?>