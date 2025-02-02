<div class="auth-container">
    <h2>Inscription Client</h2>
    
    <form action="<?php echo Flight::get('flight.base_url'); ?>/treatment/register" method="post">
        <div class="form-group">
            <label for="name">Nom Complet</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="form-group">
            <label for="phone">Numéro de téléphone</label>
            <input type="tel" name="phone" id="phone" required>
        </div>
        <button type="submit">S'inscrire</button>
    </form>

    <!-- Echec de connexion -->
    <?php if (isset($_SESSION['error_message'])): ?>
    <div class="notification error">
        <?php echo $_SESSION['error_message']; ?>
    </div>
    <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <!-- Succès de l'inscription -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="notification success">
            <?php echo $_SESSION['success_message']; ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    
    <div class="auth-links">
        <p>Déjà un compte ? <a href="<?php echo Flight::get('flight.base_url'); ?>/login">Connexion</a></p>
    </div>
</div>