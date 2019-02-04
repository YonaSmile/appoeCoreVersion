<div id="cookieInfo"
     style="position: fixed; bottom: 0; left: 0; width: 100%; padding: 10px; background: rgba(0,0,0,0.8); color: #FFF; box-sizing: border-box;z-index: 999999;text-align: left;font-size: 16px;line-height: 20px;">
    <p>En poursuivant votre navigation sur ce site, vous acceptez l’utilisation de Cookies pour réaliser des
        statistiques
        de visites.</p>
    <p>
        <a href="https://fr.wikipedia.org/wiki/Cookie_(informatique)" target="_blank"
           style="text-decoration: none; color: #ccc;"> - Pour en savoir plus </a>
        <br>
        <a href="https://www.cnil.fr/fr/cookies-les-outils-pour-les-maitriser" target="_blank"
           style="text-decoration: none; color: #ccc;"> - Apprendre comment supprimer les cookies </a>
    </p>
    <button style="float: right; cursor:pointer; border: 0; margin-right:5px; margin-top:5px; padding: 4px 10px; background:#FFF; color: #000;display: block;width: auto;"
            id="acceptCookies">J'ai compris
    </button>
</div>
<script type="text/javascript">

    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
        document.getElementById('cookieInfo').style.display = 'none';
    }

    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    if (getCookie('acceptCookies')) {
        document.getElementById('cookieInfo').style.display = 'none';
    }

    document.getElementById('acceptCookies').addEventListener('click', function () {
        setCookie('acceptCookies', 'OK', '365');
    }, false);

</script>