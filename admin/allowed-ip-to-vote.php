<?php
session_start();
include('../backend/connect/conn.php');
include('../backend/controllers/adminSessionController.php');
include('../backend/controllers/sistemController.php');
include("../backend/function/ip_ping.php");


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

$allow_ip = query("SELECT * FROM allow_ip_to_vote");
$count_allow_ip = count($allow_ip);
$filter_status = count(query("SELECT * FROM sistem_setting WHERE name = 'ip-filter' AND status = '1'"));

?>
<?php
if (!$_SESSION["admin"]) {
    // Redirect ke halaman login jika belum login
    header("Location: /");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALLOWED IP TO VOTE</title>
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
                    ALLOWED IP TO VOTE
                </p>
            </div>
        </div>
    </section>
    <!-- END JUMBOTRON 2 -->

    <!-- ALLOW IP TO VOTE -->
    <section class="allow-ip">
    <div class="container">
            <div class="row">
                <h1 class="mb-3">ALLOWED IP TO VOTE 
                    <?php if ($filter_status > 0) { ?>
                        (<span class="text-success">active</span>)
                        <?php } else { ?>
                        (<span class="text-danger">inactive</span>)
                    <?php } ?>
                </h1>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <a class="btn btn-md btn-primary" href="add-allowed-ip-address">
                        Tambah IP Address
                    </a>
                </div>
                <div class="col-md-6 mb-3 ip-filter">
                <?php if ($filter_status > 0) { ?>
                    <form method="post">
                        <button class="btn btn-md btn-success text-light" disabled>Filter Is On</button>
                        <button type="submit" name="set_off" class="btn btn-md btn-danger text-light" onclick="return confirm('Apakah Anda yakin ingin mematikan fitur ini ?');">Make Off</button>
                    </form>
                <?php } else { ?>
                    <form method="post">
                        <button class="btn btn-md btn-danger text-light" disabled>Filter Is Off</button>
                        <button type="submit" name="set_on" class="btn btn-md btn-success text-light" onclick="return confirm('Apakah Anda yakin ingin menghidupkan fitur ini ? ');">Make On</button>
                    </form>
                <?php } ?>
                </div>
            </div>
            <div class="row">
                <?php if ($count_allow_ip > 0) {
                    uasort($allow_ip, function ($a, $b) {
                        return strnatcasecmp($a['ip_address'], $b['ip_address']);
                    });
                ?>
                <table class="table table-striped table-hover table-bordered mt-3">
                    <thead>
                        <tr>
                            <th scope="col">NO</th>
                            <th scope="col">NAMA</th>
                            <th scope="col">IP ADDRESS</th>
                            <th scope="col">STATUS</th>
                            <th scope="col">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($allow_ip as $allow_ip_name => $allow_ip_data) { ?>
                            <tr>
                                <form method="post">
                                    <th scope="row">
                                        <p><?= $i ?></p>
                                    </th>
                                    <td>
                                        <input type="text" name="name" value="<?= htmlspecialchars($allow_ip_data['name']); ?>">
                                    <td>
                                        <input type="text" name="ip_address" value="<?= htmlspecialchars($allow_ip_data['ip_address']); ?>">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($allow_ip_data['id']); ?>">
                                    </td>
                                    <td>
                                    <?php
                                        $ipToPing = $allow_ip_data['ip_address'];

                                        // Panggil fungsi ping
                                        pingAddress($ipToPing);
                                        ?>

                                    </td>
                                    <td>
                                        <button type="submit" name="set_ip_address" class="btn btn-md btn-warning w-100 mb-2">
                                            Update
                                        </button>
                                        <button type="submit" name="delete_ip_address" onclick="return confirm('Apakah Anda yakin ingin menghapus Ip Address ini?');" class="btn btn-md btn-danger w-100 mb-2">
                                            Delete
                                        </button>
                                    </td>
                                </form>
                            </tr>
                            <?php $i = $i + 1 ?>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <h2 class="mt-3">Tidak Ada Data</h2>
                <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!-- END ALLOW IP TO VOTE -->

    <script src="../aset/js/jquery-3.5.1.js"></script>
    <script src="../aset/js/bootstrap.js"></script>
    <script src="../aset/js/script.js"></script>
</body>

</html>