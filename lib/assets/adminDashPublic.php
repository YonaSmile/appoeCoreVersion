<?php

use App\AppConfig;

require_once($_SERVER['DOCUMENT_ROOT'] . '/app/main.php');
if (isUserAuthorized('updatePageContent')): ?>
    <style>
        #adminDashPublic {
            position: fixed;
            bottom: 20%;
            left: 0;
            transform: translateY(-20%);
            box-sizing: border-box;
            z-index: 999999;
            text-align: left;
        }

        #adminDashPublic a,
        #adminDashPublic button {
            width: 35px;
            transition: all 0.2s;
            display: block;
            color: #FFF;
            background: rgba(0, 0, 0, 0.3);
            font-size: 13px;
            border: 0;
            padding: 10px 0;
            line-height: 16px;
            text-align: center;
        }

        #adminDashPublic a img,
        #adminDashPublic button img {
            width: 17px;
        }

        #adminDashPublic hr {
            width: 35px;
            display: block;
            color: #FFF;
            background: #FFF;
            border: 0;
            padding: 0;
            margin: 0;
            height: 1px;
        }

        #adminDashPublic a:focus,
        #adminDashPublic a:hover,
        #adminDashPublic button:focus,
        #adminDashPublic button:hover {
            background: rgba(0, 0, 0, 0.8);
        }
    </style>
    <div id="adminDashPublic">
        <a href="<?= WEB_ADMIN_URL; ?>" title="Tableau de bord">
            <img src="<?= APP_IMG_URL; ?>dashboard.svg" alt="">
        </a>
        <?php if (getPageType() === 'PAGE'): ?>
            <a href="<?= getPluginUrl('cms/page/pageContent/', getPageId()); ?>" title="Modifier la page">
                <img src="<?= APP_IMG_URL; ?>cog.svg" alt="">
            </a>
        <?php elseif (getPageType() === 'ARTICLE'): ?>
            <a href="<?= getPluginUrl('itemGlue/page/articleContent/', getPageId()); ?>" title="Modifier l'article">
                <img src="<?= APP_IMG_URL; ?>cog.svg" alt="">
            </a>
        <?php endif;
        $AppConfig = new AppConfig();
        if ($AppConfig->get('options', 'cacheProcess') === 'true'): ?>
            <a href="#" id="clearCach" data-page-slug="<?= getPageSlug(); ?>" data-page-lang="<?= LANG; ?>"
               title="Vider le cache">
                <img src="<?= APP_IMG_URL; ?>clear.svg" alt="">
            </a>
            <script type="text/javascript">
                document.getElementById('clearCach').addEventListener('click', function (e) {
                    e.preventDefault();

                    let page = e.target.parentNode;
                    if (null != page.getAttribute('data-page-slug') && null != page.getAttribute('data-page-lang')) {

                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", '/app/plugin/cms/process/ajaxProcess.php', true);

                        //Envoie les informations du header adapt??es avec la requ??te
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

                        //Appelle une fonction au changement d'??tat.
                        xhr.onreadystatechange = function () {
                            if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                                document.location.reload(true);
                            }
                        }
                        xhr.send('clearPageCache=OK&pageSlug=' + page.getAttribute('data-page-slug') + '&pageLang=' + page.getAttribute('data-page-lang'));
                    }
                }, false);
            </script>
        <?php endif; ?>
        <hr>
        <a href="<?= WEB_APP_URL . 'logout.php'; ?>" title="D??connexion">
            <img src="<?= APP_IMG_URL; ?>power.svg" alt="">
        </a>
    </div>
<?php endif; ?>