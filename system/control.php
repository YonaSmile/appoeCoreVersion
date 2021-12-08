<?php

//Get all connected users
$connectedUsers = extractFromObjArr(getAllOption('CONNECTED_USER'), 'key');
if ($connectedUsers) {

    //Checking for order about connected user
    checkConnectedUser($connectedUsers);

    //Checking for getting access to the page
    checkFreePageConnectedUsers($connectedUsers);

}

//Update connected user data
saveUserConnectionData([
    'lastConnect' => time(),
    'status' => 1,
    'pageConsulting' => getAppPageSlug(),
    'pageParameters' => (!empty($_GET['id']) ? $_GET['id'] : ''),
    'order' => null
]);
