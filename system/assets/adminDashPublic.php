<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/main.php');
if (isUserAuthorized('updatePage')): ?>
    <style>
        #adminDashPublic {
            position: fixed;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            min-width: 130px;
            background: rgba(0, 0, 0, 0.8);
            color: #FFF;
            box-sizing: border-box;
            z-index: 999999;
            text-align: left;
            line-height: 20px;
        }

        #adminDashPublic a {
            font-size: 13px;
            border: 0;
            padding: 10px;
            color: #ccc;
            display: block;
        }
    </style>
    <div id="adminDashPublic">
        <a href="http://appoe.aoe-communication.com/app/page/">Dashboard</a>
        <a href="<?= getPluginUrl('cms/page/pageContent/', $_SESSION['currentPageID']); ?>">Modifier
            la page</a>
    </div>

    <script type="text/javascript">

    </script>
<?php endif; ?>