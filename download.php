<?php

if(isset($_POST['path']))
{
	//Read the filename
	$filename = $_POST['path'];
	//Check the file exists or not
	if(file_exists($filename)) {

		//Define header information
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: 0");
		header('Content-Disposition: attachment; filename="'.basename($filename).'"');
		header('Content-Length: ' . filesize($filename));
		header('Pragma: public');

		//Clear system output buffer
		flush();

		//Read the size of the file
		readfile($filename);

		//Terminate from the script
		die();
	} else {
		echo "Το αρχείο δεν υπάρχει.";
	}
} else {
	echo "Δεν έχετε ορίσει το όνομα του αρχείου.";
}
?>