<?php
// Headers requis
// Accès depuis n'importe quel site ou appareil (*)
header("Access-Control-Allow-Origin: *");
// Format des données envoyées
header("Content-Type: application/json; charset=UTF-8");
// Méthode autorisée
header("Access-Control-Allow-Methods: GET");
// Durée de vie de la requête
header("Access-Control-Max-Age: 3600");
// Entêtes autorisées
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    // La bonne méthode est utilisée

// On inclut les fichiers de configuration et d'accès aux données
include_once '../config/Database.php';
include_once '../models/Produits.php';
// On instancie la base de données
$database = new Database();
$db = $database->getConnection();
// On instancie les produits
$produit = new Produits($db);

// On récupère les données reçues
$donnees = json_decode(file_get_contents("php://input"));
// On vérifie qu'on a bien un id
if(!empty($donnees->id)){
}

// On récupère le produit
$produit->lireUn();
// On vérifie si le produit existe
if($produit->nom != null){
// On crée un tableau contenant le produit
$prod = [
"id" => $produit->id,
"nom" => $produit->nom,
"description" => $produit->description,
"prix" => $produit->prix,
"categories_id" => $produit->categories_id,
"categories_nom" => $produit->categories_nom
];
// On envoie le code réponse 200 OK
http_response_code(200);
// On encode en json et on envoie
echo json_encode($prod);
}else{
// 404 Not found
http_response_code(404);
echo json_encode(array("message" => "Le produit n'existe pas."));
}

}else{
    // Mauvaise méthode, on gère l'erreur
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
    }

?>