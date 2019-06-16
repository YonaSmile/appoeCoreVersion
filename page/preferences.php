<?php require('header.php'); ?>
<?= getTitle($Page->getName(), $Page->getSlug()); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-lg-4">
            <h5 class="mb-3">HTACCESS</h5>
            <div class="row">
                <div class="col-12 mb-2">
                    <span class="switchBtnContenair mr-2">
                        <label class="switch">
                            <input type="checkbox" name="htacc-forceSSL">
                            <span class="slider"></span>
                        </label>
                    </span> Forcer le site en HTTPS
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4"></div>
        <div class="col-12 col-lg-4"></div>
    </div>
</div>