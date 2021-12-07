<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/main.php');

//Connected User
checkConnectedUsersStatus();

//Get all connected users
$connectedUsers = extractFromObjArr(getAllOption('CONNECTED_USER'), 'key');
if ($connectedUsers):
    $connUser = getConnectedUser(); ?>
    <li id="actifUsers" class="pt-3 pl-2 pb-0 pr-2" style="font-size: 0.8em;"
        data-user-order="<?= $connUser['order']; ?>">
        <strong><?= trans('Utilisateurs actifs'); ?></strong>
    </li>
    <?php foreach ($connectedUsers as $connectedUserId => $connectedUserData):
    $userConnData = unserialize($connectedUserData->val);
    if (getUserIdSession() != $connectedUserId && getUserRoleId() >= getUserRoleId($connectedUserId)
        && $userConnData['status'] < 4 && isUserExist($connectedUserId)): ?>
        <li class="list-inline-item p-0 pr-2 me-0" style="font-size: 0.7em;">
                <span class="activeUser pb-1 border-bottom border-<?= STATUS_CONNECTED_USER[$userConnData['status']]; ?>"
                      style="position: relative;cursor: pointer;"
                      data-page-consulting="<?= $userConnData['pageConsulting'] . ' / ' . $userConnData['pageParameters'] ?? ''; ?>"
                      data-last-connexion="<?= $userConnData['lastConnect']; ?>"
                      data-user-name="<?= getUserEntitled($connectedUserId); ?>"
                      data-user-status="<?= $userConnData['status']; ?>"
                      data-userid="<?= $connectedUserId; ?>"
                      data-txt-btn-logout="<?= trans("Déconnecter l'utilisateur"); ?>"
                      data-txt-btn-freeuser="<?= trans("Libérer la page de l'utilisateur"); ?>">
                   <?= getUserFirstName($connectedUserId) . ucfirst(substr(getUserName($connectedUserId), 0, 1)); ?>
                </span>
        </li>
    <?php endif;
endforeach;
endif; ?>
<script>
    var userOrder = $('#actifUsers').data('user-order');

    if (userOrder === 'disconnect') {
        window.location.replace('/app/logout.php');
    }

    if (userOrder === 'redirect') {
        window.location.replace('/app/page/');
    }
</script>