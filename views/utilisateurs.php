<?php
    $tHead = array_keys($utilisateurs[0]);
?>
<h1>Liste des utilisateurs :</h1>
<!-- BEGIN: Modal -->
<a id="create-user-button" href="index.php?action=admin&create=utilisateurs" class="btn-add open-user-modal">+</a>

<section id="modal-user-form" title="Enregister un utilisateur" class="modal">
    <p class="error-message"></p>
    <form id="create-user-form">
        <fieldset>
            <div>
                <label for="nom">Nom :</label>
                <input type="text" name="nom" id="nom" autofocus>
            </div>
            <div>
                <label for="prenom">Pr&eacute;nom :</label>
                <input type="text" name="prenom" id="prenom">
            </div>
            <div>
                <label for="email">Email :</label>
                <input type="email" name="email" id="email">
            </div>
            <div>
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password">
            </div>
            <div>
                <label for="confirm-password">Confirmez le mot de passe :</label>
                <input type="password" name="confirm-password" id="confirm-password">
            </div>
            <div>
                <label for="role">Role :</label>
                <select name="role" id="role">
                    <option value="Etudiant">Etudiant</option>
                    <option value="Coordinateur">Coordinateur</option>
                    <option value="Responsable">Responsable</option>
                </select>
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
                <?php
                    foreach ($tHead as $value) {
                        if ($value != 'password')
                            echo '<td>' . ucfirst($value) . '</td>';
                    }
                ?>
                <td>Actions</td>
            </tr>
        </thead>
        <tbody id="tbody-utilisateur">
            <?php
            foreach ($utilisateurs as $key => $utilisateur) {
                $id = $key + 1;
                echo '<tr>';
                    echo "<td>$id</td>";
                    foreach ($utilisateur as $key2 => $value) {
                        if ($key2 != 'password') { // ne pas afficher le mdp
                            echo '<td>';
                            if ($key2 == 'email') {
                                echo '<a href="mailto:' . $value . '">' . $value . '</a>';
                            } else {
                                echo $value;
                            }
                            echo '</td>';
                        }
                    }
                    echo '<td>';
                    echo '<a href="index.php?action=admin&edit=utilisateurs&id=' . $id . '" class="btn-edit open-user-modal">Modifier</a>';
                    if ($value != 'responsable') {
                        echo '<a href="index.php?action=admin&delete=utilisateurs&id=' . $id . '#tabs-1" class="btn-delete">Supprimer</a>';
                    }
                    echo '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</section>