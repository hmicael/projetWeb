$(function () {
    errorMessage = $('.error-message');

    /**
     * Fonction qui affiche les messages d'erreur par rapport au formulaire
     * @param string txt 
     */
    function displayError(txt) {
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
        console.log(elt.val().length);
        if (elt.val().length < min) {
            elt.addClass('ui-state-error');
            displayError('La longeur doit-être supérieur ou égale à ' + min);
            return false;
        } else {
            return true;
        }
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

    // BEGIN: dialog confirm detete
    $('#dialog-confirm').dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        height: 'auto',
        width: 400,
        buttons: {
            'Annuler': function () {
                $(this).dialog('close');
            },
            'Supprimer': function () {
                window.location.href = $(this).data('url');
            }
        }
    });
    // END: dialog confirm detete

    // Ouvrir la boîte de dialogue
    $('body').on('click', '.btn-delete', function (e) {
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
        open: function () {
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
            'Enregistrer': function () {
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
                    // Envoi des données en ajax si les données sont valides
                    $.ajax({
                        url: $(this).data('url'),
                        type: 'POST',
                        data: {
                            'nom': nom.val(), 'prenom': prenom.val(), 'password': password.val(),
                            'email': email.val(), 'role': role
                        },
                        success: function (response) {
                            response = JSON.parse(response);
                            let elt = null;
                            if (response.status == 'ok') {
                                if (action == 'create') { // si l'action est un create, on fait un append
                                    const id = $('#tbody-utilisateur').children().length + 1;
                                    $('#tbody-utilisateur').append('<tr>' +
                                            '<td>' + id + '</td>' +
                                            '<td>' + nom.val() + '</td>' +
                                            '<td>' + prenom.val() + '</td>' +
                                            '<td><a href="mailto:' + email.val() + '">' + email.val() + '</a></td>' +
                                            '<td>' + role + '</td>' +
                                            '<td>' +
                                                '<a href="' + window.location.href + '&edit=utilisateurs&id=' +
                                                id + '" class="btn btn-edit open-user-modal">Modifier</a>' +
                                                '<a href="' + window.location.href + '&delete=utilisateurs&id=' +
                                                id + '#tabs-1" class="btn btn-delete">Supprimer</a>' +
                                            '</td>' +
                                        '</tr>');
                                    elt = $('#tbody-utilisateur').children().last();
                                } else { // sinon, on change son contenu
                                    const id = response.id + 1;
                                    $('#tbody-utilisateur tr').eq(id - 1).html('<td>' + id + '</td>' +
                                        '<td>' + nom.val() + '</td>' +
                                        '<td>' + prenom.val() + '</td>' +
                                        '<td><a href="mailto:' + email.val() + '">' + email.val() + '</a></td>' +
                                        '<td>' + role + '</td>' +
                                        '<td>' +
                                            '<a href="' + window.location.href + '&edit=utilisateurs&id=' +
                                            id + '" class="btn btn-edit open-user-modal">Modifier</a>' +
                                            '<a href="' + window.location.href + '&delete=utilisateurs&id=' +
                                            id + '#tabs-1" class="btn btn-delete">Supprimer</a>' +
                                        '</td>');
                                    elt = $('#tbody-utilisateur tr').eq(id - 1);
                                }
                                $('#modal-user-form').dialog('close');
                                // Surligner la ligne crée / modifiée pendant quelques secondes
                                elt.addClass('success-highlight');
                                setTimeout(function () {
                                    elt.removeClass('success-highlight', 1500);
                                }, 500);
                            }
                        },
                        error: function (jqXHR, textStatus, error) {
                            displayError(error);
                        }
                    });
                }
            },
            'Annuler': function () {
                // Fermer la boîte de dialogue
                $('#modal-user-form').dialog('close');
            }
        },
        close: function () {
            // Réinitialiser le formulaire
            $('#modal-user-form form')[0].reset();
        }
    });

    // Ouvrir la boîte de dialogue
    $('body').on('click', '.open-user-modal', function (e) {
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
        open: function () {
            // si l'action est un edit, charger le modal form avec les données issues du tr contenant le boutton cliqué
            if ($(this).data('action') == 'edit') {
                const tr = $(this).data('tr');
                $('#nom-matiere').val(tr.children()[1].innerText);
            }
        },
        buttons: {
            'Enregistrer': function () {
                const action = $(this).data('action');
                // Récupérer les valeurs des champs de formulaire
                let nom = $('#nom-matiere');
                if (checkLength(nom, 3)) {
                    // Envoi des données en ajax si les données sont valides
                    $.ajax({
                        url: $(this).data('url'),
                        type: 'POST',
                        data: { 'nom': nom.val() },
                        success: function (response) {
                            response = JSON.parse(response);
                            let elt = null;
                            if (response.status == 'ok') {
                                if (action == 'create') { // si l'action est un create, on fait un append
                                    const id = $('#tbody-matiere').children().length + 1;
                                    $('#tbody-matiere').append('<tr>' +
                                            '<td>' + id + '</td>' +
                                            '<td>' + nom.val() + '</td>' +
                                            '<td>' +
                                                '<a href="' + window.location.href + '&edit=matieres&id=' +
                                                id + '" class="btn btn-edit open-matiere-modal">Modifier</a>' +
                                                '<a href="' + window.location.href + '&delete=matieres&id=' +
                                                id + '#tabs-1" class="btn btn-delete">Supprimer</a>' +
                                            '</td>' +
                                        '</tr>');
                                    elt = $('#tbody-matiere').children().last();
                                } else { // sinon, on change son contenu
                                    const id = response.id + 1;
                                    $('#tbody-matiere tr').eq(id - 1).html('<td>' + id + '</td>' +
                                        '<td>' + nom.val() + '</td>' +
                                        '<td>' +
                                            '<a href="' + window.location.href + '&edit=matieres&id=' +
                                            id + '" class="btn btn-edit open-matiere-modal">Modifier</a>' +
                                            '<a href="' + window.location.href + '&delete=matieres&id=' +
                                            id + '#tabs-1" class="btn btn-delete">Supprimer</a>' +
                                        '</td>');
                                    elt = $('#tbody-matiere tr').eq(id - 1);
                                }
                                $('#modal-matiere-form').dialog('close');
                                // Surligner la ligne crée / modifiée pendant quelques secondes
                                elt.addClass('success-highlight');
                                setTimeout(function () {
                                    elt.removeClass('success-highlight', 1500);
                                }, 500);
                            }
                        },
                        error: function (jqXHR, textStatus, error) {
                            displayError(error);
                        }
                    });
                }
            },
            'Annuler': function () {
                // Fermer la boîte de dialogue
                $('#modal-matiere-form').dialog('close');
            }
        },
        close: function () {
            // Réinitialiser le formulaire
            $('#modal-matiere-form form')[0].reset();
        }
    });

    // Ouvrir la boîte de dialogue
    $('body').on('click', '.open-matiere-modal', function (e) {
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
        open: function () {
            // si l'action est un edit, charger le modal form avec les données issues du tr contenant le boutton cliqué
            if ($(this).data('action') == 'edit') {
                const tr = $(this).data('tr');
                $('#nom_e').val(tr.children()[1].innerText);
                $('#matiere').val(tr.children()[2].innerText);
            }
        },
        buttons: {
            'Enregistrer': function () {
                const action = $(this).data('action');
                // Récupérer les valeurs des champs de formulaire
                let nom = $('#nom_e');
                let matiere = $('#matiere');
                // Valider les champs
                let valid = true &&
                    checkLength(nom, 3) &&
                    checkLength(matiere, 3);
                if (valid) {
                    // Envoi des données en ajax si les données sont valides
                    $.ajax({
                        url: $(this).data('url'),
                        type: 'POST',
                        data: {
                            'nom': nom.val(), 'matiere': matiere.val()
                        },
                        success: function (response) {
                            response = JSON.parse(response);
                            let elt = null;
                            if (response.status == 'ok') {
                                if (action == 'create') { // si l'action est un create, on fait un append
                                    const id = $('#tbody-utilisateur').children().length + 1;
                                    $('#tbody-utilisateur').append('<tr>' +
                                            '<td>' + id + '</td>' +
                                            '<td>' + nom.val() + '</td>' +
                                            '<td>' + matiere.val() + '</td>' +
                                            '<td>' +
                                                '<a href="' + window.location.href + '&edit=enseignants&id=' +
                                                id + '" class="btn-edit open-user-modal">Modifier</a>' +
                                                '<a href="' + window.location.href + '&delete=enseignants&id=' +
                                                id + '#tabs-1" class="btn-delete">Supprimer</a>' +
                                            '</td>' +
                                        '</tr>');
                                    elt = $('#tbody-enseignant').children().last();
                                } else { // sinon, on change son contenu
                                    const id = response.id + 1;
                                    $('#tbody-enseignant tr').eq(id - 1).html('<td>' + id + '</td>' +
                                        '<td>' + nom.val() + '</td>' +
                                        '<td>' + matiere.val() + '</td>' +
                                        '<td>' +
                                            '<a href="' + window.location.href + '&edit=enseignants&id=' +
                                            id + '" class="btn-edit open-user-modal">Modifier</a>' +
                                            '<a href="' + window.location.href + '&delete=enseignants&id=' +
                                            id + '#tabs-1" class="btn-delete">Supprimer</a>' +
                                        '</td>');
                                    elt = $('#tbody-enseignant tr').eq(id - 1);
                                }
                                $('#modal-enseignant-form').dialog('close');
                                // Surligner la ligne crée / modifiée pendant quelques secondes
                                elt.addClass('success-highlight');
                                setTimeout(function () {
                                    elt.removeClass('success-highlight', 1500);
                                }, 500);
                            }
                        },
                        error: function (jqXHR, textStatus, error) {
                            displayError(error);
                        }
                    });
                }
            },
            'Annuler': function () {
                // Fermer la boîte de dialogue
                $('#modal-enseignant-form').dialog('close');
            }
        },
        close: function () {
            // Réinitialiser le formulaire
            $('#modal-enseignant-form form')[0].reset();
        }
    });

    // Ouvrir la boîte de dialogue
    $('body').on('click', '.open-enseignant-modal', function (e) {
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
        open: function () {
            // si l'action est un edit, charger le modal form avec les données issues du tr contenant le boutton cliqué
            if ($(this).data('action') == 'edit') {
                const tr = $(this).data('tr');
                $('#nom_s').val(tr.children()[1].innerText);
            }
        },
        buttons: {
            'Enregistrer': function () {
                const action = $(this).data('action');
                // Récupérer les valeurs des champs de formulaire
                let nom = $('#nom_s');
                // Valider les champs
                let valid = true &&
                    checkLength(nom, 3);
                if (valid) {
                    // Envoi des données en ajax si les données sont valides
                    $.ajax({
                        url: $(this).data('url'),
                        type: 'POST',
                        data: {
                            'nom': nom.val()
                        },
                        success: function (response) {
                            response = JSON.parse(response);
                            let elt = null;
                            if (response.status == 'ok') {
                                if (action == 'create') { // si l'action est un create, on fait un append
                                    const id = $('#tbody-salle').children().length + 1;
                                    $('#tbody-salle').append('<tr>' +
                                            '<td>' + id + '</td>' +
                                            '<td>' + nom.val() + '</td>' +
                                            '<td>' +
                                                '<a href="' + window.location.href + '&edit=salles&id=' +
                                                id + '" class="btn-edit open-salle-modal">Modifier</a>' +
                                                '<a href="' + window.location.href + '&delete=salles&id=' +
                                                id + '#tabs-1" class="btn-delete">Supprimer</a>' +
                                            '</td>' +
                                        '</tr>');
                                    elt = $('#tbody-salle').children().last();
                                } else { // sinon, on change son contenu
                                    const id = response.id + 1;
                                    $('#tbody-salle tr').eq(id - 1).html('<td>' + id + '</td>' +
                                        '<td>' + nom.val() + '</td>' +
                                        '<td>' +
                                            '<a href="' + window.location.href + '&edit=salles&id=' +
                                            id + '" class="btn-edit open-salle-modal">Modifier</a>' +
                                            '<a href="' + window.location.href + '&delete=salles&id=' +
                                            id + '#tabs-1" class="btn-delete">Supprimer</a>' +
                                        '</td>');
                                    elt = $('#tbody-salle tr').eq(id - 1);
                                }
                                $('#modal-salle-form').dialog('close');
                                // Surligner la ligne crée / modifiée pendant quelques secondes
                                elt.addClass('success-highlight');
                                setTimeout(function () {
                                    elt.removeClass('success-highlight', 1500);
                                }, 500);
                            }
                        },
                        error: function (jqXHR, textStatus, error) {
                            displayError(error);
                        }
                    });
                }
            },
            'Annuler': function () {
                // Fermer la boîte de dialogue
                $('#modal-salle-form').dialog('close');
            }
        },
        close: function () {
            // Réinitialiser le formulaire
            $('#modal-salle-form form')[0].reset();
        }
    });

    // Ouvrir la boîte de dialogue
    $('body').on('click', '.open-salle-modal', function (e) {
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
