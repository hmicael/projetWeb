<h1>Liste des matieres :</h1>
<div class="action">
    <a id="create-matiere-button" href="index.php?action=admin&create=matieres" class="btn btn-add open-matiere-modal">+</a>
</div>
<!-- BEGIN: Modal -->
<section id="modal-matiere-form" title="Enregister une matière" class="modal">
    <form id="matiere-form" action="" method="post">
        <fieldset>
            <div>
                <label for="nom-matiere">Nom :</label>
                <input type="text" name="nom" id="nom-matiere" required autofocus>
            </div>
            <div>
                <label for="referent-mat">Ref&eacute;rent :</label>
                <select id="referent-mat" name="referent" required>
                    <?php
                    foreach ($enseignants as $e) {
                        if($e['referent'] == 'Oui')
                            echo '<option value="' . $e['nom'] . '">' . $e['nom'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div>
                <label for="couleur">Code couleur :</label>
                <input type="color" name="couleur" id="couleur" value="#FFFFFF" required>
            </div>
        </fieldset>
    </form>
</section>
<!-- END: Modal -->

<table>
    <thead>
        <tr>
            <th scope="col">#</th>
            <?php
                foreach (['Nom', 'Référent', 'Couleur', 'Actions'] as $value) {
                    echo '<th scope="col">' . ucfirst($value) . '</th>';
                }
            ?>
        </tr>
    </thead>
    <tbody id="tbody-matiere">
        <?php
        foreach ($matieres as $key => $matiere) {
            $id = $key + 1;
            echo '<tr>';
                echo "<td>$id</td>";
                foreach ($matiere as $key => $value) {
                    if ($key == 'couleur') {
                        echo '<td style="background-color:' . $value . '"></td>';
                    } else {
                        echo '<td>' . ucfirst($value) . '</td>';
                    }
                }
                echo '<td class="action-buttons">';
                    echo '<a href="index.php?action=admin&edit=matieres&id=' . $id . '" class="btn btn-edit open-matiere-modal">Modifier</a>';
                    echo '<a href="index.php?action=admin&delete=matieres&id=' . $id . '" class="btn btn-delete">Supprimer</a>';
                echo '</td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>