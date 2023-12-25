<?php 
    include "token_generator.php";

    for ($i=1; $i < 341; $i++) { 
        $token = generateRandomString();
        echo "$token <br>";
    }
   
?>
