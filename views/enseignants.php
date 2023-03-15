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
                    <label for="referant">Refer&eacute;nt :</label>
                    <div>
                        <input type="radio" id="huey" name="referant" value="1">
                        <label for="huey">Oui</label>
                    </div>

                    <div>
                        <input type="radio" id="dewey" name="referant" value="0"checked>
                        <label for="dewey">Non</label>
                    </div>

                <!-- <div>
                    <input type="radio" id="louie" name="drone" value="louie">
                    <label for="louie">Louie</label>
                </div> -->
            </div>
            <!-- <div>
                <label for="referant">Mati&eacute;re :</label>
                <input type="text" name="referant" id="referant">
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
                echo "<tr>";
                echo '<td>'.$id .'</td>';
                echo "<td>" . $value['nom'] . "</td>";
                if ($value['referant'] == 1) {
                    echo "<td>Oui</td>";
                }else {
                    echo "<td>Non</td>";
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