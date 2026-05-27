<?php 
// Cette page est privée : redirection vers connexion.php si pas connecté
require_once 'includes/auth_check.php'; 
require_once 'includes/db.php';
$pageCss = 'style.css/classes.css';
include 'includes/header.php'; 
?>
 
    <section class="hero-classes">
        <div class="container">
            <span class="badge">Programmes Académiques</span>
            <h1>Choisissez une filière de <span>Prestige</span></h1>
            <p>Des formations certifiées pour propulser nos élèves vers les meilleures universités mondiales.</p>
        </div>
    </section>

    <main class="container">
        <div class="options-boost-grid">

            <div class="glass-option">
                <div class="card-header science">
                    <span class="icon">🔬</span>
                    <h3>Sciences</h3>
                </div>
                <div class="card-body">
                    <p>Mathématique-Physique & Chimie-Biologie. Un parcours rigoureux pour les esprits analytiques.</p>
                    <div class="perks">
                        <span>Labo Connecté</span>
                        <span>Bourses d'Excellence</span>
                    </div>
                    <a href="#" class="btn-action">Découvrir le programme</a>
                </div>
            </div>

            <div class="glass-option">
                <div class="card-header technique">
                    <span class="icon">⚙️</span>
                    <h3>Technique Industrielle</h3>
                </div>
                <div class="card-body">
                    <p>Électricité et Électronique. Apprentissage par projets et immersion technologique.</p>
                    <div class="perks">
                        <span>Ateliers 4.0</span>
                        <span>Certification Pro</span>
                    </div>
                    <a href="#" class="btn-action">Découvrir le programme</a>
                </div>
            </div>

            <div class="glass-option">
                <div class="card-header gestion">
                    <span class="icon">📊</span>
                    <h3>Gestion & Info</h3>
                </div>
                <div class="card-body">
                    <p>Comptabilité numérique et Informatique de gestion. Maîtrisez les outils du futur business.</p>
                    <div class="perks">
                        <span>Coding Club</span>
                        <span>Management</span>
                    </div>
                    <a href="#" class="btn-action">Découvrir le programme</a>
                </div>
            </div>

        </div>
    </main>


    <?php include 'includes/footer.php'; ?>
