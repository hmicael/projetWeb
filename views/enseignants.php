<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h1>Liste des Enseignants :</h1>
<table>
<thead>
    <tr>
        <th scope="col">#</th>
        <?php  $entete = $enseignants[0];
        foreach ($entete as $key => $value) {
            echo '<th>'.$key.'</th>';
        }?>
        <td>Actions</td>
    </tr>
</thead>
<tbody>
    <?php 
    
    foreach ($enseignants as $key => $value) {
        $id = $key +1;
        echo "<tr>";
        echo '<td>'.$id .'</td>';
        echo "<td>" . $value['nom'] . "</td>";
        if ($value['matiere']) {
            echo "<td><select>";
            foreach ($value['matiere'] as $key => $v) {
                echo "<option>". $v . "</option>";
                }
            echo "</select></td>";
        }
        echo '<td>';
            echo '<a href="index.php?action=admin&edit=enseignants&id=' . $id . '" class="btn-edit open-enseignant-modal">Modifier</a>';
            echo '<a href="index.php?action=admin&delete=enseignants&id=' . $id . '" class="btn-delete">Supprimer</a>';
        echo '</td>';
        echo "</tr>";
    }
    
    ?>
    </table>
</tbody>
</body>
</html>