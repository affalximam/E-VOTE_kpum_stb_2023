<?php 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["update_candidate"])) {
        $id = $_POST['id'];
        $nama_capresma = $_POST['nama_capresma'];
        $nama_cawapresma = $_POST['nama_cawapresma'];
        

        // Fungsi untuk mengubah file gambar ke Base64
        function convertToBase64($imagePath)
        {
            $imageData = base64_encode(file_get_contents($imagePath));
            return $imageData;
        }

        // Periksa apakah file gambar diunggah dengan sukses
        if (isset($_FILES['gambar_paslon']) && $_FILES['gambar_paslon']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/png', 'image/jpeg', 'image/jpg', 'iamge/wepg'];
            $file_type = $_FILES['gambar_paslon']['type'];

            if (in_array($file_type, $allowed_types)) {
                $file_tmp = $_FILES['gambar_paslon']['tmp_name'];

                // Membaca file gambar ke dalam bentuk base64
                $image_base64 = base64_encode(file_get_contents($file_tmp));
                $gambar_paslon_base64 = 'data:' . $file_type . ';base64,' . $image_base64;

                // Persiapkan dan jalankan prepared statement untuk memperbarui gambar
                $sqlUpdate = "UPDATE kandidat SET nama_capresma = ?, nama_cawapresma = ?, image = ? WHERE id = ?";
                $stmtUpdate = $conn->prepare($sqlUpdate);
                $stmtUpdate->bind_param("sssi", $nama_capresma, $nama_cawapresma, $gambar_paslon_base64, $id);
                $stmtUpdate->execute();
                header("Refresh:0");
                exit;
            } else {
                include '/admin/auth/logout.php';
                exit; 
            }
        } else {
            $sqlUpdate = "UPDATE kandidat SET nama_capresma = ?, nama_cawapresma = ? WHERE id = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param("ssi", $nama_capresma, $nama_cawapresma, $id);
            $stmtUpdate->execute();
            header("Refresh:0");
            exit();
        }
    } elseif (isset($_POST["delete_candidate"])) {
        $id = $_POST["id"];
        $sqlDelete = "DELETE FROM kandidat WHERE id = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bind_param("i", $id);
        $stmtDelete->execute();
        header("Refresh:0");
        exit();
    } elseif (isset($_POST["add_candidate"])) {
        $nama_capresma = $_POST['nama_capresma'];
        $nama_cawapresma = $_POST['nama_cawapresma'];
        

        // Fungsi untuk mengubah file gambar ke Base64
        function convertToBase64($imagePath)
        {
            $imageData = base64_encode(file_get_contents($imagePath));
            return $imageData;
        }

        // Periksa apakah file gambar diunggah dengan sukses
        if (isset($_FILES['gambar_paslon']) && $_FILES['gambar_paslon']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/png', 'image/jpeg', 'image/jpg', 'iamge/wepg'];
            $file_type = $_FILES['gambar_paslon']['type'];

            if (in_array($file_type, $allowed_types)) {
                $file_tmp = $_FILES['gambar_paslon']['tmp_name'];

                // Membaca file gambar ke dalam bentuk base64
                $image_base64 = base64_encode(file_get_contents($file_tmp));
                $gambar_paslon_base64 = 'data:' . $file_type . ';base64,' . $image_base64;

                // Persiapkan dan jalankan prepared statement untuk memperbarui gambar
                $sqlUpdate = "INSERT INTO kandidat (nama_capresma, nama_cawapresma, image) VALUES (?, ?, ?)";
                $stmtUpdate = $conn->prepare($sqlUpdate);
                $stmtUpdate->bind_param("sss", $nama_capresma, $nama_cawapresma, $gambar_paslon_base64);
                $stmtUpdate->execute();
                header("Refresh:0");
                exit;
            } else {
                include '/admin/auth/logout.php';
                exit;
            }
        }
    }
}
?>