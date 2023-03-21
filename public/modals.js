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
                const action = $(this).data('action');
                // Récupérer les valeurs des champs de formulaire
                let nom = $('#nom-user');
                let prenom = $('#prenom');
                let email = $('#email');
                let password = $('#password');
                let role = $('#role').val();
                let emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
                // Valider les champs
                let valid = true &&
                    checkLength(nom, 3) &&
                    checkLength(prenom, 3) &&
                    checkLength(password, 4) &&
                    checkRegexp(email, 'email', emailRegex);
                if (password.val() != $('#confirm-password').val()) {
                    valid = false;
                    displayError('Les mots de passe ne se correspondent pas');
                }
                if (valid) {
                    // Envoi des données si les données sont valides
                    $('#user-form').submit();
                }
            },
            'Annuler': function() {
                // Fermer la boîte de dialogue
                $('#modal-user-form').dialog('close');
                hideError();
            }
        },
        close: function() {
            // Réinitialiser le formulaire
            $('#modal-user-form form')[0].reset();
            hideError();
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
                const action = $(this).data('action');
                // Récupérer les valeurs des champs de formulaire
                let nom = $('#nom-matiere');
                let referantText = $('#referant-mat option:selected').text();
                let couleur = $('#couleur');
                if (checkLength(nom, 2)) {
                    // Envoi des données si les données sont valides
                    $('#matiere-form').submit();
                }
            },
            'Annuler': function() {
                // Fermer la boîte de dialogue
                $('#modal-matiere-form').dialog('close');
                hideError();
            }
        },
        close: function() {
            // Réinitialiser le formulaire
            $('#modal-matiere-form form')[0].reset();
            hideError();
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
                const action = $(this).data('action');
                // Récupérer les valeurs des champs de formulaire
                let nom = $('#nom-enseignant');
                let referant = $("input[name='referant']:checked"); // Récuperer le radio checked
                // Valider les champs
                let valid = true &&
                    checkLength(nom, 3);
                if (valid) {
                    // Envoi des données si les données sont valides
                    $('#enseignant-form').submit();
                }
            },
            'Annuler': function() {
                // Fermer la boîte de dialogue
                $('#modal-enseignant-form').dialog('close');
                hideError();
            }
        },
        close: function() {
            // Réinitialiser le formulaire
            $('#modal-enseignant-form form')[0].reset();
            hideError();
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
            $('#salle-form').attr('action', $(this).data('url'));
            // si l'action est un edit, charger le modal form avec les données issues du tr contenant le boutton cliqué
            if ($(this).data('action') == 'edit') {
                const tr = $(this).data('tr');
                $('#nom-salle').val(tr.children()[1].innerText);
            }
        },
        buttons: {
            'Enregistrer': function() {
                const action = $(this).data('action');
                // Récupérer les valeurs des champs de formulaire
                let nom = $('#nom-salle');
                // Valider les champs
                let valid = true &&
                    checkLength(nom, 3);
                if (valid) {
                    // Envoi des données si les données sont valides
                    $('#salle-form').submit();
                }
            },
            'Annuler': function() {
                // Fermer la boîte de dialogue
                $('#modal-salle-form').dialog('close');
                hideError();
            }
        },
        close: function() {
            // Réinitialiser le formulaire
            $('#modal-salle-form form')[0].reset();
            hideError();
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
