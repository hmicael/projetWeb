$(function() {
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

    /**
     * Fonction qui permet d'obtenir le parametre GET
     * @param {*} parameterName
     * @param {*} parameterName
     * @returns 
     */
    function findGetParameter(parameterName, url) {
        var result = null,
            tmp = [];
        url.split("&") // spliter l'url avec &
            .forEach(function (item) {
                // pour chaque split, respliter pour avoir séparer la clé et le valeur
              tmp = item.split("=");
              if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
            });
        return result;
    }

    $( "#tabs" ).tabs();

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
                $('#email').attr('readonly', true); // l'email n'est plus modifiable
                $('#role').val(tr.children()[4].innerText);
            } else {
                $('#email').attr('readonly', false);
            }
            // check si les mots de passe correspondent
            $('#confirm-password').on('keyup', function() {
                if ($(this).val() != $('#password').val()) {
                    $("div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:nth-child(1)").attr("disabled", true);
                    $('#password-not-match').show();
                } else {
                    $("div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:nth-child(1)").attr("disabled", false);
                    $('#password-not-match').hide();
                }
            });
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

    // BEGIN: Modal create edt
    $('#modal-edt-form').dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        open: function() {
            // Faire en sorte que le button généré par le modal soit le boutton de submit du formulaire
            $('div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:nth-child(1)')
                .attr('type', 'submit');
            $('div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:nth-child(1)')
                .attr('form', 'edt-form');
            // Modifier la trajectoire de l'action
            $('#edt-form').attr('action', $(this).data('url'));
            // Checker le checkbox du groupe et le mettre en readonly
            const checkedGroup  = '#form-edt-groupe-' + findGetParameter("groupe", $(this).data('url'));
            $(checkedGroup).prop('checked', true);
            // empecher l'utilisateur de décocher le checkbox sur le groupe où on a declencher l'action
            $('.form-edt-groupe').removeAttr('onclick');
            $(checkedGroup).attr('onclick', "return false;");
            // set heure de début
            const hdeb = findGetParameter("heure", $(this).data('url'));
            $('#form-edt-hdebut').val(hdeb);
            $('#form-edt-hdebut').prop('readonly', true);
            // set heure de fin min = hdeb
            $('#form-edt-hfin').prop('min', hdeb);
            // set valeur heure de fin = valeur heure debut + 15mn
            const timeParts = hdeb.split(":"); // Split heure et minute
            const heure = parseInt(timeParts[0], 10);
            const minutes = parseInt(timeParts[1], 10);
            const dateFin = new Date();
            dateFin.setHours(heure);
            dateFin.setMinutes(minutes + 15);
            // Conversion de la date en un nouveau format de chaîne de temps
            const hebPlus15 = dateFin.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            $('#form-edt-hfin').val(hebPlus15);
            // set date
            const lundiSemaine = findGetParameter("semaine", $(this).data('url'));
            const jour = findGetParameter("jour", $(this).data('url')) - 1;
            let date = new Date(lundiSemaine);
            day = date.getDate() + jour;
            date.setDate(day);
            // ajout de 0 devant le mois < 10 pour avoir un format correct
            month = date.getMonth()+1;
            month = month < 10 ? '0'+month : month;
            day = date.getDate() < 10 ? '0'+date.getDate() : date.getDate();
            date = date.getFullYear() + '-' + month + '-' + day;
            $('#form-edt-date').val(date);
            $('#form-edt-date').prop('readonly', true);
            // Choix du prof lorsqu'on choisi une matière
            $('#form-edt-matiere').on('change', function() {
                // vide le select
                $('#form-edt-enseignant').empty();
                // faire une requete ajax pour obtenir les enseignants
                $.ajax({
                    url: 'index.php?action=ajax&search=enseignant',
                    method: 'POST',
                    data: {'matiere' : $(this).val()},
                    success: function(response) {
                        const obj = JSON.parse(response);
                        const data = obj.data;
                        $.each(data, function(key, value) {
                            $('#form-edt-enseignant').append('<option value="' + value + '">' + value + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log("Error: " + error);
                    }
                });
            });
            // si l'action est un edit, charger le modal form avec les données issues du tr contenant le boutton cliqué
            if ($(this).data('action') == 'edit') {
                // faire une requete ajax pour obtenir les infos de l'edt selectionné
                $.ajax({
                    url: 'index.php?action=ajax&search=edt',
                    method: 'POST',
                    data: {
                        'heure': hdeb,
                        'jour': jour,
                        'groupe':findGetParameter("groupe", $(this).data('url')) - 1,
                        'semaine': lundiSemaine
                    },
                    success: function(response) {
                        const obj = JSON.parse(response);
                        const data = obj.data;
                        $('#form-edt-matiere').val(data.matiere);
                        $('#form-edt-type').val(data.type);
                        $('#form-edt-enseignant').val(data.enseignant);
                        $('#form-edt-salle').val(data.salle);
                        $('#form-edt-hdebut').val(data.hdebut);
                        $('#form-edt-hfin').val(data.hfin);
                        $('#form-edt-date').val(data.date);
                        $.each(data.groupes, function(key, value) {
                            $('#form-edt-groupe-' + (value+1)).prop('checked', true);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log("Error: " + error);
                    }
                });
                
            }
        },
        buttons: {
            'Enregistrer': function() {
                // ne rien faire puisque la validation se fait déjà par les attributs HTML
            },
            'Annuler': function() {
                // Fermer la boîte de dialogue
                $('#modal-edt-form').dialog('close');
            }
        },
        close: function() {
            // Réinitialiser le formulaire
            $('#modal-edt-form form')[0].reset();
        }
    });
    // Ouvrir la boîte de dialogue
    $('.open-edt-modal').on('click', function(e) {
        e.preventDefault();
        const action = $(this).hasClass('btn-edit') ? 'edit' : 'create';
        $('#modal-edt-form')
            .data('url', $(this).attr('href'))
            .data('action', action)
            .dialog('open');
    });
    // END: Modal create edt

});
