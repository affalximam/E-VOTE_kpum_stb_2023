<?php 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["set_on"])) {
            $sqlUpdate = "UPDATE sistem_setting SET status = '1' WHERE name = 'ip-filter'";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->execute();
            header("Refresh:0");
            exit();
        } 
        
        elseif (isset($_POST["set_off"])) {
            $sqlUpdate = "UPDATE sistem_setting SET status = '0' WHERE name = 'ip-filter'";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->execute();
            header("Refresh:0");
            exit();
        } 
        
        elseif (isset($_POST["set_ip_address"])) {
            $name = $_POST["name"];
            $ip_address = $_POST["ip_address"];
            $id = $_POST["id"];
            $sqlUpdate = "UPDATE allow_ip_to_vote SET name = ?, ip_address = ? WHERE id = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param("ssi", $name, $ip_address, $id);
            $stmtUpdate->execute();
            header("Refresh:0");
            exit();
        } 
        
        elseif (isset($_POST["delete_ip_address"])) {
            $id = $_POST["id"];
            $sqlDelete = "DELETE FROM allow_ip_to_vote WHERE id = ?";
            $stmtDelete = $conn->prepare($sqlDelete);
            $stmtDelete->bind_param("i", $id);
            $stmtDelete->execute();
            header("Refresh:0");
            exit();
        } 
        
        elseif (isset($_POST["add_ip_address"])) {
            $name = $_POST["name"];
            $ip_address = $_POST["ip_address"];

            $check_ip_address_stmt = $conn->prepare("SELECT COUNT(*) FROM allow_ip_to_vote WHERE ip_address = ?");
            $check_ip_address_stmt->bind_param("s", $ip_address);
            $check_ip_address_stmt->execute();
            $check_ip_address_stmt->bind_result($count_ip_address);
            $check_ip_address_stmt->fetch();
            $check_ip_address_stmt->close();

            if($count_ip_address < 1){
                $sql_insert = "INSERT INTO allow_ip_to_vote (name, ip_address) VALUES(?, ?)";
                $stmt_sql_insert = $conn->prepare($sql_insert);
                $stmt_sql_insert->bind_param("ss", $name, $ip_address);
                
                if ($stmt_sql_insert->execute()) {
                    ?>
                        <script>alert("Ip Address Berhasil Ditambahkan")</script>
                    <?php 
                    header("Refresh:0");
                    exit();
                } else {
                    exit();
                }
            } else {
                ?>
                    <script>alert("Ip Address Sudah Terdaftar")</script>
                <?php 
            }


        } 
    }
?>