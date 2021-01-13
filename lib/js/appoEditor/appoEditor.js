function saveSelection() {
    if (window.getSelection) {
        var sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            return sel.getRangeAt(0);
        }
    } else if (document.selection && document.selection.createRange) {
        return document.selection.createRange();
    }
    return null;
}

function restoreSelection(range) {
    if (range) {
        if (window.getSelection) {
            var sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
        } else if (document.selection && range.select) {
            range.select();
        }
    }
}

function appoEditor() {

    if ($('textarea.appoeditor')) {

        $('.appoeditor').each(function (num, el) {

            //INSERT EDITOR CONTAINER
            var newId = Math.floor((Math.random() * 1000) + 1);
            $(el).attr('data-editor-id', newId);

            var container = $('<div class="containerAppoeditor bg-white border border-top-0 p-0" data-editor-id="' + newId + '"></div>');
            var editor = $('<div data-editor-id="' + newId + '" class="inlineAppoeditor" contenteditable="true">' + $(el).text() + '</div>');
            container.append(editor);

            container.insertAfter($(el));

            //NAVBAR
            var btnNav = $('<nav class="navButton navbar navbar-text navbar-light bg-secondary" data-editor-id="' + newId + '"></nav>');
            btnNav.insertBefore(editor);

            //BUTTONS
            var buttons = {
                buttonsStyle: [
                    '<button type="button" data-mark="strong,b" data-cmd="bold" class="btnCmdEditor btnEditor btn btn-secondary" title="Gras"><i class="fas fa-bold"></i></button>',
                    '<button type="button" data-mark="i,em" data-cmd="italic" class="btnCmdEditor btnEditor btn btn-secondary" title="Italique"><i class="fas fa-italic"></i></button>',
                    '<button type="button" data-mark="u" data-cmd="underline" class="btnCmdEditor btnEditor btn btn-secondary" title="Soulignage"><i class="fas fa-underline"></i></button>',
                    '<button type="button" data-id-modal="editText" class="openModalEditor btnEditor btn btn-secondary" title="Edition du texte"><i class="fas fa-font"></i></button>'
                ],
                buttonsJustify: [
                    '<button type="button" data-cmd="justifyLeft" class="btnCmdEditor btnEditor btn btn-secondary" title="Aligner à gauche"><i class="fas fa-align-left"></i></button>',
                    '<button type="button" data-cmd="justifyCenter" class="btnCmdEditor btnEditor btn btn-secondary" title="Centrer"><i class="fas fa-align-center"></i></button>',
                    '<button type="button" data-cmd="justifyRight" class="btnCmdEditor btnEditor btn btn-secondary" title="Aligner à droite"><i class="fas fa-align-right"></i></button>',
                    '<button type="button" data-cmd="justifyFull" class="btnCmdEditor btnEditor btn btn-secondary" title="Justifier"><i class="fas fa-align-justify"></i></button>',
                ],
                buttonsList: [
                    '<button type="button" data-cmd="insertUnorderedList" class="btnCmdEditor btnEditor btn btn-secondary" title="Liste à puces"><i class="fas fa-list-ul"></i></button>',
                    '<button type="button" data-cmd="insertOrderedList" class="btnCmdEditor btnEditor btn btn-secondary" title="Liste numérotée"><i class="fas fa-list-ol"></i></button>',
                ],
                buttonsLink: [
                    '<button type="button" data-mark="a" data-id-modal="insertLink" class="openModalEditor btnEditor btn btn-secondary" title="Créer un lien"><i class="fas fa-link"></i></button>',
                    '<button type="button" data-cmd="unlink" class="btnCmdEditor btnEditor btn btn-secondary" title="Annuler le lien"><i class="fas fa-unlink"></i></button>',
                ],
                buttonsOperations: [
                    '<button type="button" class="switchDisplay viewMode btnEditor btn btn-secondary" title="Code source"><i class="fas fa-code"></i></button>',
                ]
            };

            //SELECT BUTTONS
            var selectFormat = [
                '<div class="btn-group btn-group-sm headingSelect" role="group">' +
                '<button id="editor-formatBtns-' + newId + '" type="button" class="btn btn-secondary dropdown-toggle" ' +
                'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Format</button>' +
                '<div class="dropdown-menu" aria-labelledby="editor-formatBtns-' + newId + '">' +
                '<button type="button" class="dropdown-item btnCmdEditor btnEditor sidebarLink" data-mark="h1" data-cmd="formatBlock" data-cmd-val="<h1>"><h1>Titre 1</h1></button>' +
                '<button type="button" class="dropdown-item btnCmdEditor btnEditor sidebarLink" data-mark="h2" data-cmd="formatBlock" data-cmd-val="<h2>"><h2>Titre 2</h2></button>' +
                '<button type="button" class="dropdown-item btnCmdEditor btnEditor sidebarLink" data-mark="h3" data-cmd="formatBlock" data-cmd-val="<h3>"><h3>Titre 3</h3></button>' +
                '<button type="button" class="dropdown-item btnCmdEditor btnEditor sidebarLink" data-mark="h4" data-cmd="formatBlock" data-cmd-val="<h4>"><h4>Titre 4</h4></button>' +
                '<button type="button" class="dropdown-item btnCmdEditor btnEditor sidebarLink" data-mark="h5" data-cmd="formatBlock" data-cmd-val="<h5>"><h5>Titre 5</h5></button>' +
                '<button type="button" class="dropdown-item btnCmdEditor btnEditor sidebarLink" data-mark="h6" data-cmd="formatBlock" data-cmd-val="<h6>"><h6>Titre 6</h6></button>' +
                '<div class="dropdown-divider"></div>' +
                '<button type="button" class="dropdown-item btnCmdEditor btnEditor sidebarLink" data-mark="p" data-cmd="formatBlock" data-cmd-val="<p>">Paragraphe</button>' +
                //'<button type="button" class="dropdown-item btnCmdEditor btnEditor sidebarLink" data-mark="blockquote" data-cmd="formatBlock" data-cmd-val="<blockquote>">Citation</button>' +
                '</div></div>',
                '<div class="btn-group btn-group-sm headingSelect" role="group">' +
                '<button id="editor-editionBtns-' + newId + '" type="button" class="btn btn-secondary dropdown-toggle" ' +
                'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Edition</button>' +
                '<div class="dropdown-menu" aria-labelledby="editor-editionBtns-' + newId + '">' +
                '<button type="button" class="dropdown-item btnCmdEditor btnEditor sidebarLink" data-cmd="undo"><i class="fas fa-undo-alt"></i> Revenir en arrière</button>' +
                '<button type="button" class="dropdown-item btnCmdEditor btnEditor sidebarLink" data-cmd="redo"><i class="fas fa-redo-alt"></i> Revenir en avant</button>' +
                '<div class="dropdown-divider"></div>' +
                '<small class="dropdown-header">Impossible de revenir en arrière</small>' +
                '<button type="button" class="dropdown-item cleanTags btnEditor sidebarLink"><i class="fas fa-broom"></i> Enlever les balises vides</button>' +
                '<button type="button" class="dropdown-item removeAllStyle btnEditor sidebarLink" title="Enlever tous les styles"><i class="fas fa-remove-format"></i> Enlever tous les styles</button>' +
                '<button type="button" class="dropdown-item removeTags btnEditor sidebarLink"><i class="fas fa-shower"></i> Enlever toutes les balises</button>' +
                '</div></div>'
            ];

            //INSERT GROUPS BUTTONS
            var btnsGroup = '';
            $.each(buttons, function (num, btnGrp) {
                btnsGroup += '<div class="btn-group btn-group-sm" role="group" aria-label="Editor">';
                $.each(btnGrp, function (attr, btn) {
                    btnsGroup += btn;
                });
                btnsGroup += '</div>';
            });

            btnNav.append($(btnsGroup));

            //INSERT SELECT BUTTONS
            $.each(selectFormat, function (num, el) {
                btnNav.append($(el));
            });

        });
    }
}

