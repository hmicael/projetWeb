<?php
    $tHead = array_keys($matieres[0]);
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
            <td>Actions</td>
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