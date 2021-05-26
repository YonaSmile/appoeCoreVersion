(function ($) {
    "use strict";

    $(document).ready(function () {

        //appoe form
        if (jQuery().appoeForm !== undefined && $('.appoeForm').length) {
            $('.appoeForm').appoeForm();
        }

        //appoe pagination
        if (jQuery().pagination !== undefined && $('.appoePagination').length) {
            $('.appoePagination').pagination();
        }

        //leaflet plugin
        if (jQuery().mappoe !== undefined && $('.mappoe').length) {
            $('.mappoe').mappoe();
        }
    });

})(jQuery);