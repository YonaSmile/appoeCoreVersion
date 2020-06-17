<?php
require( 'header.php' );

use App\AppConfig;

$AppConfig = new AppConfig();
$allConfig = $AppConfig->get();

echo getTitle( getAppPageName(), getAppPageSlug() ); ?>
<button class="btn btn-sm btn-outline-info" id="restoreConfig">Réinitialiser</button>
<button class="btn btn-sm btn-outline-danger" id="clearCache">Vider le cache</button>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-lg-4 my-5">
            <div class="row">
                <div class="col-12 mb-3"><h5>Options</h5></div>
				<?php foreach ( $allConfig['options'] as $name => $val ): ?>
                <div class="col-12 mb-2">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" data-config-type="options" class="custom-control-input updatePreference"
                               name="<?= $name; ?>" id="<?= $name; ?>" <?= $val === 'true' ? 'checked' : ''; ?>>
                        <label class="custom-control-label" for="<?= $name; ?>"><?= trans( $AppConfig->configExplanation[ $name ] ); ?></label>
                    </div>
                </div>
				<?php endforeach; ?>
            </div>
        </div>
        <div class="col-12 col-lg-8 my-5">
            <div class="row">
                <div class="col-12 mb-3"><h5>Données</h5></div>
				<?php foreach ( $allConfig['data'] as $name => $val ):
					if ( ! empty( $val ) ): ?>
                        <div class="col-12 mb-2">
                            <strong><?= trans( $AppConfig->configExplanation[ $name ] ); ?></strong> :
                            <mark data-src="<?= $val; ?>" class="copyContentOnClick" style="cursor: pointer"><?= $val; ?></mark>
                        </div>
					<?php endif;
				endforeach; ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-lg-3 my-5">
            <div class="row">
                <div class="col-12 mb-3"><h5 class="m-0">Autorisations d'accès</h5>
                    <small><strong class="text-secondary">Mon IP :</strong> <?= getIP(); ?></small>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <strong class="text-secondary"><?= trans('Préconfiguré dans'); ?> ini.main</strong>
                </div>
				<?php if ( defined( 'IP_ALLOWED' ) && ! isArrayEmpty( IP_ALLOWED ) ):
					foreach ( IP_ALLOWED as $ip ): ?>
                        <div class="col-12 text-info">
                            <small class="text-secondary">
                                <em><?= ( false !== strpos( $ip, ':' ) ) ? 'IPV6' : 'IPV4'; ?></em>
                            </small> <?= $ip; ?></div>
					<?php endforeach;
				endif; ?>
            </div>
            <div class="row">
                <div class="col-12 mt-3">
                    <strong class="text-secondary"><?= trans('Ajouté manuellement'); ?></strong>
                </div>
                <div id="allPersimissions" class="col-12 mb-2">
                    <div class="row">
						<?php foreach ( $allConfig['accessPermissions'] as $val ): ?>
                            <div class="col-12 ipAccess" data-ip="<?= $val; ?>"><?= $val; ?></div>
						<?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 position-relative">
                    <input type="text" name="addPermissionAccess" placeholder="Nouvelle autorisation"
                           maxlength="45">
                    <span id="submitAddPermissionAccess"><i class="fas fa-plus"></i></span>
                </div>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript" src="/app/lib/template/js/preferences.js"></script>
<?php require( 'footer.php' ); ?>