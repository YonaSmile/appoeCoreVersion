<?php
require($_SERVER['DOCUMENT_ROOT'] . '/app/main.php');
includePluginsFiles();

mehoubarim_updateConnectedStatus('Déconnecté');

destroySessions();

header('location:' . WEB_DIR . 'hibour');