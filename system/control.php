<?php

//Get all connected users
$connectedUsers = extractFromObjArr(getAllOption('CONNECTED_USER'), 'key');
if ($connectedUsers) {

    if (array_key_exists(getUserIdSession(), $connectedUsers)) {
        $currUserConn = $connectedUsers[getUserIdSession()];
        $currUserConnData = unserialize($currUserConn->val);
        if ($currUserConnData['order'] === 'disconnect') {
            disconnectUser();
        }
    }

    //If page slug is with "update"
    if (false !== strpos(getAppPageSlug(), 'update')) {

        foreach ($connectedUsers as $userId => $data) {

            if ($userId != getUserIdSession()) {

                $userConnData = unserialize($data->val);
                if (!empty($userConnData['pageConsulting']) && $userConnData['pageConsulting'] == getAppPageSlug()
                    && isset($_GET['id']) && $userConnData['pageParameters'] == $_GET['id']) {

                    if ($userConnData['status'] == 1) {

                        $message = trans('Cette page est en ce moment occupé par') . ' <strong>' . getUserEntitled($userId) . '</strong>';

                        if (getOptionPreference('sharingWork') === 'false') {
                            echo getAsset('simpleView', true, ['title' => 'Occupé', 'content' => $message]);
                            exit();

                        } else {
                            define('MEHOUBARIM_MSG', $message);
                            break;
                        }

                    } else {
                        saveUserConnectionData([
                            'user' => $userId,
                            'lastConnect' => $userConnData['lastConnect'],
                            'status' => $userConnData['status'],
                            'pageConsulting' => '',
                            'pageParameters' => '',
                            'order' => $userConnData['order']
                        ]);
                    }
                }
            }
        }
    }
}
saveUserConnectionData([
    'lastConnect' => time(),
    'status' => 1,
    'pageConsulting' => getAppPageSlug(),
    'pageParameters' => (!empty($_GET['id']) ? $_GET['id'] : ''),
    'order' => null
]);
