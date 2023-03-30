<?php 
ob_start();
if (isset($_SESSION['error-msg'])) {
    echo '<span class="error-msg">' . $_SESSION['error-msg'] . '</span>';
    unset($_SESSION['error-msg']);
}
// Navigation semaine
echo '<nav class="semaine-nav">';
    echo '<p>';
        echo '<a href="index.php?action=visualiser&semaine=' . date('Y-m-d', strtotime($lundiDeLaSemaine . ' - 7 days')) .'">';
                echo '<i class="fa-solid fa-chevron-left" style="color: #63003c;"></i>';
            echo '</a>';
        echo '<span>
                Semaine du 
                <time datetime="' . date('d-m-Y', strtotime($lundiDeLaSemaine)) . '">' . 
                    date('d-m-Y', strtotime($lundiDeLaSemaine)) .
                '</time> au <time>' . 
                    date('d-m-Y', strtotime($lundiDeLaSemaine . ' + 4 days')) . 
                '</time>
            </span>';
        echo '<a href="index.php?action=visualiser&semaine=' . date('Y-m-d', strtotime($lundiDeLaSemaine . ' + 7 days')) .'">
                <i class="fa-solid fa-chevron-right" style="color: #63003c;"></i>
            </a>';
    echo '</p>';
echo '</nav>';
?>
<!-- BEGIN: Modal -->
<section id="modal-edt-form" title="Ajout d'une plage" class="modal">
    <form id="edt-form" action="" method="post">
        <fieldset>
            <div>
                <label for="form-edt-matiere">Mati&egrave;re: </label>
                <select id="form-edt-matiere" name="form-edt-matiere" required>
                    <option disabled selected value> -- Choisissez une mati&egrave;re -- </option>
                    <?php
                        // pour ne pas faciliter l'obtention du code couleur de la matière
                        // on va directement mettre sa couleur dans value et splitter le traitement
                        foreach ($matieres as $m) {
                            echo '<option style="background-color:' . $m['couleur'] . '" value="' . $m['nom'] . ';' . $m['couleur'] .'">' . $m['nom'] . '</option>';
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
                <select id="form-edt-enseignant" name="form-edt-enseignant" required></select>
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
                <div class="checkbox-group">
                    <input type="checkbox" id="form-edt-groupe-1" name="form-edt-groupe[0]" class="form-edt-groupe">
                    <label for="form-edt-groupe-1">Groupe 1</label>
                    <input type="checkbox" id="form-edt-groupe-2" name="form-edt-groupe[1]" class="form-edt-groupe">
                    <label for="form-edt-groupe-2">Groupe 2</label><br>
                    <input type="checkbox" id="form-edt-groupe-3" name="form-edt-groupe[2]" class="form-edt-groupe">
                    <label for="form-edt-groupe-3">Groupe 3</label>
                    <input type="checkbox" id="form-edt-groupe-4" name="form-edt-groupe[3]" class="form-edt-groupe">
                    <label for="form-edt-groupe-4">Groupe 4</label>
                </div> 
            </fieldset>
            <div>
                <label for="form-edt-hdebut">Heure de d&eacute;but: </label>
                <input type="time" id="form-edt-hdebut" name="form-edt-hdebut" min="08:00" max="18:45" step="900" required>
            </div>
            <div>
                <label for="form-edt-hfin">Heure de fin: </label>
                <input type="time" id="form-edt-hfin" name="form-edt-hfin" max="19:00" step="900" required>
            </div>
            <div>
                <label for="form-edt-date">Date: </label>
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
                foreach (['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'] as $key => $value) {
                    $date = date('d-m-Y', strtotime($lundiDeLaSemaine. ' + ' . $key . ' days'));
                    echo "<th scope=\"col\" colspan=\"4\">$value<br><time datetime=\"$date\">$date</time></th>";
                }
            ?>
        </tr>
    </thead>
    <tbody id="visualisation">
        <tr>
            <td>-</td>
            <?php
            for ($jour=0; $jour < 5; $jour++) { // boucle jour lundi à vendredi
                for ($groupe=0; $groupe < 4; $groupe++) { // boucle groupe 1 à 4
                    echo '<td>Groupe ' . ($groupe+1) . '</td>';
                }
            }
            ?>
        </tr>
        <?php
        for ($heure = $heureDebut; $heure < $heureFin; $heure += 900) { // boucle horaire 900s = 15mn
            $hDeb = date('H:i', $heure);
            echo '<tr>';
                echo "<td><time>$hDeb</time></td>";
                for ($jour=0; $jour < 5; $jour++) { // boucle jour lundi à vendredi
                    for ($groupe=0; $groupe < 4; $groupe++) { // boucle groupe 1 à 4
                        // si le bloc appartient à la liste de colonne ou de ligne à sauter à cause d'une fusion
                        if (! isset($edt[$hDeb][$jour][$groupe]['fusion'])  ) {
                            // vérifie s'il y a un contenu
                            if(isset($edt[$hDeb][$jour][$groupe]) ) {
                                // splitter le matiere puisque c'est un string nom;couleur
                                $matiere = explode(';', $edt[$hDeb][$jour][$groupe]['matiere']);
                                // calcul de la fusion de ligne: (hfin - hdebut) / 15mn
                                $slotHFin = strtotime($edt[$hDeb][$jour][$groupe]['hfin']);
                                $slotHDeb = strtotime($edt[$hDeb][$jour][$groupe]['hdebut']);
                                $rowspan = ($slotHFin - $slotHDeb) / 900;
                                // calcul fusion de colonne
                                $colspan = count($edt[$hDeb][$jour][$groupe]['groupes']);
                                // s'il n'y a pas de lignes à fusionner mettre rowspan à 1 pour éviter le rowspan=0
                                echo '<td style="background-color:'  . $matiere[1] . '" rowspan="' . $rowspan . '" colspan="' . $colspan . '">';       
                                    // affichage du contenu du slot
                                    echo $edt[$hDeb][$jour][$groupe]['type'] . '<br><br>';
                                    echo $matiere[0] . '<br>';
                                    echo $edt[$hDeb][$jour][$groupe]['enseignant'] . '<br>';
                                    echo $edt[$hDeb][$jour][$groupe]['salle'] . '<br>';
                                    echo $edt[$hDeb][$jour][$groupe]['hdebut'] . ' à ' .  $edt[$hDeb][$jour][$groupe]['hfin'] . '<br>';
                                    echo '<br>';
                                    // boutton edit
                                    echo '<a href="index.php?action=edt-edit&heure='.
                                        $hDeb .'&jour=' . ($jour+1) . '&semaine=' . $lundiDeLaSemaine .
                                        '&groupe=' . ($groupe+1) . '" class="btn btn-edit open-edt-modal"><i class="fa-solid fa-pen-to-square"></i></a>';
                                        
                                    // bouton delete
                                    echo '<a href="index.php?action=edt-delete&heure='.
                                        $hDeb .'&jour=' . ($jour+1) . '&semaine=' . $lundiDeLaSemaine .
                                        '&groupe=' . ($groupe+1) . '" class="btn btn-delete delete-modal"><i class="fa-solid fa-trash"></a>';
                                echo '</td>';                 
                            } else {
                                // si le slot est vide
                                if ($_SESSION['role'] == 'etudiant') {
                                    // si l'utilisateur est un étudiant
                                    echo '<td></td>';
                                } else {
                                    echo '<td>';
                                        echo '<a href="index.php?action=edt-add&heure='.
                                        $hDeb .'&jour=' . ($jour+1) . '&semaine=' . $lundiDeLaSemaine .
                                        '&groupe=' . ($groupe+1) . '" class="btn btn-add open-edt-modal"><i class="fa-solid fa-plus"></i></a>';
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
<?php 
// Navigation semaine
echo '<nav class="semaine-nav">';
    echo '<p>';
        echo '<a href="index.php?action=visualiser&semaine=' . date('Y-m-d', strtotime($lundiDeLaSemaine . ' - 7 days')) .'">';
                echo '<i class="fa-solid fa-chevron-left" style="color: #63003c;"></i>';
            echo '</a>';
        echo '<span>
                Semaine du 
                <time datetime="' . date('d-m-Y', strtotime($lundiDeLaSemaine)) . '">' . 
                    date('d-m-Y', strtotime($lundiDeLaSemaine)) .
                '</time> au <time>' . 
                    date('d-m-Y', strtotime($lundiDeLaSemaine . ' + 4 days')) . 
                '</time>
            </span>';
        echo '<a href="index.php?action=visualiser&semaine=' . date('Y-m-d', strtotime($lundiDeLaSemaine . ' + 7 days')) .'">
                <i class="fa-solid fa-chevron-right" style="color: #63003c;"></i>
            </a>';
    echo '</p>';
echo '</nav>';
$content = ob_get_clean();
require('template.php') 
?>
