<?php 
include 'backend/connect/conn.php';
include 'backend/user/user_access.php';
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
$panitia = query("SELECT * FROM panitia");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONTRIBUTOR</title>
    <link rel="stylesheet" href="../aset/css/bootstrap.css">
    <link rel="stylesheet" href="../aset/css/style.css">

    <link rel="shortcut icon" href="/aset/images/BEM.png" type="image/x-png">

</head>

<body>

    <!-- navbar -->
    <?php 
        include 'component/navbar-user.php';
    ?>
    <!-- end navbar -->

    <!-- JUMBOTRON 2 -->
    <section class="jumbotron-2 mt-5 pt-5">
        <div class="container">
            <div class="row">
                <p>
                    <a href="/">HOME</a> >
                    CONTRIBUTOR
                </p>
            </div>
        </div>
    </section>
    <!-- END JUMBOTRON 2 -->

    <!-- CONTRIBUTOR -->
    <section class="contributor">
        <div class="container">
            <?php 
                if ($_SERVER["REQUEST_METHOD"] == "GET") {
                    if (isset($_GET["nama"])) {
                        $searchTerm = mysqli_real_escape_string($conn, $_GET["nama"]);

                        // Kueri SQL untuk mencari data berdasarkan nama
                        $sqlName = "SELECT * FROM panitia WHERE TRIM(nama) LIKE ?";
                        $stmtName = $conn->prepare($sqlName);
                        $stmtName->bind_param("s", $searchTerm);
                        $stmtName->execute();
                        $resultName = $stmtName->get_result();
                
                        if ($resultName->num_rows > 0) {
                            while ($detail_panitia = $resultName->fetch_assoc()) {
                                ?>
                                <div class="row pb-3">
                                    <h1 class="text-center">DETAIL PANITIA</h1>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-3"></div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="card">
                                            <img src="<?= htmlspecialchars($detail_panitia['foto']) ?>" alt="">
                                            <p class="nama"><?= htmlspecialchars($detail_panitia['nama']) ?></p>
                                            <p><?= htmlspecialchars($detail_panitia['posisi']) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                            }
                        } else {
                            ?>
                                <div class="row pb-3">
                                    <h1>TIDAK TERDAFTAR SEBAGAI PANITIA</h1>
                                </div>
                            <?php 
                        }
                    } else {
                        ?>
                            <div class="row pb-3">
                                <h1>SUSUNAN PANITIA</h1>
                            </div>
                            <div class="row">
                                <?php foreach($panitia as $data_panitia){ ?>
                                <div class="col-md-4 col-sm-6">
                                    <div class="card">
                                        <img src="<?= htmlspecialchars($data_panitia['foto']) ?>" alt="">
                                        <p class="nama"><?= htmlspecialchars($data_panitia['nama']) ?></p>
                                        <p><?= htmlspecialchars($data_panitia['posisi']) ?></p>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="row pb-3 pt-5">
                                <h1>TENTANG DEVELOPER</h1>
                            </div>
                            <div class="row pb-5">
                                <ul class="ms-3">
                                    <li>UX Design : Arrazaq Panca Putra</li>
                                    <li>Front End : Imam Nurfalah</li>
                                    <li>Back End  : Agung Prasetyo</li>
                                </ul>
                            </div>
                        <?php 
                    }
                } 
            ?>
        </div>
    </section>
    <!-- END CONTRIBUTOR -->

    <script src="../aset/js/jquery-3.5.1.js"></script>
    <script src="../aset/js/bootstrap.js"></script>
    <script src="../aset/js/script.js"></script>
</body>

</html>