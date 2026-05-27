 <?php
 $pageCss = 'style.css/acueil.css';
 include 'includes/header.php';
 ?>


    <section class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <h5 class="pre-title">BIENVENUE SUR NOTRE SITE</h5>
                <h1>L'éducation de demain commence <span class="gold-txt">ici</span>.</h1>
                <p>
                    Le Complexe Scolaire <strong>ASCITECH</strong> offre un enseignement de qualité supérieure, alliant rigueur académique et innovation technologique pour l'épanouissement de vos enfants.
                </p>
            </div>
            <div class="hero-visual">
                <div class="image-wrapper">
                    <img src="image/asitech.jpg" alt="Éducation ASITECH">
                    <div class="floating-card">
                        <span class="number">100%</span>
                        <span class="label">Réussite</span>
                    </div>
                </div>
            </div>
            <!-- Action buttons placed under the image for balanced layout -->
            <div class="hero-actions hero-actions-under-image">
                <a href="inscription.php" class="btn-primary">Inscrire mon enfant</a>
                <a href="cycles.php" class="btn-secondary">Découvrir nos cycles</a>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="container">
            <div class="cards-grid">
                <div class="feature-card">
                    <div class="card-info">
                        <span class="category">Pédagogie</span>
                        <h3>Nos Enseignants</h3>
                        <p>Une équipe d'experts dévoués à la réussite et au suivi personnalisé.</p>
                    </div>
                    <div class="card-img">
                        <img src="image/enseignants.jpg" alt="Enseignement">
                    </div>
                </div>

                <div class="feature-card highlighted">
                    <div class="card-info">
                        <span class="category">Infrastructure</span>
                        <h3>Toutes les Classes</h3>
                        <p>Des environnements d'apprentissage modernes, du Primaire aux Humanités.</p>
                    </div>
                    <div class="card-img">
                        <img src="image/classe2.jpg" alt="Classes">
                    </div>
                </div>

                
            </div>
        </div>
    </section>

    <section class="home-contact" id="contacts-ecole">
        <div class="container">
            <div class="section-heading">
                <span class="category">Contacts</span>
                <h2>Contact de l'ecole</h2>
                <p>Besoin d'information ? Notre secretariat est a votre disposition.</p>
            </div>

            <div class="contact-cards">
                <div class="contact-card">
                    <h3>Campus Binza Pigeon</h3>
                    <p><strong>Adresse :</strong> Avenue de l'Ecole, Ngaliema</p>
                    <p><strong>Telephone :</strong> +243 812 000 000</p>
                </div>

                <div class="contact-card">
                    <h3>Campus Ma Campagne</h3>
                    <p><strong>Adresse :</strong> Croisement Nguma / Montagne</p>
                    <p><strong>Telephone :</strong> +243 820 000 000</p>
                </div>
            </div>
        </div>
    </section>

   <?php include 'includes/footer.php'; ?>
