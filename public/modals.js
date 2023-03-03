$(function() {
    errorMessage = $( '.error-message' );
    /**
     * Function qui affiche les messages d'erreur par rapport au formulaire
     * @param string txt 
     */
    function displayError(txt) {
        errorMessage
          .text(txt)
          .addClass('ui-state-error');
    }

    /**
     * Function qui vérifie la longeur d'un string, valeur d'un input
     * @param {*} elt element input d'un formulaire
     * @param {*} min valeur minimal autorisé
     * @returns boolean
     */
    function checkLength(elt, min) {
        if (elt.val().length < min) {
            elt.addClass('ui-state-error');
            displayError('La longeur doit-être supérieur à ' + min);
            return false;
        } else {
            return true;
        }
    }

    /**
     * Function qui vérifie si la valeur d'un input est conforme à une expression régulière donnée
     * @param {*} elt input d'un formulaire
     * @param {*} type type de l'input
     * @param {*} regexp expression régulière
     * @returns boolean
     */
    function checkRegexp(elt, type, regexp) {
        if (!(regexp.test(elt.val()))) {
            elt.addClass('ui-state-error');
            displayError('Le format de ' + type + ' est invalide');
            return false;
        } else {
            return true;
        }
    }

    // BEGIN: Modal utilisateur
    $('#modal-user-form').dialog({
        autoOpen: false,
        modal: true,
        open: function() {
            // si l'action est un edit, charger le modal form avec les données issues du tr contenant le boutton cliqué
            if($(this).data('action') == 'edit') {
                const tr = $(this).data('tr');
                $('#nom').val(tr.children()[1].innerText);
                $('#prenom').val(tr.children()[2].innerText);
                $('#email').val(tr.children()[3].innerText);
                $('#role').val(tr.children()[4].innerText);
            }
        },
        buttons: {
        'Enregistrer': function() {
            const action = $(this).data('action');
            // Récupérer les valeurs des champs de formulaire
            let nom = $('#nom');
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
                checkRegexp(email, 'email', emailRegex)
            ;
            if (password.val() != $('#confirm-password').val()) {
                valid = false;
                displayError('Les mots de passe ne se correspondent pas');
            }
            if (valid) {
                // Envoi des données en ajax si les données sont valides
                $.ajax({
                    url: $(this).data('url'),
                    type: 'POST',
                    data: {'nom': nom.val(), 'prenom': prenom.val(), 'password': password.val(), 'email': email.val(), 'role': role},
                    success: function(response) {
                        response = JSON.parse(response);
                        let elt = null;
                        if(response.status == 'ok') {
                            if (action == 'create') { // si l'action est un create
                                const id = $('#tbody-utilisateur').children().length + 1;
                                $('#tbody-utilisateur').append('<tr>' + 
                                    '<td>' + id + '</td>' +
                                    '<td>' + nom.val() +'</td>' +
                                    '<td>' + prenom.val() + '</td>' + 
                                    '<td><a href="mailto:' + email.val() + '">' + email.val() + '</a></td>' + 
                                    '<td>' + role + '</td>' + 
                                    '<td>' +
                                        '<a href="' + window.location.href + '&edit=utilisateurs&id=' + id + '" class="btn-edit open-user-modal">Modifier</a>' +
                                        '<a href="' + window.location.href + '&delete=utilisateurs&id=' + id + '" class="btn-delete">Supprimer</a>' +
                                    '</td>' +
                                '</tr>');
                                elt = $('#tbody-utilisateur').children().last();
                            } else {
                                const id = response.id + 1;
                                $('#tbody-utilisateur tr').eq(id-1).html('<td>' + id + '</td>' +
                                    '<td>' + nom.val() +'</td>' +
                                    '<td>' + prenom.val() + '</td>' + 
                                    '<td><a href="mailto:' + email.val() + '">' + email.val() + '</a></td>' + 
                                    '<td>' + role + '</td>' + 
                                    '<td>' +
                                        '<a href="' + window.location.href + '&edit=utilisateurs&id=' + id + '" class="btn-edit open-user-modal">Modifier</a>' +
                                        '<a href="' + window.location.href + '&delete=utilisateurs&id=' + id + '" class="btn-delete">Supprimer</a>' +
                                    '</td>')
                                ;
                                elt = $('#tbody-utilisateur tr').eq(id-1);
                            }
                            $('#modal-user-form').dialog('close');
                            // Surligner la ligne créée / modifiée pendant quelques secondes
                            elt.addClass('success-highlight');
                            setTimeout(function() {
                                elt.removeClass('success-highlight', 1500 );
                            }, 500 );
                        }
                    },
                    error: function(jqXHR, textStatus, error) {
                        displayError(error);
                    }
                });
            }
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
    $('.open-user-modal').click(function(e) {
        e.preventDefault();
        const action = $(this).hasClass('btn-edit') ? 'edit' : 'create';
        $('#modal-user-form')
            .data('url', $(this).attr('href'))
            .data('action', action)
            .data('tr', $(this).parent().parent())
            .dialog('open');
    });
    // END: Modal create utilisateur
});
  