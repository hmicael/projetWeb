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
                    <input type="radio" id="radio-oui" name="referant" value="Oui">
                    <label for="radio-oui">Oui</label>
                </div>
                <div>
                    <input type="radio" id="radio-non" name="referant" value="Non" checked>
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
                    echo '<td>' . $value['referant'] . '</td>';
                    echo '<td class="action-buttons">';
                        echo '<a href="index.php?action=admin&edit=enseignants&id=' . $id . '" class="btn btn-edit open-enseignant-modal">Modifier <i class="fa-solid fa-pen-to-square"></i></a>';
                        echo '<a href="index.php?action=admin&delete=enseignants&id=' . $id . '" class="btn btn-delete">Supprimer <i class="fa-solid fa-trash"></i></a>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
                <div class="action">
                    <a id="create-enseignant-button" href="index.php?action=admin&create=enseignants" class="btn-add open-enseignant-modal"><i class="fa-solid fa-plus"></i></a>      
                </div>
            </tbody>
        </table>
    </div>
</section>