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
  <title>Προφίλ</title>
</head>
<body>
<div class="container">
  <div class="my-5 border-bottom border-info text-info">
    <h2>Πρακτική Άσκηση Φοιτητών ΕΜΠ</h2>
    <h5 class="mt-3">Στοιχεία Φοιτητή</h5>
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
              if (isset($_POST['email'])) {
                $student->update($_POST['email'],$_POST['praxis']);
                echo '<div class="content-section">
                        <h3>Επιτυχής Ενημέρωση</h3>
                        <div class="border-top pt-3">
                          <small class="text-muted">
                            Πατήστε <a href="index.php">εδώ</a> για να επιστρέψετε στην αρχική σελίδα.
                          </small>
                        </div>
                      </div>';
              } else {
                $student->showMessage('danger','Σφάλμα!','Δεν ορίσατε το email σας');
                echo '<div class="content-section">
                        <h3>Δεν ορίσατε σωστά το email σας</h3>
                        <div class="border-top pt-3">
                          <small class="text-muted">
                            Πατήστε <a href="profile.php">εδώ</a> για να δοκιμάσετε ξανά ή <a href="index.php">εδώ</a> για να επιστρέψετε στην αρχική σελίδα.
                          </small>
                        </div>
                      </div>';
              }
            } else {
              $praxis = new Praxis();
              echo '<div class="shadow p-4 mb-4 bg-white">
                      <form method="post" enctype="multipart/form-data" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">
                        <div class="form-row mt-3">
                          <div class="col-md-6 form-group">
                            <label for="username">Όνομα Χρήστη:</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="'.$student->getUsername().'" readonly />
                          </div>
                          <div class="col-md-6 form-group">
                            <label for="school">Σχολή:</label>
                            <input type="text" class="form-control" id="school" name="school" placeholder="Σχολή" value="'.$student->getSchool("title").'" readonly />
                          </div>
                        </div>
                        <div class="form-row mt-3">
                          <div class="col-md-6 form-group">
                            <label for="lastname">Επώνυμο:</label>
                            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Επώνυμο" value="'.$student->getLastname().'" readonly />
                          </div>
                          <div class="col-md-6 form-group">
                            <label for="lastname">Όνομα:</label>
                            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Όνομα" value="'.$student->getFirstname().'" readonly />
                          </div>
                        </div>
                        <div class="form-row mt-6">
                          <div class="col-md-6 form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="'.$student->getEmail().'"/>
                          </div>
                          <div class="col-md-6 form-group">
                            <label for="praxis">Πρακτική Άσκηση:</label>
                            <select class="form-control" id="praxis" name="praxis" readonly/><option value="'.$student->getPraxis().'" selected>'.$praxis->getTitleByID($student->getPraxis()).'</option></select>
                          </div>
                        </div>
                        <div class="d-flex flex-row-reverse mt-3">
                          <div class="btn-group" role="group">
                            <a href="index.php" class="btn btn-danger mx-2">Άκυρο</a>
                            <button type="submit" class="btn btn-success mx-2">Εγγραφή</button>
                          </div>
                        </div>
                      </form>
                    </div>';
              $user->showMessage('warning','Προειδοποίηση!','Μπορείτε να αλλάξετε μόνο το email σας.');
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
        $user->showMessage('danger','Σφάλμα!','Ενημέρωση στοιχείων μπορούν να κάνουν μόνο φοιτητές οι οποίοι πρόκειται να παρακολουθήσουν το μάθημα της Πρακτικής Άσκησης.');
      }
    ?>
  </div>
</div>


<!--<div style="color: #777;background-color:white;text-align:center;padding:50px 80px;text-align: justify;">
  <h3 style="text-align:center;">Parallax Demo</h3>
  <p>Parallax scrolling is a web site trend where the background content is moved at a different speed than the foreground content while scrolling. Nascetur per nec posuere turpis, lectus nec libero turpis nunc at, sed posuere mollis ullamcorper libero ante lectus, blandit pellentesque a, magna turpis est sapien duis blandit dignissim. Viverra interdum mi magna mi, morbi sociis. Condimentum dui ipsum consequat morbi, curabitur aliquam pede, nullam vitae eu placerat eget et vehicula. Varius quisque non molestie dolor, nunc nisl dapibus vestibulum at, sodales tincidunt mauris ullamcorper, dapibus pulvinar, in in neque risus odio. Accumsan fringilla vulputate at quibusdam sociis eleifend, aenean maecenas vulputate, non id vehicula lorem mattis, ratione interdum sociis ornare. Suscipit proin magna cras vel, non sit platea sit, maecenas ante augue etiam maecenas, porta porttitor placerat leo.</p>
</div>

<div class="bgimg-2">
  <div class="caption">
  <span class="border" style="background-color:transparent;font-size:25px;color: #f7f7f7;">LESS HEIGHT</span>
  </div>
</div>

<div style="position:relative;">
  <div style="color:#ddd;background-color:#282E34;text-align:center;padding:50px 80px;text-align: justify;">
  <p>Scroll up and down to really get the feeling of how Parallax Scrolling works.</p>
  </div>
</div>

<div class="bgimg-3">
  <div class="caption">
  <span class="border" style="background-color:transparent;font-size:25px;color: #f7f7f7;">SCROLL UP</span>
  </div>
</div>

<div style="position:relative;">
  <div style="color:#ddd;background-color:#282E34;text-align:center;padding:50px 80px;text-align: justify;">
  <p>Scroll up and down to really get the feeling of how Parallax Scrolling works.</p>
  </div>
</div>

<div class="bgimg-1">
  <div class="caption">
  <span class="border">COOL!</span>
  </div>
</div> -->

<!--<div style="height:1000px;background-color:red;font-size:36px">
Scroll Up and Down this page to see the parallax scrolling effect.
This div is just here to enable scrolling.
Tip: Try to remove the background-attachment property to remove the scrolling effect.
</div>-->

</body>
</html>
