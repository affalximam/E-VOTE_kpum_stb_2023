<?php 
    function pingAddress($ip) {
        $output = null;
        $status = null;
        
        // Jalankan perintah ping dan tangkap output
        exec("ping -n 1 $ip", $output, $status);

        // Output hasil ping
        // echo "Ping Results for $ip:<br>";
        // echo "<pre>" . implode("\n", $output) . "</pre>";
        // if ($status == 0) {
        //     echo "<span class='text-success'>Host Is Reachable</span><br>";
        // } else {
        //     echo "<span class='text-danger'>Request Timed Out</span><br>";
        // }

        echo "Ping Results for $ip:<br>";
        echo "<pre>" . implode("\n", $output) . "</pre>";

    }
?>