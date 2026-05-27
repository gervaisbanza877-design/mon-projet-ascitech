  <?php 
// Cette page est privée : redirection vers connexion.php si pas connecté
require_once 'includes/auth_check.php'; 
require_once 'includes/db.php';
$pageCss = 'style.css/contact.css';
include 'includes/header.php'; 
?>

    <section class="hero-mini">
        <div class="container">
            <h1>Parlons de votre <span>Avenir</span></h1>
            <p>Une question ? Notre équipe vous répond en moins de 24h.</p>
        </div>
    </section>

    <main class="container">
        <div class="contact-grid">
            <div class="glass-card">
                <form id="contactForm">
                    <div class="input-row">
                        <div class="field">
                            <label>Nom complet</label>
                            <input type="text" placeholder="Jean Mukendi" required>
                        </div>
                        <div class="field">
                            <label>Téléphone</label>
                            <input type="tel" placeholder="+243..." required>
                        </div>
                    </div>
                    <div class="field">
                        <label>Sujet</label>
                        <select>
                            <option>Inscription 2026</option>
                            <option>Demande d'informations</option>
                            <option>Direction académique</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Votre message</label>
                        <textarea rows="4" placeholder="Comment pouvons-nous vous aider ?"></textarea>
                    </div>
                    <button type="submit" class="btn-boost">Envoyer le message</button>
                </form>
            </div>

            <aside class="sidebar">
                <div class="info-card">
                    <h3>Campus Binza Pigeon</h3>
                    <p>📍 Avenue de l'École, Ngaliema</p>
                    <p>📞 +243 812 000 000</p>
                </div>
                <div class="info-card">
                    <h3>Campus Ma Campagne</h3>
                    <p>📍 Croisement Nguma / Montagne</p>
                    <p>📞 +243 820 000 000</p>
                </div>
            </aside>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
