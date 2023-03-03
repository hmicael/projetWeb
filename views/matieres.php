<?php
    $tHead = array_keys($matieres[0]);
?>
<h1>Liste des matieres :</h1>
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
            foreach ($matieres as $key => $matiere) {
                $id = $key + 1;
                echo '<tr>';
                    echo "<td>$id</td>";
                    foreach ($matiere as $value) {
                        echo '<td>' . ucfirst($value) . '</td>';
                    }
                    echo '<td>';
                        echo '<a href="index.php?action=admin">Modifier</a>';
                        echo '<a href="index.php?action=admin&delete=matieres&id=' . $key . '">Supprimer</a>';
                    echo '</td>';
                echo '</tr>';
            }
        ?>
    </tbody>
</table>