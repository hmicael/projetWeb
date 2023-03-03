<?php
    $tHead = array_keys($salles[0]);
?>
<h1>Ajouter une salle  :</h1>
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
    <tbody>
        <?php
            foreach ($salles as $key => $salle) {
                $id = $key + 1;
                echo '<tr>';
                    echo "<td>$id</td>";
                    foreach ($salle as $value) {
                        echo '<td>' . ucfirst($value) . '</td>';
                    }
                    echo '<td>';
                        echo '<a href="index.php?action=admin&edit=salles&id=' . $id . '" class="btn-edit open-salle-modal>Modifier</a>';
                        echo '<a href="index.php?action=admin&delete=salles&id=' . $id . '" class="btn-delete">Supprimer</a>';
                    echo '</td>';
                echo '</tr>';
            }
        ?>
    </tbody>
</table>