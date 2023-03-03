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
                        echo '<a href="" class="">Modifier </a>';
                        echo '<a href="" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteProduit{{$produit->id}}"><i class="fa fa-trash"></i> Retirer </a>';
                        echo '</div>';
                    echo '</td>';
                echo '</tr>';
            }
        ?>
    </tbody>
</table>