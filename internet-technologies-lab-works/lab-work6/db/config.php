<?php

require "ui-components.php";

$db_config = array(
    "db_name" => "itech_lab",
    "clients_collection" => "clients",
    "traffic_collection" => "clients",
);


try{
    $mongo = new MongoDB\Client("mongodb://localhost:27017");
    if(!in_array($db_config["db_name"], iterator_to_array($mongo->listDatabaseNames()))) {
        throw new DataBaseNotFoundException("Database '".$db_config["db_name"]."' not found");
    }
    $database = $mongo->selectDatabase($db_config["db_name"]);
    validate_database($database, $db_config["clients_collection"], $db_config["traffic_collection"]);
    $clients_collection = $database->selectCollection($db_config["clients_collection"]);
    $traffic_collection = $database->selectCollection($db_config["traffic_collection"]);
}catch(Exception $e) {
    print_error_page(500, "Exception: ".$e->getMessage());
    phpinfo();
    die();
}



function validate_database($database, $clients_collection_name, $traffic_collection_name){
    if(!in_array($traffic_collection_name, iterator_to_array($database->listCollectionNames()))) {
        $database->createCollection($traffic_collection_name);
    }
    if(!in_array($clients_collection_name, iterator_to_array($database->listCollectionNames()))) {
        $database->createCollection($clients_collection_name);
    }
}