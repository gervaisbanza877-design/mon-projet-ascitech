 <?php 
// Cette page est privée : redirection vers connexion.php si pas connecté
require_once 'includes/auth_check.php'; 
require_once 'includes/db.php';
$pageCss = 'style.css/cycles.css';
include 'includes/header.php'; 
?>

    <section class="page-banner">
        <div class="container">
            <h1>Parcours d'Excellence</h1>
            <p>De la fondation à la spécialisation technique</p>
        </div>
    </section>

    <section class="cycles-section">
        <div class="container">
            <div class="timeline-line"></div>

            <div class="cycle-block">
                <div class="cycle-card">
                    <div class="cycle-content">
                        <span class="badge-cycle">Étape 01 — Fondamentaux</span>
                        <h2><span class="gold-txt"> Cycle Primaire</span></h2>
                        <p>Le début d'une aventure intellectuelle. Nous mettons l'accent sur la maîtrise des langues et du calcul.</p>
                        <div class="feature-tags">
                            <span class="tag">Discipline</span>
                            <span class="tag">Langues</span>
                        </div>
                    </div>
                    <div class="cycle-image-box">
                        <img src="image/classe2.jpg" alt="Primaire ASITECH">
                    </div>
                </div>
            </div>

            <div class="cycle-block reverse">
                <div class="cycle-card">
                    <div class="cycle-content">
                        <span class="badge-cycle">Étape 02 — Orientation</span>
                        <h2>Éducation de <span class="gold-txt">Base</span></h2>
                        <p>La 7ème et 8ème année constituent le tronc commun où l'élève explore ses affinités techniques.</p>
                        <div class="feature-tags">
                            <span class="tag">Laboratoires</span>
                            <span class="tag">Technologie</span>
                        </div>
                    </div>
                    <div class="cycle-image-box">
                        <img src="image/classes.jpg" alt="EB ASITECH">
                    </div>
                </div>
            </div>

            <div class="cycle-block">
                <div class="cycle-card">
                    <div class="cycle-content">
                        <span class="badge-cycle">Étape 03 — Expertise</span>
                        <h2>Humanités <span class="gold-txt">Techniques</span></h2>
                        <p>Une formation de pointe dans les métiers de l'industrie avec un équipement international.</p>
                        <div class="feature-tags">
                            <span class="tag">Électronique</span>
                            <span class="tag">Mécanique</span>
                            <span class="tag">Informatique</span>
                        </div>
                        <a href="inscription.html" class="btn-gold">Choisir une option</a>
                    </div>
                    <div class="cycle-image-box">
                        <img src="image/humanites.jpg" alt="Humanités ASITECH">
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php include 'includes/footer.php'; ?>
