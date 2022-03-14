<!DOCTYPE html>
<html lang="el">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

  <script src="https://kit.fontawesome.com/a076d05399.js"></script>
  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js" integrity="sha512-hZf9Qhp3rlDJBvAKvmiG+goaaKRZA6LKUO35oK6EsM0/kjPK32Yw7URqrq3Q+Nvbbt8Usss+IekL7CRn83dYmw==" crossorigin="anonymous"></script>

  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

  <script src="js/praxisdoc.js"></script>

  <link rel="stylesheet" type="text/css" href="css/main.css">
  <title>Υποβολή Δικαιολογητικών</title>
</head>
<body>
<div class="container">
  <div class="my-5 border-bottom border-info text-info">
    <h2>Πρακτική Άσκηση Φοιτητών ΕΜΠ</h2>
    <h5 class="mt-3">Υποβολή Δικαιολογητικών</h5>
  </div>
  <div>
    <?php
      include 'lib/praxisdoc.php';
      $user = new User();
      if ($user->isStudent()) {
        $student = new Student();
        try {
          if (!$student->isRegistered()) {
            echo '<div class="content-section">
                    <h3>Θα πρέπει πρώτα να κάνετε εγγραφή στο σύστημα</h3>
                    <div class="border-top pt-3">
                      <small class="text-muted">
                        Πατήστε <a href="index.php">εδώ</a> για να επιστρέψετε στην αρχική σελίδα.
                      </small>
                    </div>
                  </div>';
          } else {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

              if(isset($_POST['filename'])) {
                $file_name=$_POST['filename'];
                if (isset($_FILES[$file_name]) && $_FILES[$file_name]["error"] == 0) {
                  //$file_name     = $_FILES["fileID"]["name"];
                  $file_type     = $_FILES[$file_name]["type"];
                  $file_size     = $_FILES[$file_name]["size"];
                  $file_tmp_name = $_FILES[$file_name]["tmp_name"];
                  try {
                    $student->saveFile($file_name,$file_type,$file_size,$file_tmp_name);
                    $student->showMessage('success','Επιτυχία!','Το αρχείο αποθηκεύτηκε.');
                    // header('Location: upload.php');
                    //  ob_end_flush();
                    //  die();
                  } catch(Exception $e) {

                    $student->showMessage('danger','Σφάλμα!',$e->getMessage());
                  } finally {
                    echo '<div class="shadow p-4 mb-4 bg-white">
                          <h4>'.$student->getLastname().' '.$student->getFirstname().'</h4>
                          <div class="table-responsive">
                            <table class="table mb-0">
                              <thead>
                                <tr>
                                  <th>Α/Α</th>
                                  <th>Έγγραφο</th>
                                  <th>Ημ/νία Αποστολής</th>
                                  <th>
                                    <div class="d-flex justify-content-center">Λήψη/Αποστολή</div>
                                  </th>
                                  <th>
                                    <div class="d-flex justify-content-center">Διαγραφή</div>
                                  </th>
                                </tr>
                              </thead>
                              <tbody>';
                    $upload = new Upload();
                    echo $upload->getStudentUploadedFiles($student->getID(),$student->getPeriod());
                    echo ' </tbody>
                          </table>
                            <div class="border-top pt-3">
                              <div class="text-muted">
                                <ul>
                                	<li>Μπορείτε να ανεβάσετε αρχεία pdf ή αρχεία εικόνων. Το μέγεθος κάθε αρχείου δεν θα πρέπει να ξεπερνά το <b>1ΜΒ</b>.</li>
                                	<li>Τη βεβαίωση απογραφής ΕΦΚΑ μπορείτε να την πάρετε ηλεκτρονικά μέσω της δικτυακής πύλης <a href="https://www.gov.gr/ipiresies/ergasia-kai-asphalise/asphalise/bebaiose-apographes-eephka" target="_blank">gov.gr</a> εφόσον έχετε ήδη καταχωρηθεί στο Μητρώο του e-ΕΦΚΑ (μισθωτοί, μη μισθωτοί, έμμεσα ασφαλισμένοι).</li>
                                	<li>Τη δήλωση ΙΒΑΝ μπορείτε να την κατεβάστε από <a href="http://praktiki.ntua.gr/site/pdf/IBAN_form.pdf" target="_blank">εδώ</a>.</li>
                                	<li>Υπάρχουν αρκετές εφαρμογές με τις οποίες μπορείτε να σαρώσετε ένα έγγραφο μέσω κινητού τηλεφώνου και να αποθηκεύσετε το αρχείο σε μορφή pdf. Ενδεικτικά αναφέρουμε την εφαρμογή <a href="https://play.google.com/store/apps/details?id=com.intsig.camscanner" target="_blank">CamScanner</a>.</li>
                            	</ul>
                              </div>
                            </div>
                            <div class="d-flex flex-row-reverse mt-3">
                              <a href="index.php" class="btn btn-info mx-2"><i class="fas fa-undo"></i> Επιστροφή</a>
                            </div>';
                  }
                } else {
                  $student->showMessage('danger','Σφάλμα!','Δεν επιλέξατε κάποιο αρχείο.');
                  echo '<div class="shadow p-4 mb-4 bg-white">
                        <h4>'.$student->getLastname().' '.$student->getFirstname().'</h4>
                        <div class="table-responsive">
                          <table class="table mb-0">
                            <thead>
                              <tr>
                                <th>Α/Α</th>
                                <th>Έγγραφο</th>
                                <th>Ημ/νία Αποστολής</th>
                                <th>
                                  <div class="d-flex justify-content-center">Λήψη/Αποστολή</div>
                                </th>
                                <th>
                                  <div class="d-flex justify-content-center">Διαγραφή</div>
                                </th>
                              </tr>
                            </thead>
                          <tbody>';
                  $upload = new Upload();
                  echo $upload->getStudentUploadedFiles($student->getID(),$student->getPeriod());
                  echo '  </tbody>
                        </table>
                          <div class="border-top pt-3">
                            <div class="text-muted">
                              <ul>
                                <li>Μπορείτε να ανεβάσετε αρχεία pdf ή αρχεία εικόνων. Το μέγεθος κάθε αρχείου δεν θα πρέπει να ξεπερνά το <b>1ΜΒ</b>.</li>
                                <li>Τη βεβαίωση απογραφής ΕΦΚΑ μπορείτε να την πάρετε ηλεκτρονικά μέσω της δικτυακής πύλης <a href="https://www.gov.gr/ipiresies/ergasia-kai-asphalise/asphalise/bebaiose-apographes-eephka" target="_blank">gov.gr</a> εφόσον έχετε ήδη καταχωρηθεί στο Μητρώο του e-ΕΦΚΑ (μισθωτοί, μη μισθωτοί, έμμεσα ασφαλισμένοι).</li>
                                <li>Τη δήλωση ΙΒΑΝ μπορείτε να την κατεβάστε από <a href="http://praktiki.ntua.gr/site/pdf/IBAN_form.pdf" target="_blank">εδώ</a>.</li>
                                <li>Υπάρχουν αρκετές εφαρμογές με τις οποίες μπορείτε να σαρώσετε ένα έγγραφο μέσω κινητού τηλεφώνου και να αποθηκεύσετε το αρχείο σε μορφή pdf. Ενδεικτικά αναφέρουμε την εφαρμογή <a href="https://play.google.com/store/apps/details?id=com.intsig.camscanner" target="_blank">CamScanner</a>.</li>
                              </ul>
                            </div>
                          </div>
                          <div class="d-flex flex-row-reverse mt-3">
                            <a href="index.php" class="btn btn-info mx-2"><i class="fas fa-undo"></i> Επιστροφή</a>
                          </div>';
                }
              }
            } else {
              if ($student->isTimeOut()) {
                echo '<div class="d-flex justify-content-center mb-4">
                        <p>Έχει λήξει η προθεσμία υποβολής των δικαιολογητικών (<b>'.$student->getDeadline().'</b>)</p>
                      </div>';
              } else {
                echo '<div class="shadow p-4 mb-4 bg-white">
                        <h4>'.$student->getLastname().' '.$student->getFirstname().'</h4>
                        <div class="table-responsive">
                        <table class="table mb-0">
                          <thead>
                            <tr>
                              <th>Α/Α</th>
                              <th>Έγγραφο</th>
                              <th>Ημ/νία Αποστολής</th>
                              <th>
                                <div class="d-flex justify-content-center">Λήψη/Αποστολή</div>
                              </th>
                              <th>
                                <div class="d-flex justify-content-center">Διαγραφή</div>
                              </th>
                            </tr>
                          </thead>
                          <tbody>';
                $upload = new Upload();
                echo $upload->getStudentUploadedFiles($student->getID(),$student->getPeriod());
                echo '    </tbody>
                        </table>
                          <div class="border-top pt-3">
                            <div class="text-muted">
                              <ul>
                                <li>Μπορείτε να ανεβάσετε αρχεία pdf ή αρχεία εικόνων. Το μέγεθος κάθε αρχείου δεν θα πρέπει να ξεπερνά το <b>1ΜΒ</b>.</li>
                                <li>Τη βεβαίωση απογραφής ΕΦΚΑ μπορείτε να την πάρετε ηλεκτρονικά μέσω της δικτυακής πύλης <a href="https://www.gov.gr/ipiresies/ergasia-kai-asphalise/asphalise/bebaiose-apographes-eephka" target="_blank">gov.gr</a> εφόσον έχετε ήδη καταχωρηθεί στο Μητρώο του e-ΕΦΚΑ (μισθωτοί, μη μισθωτοί, έμμεσα ασφαλισμένοι).</li>
                                <li>Τη δήλωση ΙΒΑΝ μπορείτε να την κατεβάστε από <a href="http://praktiki.ntua.gr/site/pdf/IBAN_form.pdf" target="_blank">εδώ</a>.</li>
                                <li>Υπάρχουν αρκετές εφαρμογές με τις οποίες μπορείτε να σαρώσετε ένα έγγραφο μέσω κινητού τηλεφώνου και να αποθηκεύσετε το αρχείο σε μορφή pdf. Ενδεικτικά αναφέρουμε την εφαρμογή <a href="https://play.google.com/store/apps/details?id=com.intsig.camscanner" target="_blank">CamScanner</a>.</li>
                              </ul>
                            </div>
                          </div>
                          <div class="d-flex flex-row-reverse mt-3">
                            <a href="index.php" class="btn btn-info mx-2"><i class="fas fa-undo"></i> Επιστροφή</a>
                          </div>';
              }
            }
          }
        } catch(Exception $e) {
          echo '<div class="content-section">
                          <h3>Αποτυχία Ενημέρωσης Στοιχείων Φοιτητή</h3>
                          <div class="border-top pt-3">
                            <p>'.$e->getMessage().'</p>
                            <p><small class="text-muted">Πατήστε <a href="index.php">εδώ</a> για να επιστρέψετε στην αρχική σελίδα.</small></p>
                          </div>
                        </div>';
        }
      } else {
        $user->showMessage('danger','Σφάλμα!','Υποβολή δικαιολογητικών μπορούν να κάνουν μόνο φοιτητές οι οποίοι πρόκειται να παρακολουθήσουν το μάθημα της Πρακτικής Άσκησης.');
      }
    ?>
  </div>
</div>

<div class="alert alert-danger alert-dismissible fade show" id="sizeExceeded" style="position: fixed; bottom:0px; right:10px;display:none">
     <button type="button" class="close" data-dismiss="alert">&times;</button>
     <strong>Σφάλμα</strong></br><p class="small">Τα αρχεία που μπορείτε να ανεβάσετε πρέπει να έχουν μέγεθος μέχρι 1ΜΒ</p>
</div>

<script>
// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});


</script>
</body>
</html>
