<?php
header('Content-Type: application/json');

if (isset($_GET['idEspece'])) {
    $idEspece = intval($_GET['idEspece']); // Validation de l'entrée

    $model = new app\models\TempModel(Flight::db());
    $stmt = $model->db->prepare('SELECT poids FROM poids_especes WHERE id_espece = :idEspece');

    // Connexion à la base de données
    $stmt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête SQL pour récupérer les poids
    $stmt->execute(['idEspece' => $idEspece]);
    $poids = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Retourner les poids au format JSON
    echo json_encode($poids);
} else {
    echo json_encode([]); // Retourner un tableau vide si l'ID de l'espèce n'est pas fourni
}
?>