<?php
// On vérifie la méthode utilisée
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    // On est en GET
    // On vérifie si on a reçu un id
    if(isset($_GET['lastId'])){
        // On récupère l'id et on le nettoie
        $lastId = (int)strip_tags($_GET['lastId']);

        // On initialise le filtre
        $filtre = ($lastId > 0) ? " WHERE `contents`.`id` > $lastId" : '';

        // On se connecte à la base
        require_once('.env');

        // On écrit la requête
        $sql = 'SELECT `contents`.`id`, `contents`.`message`, `contents`.`created_at`, `users`.`pseudo` FROM `contents` LEFT JOIN `users` ON `contents`.`users_id` = `users`.`id`'.$filtre.' ORDER BY `contents`.`id` DESC LIMIT 5;';

        // On exécute la requête
        $query = $db->query($sql);

        // On récupère les données
        $contents = $query->fetchAll();

        // On encode en JSON
        $messagesJson = json_encode($contents);

        // On envoie
        echo $messagesJson;
    }
}else{
    // Mauvaise méthode
    http_response_code(405);
    echo json_encode(['message' => 'Mauvaise méthode']);
}