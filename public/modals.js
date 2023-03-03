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

    // BEGIN: Modal create utilisateur
    $('#dialog-create-user-form').dialog({
        autoOpen: false,
        modal: true,
        buttons: {
        'Créer': function() {
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
                    url: window.location.href + '&create=utilisateurs',
                    type: 'POST',
                    data: {'nom': nom.val(), 'prenom': prenom.val(), 'password': password.val(), 'email': email.val(), 'role': role},
                    success: function(response) {
                        response = JSON.parse(response);
                        if(response.status == 'ok') {
                            $('#tbody-utilisateur').append('<tr><td></td></tr>');
                            $('#dialog-create-user-form').dialog('close');
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
            $('#dialog-create-user-form').dialog('close');
        }
        },
        close: function() {
            // Réinitialiser le formulaire
            $('#dialog-create-user-form form')[0].reset();
        }
    });

    // Ouvrir la boîte de dialogue lorsqu'on clique sur le bouton Créer un utilisateur
    $('#create-user-button').click(function() {
        $('#dialog-create-user-form').dialog('open');
    });
    // END: Modal create utilisateur
});
  