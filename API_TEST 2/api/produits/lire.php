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
    }else{
    // Mauvaise méthode, on gère l'erreur
    http_response_code(405);
    echo json_encode(["message" => "La méthode n'est pas autorisée"]);
    }



// On inclut les fichiers de configuration et d'accès aux données
include_once '../config/Database.php';
include_once '../models/Produits.php';
// On instancie la base de données
$database = new Database();
$db = $database->getConnection();
// On instancie les produits
$produit = new Produits($db);


// On récupère les données
$stmt = $produit->lire();
// On vérifie si on a au moins 1 produit
if($stmt->rowCount() > 0){
// On initialise un tableau associatif
$tableauProduits = [];
$tableauProduits['produits'] = [];
// On parcourt les produits
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
extract($row);
$prod = [
"id" => $id,
"nom" => $nom,
"description" => $description,
"prix" => $prix,
"categories_id" => $categories_id,
"categories_nom" => $categories_nom
];
$tableauProduits['produits'][] = $prod;
}
// On envoie le code réponse 200 OK
http_response_code(200);
// On encode en json et on envoie
echo json_encode($tableauProduits);
}

/**
* Lecture des produits
*
* @return void
*/
function lire()
{
    // On écrit la requête
    $sql = "SELECT c.nom as categories_nom, p.id, p.nom, p.description, p.prix, p.categories_id, p.created_at FROM
    " . $this->table . " p LEFT JOIN categories c ON p.categories_id = c.id ORDER BY p.created_at DESC";
    // On prépare la requête
    $query = $this->connexion->prepare($sql);
    // On exécute la requête
    $query->execute();
    // On retourne le résultat
    return $query;
}


?>