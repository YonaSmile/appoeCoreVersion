<?php
require($_SERVER['DOCUMENT_ROOT'] . '/app/main.php');
includePluginsFiles(true);

mehoubarim_updateConnectedStatus('Déconnecté');

session_unset();
session_destroy();

header('location:' . WEB_DIR . 'hibour');