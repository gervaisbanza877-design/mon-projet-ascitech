<?php 
// 1. Protection de la page : le parent doit être connecté
require_once 'includes/auth_check.php'; 

// 2. Connexion à la base de données
require_once 'includes/db.php';

$message = "";

// 3. Traitement du formulaire lors du clic sur le bouton d'envoi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et sécurisation des données saisies
    $nom = htmlspecialchars(trim($_POST['nom']));
    $postnom = htmlspecialchars(trim($_POST['postnom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $date_naissance = htmlspecialchars(trim($_POST['date_naissance']));
    $genre = htmlspecialchars(trim($_POST['genre']));
    $adresse = htmlspecialchars(trim($_POST['adresse']));
    
    $nom_pere = htmlspecialchars(trim($_POST['nom_pere']));
    $tel_pere = htmlspecialchars(trim($_POST['tel_pere']));
    $nom_mere = htmlspecialchars(trim($_POST['nom_mere']));
    $tel_mere = htmlspecialchars(trim($_POST['tel_mere']));
    $tuteur = htmlspecialchars(trim($_POST['tuteur']));
    
    // Récupération de la classe / option selon le choix
    $classe_primaire = htmlspecialchars(trim($_POST['classe_primaire']));
    $annee_humanite = htmlspecialchars(trim($_POST['annee_humanite']));
    $option_humanite = htmlspecialchars(trim($_POST['option_humanite']));

    // On détermine la classe finale choisie
    $classe_finale = !empty($option_humanite) ? $annee_humanite . " - " . $option_humanite : $classe_primaire;
    
    // Récupération de l'ID du parent connecté grâce à la session
    $parent_id = $_SESSION['parent_id'];

    try {
        $checkSql = "SELECT id
                     FROM inscriptions
                     WHERE LOWER(TRIM(nom)) = LOWER(TRIM(?))
                       AND LOWER(TRIM(postnom)) = LOWER(TRIM(?))
                       AND LOWER(TRIM(prenom)) = LOWER(TRIM(?))
                       AND date_naissance = ?
                     LIMIT 1";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([$nom, $postnom, $prenom, $date_naissance]);

        if ($checkStmt->fetch()) {
            $message = "<div class='alert alert-warning text-center fw-bold'>Cet eleve existe deja dans la base de donnees. L'inscription n'a pas ete enregistree une deuxieme fois.</div>";
        } else {
        // Préparation de la requête d'insertion SQL
        $sql = "INSERT INTO inscriptions (parent_id, nom, postnom, prenom, date_naissance, genre, adresse, nom_pere, tel_pere, nom_mere, tel_mere, tuteur, classe_choisie) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $parent_id, $nom, $postnom, $prenom, $date_naissance, $genre, $adresse, 
            $nom_pere, $tel_pere, $nom_mere, $tel_mere, $tuteur, $classe_finale
        ]);

        $message = "<div class='alert alert-success text-center fw-bold'>🎉 Fiche d'inscription enregistrée avec succès pour l'élève $prenom $nom !</div>";
        }
    } catch (PDOException $e) {
        $message = "<div class='alert alert-danger text-center fw-bold'>❌ Erreur lors de l'enregistrement : " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASCITECH | Excellence & Technologie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <div class="hero-section text-center text-white py-5">
        <div class="container">
            <h1 class="display-4 fw-bold mb-2">Rejoignez l'élite</h1>
            <p class="lead opacity-75">Inscrivez-vous dès aujourd'hui pour l'année scolaire 2025-2026</p>
        </div>
    </div>

    <div class="container pb-5 mt-n5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <form action="inscription.php" method="POST" class="form-prestige shadow-2xl bg-white animate-fade-in">
                    <div class="premium-gradient-bar"></div>

                    <div class="p-4 p-md-5">
                        <div class="text-center mb-5">
                            <span class="badge bg-gold-soft or-txt mb-2 px-3">INSCRIPTION OFFICIELLE</span>
                            <h2 class="section-main-title">FICHE D'INSCRIPTION</h2>
                        </div>

                        <?php echo $message; ?>

                        <div class="section-card mb-4">
                            <h6 class="titre-orné">I. IDENTITÉ DE L'ÉLÈVE</h6>
                            <div class="row g-4 mt-1">
                                <div class="col-md-4">
                                    <label class="custom-label">Nom</label>
                                    <input type="text" name="nom" class="form-input" placeholder="Nom" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="custom-label">Postnom</label>
                                    <input type="text" name="postnom" class="form-input" placeholder="Postnom" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="custom-label">Prénom</label>
                                    <input type="text" name="prenom" class="form-input" placeholder="Prénom" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="custom-label">Date de naissance</label>
                                    <input type="date" name="date_naissance" class="form-input" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="custom-label">Genre</label>
                                    <select name="genre" class="form-input" required>
                                        <option value="Masculin">Masculin</option>
                                        <option value="Féminin">Féminin</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="section-card mb-4">
                            <h6 class="titre-orné">II. PARENTS ET TUTEURS</h6>
                            <div class="row g-4 mt-1">
                                <div class="col-md-6">
                                    <div class="sub-card">
                                        <p class="sub-card-title">PÈRE</p>
                                        <input type="text" name="nom_pere" class="form-input mb-3" placeholder="Nom complet du père">
                                        <input type="tel" name="tel_pere" class="form-input" placeholder="N° Téléphone">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="sub-card">
                                        <p class="sub-card-title">MÈRE</p>
                                        <input type="text" name="nom_mere" class="form-input mb-3" placeholder="Nom complet de la mère">
                                        <input type="tel" name="tel_mere" class="form-input" placeholder="N° Téléphone">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="sub-card border-gold-light">
                                        <p class="sub-card-title text-dark">ADRESSE & TUTEUR</p>
                                        <input type="text" name="adresse" class="form-input mb-3" placeholder="Adresse complète de résidence" required>
                                        <input type="text" name="tuteur" class="form-input" placeholder="Nom du Tuteur (si applicable)">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="section-card mb-5">
                            <h6 class="titre-orné">III. CURSUS SCOLAIRE</h6>

                            <div class="orientation-box mt-3">
                                <input type="radio" name="cycle" id="prim" class="d-none" checked>
                                <input type="radio" name="cycle" id="hum" class="d-none">

                                <div class="selector-pills mb-4">
                                    <label for="prim" class="pill-btn">PRIMAIRE / EB</label>
                                    <label for="hum" class="pill-btn">HUMANITÉS</label>
                                </div>

                                <div class="content-prim">
                                    <label class="custom-label">Choisir la classe (Primaire / EB)</label>
                                    <select name="classe_primaire" class="form-input">
                                        <option value="">-- Choisir la classe --</option>
                                        <option value="1ère Primaire">1ère Primaire</option>
                                        <option value="2ème Primaire">2ème Primaire</option>
                                        <option value="3ème Primaire">3ème Primaire</option>
                                        <option value="4ème Primaire">4ème Primaire</option>
                                        <option value="5ème Primaire">5ème Primaire</option>
                                        <option value="6ème Primaire">6ème Primaire</option>
                                        <option value="7ème Année EB">7ème Année EB</option>
                                        <option value="8ème Année EB">8ème Année EB</option>
                                    </select>
                                </div>

                                <div class="content-hum">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="custom-label">Année</label>
                                            <select name="annee_humanite" class="form-input">
                                                <option value="">-- Choisir l'année --</option>
                                                <option value="1ère Humanités">1ère Humanités</option>
                                                <option value="2ème Humanités">2ème Humanités</option>
                                                <option value="3ème Humanités">3ème Humanités</option>
                                                <option value="4ème Humanités">4ème Humanités</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="custom-label">Option de spécialisation</label>
                                            <select name="option_humanite" class="form-input highlight-border">
                                                <option value="">-- Sélectionner l'option --</option>
                                                <optgroup label="Technique Industrielle">
                                                    <option value="Électronique">Électronique</option>
                                                    <option value="Électricité">Électricité</option>
                                                    <option value="Mécanique Industrielle">Mécanique Industrielle</option>
                                                </optgroup>
                                                <optgroup label="Gestion & Sciences">
                                                    <option value="Informatique de Gestion">Informatique de Gestion</option>
                                                    <option value="Commerciale et Gestion">Commerciale et Gestion</option>
                                                    <option value="Sciences (Math-Phys / Bio-Chim)">Sciences (Math-Phys / Bio-Chim)</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center pt-3">
                            <button type="submit" class="btn btn-elegant-green btn-lg w-100 shadow-lg">
                                VALIDER L'INSCRIPTION
                            </button>
                            <p class="text-muted mt-4 x-small">Document officiel à valeur juridique une fois déposé physiquement.</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
