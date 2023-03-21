<h1>Ajouter une salle  :</h1>
<!-- BEGIN: Modal -->
<a id="create-salle-button" href="index.php?action=admin&create=salles" class="btn-add open-salle-modal">+</a>

<section id="modal-salle-form" title="Enregister un utilisateur" class="modal">
    <p class="error-message"></p>
    <form id="salle-form" action="" method="post">
        <fieldset>
            <div>
                <label for="nom-salle">Nom :</label>
                <input type="text" name="nom" id="nom-salle" autofocus>
            </div>
        </fieldset>
    </form>
</section>
<!-- END: Modal -->

<section>
    <table>
        <thead>
            <tr>
                <td>#</td>
                <td>Nom</td>
                <td>Actions</td>
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
                        echo '<td>';
                            echo '<a href="index.php?action=admin&edit=salles&id=' . $id . '" class="btn btn-edit open-salle-modal">Modifier</a>';
                            echo '<a href="index.php?action=admin&delete=salles&id=' . $id . '" class="btn btn-delete">Supprimer</a>';
                        echo '</td>';
                    echo '</tr>';
                }
            ?>
        </tbody>
    </table>
</section>