<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<table>
<thead>
    <tr>
        <th scope="col">#</th>
        <?php ?>
        <th scope="col">Nom</th>
        <th scope="col">Matiere</th>
    </tr>
</thead>
<tbody>
    <?php 
    
    foreach ($enseignants as $key => $value) {
        echo "<tr>";
        echo '<td>'.$value['nom']
    }
    
    ?>
    </table>
</tbody>
</body>
</html>