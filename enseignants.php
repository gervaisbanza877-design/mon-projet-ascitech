<?php 
// Cette page est privée : redirection vers connexion.php si pas connecté
require_once 'includes/auth_check.php'; 
require_once 'includes/db.php';
$pageCss = 'style.css/enseignants.css';
include 'includes/header.php'; 
?>


    <section class="profs-hero">
        <div class="container">
            <span class="gold-badge">L'Élite Pédagogique</span>
            <h1>Le Corps <span class="gold-gradient">Professoral</span></h1>
            <p>Des experts passionnés dévoués à la transmission du savoir et à l'excellence technologique.</p>
        </div>
    </section>

    <main class="profs-section">
        <div class="container">

            <div class="profs-grid">

                <article class="prof-card">
                    <div class="prof-img">
                        <img src="image/directeur.jpg" alt="Directeur">
                        <div class="card-overlay"></div>
                    </div>
                    <div class="prof-info">
                        <span class="dept">Direction Scolaire</span>
                        <h3>Mr. Philippe Jamet</h3>
                        <p>Directeur des Études. Expert en management des systèmes éducatifs techniques.</p>
                        <div class="tag-box"><span class="tag">15 ans d'Exp.</span></div>
                    </div>
                </article>

                <article class="prof-card">
                    <div class="prof-img">
                        <img src="image/jhon's.jpg" alt="Professeur Tech">
                    </div>
                    <div class="prof-info">
                        <span class="dept">Humanités Techniques</span>
                        <h3>Jhon's smith</h3>
                        <p>Titulaire d'Électronique industrielle. Spécialiste en automatisme et robotique.</p>
                        <div class="tag-box"><span class="tag">Certifié Cisco</span></div>
                    </div>
                </article>

                <article class="prof-card">
                    <div class="prof-img">
                        <img src="image/maryse.jpg" alt="Enseignante Primaire">
                    </div>
                    <div class="prof-info">
                        <span class="dept">Cycle Primaire</span>
                        <h3>Maryse Guibolt</h3>
                        <p>Coordinatrice pédagogique. Experte en psychologie de l'enfant et éveil scolaire.</p>
                        <div class="tag-box"><span class="tag">Maîtrise FLE</span></div>
                    </div>
                </article>

                <article class="prof-card">
                    <div class="prof-img">
                        <img src="image/roland.jpg" alt="Professeur Info">
                    </div>
                    <div class="prof-info">
                        <span class="dept">Pôle Numérique</span>
                        <h3>Roland fryer</h3>
                        <p>Expert en programmation et réseaux. Responsable du Laboratoire Informatique.</p>
                        <div class="tag-box"><span class="tag">Dév Fullstack</span></div>
                    </div>
                </article>

                <article class="prof-card">
                    <div class="prof-img">
                        <img src="image/jeannine.jpg" alt="Professeur de Sciences">
                    </div>
                    <div class="prof-info">
                        <span class="dept">Sciences Physiques</span>
                        <h3>jeannine Kambale</h3>
                        <p>Enseignant de Physique-Chimie. Responsable des travaux pratiques en laboratoire.</p>
                        <div class="tag-box"><span class="tag">Agrégé d'État</span></div>
                    </div>
                </article>

                <article class="prof-card">
                    <div class="prof-img">
                        <img src="image/valerie.jpg" alt="Professeur d'Anglais">
                    </div>
                    <div class="prof-info">
                        <span class="dept">Langues Étrangères</span>
                        <h3>Valerie.G</h3>
                        <p>Professeur d'Anglais Technique. Spécialisée dans la communication internationale.</p>
                        <div class="tag-box"><span class="tag">Native Speaker</span></div>
                    </div>
                </article>

                <article class="prof-card">
                    <div class="prof-img">
                        <img src="image/joseph.jpg" alt="Coach Sportif">
                    </div>
                    <div class="prof-info">
                        <span class="dept">Sport & Discipline</span>
                        <h3>Joseph gonfroy</h3>
                        <p>Responsable des activités sportives et de l'encadrement disciplinaire.</p>
                        <div class="tag-box"><span class="tag">Brevet Sportif</span></div>
                    </div>
                </article>

                <article class="prof-card">
                    <div class="prof-img">
                        <img src="image/josey.jpg" alt="Comptabilité">
                    </div>
                    <div class="prof-info">
                        <span class="dept">Sciences Commerciales</span>
                        <h3>Josey solar</h3>
                        <p>Titulaire de Comptabilité et Fiscalité. Formatrice en entrepreneuriat.</p>
                        <div class="tag-box"><span class="tag">Expert-Comptable</span></div>
                    </div>
                </article>

            </div>
        </div>
    </main>

<?php include 'includes/footer.php'; ?>
