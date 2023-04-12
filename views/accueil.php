<?php ob_start(); ?>
<marquee class="marque">Bienvenu.e <?= $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . ' - ' . ucfirst($_SESSION['role']) ?></marquee>
<div id="acceuil">
    <p>Bienvenue</p>
    <ul>
        <li><a href="index.php?action=visualiser">Visualiser</a></li>
            <?php if ($_SESSION['role'] == 'responsable') {
                echo '<li><a href="index.php?action=admin">Edition</a></li>';
            }
            ?>
    </ul>
</div>
<div class="background-image-acceuil"></div>
<?php $content = ob_get_clean(); ?>
<?php require('template.php') ?>
