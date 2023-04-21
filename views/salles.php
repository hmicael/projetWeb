<h1>Ajouter une salle  :</h1>
<!-- BEGIN: Modal -->
<div class="action">
    <a id="create-salle-button" href="index.php?action=admin&create=salles" class="btn-add open-salle-modal"><i class="fa-solid fa-plus"></i></a>
</div>
<section id="modal-salle-form" title="Enregister un utilisateur" class="modal">
    <form id="salle-form" action="" method="post">
        <fieldset>
            <div>
                <label for="nom-salle">Nom :</label>
                <input type="text" name="nom" id="nom-salle" required autofocus>
            </div>
        </fieldset>
    </form>
</section>
<!-- END: Modal -->

<section>
    <table>
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nom</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody id="tbody-salle">
            <?php
                foreach ($salles as $key => $salle) {
                    $id = $key + 1;
                    echo '<tr>';
                        echo "<td>$id</td>";
                        foreach ($salle as $value) {
                            echo '<td>' . ucfirst($value) . '</td>';
                        }
                        echo '<td class="action-buttons">';
                            echo '<a href="index.php?action=admin&edit=salles&id=' . $id . '" class="btn-edit open-salle-modal">Modifier <i class="fa-solid fa-pen-to-square"></i></a>';
                            echo '<a href="index.php?action=admin&delete=salles&id=' . $id . '" class="btn-delete">Supprimer <i class="fa-solid fa-trash"></i></a>';
                        echo '</td>';
                    echo '</tr>';
                }
            ?>
        </tbody>
    </table>
</section>