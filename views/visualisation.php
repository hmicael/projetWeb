<?php 
ob_start();
?>
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
                <input type="time" id="form-edt-hfin" name="form-edt-hfin" max="19:00" step="900" required>
            </div>
            <div>
                <label for=form-edt-date">Date: </label>
                <input type="date" id="form-edt-date" name="form-edt-date" readonly required>
            </div>
        </fieldset>
    </form>
</section>
<!-- END: Modal -->
<!-- BEGIN: table -->
<table>
    <thead>
        <tr>
            <th scope="col">Horaires</td>
            <?php
                foreach (["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi"] as $key => $value) {
                    $date = date('d-m-Y', strtotime($lundiDeLaSemaine. ' + ' . $key . ' days'));
                    echo "<th scope=\"col\" colspan=\"4\">$value<br>$date</th>";
                }
            ?>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td></td>
            <?php
            for ($jour=0; $jour < 5; $jour++) { // boucle jour lundi à vendredi
                for ($groupe=0; $groupe < 4; $groupe++) { // boucle groupe 1 à 4
                    echo '<td>Groupe ' . ($groupe+1) . '</td>';
                }
            }
            ?>
        </tr>
        <?php
        // un tableau pour enregistrer les sauts de ligne à faire s'il y a eu des fusions de ligne en haut
        $sautsLigne = [];
        $sautsColonne = [];
        for ($heure = $heureDebut; $heure <= $heureFin; $heure += 900) { // boucle horaire 900s = 15mn
            $hDeb = date('H:i', $heure);
            echo '<tr>';
                echo "<td>$hDeb</td>";
                for ($jour=0; $jour < 5; $jour++) { // boucle jour lundi à vendredi
                    for ($groupe=0; $groupe < 4; $groupe++) { // boucle groupe 1 à 4
                        // si le bloc appartient à la liste de colonne ou de ligne à sauter à cause d'une fusion
                        if (! isset($sautsLigne[$hDeb][$jour][$groupe]) && ! isset($sautsColonne[$hDeb][$jour][$groupe])) {
                            // s'il y a un contenu et que c'est le bon groupe
                            if(isset($edt[$hDeb][$jour][$groupe]) &&
                                in_array($groupe, $edt[$hDeb][$jour][$groupe]['groupes'])) {
                                // calcul de la fusion de ligne: (hfin - hdebut) / 15mn
                                $slotHFin = strtotime($edt[$hDeb][$jour][$groupe]['hfin']);
                                $slotHDeb = strtotime($edt[$hDeb][$jour][$groupe]['hdebut']);
                                $rowspan = ($slotHFin - $slotHDeb) / 900;
                                $colspan = 1;
                                // calcul fusion de colonne
                                $slotGroupes = $edt[$hDeb][$jour][$groupe]['groupes'];
                                // on va tester sy le groupes dans le slot appartient à la 
                                // liste d'arrangement possible pour un fusion de colonne
                                // si array_diff renvoi un array vide, ça veut dire que 
                                // les éléments du tableau 1 se trouve dans le tableau 2
                                if(! array_diff([0, 1, 2, 3], $slotGroupes) && $groupe == $slotGroupes[0]) {
                                    $colspan = 4;
                                // mila sarahana 2 ref misy decalage
                                // } else if(! array_diff([0, 2, 3], $slotGroupes)) {
                                //     $colspan = 2;
                                // } else if(! array_diff([0, 1, 3], $slotGroupes) && $groupe == $slotGroupes[0]) {
                                //     $colspan = 2;
                                } else if(! array_diff([1, 2, 3], $slotGroupes) && $groupe == $slotGroupes[0] ||
                                    ! array_diff([0, 1, 2], $slotGroupes) && $groupe == $slotGroupes[0]) {
                                    $colspan = 3;
                                } else if(! array_diff([0, 1], $slotGroupes) && $groupe == $slotGroupes[0] ||
                                    ! array_diff([2, 3], $slotGroupes) && $groupe == $slotGroupes[0] || 
                                    ! array_diff([1, 2], $slotGroupes) && $groupe == $slotGroupes[0]) {
                                    $colspan = 2;
                                }
                                // s'il n'y a pas ligne à fusionner mettre rowspan à 1 pour éviter le rowspan=0
                                if ($rowspan > 0) {
                                    for ($i=$slotHDeb+900; $i < $slotHFin; $i+=900) {
                                        // ajout des slots à sauter à cause de la fusion
                                        $sautsLigne[date('H:i', $i)][$jour][$groupe] = true;
                                        if ($colspan > 1) {
                                            for ($j=$groupe; $j < ($groupe + $colspan); $j++) { 
                                                $sautsColonne[date('H:i', $i)][$jour][$j] = true;
                                            }
                                        }
                                    }
                                    if ($colspan > 1) {
                                        echo '<td style="background-color:red" rowspan="' . $rowspan . '" colspan="' . $colspan . '">';
                                    } else {
                                        echo '<td style="background-color:red" rowspan="' . $rowspan . '">';
                                    }
                                } else {
                                    if ($colspan > 1) {
                                        echo '<td colspan="' . $colspan . '">';
                                    } else {
                                        echo '<td>';
                                    }
                                }
                                // affichage du contenu du slot
                                echo $edt[$hDeb][$jour][$groupe]['matiere'];
                                echo '<a href="index.php?action=delete-edt&heure='.
                                    $hDeb .'&jour=' . ($jour+1) . '&semaine=' . $lundiDeLaSemaine .
                                    '&groupe=' . ($groupe+1) . '" class="btn btn-delete">-</a>';
                                echo '</td>';
                                if ($colspan > 1) {
                                    // si colspan > 1 on va decaler la position de groupe pour ne pas mettre un td en exces
                                    $groupe += ($colspan - 1);
                                }                   
                            } else {
                                // si le slot est vide
                                if ($_SESSION['role'] == 'etudiant') {
                                    echo '<td></td>';
                                } else {
                                    echo '<td>';
                                        echo '<a href="index.php?action=add-edt&heure='.
                                        $hDeb .'&jour=' . ($jour+1) . '&semaine=' . $lundiDeLaSemaine .
                                        '&groupe=' . ($groupe+1) . '" class="btn btn-add open-edt-modal">+</a>';
                                    echo '</td>';
                                }
                            }
                        }
                    }
                }
            echo '</tr>';
        }
        ?>
    </tbody>
</table>
<!-- END: table -->
<?php $content = ob_get_clean(); ?>
<?php require('template.php') ?>