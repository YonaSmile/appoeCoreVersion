<div class="my-4"></div>
</div><!-- END BASE -->
</div><!-- END DIV MAIN CONTENT-->
</div><!-- END DIV MAIN -->
<div id="notifications_footer"><?php getSessionNotifications(); ?></div>
<?php \App\Flash::constructAndDisplay(); ?>
<div id="overlay">
    <div id="overlayContent" class="overlayContent"></div>
</div>
</div><!-- END DIV SITE -->

<!-- MODAL INFO -->
<div class="modal fade" id="modalInfo" tabindex="-1" role="dialog" aria-labelledby="modalTitle"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
            </div>
            <div class="modal-body" id="modalBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<!-- MODAL INFO END -->
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
<script type="text/javascript" src="/app/js/footer.js"></script>
</body>
</html>