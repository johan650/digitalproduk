<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nama = htmlspecialchars($_POST["nama"]);
  $no_hp = htmlspecialchars($_POST["no_hp"]);
  $produk = htmlspecialchars($_POST["produk"]);

  $to = "sumiyatun993@gmail.com"; // Ganti dengan email Anda
  $subject = "Data Produk dari $nama";
  $message = "Nama: $nama\nNo HP: $no_hp\nNama Produk: $produk";

  $headers = "From: ssproduk@jhnz.my.id";

  // Cek file
  if (isset($_FILES["gambar"]) && $_FILES["gambar"]["error"] == 0) {
    $allowed = ["jpg" => "image/jpeg", "jpeg" => "image/jpeg", "png" => "image/png"];
    $filename = $_FILES["gambar"]["name"];
    $filetype = $_FILES["gambar"]["type"];
    $filesize = $_FILES["gambar"]["size"];
    $filetmp = $_FILES["gambar"]["tmp_name"];

    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    if (!array_key_exists($ext, $allowed)) {
      die("Format file tidak diizinkan.");
    }

    if ($filesize > 2 * 1024 * 1024) {
      die("Ukuran gambar maksimal 2MB.");
    }

    $content = chunk_split(base64_encode(file_get_contents($filetmp)));
    $uid = md5(uniqid(time()));
    $filename = basename($filename);

    $headers = "From: ssproduk@jhnz.my.id\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";

    $body = "--".$uid."\r\n";
    $body .= "Content-type:text/plain; charset=utf-8\r\n";
    $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $body .= $message."\r\n\r\n";

    $body .= "--".$uid."\r\n";
    $body .= "Content-Type: ".$filetype."; name=\"".$filename."\"\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n";
    $body .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
    $body .= $content."\r\n\r\n";
    $body .= "--".$uid."--";

    if (mail($to, $subject, $body, $headers)) {
      header("Location: success.html");
    } else {
      echo "Gagal mengirim email.";
    }
  } else {
    echo "Gambar tidak ditemukan atau terjadi kesalahan upload.";
  }
}
?>