<?php ob_start(); ?>
<marquee class="marque">Bienvenu.e <?= $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . ' - ' . ucfirst($_SESSION['role']) ?></marquee>
<div>
    <a href="index.php?action=visualiser">Visualiser</a>
    <?php if ($_SESSION['role'] == 'responsable') {
        echo '<a href="index.php?action=admin">Edition</a>';
    }
    ?>
</div>
<?php $content = ob_get_clean(); ?>
<?php require('template.php') ?>
