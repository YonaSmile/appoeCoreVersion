<?php
require('header.php');
require_once(WEB_PROCESS_PATH . 'categories.php');
$Category = new App\Category();
$allCategories = $Category->showAll();
$allTypes = getAppTypes();

$separetedCategories = array();
if ($allCategories) {
    foreach ($allCategories as $category) {
        $separetedCategories[$allTypes[$category->type]][$category->parentId][] = $category;
    }
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="display-4 bigTitle"><?= trans('Catégories'); ?></h1>
            <hr class="my-2">
            <button id="addCategory" type="button" class="btn btn-primary mb-4" data-toggle="modal"
                    data-target="#modalAddCategory">
                <?= trans('Nouvelle Catégorie'); ?>
            </button>
        </div>
    </div>
    <?php if (isset($Response)): ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-<?= $Response->display()->status ?>" role="alert">
                    <?= $Response->display()->error_msg; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="my-4"></div>
    <?php if ($separetedCategories): ?>
        <div class="row my-3">
            <?php foreach ($separetedCategories as $key => $categoryType): ?>
                <div class="col-12 col-lg-6">
                    <h5><?= $Traduction->trans($key); ?></h5>
                    <?php foreach ($categoryType[10] as $separetedCategory): ?>
                        <div class="mb-2">
                            <div class="fileContent">
                                <button type="button" class="deleteBtn deleteCategory"
                                        data-idcategory="<?= $separetedCategory->id; ?>">
                                    &times;
                                </button>
                                <input type="text" class="p-3 bg-primary text-white border-0 form-control libraryInput"
                                       data-idcategory="<?= $separetedCategory->id; ?>"
                                       value="<?= $separetedCategory->name; ?>"
                                       placeholder="<?= $separetedCategory->id; ?>">
                                <small class="inputInfo"></small>
                            </div>
                            <?php if (!empty($categoryType[$separetedCategory->id])): ?>
                                <div class="row ml-2">
                                    <?php foreach ($categoryType[$separetedCategory->id] as $separetedSubCategory): ?>
                                        <div class="col-12">
                                            <div class="fileContent">
                                                <button type="button" class="deleteBtn deleteCategory"
                                                        data-idcategory="<?= $separetedSubCategory->id; ?>">
                                                    &times;
                                                </button>
                                                <input type="text"
                                                       class="p-3 bg-info text-white border-0 form-control libraryInput"
                                                       data-idcategory="<?= $separetedSubCategory->id; ?>"
                                                       value="<?= $separetedSubCategory->name; ?>"
                                                       placeholder="<?= $separetedSubCategory->id; ?>"">
                                                <small class="inputInfo"></small>
                                            </div>
                                            <?php if (!empty($categoryType[$separetedSubCategory->id])): ?>
                                                <div class="row ml-2">
                                                    <?php foreach ($categoryType[$separetedSubCategory->id] as $separetedSubSubCategory): ?>
                                                        <div class="col-12">
                                                            <div class="fileContent">
                                                                <button type="button" class="deleteBtn deleteCategory"
                                                                        data-idcategory="<?= $separetedSubSubCategory->id; ?>">
                                                                    &times;
                                                                </button>
                                                                <input type="text"
                                                                       class="p-3 bg-secondary text-white border-0 form-control libraryInput"
                                                                       data-idcategory="<?= $separetedSubSubCategory->id; ?>"
                                                                       value="<?= $separetedSubSubCategory->name; ?>"
                                                                       placeholder="<?= $separetedSubSubCategory->id; ?>">
                                                                <small class="inputInfo"></small>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<div class="modal fade" id="modalAddCategory" tabindex="-1" role="dialog" aria-labelledby="modalAddCategoryTitle"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="post" id="addCategoryForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddCategoryTitle">Ajouter une catégorie</h5>
                </div>
                <div class="modal-body" id="modalCategoryBody">
                    <?= getTokenField(); ?>
                    <div class="row">
                        <div class="col-12 my-2">
                            <?= App\Form::text(trans('Nom'), 'name', 'text', !empty($_POST['name']) ? $_POST['name'] : '', true, 150); ?>
                        </div>
                        <div class="col-12 my-2">
                            <?= App\Form::select(trans('Type de catégorie'), 'type', $allTypes, '', true); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 my-2" id="categoryTypeForm"></div>
                    </div>
                </div>
                <div class="modal-footer" id="modalCategoryFooter">
                    <button type="submit" name="ADDCATEGORY"
                            class="btn btn-primary"><?= trans('Enregistrer'); ?></button>
                    <button type="button" class="btn btn-secondary"
                            data-dismiss="modal"><?= trans('Fermer'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {

        $('select#type').on('change', function () {
            var categoryType = $("select#type option:selected").text();
            var $categoryTypeInput = $('#categoryTypeForm');
            $categoryTypeInput.html('<i class="fas fa-circle-notch fa-spin"></i> <?= trans('Chargement'); ?>');
            $.post(
                '<?= WEB_DIR; ?>app/ajax/categories.php',
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
                    '<?= WEB_DIR; ?>app/ajax/categories.php',
                    {
                        updateCategoryName: 'OK',
                        idCategory: idCategory,
                        newName: newName
                    },
                    function (data) {
                        if (data && (data == 'true' || data === true)) {
                            $inputInfo.html('<?= trans('Enregistré'); ?>');
                            availableApp();
                        }
                    }
                )
            } else {
                $inputInfo.html('<?= trans('Le nom doit contenir au moins une lettre'); ?>');
            }
        });

        $('.retaureCategory').on('click', function () {
            var $btn = $(this);
            var idCategory = $btn.data('restaureid');

            $.post(
                '<?= WEB_DIR; ?>app/ajax/categories.php',
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
            if (confirm('<?= trans('Vous allez supprimer cette catégorie'); ?>')) {
                busyApp();
                var $btn = $(this);
                var idCategory = $btn.data('idcategory');

                $.post(
                    '<?= WEB_DIR; ?>app/ajax/categories.php',
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
</script>
<?php require('footer.php'); ?>
