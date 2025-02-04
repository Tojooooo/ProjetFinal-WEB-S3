<?php
use app\models\TempModel;

// Instanciation du modèle et récupération des espèces et des poids
$alimentationModel = new TempModel(Flight::db());
$especes = $alimentationModel->getAllEspeces(); // Récupérer toutes les espèces
try {
    $poidsParEspece = $alimentationModel->getDistinctWeightsByEspeceId(2);
    print_r($poidsParEspece);
    // Récupérer les poids par espèce
}
catch (\Exception $exception){

}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Nourrissage</title>
</head>
<body>
<h1>Nourrir les Animaux</h1>
<form action="<?= Flight::get('flight.base_url'); ?>/nourrir" method="POST">
    <label for="idEspece">Sélectionner l'Espèce :</label>
    <select id="speciesSelect" name="idEspece" required aria-label="Sélectionner l'espèce">
        <option value="">-- Choisissez une espèce --</option>
        <?php if (!empty($especes)): ?>
            <?php foreach ($especes as $espece): ?>
                <option value="<?= $espece['id_espece']; ?>"><?= htmlspecialchars($espece['nom']); ?></option>
            <?php endforeach; ?>
        <?php else: ?>
            <option value="">Aucune espèce disponible</option>
        <?php endif; ?>
    </select><br><br>

    <label for="weightSelect">Poids :</label>
    <select id="weightSelect" name="weight" aria-label="Sélectionner le poids">
        <option value="">Sélectionnez d'abord une espèce</option>
    </select><br><br>

    <label for="nbAnimal">Nombre d'Animaux :</label>
    <input type="number" id="nbAnimal" name="nbAnimal" required aria-label="Nombre d'animaux"><br><br>

    <label for="date">Date de Nourrissage :</label>
    <input type="date" id="date" name="date" required aria-label="Date de nourrissage"><br><br>

    <input type="submit" value="Nourrir">
</form>

<!-- Inclure les données des poids dans un script JavaScript -->
<script>
    // Données des poids par espèce encodées en JSON
    var poidsParEspece = <?= json_encode($poidsParEspece); ?>;
</script>

<!-- Script JavaScript pour gérer la sélection des poids -->
<script>
    // Gestion du changement d'espèce
    document.getElementById('speciesSelect').addEventListener('change', function() {
        var speciesId = this.value;
        var weightSelect = document.getElementById('weightSelect');

        if (speciesId && poidsParEspece[speciesId]) {
            populateWeightSelect(poidsParEspece[speciesId]);
        } else {
            weightSelect.innerHTML = '<option value="">Aucun poids disponible</option>';
        }
    });

    // Fonction pour remplir la liste des poids
    function populateWeightSelect(weights) {
        var weightSelect = document.getElementById('weightSelect');
        weightSelect.innerHTML = '<option value="">Sélectionnez un poids</option>'; // Réinitialiser les options

        weights.forEach(function(weight) {
            var option = document.createElement('option');
            option.value = weight;
            option.textContent = weight + ' kg';
            weightSelect.appendChild(option);
        });
    }

    // Validation du formulaire avant soumission
    document.querySelector('form').addEventListener('submit', function(event) {
        var speciesSelect = document.getElementById('speciesSelect');
        var nbAnimal = document.getElementById('nbAnimal');
        var date = document.getElementById('date');

        if (!speciesSelect.value || !nbAnimal.value || !date.value) {
            alert('Veuillez remplir tous les champs obligatoires.');
            event.preventDefault();
        }
    });
</script>
</body>
</html>