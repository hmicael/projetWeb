<?php
if ($_GET['action'] == 'edt-add') {
    echo '<pre>';
    var_dump($_POST);
} else if ($_GET['action'] == 'edt-edit') {
    echo "edit";
}  else if ($_GET['action'] == 'edt-delete') {
    echo "dele";
}