$(function() {
    errorMessage = $('.error-message');

    /**
     * Fonction qui affiche les messages d'erreur par rapport au formulaire
     * @param string txt 
     */
    function displayError(txt) {
        $('.error-message').show();
        errorMessage
            .text(txt)
            .addClass('ui-state-error');
    }

    /**
     * Fonction qui vérifie la longeur d'un string, valeur d'un input
     * @param {*} elt element input d'un formulaire
     * @param {*} min valeur minimal autorisé
     * @returns boolean
     */
    function checkLength(elt, min) {
        if (elt.val().length < min) {
            elt.addClass('ui-state-error');
            displayError('La longeur doit-être supérieur ou égale à ' + min);
            return false;
        } else {
            return true;
        }
    }

    /**
     * Fonction qui enlève toutes les messages ou forme d'erreur
     */
    function hideError() {
        errorMessage.hide();
        $('.ui-state-error').removeClass('ui-state-error');
    }

    /**
     * Fonction qui vérifie si la valeur d'un input est conforme à une expression régulière donnée
     * @param {*} elt input d'un formulaire
     * @param {*} type type de l'input
     * @param {*} regexp expression régulière
     * @returns boolean
     */
    function checkRegexp(elt, type, regexp) {
        if (!(regexp.test(elt.val()))) {
            elt.addClass('ui-state-error');
            displayError('Le format de l\' ' + type + ' est invalide');
            return false;
        } else {
            return true;
        }
    }

    /**
     * Fonction qui converti un couleur RGB en Hex
     * @param {} rgb 
     * @returns 
     */
    function convertRgbToHex(rgb) {
        rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
      
        function hexCode(i) {
            return ("0" + parseInt(i).toString(16)).slice(-2);
        }
        return "#" + hexCode(rgb[1]) + hexCode(rgb[2])
                + hexCode(rgb[3]);
    }

    // BEGIN: dialog confirm detete
    $('#dialog-confirm').dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        height: 'auto',
        width: 400,
        buttons: {
            'Annuler': function() {
                $(this).dialog('close');
            },
            'Supprimer': function() {
                window.location.href = $(this).data('url');
            }
        }
    });
    // END: dialog confirm detete

    // Ouvrir la boîte de dialogue
    $('body').on('click', '.btn-delete', function(e) {
        e.preventDefault();
        $('#dialog-confirm')
            .data('url', $(this).attr('href'))
            .dialog('open');
    });
    // END: dialog confirm delete

    // BEGIN: Modal utilisateur
    $('#modal-user-form').dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        open: function() {
            // Faire en sorte que le button généré par le modal soit le boutton de submit du formulaire
            $('div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:nth-child(1)').attr('type', 'submit');
            $('div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:nth-child(1)').attr('form', 'user-form');
            // Modifier la trajectoire de l'action
            $('#user-form').attr('action', $(this).data('url'));
            // si l'action est un edit, charger le modal form avec les données issues du tr contenant le boutton cliqué
            if ($(this).data('action') == 'edit') {
                const tr = $(this).data('tr');
                $('#nom-user').val(tr.children()[1].innerText);
                $('#prenom').val(tr.children()[2].innerText);
                $('#email').val(tr.children()[3].innerText);
                $('#role').val(tr.children()[4].innerText);
            }
        },
        buttons: {
            'Enregistrer': function() {
                // ne rien faire puisque la validation se fait déjà par les attributs HTML
            },
            'Annuler': function() {
                // Fermer la boîte de dialogue
                $('#modal-user-form').dialog('close');
            }
        },
        close: function() {
            // Réinitialiser le formulaire
            $('#modal-user-form form')[0].reset();
        }
    });

    // Ouvrir la boîte de dialogue
    $('.open-user-modal').on('click', function(e) {
        e.preventDefault();
        const action = $(this).hasClass('btn btn-edit') ? 'edit' : 'create';
        $('#modal-user-form')
            .data('url', $(this).attr('href'))
            .data('action', action)
            .data('tr', $(this).parent().parent())
            .dialog('open');
    });
    // END: Modal create utilisateur

    // BEGIN: Modal matiere
    $('#modal-matiere-form').dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        open: function() {
            // Faire en sorte que le button généré par le modal soit le boutton de submit du formulaire
            $('div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:nth-child(1)').attr('type', 'submit');
            $('div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:nth-child(1)').attr('form', 'matiere-form');
            // Modifier la trajectoire de l'action
            $('#matiere-form').attr('action', $(this).data('url'));
            // si l'action est un edit, charger le modal form avec les données issues du tr contenant le boutton cliqué
            if ($(this).data('action') == 'edit') {
                const tr = $(this).data('tr');
                $('#nom-matiere').val(tr.children()[1].innerText);
                $('#referant-mat').val(tr.children()[2].innerText);
                $('#couleur').val(convertRgbToHex(tr.children()[3].style.backgroundColor));
            }
        },
        buttons: {
            'Enregistrer': function() {
                // ne rien faire puisque la validation se fait déjà par les attributs HTML
            },
            'Annuler': function() {
                // Fermer la boîte de dialogue
                $('#modal-matiere-form').dialog('close');
            }
        },
        close: function() {
            // Réinitialiser le formulaire
            $('#modal-matiere-form form')[0].reset();
        }
    });

    // Ouvrir la boîte de dialogue
    $('.open-matiere-modal').on('click', function(e) {
        e.preventDefault();
        const action = $(this).hasClass('btn btn-edit') ? 'edit' : 'create';
        $('#modal-matiere-form')
            .data('url', $(this).attr('href'))
            .data('action', action)
            .data('tr', $(this).parent().parent())
            .dialog('open');
    });
    // END: Modal create matiere

    // BEGIN: Modal Enseignants
    $('#modal-enseignant-form').dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        open: function() {
            // Faire en sorte que le button généré par le modal soit le boutton de submit du formulaire
            $('div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:nth-child(1)').attr('type', 'submit');
            $('div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:nth-child(1)').attr('form', 'enseignant-form');
            // Modifier la trajectoire de l'action
            $('#enseignant-form').attr('action', $(this).data('url'));
            // si l'action est un edit, charger le modal form avec les données issues du tr contenant le boutton cliqué
            if ($(this).data('action') == 'edit') {
                const tr = $(this).data('tr');
                $('#nom-enseignant').val(tr.children()[1].innerText);
                if (tr.children()[2].innerText == "Oui") {
                    $('#radio-oui').attr('checked', 'checked');
                } else {
                    $('#radio-non').attr('checked', 'checked');
                }
            }
        },
        buttons: {
            'Enregistrer': function() {
                // ne rien faire puisque la validation se fait déjà par les attributs HTML
            },
            'Annuler': function() {
                // Fermer la boîte de dialogue
                $('#modal-enseignant-form').dialog('close');
            }
        },
        close: function() {
            // Réinitialiser le formulaire
            $('#modal-enseignant-form form')[0].reset();
        }
    });

    // Ouvrir la boîte de dialogue
    $('.open-enseignant-modal').on('click', function(e) {
        e.preventDefault();
        const action = $(this).hasClass('btn-edit') ? 'edit' : 'create';
        $('#modal-enseignant-form')
            .data('url', $(this).attr('href'))
            .data('action', action)
            .data('tr', $(this).parent().parent())
            .dialog('open');
    });
    // END: Modal create Enseignants

    // BEGIN: Modal Salle
    $('#modal-salle-form').dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        open: function() {
            // Faire en sorte que le button généré par le modal soit le boutton de submit du formulaire
            $('div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:nth-child(1)').attr('type', 'submit');
            $('div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:nth-child(1)').attr('form', 'salle-form');
            // Modifier la trajectoire de l'action
            $('#salle-form').attr('action', $(this).data('url'));
            // si l'action est un edit, charger le modal form avec les données issues du tr contenant le boutton cliqué
            if ($(this).data('action') == 'edit') {
                const tr = $(this).data('tr');
                $('#nom-salle').val(tr.children()[1].innerText);
            }
        },
        buttons: {
            'Enregistrer': function() {
                // ne rien faire puisque la validation se fait déjà par les attributs HTML
            },
            'Annuler': function() {
                // Fermer la boîte de dialogue
                $('#modal-salle-form').dialog('close');
            }
        },
        close: function() {
            // Réinitialiser le formulaire
            $('#modal-salle-form form')[0].reset();
        }
    });

    // Ouvrir la boîte de dialogue
    $('.open-salle-modal').on('click', function(e) {
        e.preventDefault();
        const action = $(this).hasClass('btn-edit') ? 'edit' : 'create';
        $('#modal-salle-form')
            .data('url', $(this).attr('href'))
            .data('action', action)
            .data('tr', $(this).parent().parent())
            .dialog('open');
    });
    // END: Modal create Salle
});
