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
  <!--<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>-->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

  <script src="js/praxisdoc.js"></script>

  <link rel="stylesheet" type="text/css" href="css/main.css">
  <title>Προβολή Δικαιολογητικών</title>
</head>
<body>
<div class="container">
  <div class="my-5 border-bottom border-info text-info">
    <h2>Πρακτική Άσκηση Φοιτητών ΕΜΠ</h2>
    <h5 class="mt-3">Προβολή Δικαιολογητικών</h5>
  </div>
  <div>
    <?php
      include 'lib/praxisdoc.php';
      $user = new User();
      if ($user->isEmployee()) {
        $employee = new Employee();
        if ($employee->isRegistered()) {
          if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $upload = new Upload();
            try {
              $upload->getSchoolUploadedFiles($_POST['school'],$_POST['praxis'],$employee->getPeriod());
            } catch(Exception $e) {
              echo $e->getMessage();
            }
            echo '
                        <div class="d-flex flex-row-reverse my-3">
                            <a href="index.php" class="btn btn-info mx-2"><i class="fas fa-undo"></i> Επιστροφή</a>
                        </div>';
          } else {
            echo '<div class="content-section">
                    <h3>Μη επαρκή δεδομένα</h3>
                    <div class="border-top pt-3">
                      <p><small class="text-muted">Πατήστε <a href="index.php">εδώ</a> για να επιστρέψετε στην αρχική σελίδα.</small></p>
                    </div>
                  </div>';
        }
      } else {
          echo '<div class="content-section">
                  <h3>Στα δικαιολογητικά έχουν πρόσβαση μόνο οι εξουσιοδοτημένοι χρήστες του Γραφείου Πρακτικής Άσκησης</h3>
                    <div class="border-top pt-3">
                      <p><small class="text-muted">Πατήστε <a href="index.php">εδώ</a> για να επιστρέψετε στην αρχική σελίδα.</small></p>
                    </div>
                  </div>';

      }
    } else {
        echo '<div class="content-section">
                  <h3>Στα δικαιολογητικά έχουν πρόσβαση μόνο οι εξουσιοδοτημένοι χρήστες του Γραφείου Πρακτικής Άσκησης</h3>
                    <div class="border-top pt-3">
                      <p><small class="text-muted">Πατήστε <a href="index.php">εδώ</a> για να επιστρέψετε στην αρχική σελίδα.</small></p>
                    </div>
                  </div>';
    }
    ?>
  </div>
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