function insertHTML(element) {
    var sel, range;
    if (window.getSelection && (sel = window.getSelection()).rangeCount) {
        range = sel.getRangeAt(0);
        range.deleteContents();
        range.collapse(true);
        range.insertNode(element);

        // Move the caret immediately after the inserted element
        range.setStartAfter(element);
        range.collapse(true);
        sel.removeAllRanges();
        sel.addRange(range);
    }
}

function getViewMode($contenair) {
    var $btn = $contenair.closest('div.containerAppoeditor').find('button.switchDisplay');
    return $btn.hasClass('viewMode') ? 'viewMode' : 'codeMode';
}

function disableBtns($contenair) {
    $('nav button', $contenair.closest('div.containerAppoeditor')).not('button.switchDisplay').attr('disabled', 'disabled');
}

function ableBtns($contenair) {
    $('nav button', $contenair.closest('div.containerAppoeditor')).attr('disabled', false);
}

function changeViewMode($contenair) {

    var $btn = $contenair.closest('div.containerAppoeditor').find('button.switchDisplay');

    if ($btn.hasClass('viewMode')) {
        $contenair.html(escapeHtml($contenair.html()));
        $btn.removeClass('viewMode btn-secondary').addClass('codeMode btn-light');
        disableBtns($contenair);
        return true;
    }

    if ($btn.hasClass('codeMode')) {
        $contenair.html($contenair.text());
        $btn.removeClass('codeMode btn-light').addClass('viewMode btn-secondary');
        ableBtns($contenair);
        return true;
    }
}

