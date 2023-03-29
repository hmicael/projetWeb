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
<section id="modal-enseignant-form" title="Enregister un enseignant" class="modal">
    <form id="enseignant-form" action="" method="post">
        <fieldset>
            <div>
                <label for="nom-enseignant">Nom :</label>
                <input type="text" name="nom" id="nom-enseignant" required autofocus>
            </div>
            <fieldset>
                <legend>Refer&eacute;nt :</legend>
                <div>
                    <input type="radio" id="radio-oui" name="referent" value="Oui">
                    <label for="radio-oui">Oui</label>
                </div>
                <div>
                    <input type="radio" id="radio-non" name="referent" value="Non" checked>
                    <label for="radio-non">Non</label>
                </div>
            </fieldset>
        </fieldset>
    </form>
</section>
<!-- END: Modal -->
<section>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <?php
                    foreach (['Nom', 'Référant', 'Actions'] as $value) {
                        echo '<th scope="col">' . $value . '</th>';
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
                    echo '<td>' . $value['referent'] . '</td>';
                    echo '<td class="action-buttons">';
                        echo '<a href="index.php?action=admin&edit=enseignants&id=' . $id . '" class="btn btn-edit open-enseignant-modal">Modifier</a>';
                        echo '<a href="index.php?action=admin&delete=enseignants&id=' . $id . '" class="btn btn-delete">Supprimer</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
                <div class="action">
                    <a id="create-enseignant-button" href="index.php?action=admin&create=enseignants" class="btn-add open-enseignant-modal">+</a>      
                </div>
            </tbody>
        </table>
    </div>
</section>
</body>
</html>