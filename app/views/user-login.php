<div class="auth-container">
    <h2>Connexion Client</h2>
    
    <form action="<?php echo Flight::get('flight.base_url'); ?>/treatment/login" method="post">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Connexion</button>
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
        <p>Pas de compte ? <a href="<?php echo Flight::get('flight.base_url'); ?>/register">Inscription</a></p>
        <p><a href="<?php echo Flight::get('flight.base_url'); ?>/adminConnexion">Connexion Administrateur</a></p>
    </div>
</div>