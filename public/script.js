$(function () {
    /**
     * Fonction qui convertit une couleur RGBA en Hex (ex: rgba(255, 255, 255, 1) => #FFFFFF)
     * @param {string} rgba la couleur en rgba
     * @returns {string} la couleur en hex
     */
    function convertRgbaToHex(rgba) {
        rgba = rgba.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+),?\s*([\d\.]+)?\)$/);

        function hexCode(i) {
            // convertit decimal en hexadecimal
            return ('0' + parseInt(i).toString(16)).slice(-2);
        }

        return '#' + hexCode(rgba[1]) + hexCode(rgba[2]) + hexCode(rgba[3]);
    }


    /**
     * Fonction qui permet d'obtenir le parametre GET d'une url
     * @param {*} parameterName le n
     * @param {*} url l'url
     * @returns 
     */
    function findGetParameter(parameterName, url) {
        let result = null,
            tmp = [];
        url.split('&') // spliter l'url avec &
            .forEach(function (item) {
                // pour chaque split, respliter pour avoir séparer la clé et le valeur
                tmp = item.split('=');
                if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
            });
        return result;
    }

    /**
     * Fonction qui fait une requete ajax pour rechercher le bon enseignant pour une matière donnée
     * @param {*} matiere le nom de la matière
     */
    function searchEnseignantByMatiere(matiere, enseignant = null) {
        $.ajax({
            url: 'index.php?action=ajax&search=enseignant',
            method: 'POST',
            data: { 'matiere': matiere.split(';')[0] }, // matiere: nom;couleur, on recherche par le nom
            success: function (response) {
                $('#form-edt-enseignant').empty();
                const obj = JSON.parse(response);
                const data = obj.data;
                $.each(data, function (key, value) {
                    $('#form-edt-enseignant').append('<option value="' + value.replace(/ /g, '_') + '">' + value + '</option>');
                });
                if (enseignant != null) {
                    $('#form-edt-enseignant').val(enseignant.replace(/ /g, '_'));
                }
            },
            error: function (xhr, status, error) {
                // erreur
            }
        });
    }

    /**
     * Fonction appelée lorsqu'on clique sur un boutton qui est censé ouvrir un modal
     * @param {*} e c'est l'event
     * @param {*} modalSelector le selecteur du modal
     */
    function callbackClickButtonModal(e, modalSelector) {
        e.preventDefault(); // empecher le comportement par défaut du boutton
        const action = $(e.currentTarget).hasClass('btn-edit') ? 'edit' : 'create'; // si le boutton a la classe btn-edit, on est en mode edit
        $(modalSelector)
            .data('url', $(e.currentTarget).attr('href')) // on stocke l'url dans le modal
            .data('action', action) // on stocke l'action dans le modal
            .data('tr', $(e.currentTarget).parent().parent()) // on stocke le tr dans le modal
            .dialog('open'); // on ouvre le modal
    }


    $('#tabs').tabs(); // active le tabs dans la page d'admin

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
                window.location.href = $(this).data('url'); // rediriger vers la page de suppression
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
        width: 400,
        height: 500,
        open: function () {
            // Faire en sorte que le button généré par le modal soit le boutton de submit du formulaire
            // et que le formulaire soit celui du modal
            $('div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:nth-child(1)').attr('type', 'submit');
            $('div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:nth-child(1)').attr('form', 'user-form');
            // Modifier la trajectoire de l'action
            $('#user-form').attr('action', $(this).data('url'));
            // si l'action est un edit, charger le modal form avec les données issues du tr contenant le boutton cliqué
            if ($(this).data('action') === 'edit') {
                const tr = $(this).data('tr');
                $('#nom-user').val(tr.children()[1].innerText);
                $('#prenom').val(tr.children()[2].innerText);
                $('#email').val(tr.children()[3].innerText);
                $('#email').attr('readonly', true); // l'email n'est plus modifiable
                $('#role').val(tr.children()[4].innerText);
            } else {
                $('#email').attr('readonly', false);
                document.getElementById('user-form').reset(); // reset le formulaire
            }
            // check si les mots de passe correspondent
            $('#confirm-password').on('keyup', function () {
                if ($(this).val() !== $('#password').val()) {
                    $('div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:nth-child(1)').attr('disabled', true);
                    $('#password-not-match').show();
                } else {
                    $('div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:nth-child(1)').attr('disabled', false);
                    $('#password-not-match').hide();
                }
            });
        },
        buttons: {
            'Enregistrer': function () {
                // ne rien faire puisque la validation se fait déjà par les attributs HTML
            },
            'Annuler': function () {
                // Fermer la boîte de dialogue
                $('#modal-user-form').dialog('close');
            }
        },
        close: function () {
            $('#modal-user-form').dialog('close');
        }
    });

    // Ouvrir la boîte de dialogue
    $('.open-user-modal').on('click', function (e) {
        callbackClickButtonModal(e, '#modal-user-form');
    });
    // END: Modal create utilisateur

    // BEGIN: Modal matiere
    $('#modal-matiere-form').dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        open: function () {
            // Faire en sorte que le button généré par le modal soit le boutton de submit du formulaire
            $('div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:nth-child(1)').attr('type', 'submit');
            $('div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:nth-child(1)').attr('form', 'matiere-form');
            // Modifier la trajectoire de l'action
            $('#matiere-form').attr('action', $(this).data('url'));
            // si l'action est un edit, charger le modal form avec les données issues du tr contenant le boutton cliqué
            if ($(this).data('action') === 'edit') {
                const tr = $(this).data('tr');
                $('#nom-matiere').val(tr.children()[1].innerText);
                $('#referent-mat').val(tr.children()[2].innerText.replace(/ /g, '_')); // enlever les espaces
                $('#couleur').val(convertRgbaToHex(tr.children()[3].style.backgroundColor));
            } else {
                document.getElementById('matiere-form').reset(); // reset le formulaire
            }
        },
        buttons: {
            'Enregistrer': function () {
                // ne rien faire puisque la validation se fait déjà par les attributs HTML
            },
            'Annuler': function () {
                // Fermer la boîte de dialogue
                $('#modal-matiere-form').dialog('close');
            }
        },
        close: function () {
            $('#modal-matiere-form').dialog('close');
        }
    });

    // Ouvrir la boîte de dialogue
    $('.open-matiere-modal').on('click', function (e) {
        callbackClickButtonModal(e, '#modal-matiere-form');
    });
    // END: Modal create matiere

    // BEGIN: Modal Enseignants
    $('#modal-enseignant-form').dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        open: function () {
            // Faire en sorte que le button généré par le modal soit le boutton de submit du formulaire
            $('div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:nth-child(1)').attr('type', 'submit');
            $('div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:nth-child(1)').attr('form', 'enseignant-form');
            // Modifier la trajectoire de l'action
            $('#enseignant-form').attr('action', $(this).data('url'));
            // si l'action est un edit, charger le modal form avec les données issues du tr contenant le boutton cliqué
            if ($(this).data('action') === 'edit') {
                const tr = $(this).data('tr');
                $('#nom-enseignant').val(tr.children()[1].innerText);
                if (tr.children()[2].innerText == 'Oui') {
                    $('#referent-radio-non').removeAttr('checked');
                    $('#referent-radio-oui').attr('checked', 'checked');
                } else {
                    $('#referent-radio-oui').removeAttr('checked');
                    $('#referent-radio-non').attr('checked', 'checked');
                }
            } else {
                document.getElementById('enseignant-form').reset(); // reset le formulaire
            }
        },
        buttons: {
            'Enregistrer': function () {
                // ne rien faire puisque la validation se fait déjà par les attributs HTML
            },
            'Annuler': function () {
                // Fermer la boîte de dialogue
                $('#modal-enseignant-form').dialog('close');
            }
        },
        close: function () {
            $('#modal-enseignant-form').dialog('close');
        }
    });

    // Ouvrir la boîte de dialogue
    $('.open-enseignant-modal').on('click', function (e) {
        callbackClickButtonModal(e, '#modal-enseignant-form');
    });
    // END: Modal create Enseignants

    // BEGIN: Modal Salle
    $('#modal-salle-form').dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        open: function () {
            // Faire en sorte que le button généré par le modal soit le boutton de submit du formulaire
            $('div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:nth-child(1)').attr('type', 'submit');
            $('div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:nth-child(1)').attr('form', 'salle-form');
            // Modifier la trajectoire de l'action
            $('#salle-form').attr('action', $(this).data('url'));
            // si l'action est un edit, charger le modal form avec les données issues du tr contenant le boutton cliqué
            if ($(this).data('action') === 'edit') {
                const tr = $(this).data('tr');
                $('#nom-salle').val(tr.children()[1].innerText);
            } else {
                document.getElementById('salle-form').reset(); // reset le formulaire
            }
        },
        buttons: {
            'Enregistrer': function () {
                // ne rien faire puisque la validation se fait déjà par les attributs HTML
            },
            'Annuler': function () {
                // Fermer la boîte de dialogue
                $('#modal-salle-form').dialog('close');
            }
        },
        close: function () {
            $('#modal-salle-form').dialog('close');
        }
    });

    // Ouvrir la boîte de dialogue
    $('.open-salle-modal').on('click', function (e) {
        callbackClickButtonModal(e, '#modal-salle-form');
    });
    // END: Modal create Salle

    // BEGIN: Modal create edt
    // Choix du prof lorsqu'on choisi une matière
    $('#form-edt-matiere').on('change', function () {
        // faire une requete ajax pour obtenir les enseignants
        searchEnseignantByMatiere($(this).val());
    });
    $('#modal-edt-form').dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        width: 400,
        height: 500,
        open: function () {
            // reset le formulaire
            // Puis charger le formulaire avec les données du tr cliqué
            if ($(this).data('action') !== 'edit') {
                // reset le formulaire
                document.getElementById('edt-form').reset();
                // reset les checkbox du groupe
                $('.form-edt-groupe').prop('checked', false);
                // reset les checkbox du jour
                $('.form-edt-jour').prop('checked', false);
                // reset les checkbox du jour
                $('.form-edt-heure').prop('checked', false);
            }
            // Faire en sorte que le button généré par le modal soit le boutton de submit du formulaire
            $('div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:nth-child(1)')
                .attr('type', 'submit');
            $('div.ui-dialog-buttonpane.ui-widget-content.ui-helper-clearfix > div > button:nth-child(1)')
                .attr('form', 'edt-form');
            // Modifier la trajectoire de l'action
            $('#edt-form').attr('action', $(this).data('url'));
            // Checker le checkbox du groupe et le mettre en readonly
            let checkedGroup = '#form-edt-groupe-' + findGetParameter('groupe', $(this).data('url'));
            $(checkedGroup).prop('checked', true);
            // empecher l'utilisateur de décocher le checkbox sur le groupe où on a declencher l'action
            $('.form-edt-groupe').removeAttr('onclick');
            $(checkedGroup).attr('onclick', 'return false;');
            // set heure de début
            const hdeb = findGetParameter('heure', $(this).data('url'));
            $('#form-edt-hdebut').val(hdeb);
            $('#form-edt-hdebut').prop('readonly', true);
            // set heure de fin min = hdeb
            $('#form-edt-hfin').prop('min', hdeb);
            // set valeur heure de fin = valeur heure debut + 15mn
            const timeParts = hdeb.split(':'); // Split heure et minute
            const heure = parseInt(timeParts[0], 10);
            const minutes = parseInt(timeParts[1], 10);
            let dateFin = new Date();
            dateFin.setHours(heure);
            dateFin.setMinutes(minutes + 15);
            // Conversion de la date en un nouveau format de chaîne de temps
            const hdebPlus15 = dateFin.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            $('#form-edt-hfin').val(hdebPlus15);
            // set date
            const lundiSemaine = findGetParameter('semaine', $(this).data('url'));
            const jour = findGetParameter('jour', $(this).data('url')) - 1;
            let date = new Date(lundiSemaine);
            let day = date.getDate() + jour;
            date.setDate(day);
            // ajout de 0 devant le mois < 10 pour avoir un format correct
            let month = date.getMonth() + 1;
            month = month < 10 ? '0' + month : month;
            day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate();
            date = date.getFullYear() + '-' + month + '-' + day;
            $('#form-edt-date').val(date);
            $('#form-edt-date').prop('readonly', true);
            // vide le select des enseignants
            $('#form-edt-enseignant').empty();
            // si l'action est un edit, charger le modal form avec les données issues du tr contenant le boutton cliqué
            if ($(this).data('action') === 'edit') {
                let enseignant = $(this).data('enseignant');
                // faire une requete ajax pour obtenir les infos de l'edt selectionné
                $.ajax({
                    url: 'index.php?action=ajax&search=edt',
                    method: 'POST',
                    data: {
                        'heure': hdeb,
                        'jour': jour,
                        'groupe': findGetParameter('groupe', $(this).data('url')) - 1,
                        'semaine': lundiSemaine
                    },
                    success: function (response) {
                        const obj = JSON.parse(response);
                        const data = obj.data;
                        $('#form-edt-matiere').val(data.matiere);
                        $('#form-edt-type').val(data.type);
                        searchEnseignantByMatiere(data.matiere, enseignant); // recherche d'enseignant par matiere
                        $('#form-edt-salle').val(data.salle);
                        $('#form-edt-hdebut').val(data.hdebut);
                        $('#form-edt-hfin').val(data.hfin);
                        $('#form-edt-date').val(data.date);
                        // checker les checkbox des groupes
                        $.each(data.groupes, function (key, value) {
                            $('#form-edt-groupe-' + (value + 1)).prop('checked', true);
                        });
                    },
                    error: function (xhr, status, error) {
                        // TODO: afficher un message d'erreur
                    }
                });
            }
        },
        buttons: {
            'Enregistrer': function () {
                // ne rien faire puisque la validation se fait déjà par les attributs HTML
            },
            'Annuler': function () {
                // Fermer la boîte de dialogue
                $('#modal-edt-form').dialog('close');
            }
        },
        close: function () {
            $('#modal-edt-form').dialog('close');
        }
    });
    // Ouvrir la boîte de dialogue
    $('.open-edt-modal').on('click', function (e) {
        e.preventDefault();
        const action = $(this).hasClass('btn-edit') ? 'edit' : 'create';
        let enseignant = '';
        if (action === 'edit') {
            enseignant = $(e.currentTarget).parent().children('.edt-enseignant').text();
        }
        $('#modal-edt-form')
            .data('url', $(this).attr('href'))
            .data('action', action)
            .data('enseignant', enseignant)
            .dialog('open');
    });
    // END: Modal create edt

    const iddiv = document.getElementById("error");
    const idspan = document.getElementById("error-msg");

    if (iddiv) {
        iddiv.addEventListener("click", function () {
            idspan.style.display = "none";
        });
    }
});
