<?php 
ob_start();
$thead = ["Horaires", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi"];
?>
Visualisation etudiant
<table>
    <thead>
        <tr>
            <?php
                foreach ($thead as $value) {
                    echo "<td>$value</td>";
                }
            ?>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>
<?php $content = ob_get_clean(); ?>
<?php require('template.php') ?>