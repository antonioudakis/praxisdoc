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
  <title>ΠA Υποβολή Δικαιολογητικών</title>
</head>
<body>
  <div class="container">
    <div class="jumbotron shadow bg-info text-white" style="margin-top:100px">
      <div class="text-center">
        <h3 class="mb-4">Γραφείο Πρακτικής Άσκησης ΕΜΠ</h3>
        <h4>Πρακτική Άσκηση Φοιτητών ΕΜΠ</h4>
        <h5>Ηλεκτρονική Υποβολή Δικαιολογητικών</h5>
      </div>
    </div>
  <div>
    <?php
      include 'lib/praxisdoc.php';
      $user = new User();
      if ($user->isStudent()) {
        $student = new Student();
        try {
          if ($student->isRegistered()) {
            echo '<div class="text-white p-2" style="position: fixed; top:0px; right:10px; background-color:#3c3c3c;"><small><i class="fas fa-user"></i> '.$student->getLastname().' '.$student->getFirstname().'</small></div>';
            if ($student->isTimeOut()) {
              echo '<div class="d-flex justify-content-center mb-4">
                      <p>Έχει λήξει η προθεσμία υποβολής των δικαιολογητικών (<b>'.$student->getDeadline().'</b>)</p>
                    </div>';
            } else {
              echo '<div class="d-flex justify-content-center mb-4">
                      <p>Καταληκτική Ημερομηνία Υποβολής των Δικαιολογητικών : <b>'.$student->getDeadline().'</b></p>
                    </div>';
              echo '<div class="d-flex justify-content-center">
                      <div class="btn-group" role="group">
                        <a href="profile.php" class="btn btn-success btn-lg mx-2"><i class="fas fa-user"></i> Προφίλ Χρήστη</a>
                        <a href="upload.php" class="btn btn-success btn-lg mx-2"><i class="fas fa-file"></i> Υποβολή Εγγράφων</a>
                     </div>
                    </div>';
            }
          } else {
            if ($student->getPeriod()==2) {
              echo '<div class="d-flex justify-content-center mb-4">
                      <p>Έχει λήξει η περίοδος εγγραφής.</p>
                    </div>';
            } else {
              if ($student->isTimeOut()) {
                echo '<div class="d-flex justify-content-center mb-4">
                        <p>Έχει λήξει η προθεσμία υποβολής των δικαιολογητικών (<b>'.$student->getDeadline().'</b>)</p>
                      </div>';
              } else {
                echo '<div class="d-flex justify-content-center mb-4">
                        <p>Καταληκτική Ημερομηνία Υποβολής των Δικαιολογητικών : <b>'.$student->getDeadline().'</b></p>
                      </div>';
                echo '<div class="d-flex justify-content-center">
                        <a href="register.php" class="btn btn-success m-4 btn-lg">Εγγραφή</a>
                      </div>';
              }
            }
          }
        } catch(Exception $e) {
          $student->showMessage('danger','Σφάλμα!',$e->getMessage());
        }
      } elseif ($user->isEmployee()) {

        $employee = new Employee();
        try {
          $database = new DB();
          if ($employee->isRegistered()) {
            echo '<div class="text-white p-2" style="position: fixed; top:0px; right:10px; background-color:#3c3c3c;"><small><i class="fas fa-user"></i> '.$employee->getLastname().' '.$employee->getFirstname().'</small></div>';
            $praxis = new Praxis();
            echo '<div class="shadow p-4 mb-4 bg-white">


                      <div class="border-bottom py-3 my-3">
                      <h5>Αναζήτηση Υποβληθέντων Δικαιολογητικών Φοιτητών</h5>
                      </div>
                      <form class="form-inline" action="view.php" method="POST">
                        <div class="form-group">
                          <label class="sr-only" for="school">Σχολή:</label>
                          <select class="form-control mr-3" id="school" name="school">'.$praxis->getSchoolOptions($employee->getID()).'</select>
                        </div>
                        <div class="form-group">
                           <label class="sr-only" for="praxis">Πρακτική Άσκηση:</label>
                          <select class="form-control mr-3" id="praxis" name="praxis">'.$praxis->getOptions($employee->getPraxis()).'</select>
                        </div>
                        <button type="submit" class="btn btn-success"><i class="fas fa-search"></i> Αναζήτηση</button>
                      </form>
                    </div>';

          } else {
            $employee->showMessage('danger','Σφάλμα!','Δεν είστε εγγεγραμμένος στο σύστημα. Επικοινωνήστε με τον διαχειριστή του συστήματος.');
          }
        } catch(Exception $e) {
          $employee->showMessage('danger','Σφάλμα!',$e->getMessage());
        }
      } else {
        $user->showMessage('danger','Σφάλμα!','Δεν σας έχει οριστεί κάποιος ρόλος στο σύστημα.');
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
