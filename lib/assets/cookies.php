<div id="cookieInfo"
     style="position: fixed; bottom: 10px; left: 10px; min-width: 470px; max-width: 700px; padding: 20px; background: rgba(255,255,255,0.9); color: #000; box-sizing: border-box;z-index: 999999;text-align: left;font-size: 16px;line-height: 20px;">
    <h3 style="margin-top: 0;line-height: 25px;font-size: 22px;">Le respect de votre vie privée est notre priorité</h3>
    <p>Ce site Web utilise des cookies qui nous permettent d'analyser le trafic.<br>
        Ces informations ne sont pas partagées avec des partenaires d'analyse ou autres.<br>
        Voulez-vous continuer à utiliser notre site Web avec les catégories suivantes de cookies activées ?</p>
    <button style="float: right; cursor:pointer; border: 0; margin-right:5px; margin-top:5px; padding: 10px 20px;
    background:#000; color: #FFF;display: block;width: auto;" id="acceptCookies">J'ACCEPTE TOUS
    </button>
    <button style="float: right; cursor:pointer; border: 0; margin-right:5px; margin-top:5px; padding: 10px 20px;
    background:#FFF; color: #000;display: block;width: auto;" id="configureCookies">VOIR LES PRÉFÉRENCES
    </button>
    <div style="clear: both;margin: 20px auto 0 auto;max-width:400px;display: none;" id="configureContentCookies"></div>
</div>
<script type="text/javascript">
    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            let date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
        document.getElementById('cookieInfo').style.display = 'none';
    }

    function getCookie(name) {
        let nameEQ = name + "=";
        let ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    let nbCookies = 0;
    var existCookies = {
        _ga: 'Google Analytics'
    };

    function closeCookieContent(name) {
        document.querySelector('div.cookieCondition[data-cookie-name="' + name + '"]').style.display = 'none';

        if (nbCookies === 0) {
            setCookie('acceptCookies', 'OK', '365');
            document.getElementById('cookieInfo').style.display = 'none';
        } else {
            document.getElementById('cookieInfo').style.display = 'block';
        }
    }

    function availableCookie(name) {
        nbCookies--;
        setCookie('okCookie-' + name, 'OK', '356');
        closeCookieContent(name);
    }

    function disableCookie(name) {
        nbCookies--;
        if (name === '_ga' && typeof gaid !== 'undefined') {
            window['ga-disable-' + gaid] = true;
        }
        setCookie('okCookie-' + name, 'NO', '356');
        closeCookieContent(name);
    }

    function getConfigureCookieContent(name, title) {
        return '<div class="cookieCondition" data-cookie-name="' + name + '"><p style="display: inline-block;padding: 10px;margin:0 4px;">' + title + '</p>' +
            '<button style="cursor:pointer; border: 0; margin:0 4px;padding: 10px; background:#F69595; color: #000;' +
            'display: inline-block;width: auto;" onclick="disableCookie(\'' + name + '\')">INTERDIRE</button>' +
            '<button style="cursor:pointer; border: 0; margin:0 4px; padding: 10px; background:#F29F13; ' +
            'color: #000;display: inline-block;width: auto;" onclick="availableCookie(\'' + name + '\')">AUTORISER</button>' +
            '<hr style="clear:both;"></div>';
    }

    function getCookiesContent() {
        let html = '<h4 style="text-align:center;margin-bottom:30px;border-bottom:1px solid #000;padding: 5px;font-size: 20px;">' +
            'Préférence pour tous les services</h4>';

        Object.keys(existCookies).map(function (key, index) {
            if (getCookie(key) && !getCookie('okCookie-' + key)) {
                nbCookies++;
                html += getConfigureCookieContent(key, existCookies[key]);
            }
        });
        document.getElementById('configureContentCookies').innerHTML = html;

        if (nbCookies === 0) {
            setCookie('acceptCookies', 'OK', '365');
            document.getElementById('cookieInfo').style.display = 'none';
        }
    }

    //Check if User accepted cookies
    if (getCookie('acceptCookies')) {
        document.getElementById('cookieInfo').style.display = 'none';

    } else {

        //Fill in the content of cookies
        getCookiesContent();

        //The user accepts cookies
        document.getElementById('acceptCookies').addEventListener('click', function () {
            setCookie('acceptCookies', 'OK', '365');
            document.getElementById('cookieInfo').style.display = 'none';
        }, false);

        //User clicks on cookie preferences
        document.getElementById('configureCookies').addEventListener('click', function () {
            this.style.display = 'none';
            document.getElementById('configureContentCookies').style.display = 'block';
        }, false);
    }
</script>