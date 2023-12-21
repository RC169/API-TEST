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

/**
* Lire un produit
*
* @return void
*/
function lireUn()
{
    // On écrit la requête
    $sql = "SELECT c.nom as categories_nom, p.id, p.nom, p.description, p.prix, p.categories_id, p.created_at FROM
    " . $this->table . " p LEFT JOIN categories c ON p.categories_id = c.id WHERE p.id = ? LIMIT 0,1";
    // On prépare la requête
    $query = $this->connexion->prepare( $sql );
    // On attache l'id
    $query->bindParam(1, $this->id);
    // On exécute la requête
    $query->execute();
    // on récupère la ligne
    $row = $query->fetch(PDO::FETCH_ASSOC);
    // On hydrate l'objet
    $this->nom = $row['nom'];
    $this->prix = $row['prix'];
    $this->description = $row['description'];
    $this->categories_id = $row['categories_id'];
    $this->categories_nom = $row['categories_nom'];
}


?>