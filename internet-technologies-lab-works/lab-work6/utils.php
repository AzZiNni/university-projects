<?php


use models\ClientStatistic;
use MongoDB\BSON\ObjectId;
use MongoDB\Collection;
use MongoDB\Driver\Exception\RuntimeException as DriverRuntimeException;
use MongoDB\Exception\InvalidArgumentException;
use MongoDB\Exception\UnsupportedException;


require_once "PageBuilder.php";
require_once "models/ClientStatistic.php";

/**
 * @throws UnsupportedException if options are not supported by the selected server
 * @throws InvalidArgumentException for parameter/option parsing errors
 * @throws DriverRuntimeException for other driver errors (e.g. connection errors)
*/
function getClients(Collection $clients) : array{
    return match (PageBuilder::getFilterValue()) {
        'cf2' => $clients->find(['balance' => ['$gt' => 0]])->toArray(),
        'cf3' => $clients->find(['balance' => ['$lt' => 0]])->toArray(),
        'cf4' => $clients->find(
            ['balance' => array(
                '$gt' => intval($_GET['number1'] - 1),
                '$lt' => intval($_GET['number2'] + 1))
            ]
        )->toArray(),
        default => $clients->find()->toArray(),
    };
}

function getClientId(): ObjectID {
    if(array_key_exists("id", $_GET)){
        return new ObjectID($_GET['id']);
    }
    throw new InvalidArgumentException("key id was not found");
}

/**
 * @throws UnsupportedException if options are not supported by the selected server
 * @throws InvalidArgumentException for parameter/option parsing errors
 * @throws DriverRuntimeException for other driver errors (e.g. connection errors)
 * @throws ClientNotFountException
*/
function getClient(ObjectID $client_id, Collection $collection): object{
    $client = $collection->findOne(['_id' => $client_id]);
    if($client == null || count($client) == 0){
        throw new ClientNotFountException("Client with id ".$_GET['id']." not found");
    }
    return $client;
}


/**
 * @throws Exception if DataTime creation fails
 */
function getClientStatistic(ObjectID $clientId, Collection $sessions) : ClientStatistic{

    $clientSessions = $sessions->find(['client' => $clientId])->toArray();
    if(count($clientSessions) == 0){
        return new ClientStatistic($clientId->serialize(), 0,0,0,0,0);
    }
    $inTraffic = 0;
    $outTraffic = 0;
    $price = 0;
    $time = 0;

    foreach ($clientSessions as $session){
        $inTraffic += $session->in;
        $outTraffic += $session->out;
        $price += $session->price;
        $time += (new DateTime($session->end))->getTimestamp() - (new DateTime($session->start))->getTimestamp();
    }
    return new ClientStatistic($clientId->serialize(), $inTraffic,$outTraffic,count($clientSessions),$time,$price);
}