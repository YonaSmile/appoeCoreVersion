$(document).ready(function () {

    $('select#type').on('change', function () {
        var categoryType = $("select#type option:selected").text();
        var $categoryTypeInput = $('#categoryTypeForm');
        $categoryTypeInput.html('<i class="fas fa-circle-notch fa-spin"></i> Chargement');
        $.post(
            '/app/ajax/categories.php',
            {
                getCategoriesByType: 'OK',
                categoryType: categoryType
            },
            function (data) {
                if (data) {
                    $categoryTypeInput.html(data);
                }
            }
        )
    });

    $('.libraryInput').on('keyup', function () {
        var $input = $(this);
        var idCategory = $input.data('idcategory');
        var newName = $input.val();
        var $inputInfo = $input.next('small');
        $inputInfo.html('');

        if (newName.length > 0) {
            busyApp();
            $.post(
                '/app/ajax/categories.php',
                {
                    updateCategoryName: 'OK',
                    idCategory: idCategory,
                    newName: newName
                },
                function (data) {
                    if (data && (data == 'true' || data === true)) {
                        $inputInfo.html('Enregistré');
                        availableApp();
                    }
                }
            )
        } else {
            $inputInfo.html('Le nom doit contenir au moins une lettre');
        }
    });

    $('.retaureCategory').on('click', function () {
        var $btn = $(this);
        var idCategory = $btn.data('restaureid');

        $.post(
            '/app/ajax/categories.php',
            {
                restaureCategory: 'OK',
                idCategoryToRestaure: idCategory
            },
            function (data) {
                if (data && (data == 'true' || data === true)) {
                    window.location = window.location.href;
                    window.location.reload(true);
                }
            }
        )
    });

    $('.deleteCategory').on('click', function () {
        if (confirm('Vous allez supprimer cette catégorie')) {
            busyApp();
            var $btn = $(this);
            var idCategory = $btn.data('idcategory');

            $.post(
                '/app/ajax/categories.php',
                {
                    deleteCategory: 'OK',
                    idCategory: idCategory
                },
                function (data) {
                    if (data && (data == 'true' || data === true)) {
                        $btn.parent('div').fadeOut('fast');
                        availableApp();
                    }
                }
            )
        }
    });

});