$(document).ready(function () {

    appoEditor();
    var selection = '';
    var $editorContainer = '';
    var modalLinkEditor = WEB_APP_URL + 'lib/js/appoEditor/modal.html';

    if ($('textarea.appoeditor')) {

        // ON PRESS "ENTER" KEY
        document.execCommand('defaultParagraphSeparator', false, 'br');

        //SYNCHRONIZE DATA
        $(document.body).on('input change', 'div.inlineAppoeditor', function (e) {
            if (getViewMode($(this)) !== 'viewMode') {
                e.preventDefault();
                e.stopPropagation();
                return;
            }
            var id = $(this).data('editor-id');
            $('textarea.appoeditor[data-editor-id="' + id + '"]').val($(this).html());
        });

        //PASTE DATA
        $(document.body).on('paste', 'div.inlineAppoeditor', function (e) {
            var content;

            e.preventDefault();

            if (e.originalEvent.clipboardData) {
                content = (e.originalEvent || e).clipboardData.getData('text/plain');
                document.execCommand('insertText', false, content);
                return false;
            } else if (window.clipboardData) {
                content = window.clipboardData.getData('Text');
                if (window.getSelection)
                    window.getSelection().getRangeAt(0).insertNode(document.createTextNode(content));
            }
        });

        //CLICK ON EDITOR
        $(document.body).on('mouseup', 'div.inlineAppoeditor', function () {
            $(this).focus();
            $editorContainer = $(this);
            $('button.btnEditor').removeClass('active');

            selection = saveSelection();

            var id = $(this).data('editor-id');
            var mark = selection.startContainer.parentNode.nodeName.toLowerCase();
            var nav = $(this).prev('nav.navButton[data-editor-id="' + id + '"]');
            $('button.btnEditor[data-mark*="' + mark + '"]', nav).addClass('active');

            if (getViewMode($editorContainer) === 'codeMode') {
                disableBtns($editorContainer);
            }
        });

        //CLICK ON BUTTON TO OPEN MODAL
        $(document.body).on('click', 'button.openModalEditor', function () {

            selection = saveSelection();
            if (selection.toString().length > 0) {

                var $btn = $(this);
                $editorContainer = $btn.closest('div.containerAppoeditor').find('div.inlineAppoeditor');
                var btnContent = $btn.html();
                var idModal = $btn.data('id-modal');
                $btn.html(loaderHtml());

                busyApp();
                $('div.inlineAppoeditor').attr('contenteditable', false);
                var modal = $('<div id="modalContainerEditor" />').load(modalLinkEditor + '#' + idModal, function () {
                    $('body').append(modal);

                    var $persoModal = $('div#' + idModal);
                    $persoModal.modal('show');
                    $('.modalTitle', $persoModal).html(selection.toString());

                    if (idModal === 'insertLink') {

                        if (selection.startContainer.parentNode.href !== undefined) {
                            let $input;
                            let href = selection.startContainer.parentNode.href;
                            $('div.linkChoiceInput').css('display', 'none');

                            if (href.startsWith('http')) {
                                $('input#urlChoice').prop('checked', true);
                                $input = $('input#linkInput');
                            } else if (href.startsWith('mailto')) {
                                href = href.replace('mailto:', '');
                                $('input#emailChoice').prop('checked', true);
                                $input = $('input#emailInput');
                            } else if (href.startsWith('tel')) {
                                href = href.replace('tel:', '');
                                $('input#telChoice').prop('checked', true);
                                $input = $('input#telInput');
                            }

                            $input.val(href).parent().css('display', 'block');
                        }

                        $('#linkTitleInput', $persoModal).val(selection.startContainer.parentNode.title !== undefined ? selection.startContainer.parentNode.title : '');
                        $('#linkInputTarget', $persoModal).prop('checked', selection.startContainer.parentNode.target === '_blank');

                    } else if (idModal === 'editText') {

                        var $sel = $(selection.startContainer.parentNode)[0];
                        var style = getComputedStyle($sel);

                        var fontColor = rgb2hex(style.color);
                        var fontSize = parseFloat(style.fontSize);
                        var fontWeight = style.fontWeight;

                        var origineStyles = 'Taille du texte: <strong>' + fontSize + 'px</strong>' +
                            '<br>Poids du texte: <strong>' + fontWeight + '</strong>' +
                            '<br>Couleur du texte: <strong>' + fontColor + '</strong>';

                        $('#styleUsed', $persoModal).html(origineStyles);
                        $('.modalTitle', $persoModal).css({
                            'font-size': fontSize + 'px',
                            'font-weight': fontWeight,
                            'color': fontColor
                        });

                        $('#textSizeRange, #outputTextSizeRange', $persoModal).val(fontSize);
                        $('#textWeightRange, #outputTextWeightRange', $persoModal).val(fontWeight);
                        $('#colorInput', $persoModal).val(fontColor);

                        $('div.editorEditionInput', $persoModal).each(function (num, el) {
                            let $input = $(el).find('.editionTextInput');
                            $(el).prepend('<input type="checkbox" class="diabledInputCheckBox diabledInput" data-id-input="' + $input.attr('id') + '">');
                            $input.prop('disabled', true);
                        });
                    }

                    availableApp();
                    $('div.inlineAppoeditor').attr('contenteditable', true);
                    $btn.html(btnContent);
                });
            } else {
                alert('Veuillez sélectionner un texte !');
            }
        });

        //EDITOR TEXT EDITION DISABLED INPUT
        $(document.body).on('change', 'input.diabledInputCheckBox', function () {
            var $input = $(this);
            var $container = $input.closest('div.editorEditionInput');
            var $dataInput = $container.find('.editionTextInput');

            if ($input.hasClass('diabledInput')) {
                $input.removeClass('diabledInput');
                $dataInput.addClass('activeData').animate({opacity: 1});
            } else {
                $input.addClass('diabledInput');
                $dataInput.removeClass('activeData').animate({opacity: 0});
            }
        });

        //ON HIDDEN MODAL
        $(document.body).on('hidden.bs.modal', 'div.editorModal', function () {
            $('input, textarea', $(this)).val('');
            $('div#modalContainerEditor').remove();
        });

        //CLICK ON MODAL SUBMIT
        $(document.body).on('click', 'button.saveEditorData', function () {

            var $btn = $(this);
            var element;
            restoreSelection(selection);

            if ($btn.data('editor-save') === 'link') {

                let blank = $('input#linkInputTarget').is(':checked') ? '_blank' : '_self';
                let description = $('input#linkTitleInput').val();
                let href;
                if ($('input#urlChoice').is(':checked')) {
                    href = $('input#linkInput').val();
                } else if ($('input#emailChoice').is(':checked')) {
                    href = 'mailto:' + $('input#emailInput').val();
                } else if ($('input#telChoice').is(':checked')) {
                    href = 'tel:' + $('input#telInput').val();
                }

                //$('button.btnCmdEditor[data-cmd="unlink"]').trigger('click');
                restoreSelection(selection);

                element = document.createElement("a");
                element.setAttribute('href', href);
                element.setAttribute('title', description);
                element.setAttribute('target', blank);
                element.appendChild(document.createTextNode(selection.toString()));

            } else if ($btn.data('editor-save') === 'textEdition') {

                var color = $('input#colorInput').hasClass('activeData') ? 'color:' + $('input#colorInput').val() + ';' : '';
                var size = $('input#textSizeRange').hasClass('activeData') ? 'font-size:' + $('input#textSizeRange').val() + 'px;' : '';
                var weight = $('input#textWeightRange').hasClass('activeData') ? 'font-weight:' + $('input#textWeightRange').val() + ';' : '';

                element = document.createElement("span");
                element.setAttribute('style', color + size + weight);
                element.appendChild(document.createTextNode(selection.toString()));
            }

            insertHTML(element);
            $editorContainer.trigger('change');
            $('div.editorModal').modal('hide');
        });

        //SWITCH BETWEEN SOURCE CODE AND DISPLAY
        $(document.body).on('click', 'button.switchDisplay', function () {
            var $btn = $(this);
            $editorContainer = $btn.closest('div.containerAppoeditor').find('div.inlineAppoeditor');

            changeViewMode($editorContainer);
        });

        //SWITCH BETWEEN LINK CHOICE
        $(document.body).on('input', 'input[name="linkChoice"]', function () {
            var $input = $(this);
            var $parent = $(this).parent();
            var choice = $parent.data('choice');
            $('div.linkChoiceInput').css('display', 'none');
            $('div.linkChoiceInput[data-choice="' + choice + '"]').css('display', 'block');
        });

        //CLEAN ALL ATTRIBUTES TAGS BUTTON
        $(document.body).on('click', 'button.removeAllStyle', function () {

            var $btn = $(this);
            $editorContainer = $btn.closest('div.containerAppoeditor').find('div.inlineAppoeditor');

            if (getViewMode($editorContainer) === 'codeMode') {
                changeViewMode($editorContainer);
            }

            $('h1, h2, h3, h4, h5, h6, hr, br, p, div, span, a, strong, em, b, i, ul, ol, li', $editorContainer).each(function () {
                while (this.attributes.length > 0) {
                    this.removeAttribute(this.attributes[0].name);
                }
            }).promise().done(function () {
                $editorContainer.trigger('change');
            });
        });

        //CLEAN TAGS BUTTON
        $(document.body).on('click', 'button.cleanTags', function () {

            var $btn = $(this);
            $editorContainer = $btn.closest('div.containerAppoeditor').find('div.inlineAppoeditor');

            if (getViewMode($editorContainer) === 'codeMode') {
                changeViewMode($editorContainer);
            }

            $('p, div, span, a, strong, em, b, i', $editorContainer).each(function () {
                var $this = $(this);
                if ($this.html().replace(/\s|&nbsp;|<br>/g, '').length === 0)
                    $this.remove();
            }).promise().done(function () {
                $editorContainer.trigger('change');
            });
        });

        //REMOVE TAGS BUTTON
        $(document.body).on('click', 'button.removeTags', function () {

            var $btn = $(this);
            $editorContainer = $btn.closest('div.containerAppoeditor').find('div.inlineAppoeditor');
            if (getViewMode($editorContainer) === 'codeMode') {
                changeViewMode($editorContainer);
            }
            $editorContainer.html($editorContainer.text()).trigger('change');
        });

        //CLICK ON SIMPLE COMMAND BUTTONS
        $(document.body).on('click change', 'button.btnCmdEditor', function () {
            var $btn = $(this);
            $editorContainer = $btn.closest('div.containerAppoeditor').find('div.inlineAppoeditor');

            if (selection) {
                var val = (typeof $btn.data('cmd-val') !== 'undefined' ? $btn.data('cmd-val') : null);
                document.execCommand($btn.data('cmd'), false, val);
            }
        });

        //CLICK ON COLOR BUTTON
        $(document.body).on('click', 'div.js-color', function () {
            var $btn = $(this);
            $('div.js-color').removeClass('active');
            $btn.addClass('active');
            var color = rgb2hex($btn.css('background-color'));
            $('input#colorInput').val(color);

            if ($('#editionTextSel')) {
                $('#editionTextSel').css('color', color);
            }
        });
    }
});