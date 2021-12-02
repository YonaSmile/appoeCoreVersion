</div><!-- END DIV PANEL -->
</div><!-- END DIV COL -->
</div><!-- END DIV ROW -->
</div><!-- END DIV CONTAINER -->
</div><!-- END DIV CONTENT-AREA -->
</div><!-- END DIV WRAPPER -->
<div id="loadMediaLibrary"></div>
<div class="offcanvas offcanvas-end" id="mediaDetails" data-file-id="" tabindex="-1" aria-labelledby="mediaDetailsTitle">
    <div class="offcanvas-header">
        <h5 id="mediaDetailsTitle">Détails du média</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">Aucun fichier sélectionné</div>
</div>
</div><!-- END DIV SITE -->
<!-- MODAL INFO -->
<div class="modal fade" id="modalInfo" tabindex="-1" role="dialog" aria-labelledby="modalTitle"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
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