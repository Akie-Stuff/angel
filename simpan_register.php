<?php

include "koneksi.php";


$username=$_POST["username"];
$email=$_POST["email"];
$password=$_POST["password"];


  $hasil=mysqli_query($koneksi, "INSERT INTO login (username,email,password) VALUES('$username','$email','$password')");


  if ($hasil) 
  {
	echo "<script>
				alert('Anda Berhasil Registrasi !');
				document.location='login.php';
		  </script>";
  }
  else 
  {
	echo "<script>
				alert('Registrasi Anda Gagal !');
		  </script>";
  }

?>