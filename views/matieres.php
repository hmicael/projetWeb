<?php
    $tHead = ['Nom', 'Référant', 'Actions'];
?>
<h1>Liste des matieres :</h1>

<a id="create-matiere-button" href="index.php?action=admin&create=matieres" class="btn btn-add open-matiere-modal">+</a>
<!-- BEGIN: Modal -->
<section id="modal-matiere-form" title="Enregister une matière" class="modal">
    <p class="error-message"></p>
    <form id="create-matiere-form">
        <fieldset>
            <div>
                <label for="nom-matiere">Nom :</label>
                <input type="text" name="nom" id="nom-matiere" autofocus>
            </div>
            <div>
                <label for="referant_mat">Refer&eacute;nt :</label>
                <select id="referant_mat">
                    <?php
                    foreach ($enseignants as $e) {
                        if($e['referant'] == 'Oui')
                            echo '<option value="' . $e['nom'] . '">' . $e['nom'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </fieldset>
    </form>
</section>
<!-- END: Modal -->

<table>
    <thead>
        <tr>
            <td>#</td>
            <?php
                foreach ($tHead as $value) {
                    echo '<td>' . ucfirst($value) . '</td>';
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
                foreach ($matiere as $value) {
                    echo '<td>' . ucfirst($value) . '</td>';
                }
                echo '<td>';
                    echo '<a href="index.php?action=admin&edit=matieres&id=' . $id . '" class="btn btn-edit open-matiere-modal">Modifier</a>';
                    echo '<a href="index.php?action=admin&delete=matieres&id=' . $id . '#tabs-2" class="btn btn-delete">Supprimer</a>';
                echo '</td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>