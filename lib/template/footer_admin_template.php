<div id="loadMediaLibrary"></div>
</div><!-- END DIV PANEL -->
</div><!-- END DIV COL -->
</div><!-- END DIV ROW -->
</div><!-- END DIV CONTAINER -->
</div><!-- END DIV WRAPPER -->
</div><!-- END DIV SITE -->
<div class="position-fixed bottom-0 right-0 p-3" style="z-index: 99999; right: 0; bottom: 0;">
    <div id="pageStatus" class="toast fade hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <img src="<?= getLogo(true); ?>" width="25" style="vertical-align: initial;" alt="APPOE"
                 title="APPOE | Art Of Event - Communication">&nbsp; APPOE
        </div>
        <div class="toast-body"></div>
    </div>
</div>
<!-- MODAL INFO -->
<div class="modal fade" id="modalInfo" tabindex="-1" role="dialog" aria-labelledby="modalTitle"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="modalBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<!-- MODAL INFO END -->

<?php includePluginsFilesForAppInFooter();
\App\Flash::constructAndDisplay(); ?>
<div id="overlay">
    <div id="overlayContent" class="overlayContent"></div>
</div>
<script type="text/javascript" src="<?= WEB_TEMPLATE_URL; ?>plugins/bootstrap/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="<?= WEB_TEMPLATE_URL; ?>plugins/js/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="<?= WEB_TEMPLATE_URL; ?>plugins/waves/waves.min.js"></script>
<script type="text/javascript" src="<?= WEB_LIB_URL; ?>js/appoEditor/appoEditor.js"></script>
<script type="text/javascript" src="<?= WEB_LIB_URL; ?>js/datatable/dataTables.min.js"></script>
<script type="text/javascript" src="<?= WEB_LIB_URL; ?>js/datatable/bootstrap4.min.js"></script>
<?php includePluginsJs(true); ?>
</body>
</html>