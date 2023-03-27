<?php
/**
 * Function qui echappe les caractères spécials des données envoyé par les utilisateurs
 *
 * @param array $data
 * @return
 */
function sanitize(array $data, $dataKey) {
    $sanitizedData = [];
    foreach ($dataKey as $key) {
        if (! isset($data[$key])) {
            // si une donnée obligatoire est absent -> redirection
            echo json_encode(
                ['code' => 500, 'status' => 'Bad Request', 'message' => "$key obligatoire"],
                JSON_PRETTY_PRINT
            );
            exit();
        } else {  
            if (! is_array($data[$key])) {
                $sanitizedData[$key] = htmlspecialchars($data[$key]);
            } else {
                $sanitizedData[$key] = sanitize($data[$key], array_keys($data[$key]));
            }
        }
    }
}
if ($_GET['search'] == 'edt') {
    $data
}
