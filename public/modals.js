$(function() {
    errorMessage = $( ".error-message" );

    function displayError(t) {
        errorMessage
          .text(t)
          .addClass("ui-state-error");
    }

    function checkLength(elt, min) {
        if (elt.val().length < min) {
            elt.addClass("ui-state-error");
            displayError("La longeur doit-être supérieur à " + min);
            return false;
        } else {
            return true;
        }
    }

    function checkRegexp(elt, type, regexp) {
        if (!(regexp.test(elt.val()))) {
            elt.addClass("ui-state-error");
            displayError("Le format de " + type + " est invalide");
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
        "Créer": function() {
            // Récupérer les valeurs des champs de formulaire
            let nom = $('#nom');
            let prenom = $('#prenom');
            let email = $('#email');
            let password = $('#password');
            let role = $('#role').val();
            let emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
            let valid = true &&
                checkLength(nom, 3) &&
                checkLength(prenom, 3) &&
                checkLength(password, 4) &&
                checkRegexp(email, "email", emailRegex)
            ;
            if (password.val() != $('#confirm-password').val()) {
                valid = false;
                displayError("Les mots de passe ne se correspondent pas");
            }
            if (valid) {
                // Envoi des données en ajax
                $.ajax({
                    url: window.location.href + '&create=utilisateurs',
                    type: 'POST',
                    data: {'nom': nom.val(), 'prenom': prenom.val(), 'password': password.val(), 'email': email.val(), 'role': role},
                    success: function(response) {
                        console.log(response);
                        $('#dialog-create-user-form').dialog('close');
                    },
                    error: function(jqXHR, textStatus, error) {
                        displayError(error);
                    }
                });
            }
        },
        "Annuler": function() {
            // Fermer la boîte de dialogue
            $('#dialog-create-user-form').dialog('close');
        }
        },
        close: function() {
            // Réinitialiser le formulaire
            $('#dialog-create-user-form form')[0].reset();
        }
    });

    // Ouvrir la boîte de dialogue lorsqu'on clique sur le bouton "Créer un utilisateur"
    $('#create-user-button').click(function() {
        $('#dialog-create-user-form').dialog('open');
    });
    // END: Modal create utilisateur
});
  