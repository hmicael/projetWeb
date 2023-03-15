<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h1>Liste des Enseignants :</h1>
<!-- BEGIN: Modal -->
<!-- Le modal d'affichage de l'ajout et de modification d'un enseignant -->
<a id="create-enseignant-button" href="index.php?action=admin&create=enseignants" class="btn-add open-enseignant-modal">+</a>

<section id="modal-enseignant-form" title="Enregister un enseignant" class="modal">
    <p class="error-message"></p>
    <form id="create-enseignant-form">
        <fieldset>
            <div>
                <label for="nom-enseignant">Nom :</label>
                <input type="text" name="nom" id="nom-enseignant" autofocus>
            </div>
            <fieldset>
                <legend>Refer&eacute;nt :</legend>
                <div>
                    <input type="radio" id="radio-oui" name="referant" value="Oui">
                    <label for="radio-oui">Oui</label>
                </div>
                <div>
                    <input type="radio" id="radio-non" name="referant" value="Non" checked>
                    <label for="radio-non">Non</label>
                </div>
            </fieldset>
        </fieldset>
    </form>
</section>
<!-- END: Modal -->
<section>
    <table>
        <thead>
            <tr>
                <th scope="col">#</th>
                <?php  $entete = ['Nom', 'Référant', 'Actions'];
                foreach ($entete as $value) {
                    echo '<th>' . $value . '</th>';
                }?>
                
            </tr>
        </thead>
        <tbody id="tbody-enseignant">
            <?php
            foreach ($enseignants as $key => $value) {
                $id = $key +1;
                echo '<tr>';
                echo '<td>'.$id .'</td>';
                echo '<td>' . $value['nom'] . '</td>';
                echo '<td>' . $value['referant'] . '</td>';
                echo '<td>';
                    echo '<a href="index.php?action=admin&edit=enseignants&id=' . $id . '#tabs-3" class="btn btn-edit open-enseignant-modal">Modifier</a>';
                    echo '<a href="index.php?action=admin&delete=enseignants&id=' . $id . '#tabs-3" class="btn btn-delete">Supprimer</a>';
                echo '</td>';
                echo '</tr>';
            }
            ?>
            
        </tbody>
    </table>
</section>
</body>
</html>