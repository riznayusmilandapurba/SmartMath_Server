<?php

$koneksi = mysqli_connect("localhost", "root", "", "smartmath");

if($koneksi){

	// echo "Database berhasil Conect";
	
} else {
	echo "gagal Connect";
}

?>