<?php 

    $filter_status = count(query("SELECT * FROM sistem_setting WHERE name = 'ip-filter' AND status = '1'"));
    if($filter_status > 0){
        $user_ip_address = $_SERVER['REMOTE_ADDR'];
        $cek_ip_address = count(query("SELECT * FROM allow_ip_to_vote WHERE ip_address = '$user_ip_address'"));
        if($cek_ip_address < 1){
            ?>
            <script>
                window.location.href = "/block";
            </script>
            <?php 
        }
    } else {
        
    }

    
?>