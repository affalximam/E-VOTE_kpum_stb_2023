<?php
session_start();
include('../backend/connect/conn.php');
include('../backend/controllers/adminSessionController.php');
$keyComponent = "kpum";

function query($query)
{
    $result = mysqli_query($GLOBALS['conn'], $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}
$voters_data = query("SELECT * FROM data_registrasi_pemilih");
$count_voters_data = count(query("SELECT * FROM data_registrasi_pemilih"));

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voters Register</title>
    <link rel="stylesheet" href="../aset/css/bootstrap.css">
    <link rel="stylesheet" href="../aset/css/style.css">
    <link rel="shortcut icon" href="/aset/images/BEM.png" type="image/x-png">
</head>

<body>

    <!-- navbar -->
    <nav class="navbar fixed-top" id="navbar" data-scroll-index="0">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <h1 class="navbar-brand">ADMINISTRATOR</h1>
            <div class="collapse navbar-collapse justify-content-end text-center" id="navbarNav">
            </div>
        </div>
    </nav>
    <!-- end navbar -->

    <!-- SIDEBAR -->
    <?php
    include '../component/sidebar.php'
    ?>
    <!-- END SIDEBAR -->

    <!-- JUMBOTRON 2 -->
    <section class="jumbotron-2 mt-5 pt-5">
        <div class="container">
            <div class="row">
                <p>
                    <a href="../">HOME</a> >
                    <a href="dashboard">ADMIN</a> >
                    VOTERS REGISTER
                </p>
            </div>
        </div>
    </section>
    <!-- END JUMBOTRON 2 -->

    <!-- DATA SUARA -->
    <section class="data-voters">
        <div class="container">
            <div class="row">
                <h1 class="mb-3">DATA REGISTRASI PEMILIH</h1>
            </div>
            <div class="row">
                <?php if ($count_voters_data > 0) {
                    uasort($voters_data, function ($a, $b) {
                        return strnatcasecmp($a['identity_number'], $b['identity_number']);
                    });
                    ?>
                    <table class="table table-striped table-hover table-bordered mt-3">
                        <thead>
                            <tr>
                                <th scope="col">NO</th>
                                <th scope="col">NAMA</th>
                                <th scope="col">NOMOR IDENTITAS</th>
                                <th scope="col">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($voters_data as $voter) { ?>
                                <?php 
                                    $voter_identity_number = $voter['identity_number'];
                                    $detail_voters_data = query("SELECT * FROM data_induk_pemilih WHERE identity_number = '$voter_identity_number';");

                                    // Pastikan ada hasil dari kueri
                                    if ($detail_voters_data) {
                                        $detail_voters_data = $detail_voters_data[0];
                                    ?>
                                    <tr>
                                        <th scope="row">
                                            <?= $i++ ?>
                                        </th>
                                        <td>
                                            <input type="text" value="<?= htmlspecialchars($detail_voters_data['name']); ?>">
                                        </td>
                                        <td>
                                            <input type="text" value="<?= htmlspecialchars($voter['identity_number']); ?>">
                                        </td>
                                        <td>
                                            <?php
                                            $isvoted = $detail_voters_data['is_voted'];
                                            if ($isvoted == 1) {
                                            ?>
                                                <p class="text-success">Sudah Memilih</p>
                                            <?php
                                            } else {
                                            ?>
                                                <p class="text-danger">Belum Memilih</p>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <h2 class="mt-3">Tidak Ada Data</h2>
                <?php } ?>
            </div>
        </div>
    </section>
    <!-- DATA SUARA -->

    <script src="../aset/js/jquery-3.5.1.js"></script>
    <script src="../aset/js/bootstrap.js"></script>
    <script src="../aset/js/script.js"></script>
</body>

</html>