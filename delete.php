<?php

include 'lib/praxisdoc.php';

$student = new Student();


if (isset($_POST['fileID'])) {
	$fileID = $_POST['fileID'];
	$student->deleteFile($fileID);
} else {
  echo $student->showMessage('danger','Σφάλμα!','Δεν ορίστηκαν σωστά οι παράμετροι για την διαγραφή του αρχείου');
}
header('Location: upload.php');
ob_end_flush();
die();
?>