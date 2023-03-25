<?php 
ob_start();
?>
Visualisation non etudiant
<a id="create-edt-button" href="index.php?action=visualiser&create=edt" class="btn btn-add open-edt-modal">+</a>
<!-- BEGIN: Modal -->
<section id="modal-edt-form" title="Ajout d'une plage" class="modal">
    <p class="error-message"></p>
    <form id="edt-form" action="" method="post">
        <fieldset>
            <div>
                <label for="form-edt-matiere">Mati&egrave;re: </label>
                <select id="form-edt-matiere" name="form-edt-matiere" required>
                    <?php
                        foreach ($matieres as $m) {
                            echo '<option value="' . $m['nom'] . '">' . $m['nom'] . '</option>';
                        }
                    ?>
                </select>
            </div>
            <div>
                <label for="form-edt-type">Type: </label>
                <select id="form-edt-type" name="form-edt-type" required>
                    <option value="Cours">Cours</option>
                    <option value="TD">TD</option>
                    <option value="TP">TP</option>
                    <option value="Soutenance">Soutenance</option>
                    <option value="Amphi">Amphi</option>
                </select>
            </div>
            <div>
                <label for="form-edt-enseignant">Enseignant: </label>
                <select id="form-edt-enseignant" name="form-edt-enseignant" required>
                    <?php
                        foreach ($enseignants as $e) {
                            echo '<option value="' . $e['nom'] . '">' . $e['nom'] . '</option>';
                        }
                    ?>
                </select>
            </div>
            <div>
                <label for="form-edt-salle">Salle: </label>
                <select id="form-edt-salle" name="form-edt-salle" required>
                    <?php
                        foreach ($salles as $s) {
                            echo '<option value="' . $s['nom'] . '">' . $s['nom'] . '</option>';
                        }
                    ?>
                </select>
            </div>
            <fieldset>
                <legend>Groupe concern&eacute;</legend>
                <div class="checkbox-group required">
                    <input type="checkbox" id="form-edt-groupe-1" name="form-edt-groupe[]">
                    <label for="form-edt-groupe-1">Groupe 1</label>
                    <input type="checkbox" id="form-edt-groupe-2" name="form-edt-groupe[]">
                    <label for="form-edt-groupe-2">Groupe 2</label>
                    <input type="checkbox" id="form-edt-groupe-3" name="form-edt-groupe[]">
                    <label for="form-edt-groupe-3">Groupe 3</label>
                    <input type="checkbox" id="form-edt-groupe-4" name="form-edt-groupe[]">
                    <label for="form-edt-groupe-4">Groupe 4</label>
                </div> 
            </fieldset>
            <div>
                <label for=form-edt-hdebut">Heure de d&eacute;but: </label>
                <input type="time" id="form-edt-hdebut" name="form-edt-hdebut" min="08:00" max="19:00" step="900" required>
            </div>
            <div>
                <label for=form-edt-hfin">Heure de fin: </label>
                <input type="time" id="form-edt-hfin" name="form-edt-hfin" min="08:00" max="19:00" step="900" required>
            </div>
            <div>
                <label for=form-edt-date">Date: </label>
                <input type="date" id="form-edt-date" name="form-edt-date" min="<?=date('d-m-y') ?>" required>
            </div>
        </fieldset>
    </form>
</section>
<!-- END: Modal -->
<table>
    <thead>
        <tr>
            <td>Horaires</td>
            <?php
                foreach (["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi"] as $value) {
                    echo "<td colspan=\"4\">$value</td>";
                }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($heure = $heureDebut; $heure <= $heureFin; $heure += 900) {
            echo '<tr>';
                echo '<td>' . date('H:i', $heure) . '</td>';
                for ($i=0; $i < 5; $i++) { 
                    for ($j=0; $j < 4; $j++) {
                        echo '<td>' . $j . '</td>';
                    }
                }
            echo '</tr>';
        }
        ?>
    </tbody>
</table>
<?php $content = ob_get_clean(); ?>
<?php require('template.php') ?>