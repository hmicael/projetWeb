<?php
    $tHead = array_keys($utilisateurs[0]);
?>
<h1>Liste des utilisateurs :</h1>
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
            foreach ($utilisateurs as $key => $utilisateur) {
                $id = $key + 1;
                echo '<tr>';
                    echo "<td>$id</td>";
                    foreach ($utilisateur as $value) {
                        echo '<td>' . ucfirst($value) . '</td>';
                    }
                    echo '<td>';
        ?>
                    <a href="index.php?action=admin">Modifier</a>
                    <?php
                    if ($_SESSION['id'] != $key) {
                        echo '<a href="index.php?action=admin&delete=utilisateurs&id=' . $key . '">Supprimer</a>';
                    }
                    ?>
        <?php
                    echo '</td>';
                echo '</tr>';
            }
        ?>
    </tbody>
</table>