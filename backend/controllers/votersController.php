<?php

function updateVoter($conn, $new_name, $new_token, $new_identity_number, $old_identity_number) {
    $sqlIdentityNumber = "SELECT * FROM data_induk_pemilih WHERE identity_number = ?";
    $stmtIdentityNumber = $conn->prepare($sqlIdentityNumber);
    $stmtIdentityNumber->bind_param("s", $new_identity_number);
    $stmtIdentityNumber->execute();
    $resultIdentityNumber = $stmtIdentityNumber->get_result();

    if ($resultIdentityNumber->num_rows > 0) {
        $sqlUpdate = "UPDATE data_induk_pemilih SET name = ?, voter_token = ? WHERE identity_number = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("sss", $new_name, $new_token, $old_identity_number);
        $stmtUpdate->execute();
        ?>
            <script>
                alert("Nomor Identitas Tidak Diubah");
            </script>
        <?php
    } else {
        $sqlUpdate = "UPDATE data_induk_pemilih SET name = ?, identity_number = ?, voter_token = ? WHERE identity_number = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("ssss", $new_name, $new_identity_number, $new_token, $old_identity_number);
        $stmtUpdate->execute();?>
        <script>
            alert("Data Berhasil Diubah");
        </script>
    <?php
    }
}

function setVoterIsVoted($conn, $is_voted, $old_identity_number) {
    $sqlUpdate = "UPDATE data_induk_pemilih SET is_voted = ? WHERE identity_number = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("is", $is_voted, $old_identity_number);
    $stmtUpdate->execute();
}

function addVoter($conn, $name, $identity_number, $voter_token, $is_voted) {
    $check_identity_number_stmt = $conn->prepare("SELECT COUNT(*) FROM data_induk_pemilih WHERE identity_number = ?");
    $check_identity_number_stmt->bind_param("s", $identity_number);
    $check_identity_number_stmt->execute();
    $check_identity_number_stmt->bind_result($count_identity_number);
    $check_identity_number_stmt->fetch();
    $check_identity_number_stmt->close();

    $check_voter_token_stmt = $conn->prepare("SELECT COUNT(*) FROM data_induk_pemilih WHERE voter_token = ?");
    $check_voter_token_stmt->bind_param("s", $voter_token);
    $check_voter_token_stmt->execute();
    $check_voter_token_stmt->bind_result($count_voter_token);
    $check_voter_token_stmt->fetch();
    $check_voter_token_stmt->close();

    if ($count_identity_number == 0) {
        if ($count_voter_token == 0) {
            $sql_insert = "INSERT INTO data_induk_pemilih (identity_number, name, voter_token, is_voted) VALUES(?, ?, ?, ?)";
            $stmt_sql_insert = $conn->prepare($sql_insert);
            $stmt_sql_insert->bind_param("ssss", $identity_number, $name, $voter_token, $is_voted);

            // Execute SQL statement and handle errors
            if ($stmt_sql_insert->execute()) {
                header("Refresh:0");
                exit();
            } else {
                exit();
            }
        } else {
            $i = 0;
            do {
                $voter_token_new = generateRandomString();
                $check_voter_token_stmt = $conn->prepare("SELECT COUNT(*) FROM data_induk_pemilih WHERE voter_token = ?");
                $check_voter_token_stmt->bind_param("s", $voter_token_new);
                $check_voter_token_stmt->execute();
                $check_voter_token_stmt->bind_result($count_voter_token);
                $check_voter_token_stmt->fetch();
                $check_voter_token_stmt->close();
                $i++;
            } while ($count_voter_token > 0);

            $sql_insert = "INSERT INTO data_induk_pemilih (identity_number, name, voter_token, is_voted) VALUES(?, ?, ?, ?)";
            $stmt_sql_insert = $conn->prepare($sql_insert);
            $stmt_sql_insert->bind_param("ssss", $identity_number, $name, $voter_token_new, $is_voted);

            // Execute SQL statement and handle errors
            if ($stmt_sql_insert->execute()) {
                header("Refresh:0");
                exit();
            } else {
                // Handle error if needed
            }
        }
    } else {
        ?>
        <script>alert("Nomer Identitas Sudah Terdata");</script>
        <?php
        exit();
    }
}

function deleteVoter($conn, $old_identity_number) {
    $sqlDelete = "DELETE FROM data_induk_pemilih WHERE identity_number = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("s", $old_identity_number);
    $stmtDelete->execute();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["set_voter"])) {
        updateVoter($conn,$_POST["new_name"],$_POST["new_token"],$_POST["new_identity_number"],$_POST["old_identity_number"]);
        header("Refresh:0");
        exit();
    } elseif (isset($_POST["set_is_voted_0"])) {
        setVoterIsVoted($conn, 0, $_POST["old_identity_number"]);
        header("Refresh:0");
        exit();
    } elseif (isset($_POST["set_is_voted_1"])) {
        setVoterIsVoted($conn, 1, $_POST["old_identity_number"]);
        header("Refresh:0");
        exit();
    } elseif (isset($_POST["add-voters"])) {
        $name = $_POST['name'];
        $identity_number = $_POST['identity_number'];
        $voter_token = $_POST['voter_token'];
        $is_voted = 0;
        addVoter($conn, $name, $identity_number, $voter_token, $is_voted);
    } elseif (isset($_POST["delete_voter"])) {
        // Panggil fungsi untuk menghapus voter
        deleteVoter($conn, $_POST["old_identity_number"]);
        header("Refresh:0");
        exit();
    }
}

?>
