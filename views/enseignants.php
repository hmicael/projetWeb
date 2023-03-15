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
                <label for="nom_e">Nom :</label>
                <input type="text" name="nom" id="nom_e" autofocus>
            </div>
            <div>
                <label for="matiere">Refer&eacute;nt :</label>
                    <div>
                    <input type="radio" id="huey" name="drone" value="huey">
                    <label for="huey">Oui</label>
                </div>

                <div>
                    <input type="radio" id="dewey" name="drone" value="dewey"checked>
                    <label for="dewey">Non</label>
                </div>

                <!-- <div>
                    <input type="radio" id="louie" name="drone" value="louie">
                    <label for="louie">Louie</label>
                </div> -->
            </div>
            <!-- <div>
                <label for="matiere">Mati&eacute;re :</label>
                <input type="text" name="matiere" id="matiere">
            </div> -->
        </fieldset>
    </form>
</section>
<!-- END: Modal -->
<section>
    <table>
        <thead>
            <tr>
                <th scope="col">#</th>
                <?php  $entete = $enseignants[0];
                foreach ($entete as $key => $value) {
                    echo '<th>'.$key.'</th>';
                }?>
                <td>Actions</td>
            </tr>
        </thead>
        <tbody id="tbody-enseignant">
            <?php
            foreach ($enseignants as $key => $value) {
                $id = $key +1;
                echo "<tr>";
                echo '<td>'.$id .'</td>';
                echo "<td>" . $value['nom'] . "</td>";
                if ($value['matiere']) {
                    echo "<td><select>";
                    foreach ($value['matiere'] as $key => $v) {
                        echo "<option>". $v . "</option>";
                        }
                    echo "</select></td>";
                }
                echo '<td>';
                    echo '<a href="index.php?action=admin&edit=enseignants&id=' . $id . '" class="btn btn-edit open-enseignant-modal">Modifier</a>';
                    echo '<a href="index.php?action=admin&delete=enseignants&id=' . $id . '#tabs-3" class="btn btn-delete">Supprimer</a>';
                echo '</td>';
                echo "</tr>";
            }
            ?>
            
        </tbody>
    </table>
</section>
</body>
</html>