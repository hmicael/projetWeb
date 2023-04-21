<h1>Liste des utilisateurs :</h1>
<div class="action">
    <a id="create-user-button" href="index.php?action=admin&create=utilisateurs" class="btn-add open-user-modal"><i
                class="fa-solid fa-plus"></i></a>
</div>
<!-- BEGIN: Modal -->
<section id="modal-user-form" title="Enregister un utilisateur" class="modal">
    <form id="user-form" action="" method="post">
        <fieldset>
            <div>
                <label for="nom-user">Nom :</label>
                <input type="text" name="nom" id="nom-user" required autofocus>
            </div>
            <div>
                <label for="prenom">Pr&eacute;nom :</label>
                <input type="text" name="prenom" id="prenom" required>
            </div>
            <div>
                <label for="email">Email :</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div>
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                       title="Doit contenir au moins un chiffre et une lettre majuscule et minuscule, et au moins 8 caractères."
                       required>
            </div>
            <div>
                <label for="confirm-password">Confirmez le mot de passe :</label><br>
                <span id="password-not-match" style="color:red;font-size:small;display:none">Les mots de passe ne se correspondent pas</span>
                <input type="password" name="confirm-password" id="confirm-password" required>
            </div>
            <div>
                <label for="role">Role :</label>
                <select name="role" id="role" required>
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
            <th scope="col">#</th>
            <?php
            foreach (['Nom', 'Prénom(s)', 'Email', 'Role', 'Actions'] as $value) {
                if ($value != 'password')
                    echo '<th scope="col">' . ucfirst($value) . '</th>';
            }
            ?>
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
                    if ($key2 == 'email') { // afficher l'email comme un lien
                        echo '<a href="mailto:' . $value . '">' . $value . '</a>';
                    } else { // afficher les autres valeurs
                        echo $value;
                    }
                    echo '</td>';
                }
            }
            echo '<td class="action-buttons">';
            // un responsable ne peut pas modifier les informations d'un autre responsable sauf si c'est lui
            if (strtolower($_SESSION['role']) == 'responsable' && strtolower($utilisateur['role']) != 'responsable' ||
                strtolower($_SESSION['role']) == 'responsable' && strtolower($utilisateur['role']) == 'responsable' && $utilisateur['email'] == $_SESSION['email']) {

                echo '<a href="index.php?action=admin&edit=utilisateurs&id=' . $id . '" class="btn-edit open-user-modal">Modifier <i class="fa-solid fa-pen-to-square"></i></a>';
            }
            if (strtolower($utilisateur['role']) != 'responsable') { // ne pas supprimer le responsable
                echo '<a href="index.php?action=admin&delete=utilisateurs&id=' . $id . '" class="btn-delete">Supprimer <i class="fa-solid fa-trash"></i></a>';
            }
            echo '</td>';
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>
</section>