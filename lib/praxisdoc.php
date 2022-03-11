<?php

class User {
	private $username;
	private $lastname;
	private $firstname;
	private $email;
	private $praxis;
	private $remoteIP;

	function __construct() {
		$this->username = explode('@',$_SERVER['eppn'])[0];
		$this->lastname = $this->removeAccent(mb_strtoupper($_SERVER['sn']));
		$this->firstname = $this->removeAccent(mb_strtoupper($_SERVER['givenName']));
		$this->email = $_SERVER['mail'];
		$this->remoteIP = $_SERVER['REMOTE_ADDR'];
		$this->getCurrentPraxis();
	}

	function getUsername() {
		return $this->username;
	}

	function getLastname() {
		return $this->lastname;
	}

	function getFirstname() {
		return $this->firstname;
	}

	function getEmail() {
		return $this->email;
	}

	function getPraxis(){
		return $this->praxis;
	}

	function getRemoteIP() {
		return $this->remoteIP;
	}

	function removeAccent($word) {
		$word = str_replace("Ά","Α",$word);
		$word = str_replace("Έ","Ε",$word);
		$word = str_replace("Ή","Η",$word);
		$word = str_replace("Ί","Ι",$word);
		$word = str_replace("Ό","Ο",$word);
		$word = str_replace("Ύ","Υ",$word);
		$word = str_replace("Ώ","Ω",$word);
		return $word;
	}

	function getCurrentPraxis() {
		if ($file = file_get_contents('/usr/local/etc/config.json')) {
			$obj = json_decode($file);
			$this->praxis = $obj->current_praxis;
		} else {
			throw new Exception("Σφάλμα κατά την παραμετροποίηση.");
		}
	}

	function isStudent() {
		if ($_SERVER['primary-affiliation'] == 'student') {
			return true;
		} else {
			return false;
		}
	}


	function isEmployee() {
		if (($_SERVER['primary-affiliation'] == 'employee') || ($_SERVER['primary-affiliation'] == 'faculty') || ($_SERVER['primary-affiliation'] == 'staff')) {
			return true;
		} else {
			return false;
		}
	}

	function showMessage($type,$title,$message) {
		echo '<div class="alert alert-'.$type.' alert-dismissible alert-autoremove fade show" style="position: fixed; bottom:0px; right:10px;">
                	<button type="button" class="close" data-dismiss="alert">&times;</button>
                	<strong>'.$title.'</strong></br><p class="small">'.$message.'</p>
              </div>';
	}
}

class Student extends User {

	function getShibSchool() {
		$school = $_SERVER['primary-orgunit-dn'];
        $school = explode(",",$school);
        $school = explode("=",$school[0]);
        $school = $school[1];
        switch ($school){
          	case 'Dep8':
        		return 1;
        	case 'Dep6':
            	return 2;
           	case 'Dep4':
            	return 3;
            case 'Dep2':
            	return 4;
            case 'Dep9':
            	return 5;
            case 'Dep1':
            	return 6;
            case 'Dep5':
            	return 7;
            case 'Dep7':
            	return 8;
            case 'Dep3':
            	return 9;
            default:
            	throw new Exception("Μη αποδεκτή περιγραφή Σχολής",1);
        }
	}

	function getID() {
		try {
			if ($this->isRegistered()) {
				$database = new DB();
				$conn = $database->connect();
  				$sql = "SELECT id FROM student WHERE username='".$this->getUsername()."' and praxis=".$this->getPraxis();
  				$result = $conn->query($sql);
				if ($result->num_rows == 0) {
					$conn = $database->connect();
					throw new Exception("Μη εγγεγραμμένος φοιτητής.",2);
				} else {
					$row = $result->fetch_assoc();
					$id = $row['id'];
					$conn = $database->connect();
					return $id;
  				}
  			} else {
  				throw new Exception("Μη εγγεγραμμένος φοιτητής.",2);
  			}
  		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	function getSchool($view) {
		try {
			$id = $this->getShibSchool();
			if ($view=="code") {
        		return $id;
        	} elseif ($view=="title") {
        		$database = new DB();
				$conn = $database->connect();
  				$sql = "SELECT title FROM school WHERE id=".$id;
  				$result = $conn->query($sql);
				if ($result->num_rows == 0) {
					$database->disconnect($conn);
					throw new Exception("Μη αποδεκτός κωδικός Σχολής",2);
				} else {
					$row = $result->fetch_assoc();
					$title = $row['title'];
					$database->disconnect($conn);
					return $title;
  				}
  			} else {
        		throw new Exception("Μη αποδεκτός τύπος προβολής Σχολής",3);
        	}
		} catch (Exception $e) {
			$this->showMessage('danger','Σφάλμα!',$e->getMessage());
		}
	}

	function getEmail() {
		try {
			if ($this->isRegistered()) {
				$database = new DB();
				$conn = $database->connect();
  				$sql = "SELECT email FROM student WHERE username='".$this->getUsername()."'";
  				$result = $conn->query($sql);
				$row = $result->fetch_assoc();
				$email = $row['email'];
				$database->disconnect($conn);
				return  $email;
			} else {
				return parent::getEmail();
			}
		} catch (Exception $e) {
			$this->showMessage('danger','Σφάλμα!',$e->getMessage());
		}
	}

	/*function getPraxis() {
		try {
			if ($this->isRegistered()) {
				$database = new DB();
				$conn = $database->connect();
  				$sql = "SELECT max(praxis) FROM student WHERE username='".$this->getUsername()."'";
  				$result = $conn->query($sql);
				$row = $result->fetch_assoc();
				$praxis = $row['praxis'];
				$database->disconnect($conn);
				return  $praxis;
			} else {
				return parent::getPraxis();
			}
		} catch (Exception $e) {
			$this->showMessage('danger','Σφάλμα!',$e->getMessage());
		}
	}*/

	function isRegistered() {
		try {
			$database = new DB();
			$conn = $database->connect();
			if ($this->isStudent()) {
				$sql = "SELECT id FROM student WHERE username='".$this->getUsername()."' and praxis=".$this->getPraxis();
				$result = $conn->query($sql);
				if ($result->num_rows == 0) {
					$database->disconnect($conn);
					return false;
				} else {
					$database->disconnect($conn);
					return true;
				}
			} else {
				$database->disconnect($conn);
  				throw new Exception("Δεν έχετε συνδεθεί ως φοιτητής.",2);
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	function register($email,$praxis) {
		try {
			$database = new DB();
			$conn = $database->connect();
			$sql = "INSERT INTO student (username,lastname,firstname,email,praxis,school) VALUES ('".$this->getUsername()."','".$this->getLastname()."','".$this->getFirstname()."','".$email."',".$praxis.",".$this->getShibSchool().")";
			if ($conn->query($sql)) {
				$sql = "SELECT id from file";
				$result = $conn->query($sql);
				while ($row = $result->fetch_assoc()) {
					$sql1 = "INSERT INTO upload (student,file) values (".$this->getID().",".$row['id'].")";
					if (!$conn->query($sql1)) {
						throw new Exception("Η εγγραφή δεν ολοκληρώθηκε. Προσπαθήστε ξανά.",2);
					}
				}
				$database->disconnect($conn);
			} else {
				$database->disconnect($conn);
				throw new Exception("Η εγγραφή δεν ολοκληρώθηκε. Προσπαθήστε ξανά.",2);
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	function update($email) {
		try {
			$database = new DB();
			$conn = $database->connect();
			$sql = "UPDATE student set email = '".$email."' where username ='".$this->getUsername()."'";
			if ($conn->query($sql)) {
				$database->disconnect($conn);
			} else {
				$database->disconnect($conn);
				throw new Exception("Η ενημέρωση δεν ολοκληρώθηκε. Προσπαθήστε ξανά.",2);
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	function saveFile($file_name,$file_type,$file_size,$file_tmp_name) {
		if (($file_type == 'application/pdf') || (explode('/',$file_type)[0] == 'image')) {
			if ($file_size<=1048576) {
				try {
					$database = new DB();
					$conn = $database->connect();
  					$sql="SELECT id FROM file where filename='".$file_name."'";
  					$result = $conn->query($sql);
					$row = $result->fetch_assoc();
					$fileID = $row['id'];

					$filepath = 'uploads/'.$this->getPraxis().'/'.$this->getschool("code").'/'.$this->getUsername().'_'.$file_name.'.'.explode('/',$file_type)[1];
  					$sql='UPDATE upload set filepath="'.$filepath.'",uploadedTime='.CURRENT_TIMESTAMP.',filetype="'.$file_type.'",size='.$file_size.',remoteIP="'.$this->getRemoteIP().'" where student='.$this->getID().' and file='.$fileID;
  					if ($conn->query($sql)) {
						$database->disconnect($conn);
						if (move_uploaded_file($file_tmp_name, $filepath)) {
							return 1;
						} else {
							throw new Exception("Η ενημέρωση δεν ολοκληρώθηκε. Προσπαθήστε ξανά.",2);
						}
					} else {
						$database->disconnect($conn);
						throw new Exception("Η ενημέρωση δεν ολοκληρώθηκε. Προσπαθήστε ξανά.",2);
					}
  				} catch (Exception $e) {
					throw new Exception($e->getMessage());
				}
  			} else {
  				throw new Exception("Τα αρχεία που μπορείτε να ανεβάσετε πρέπει να έχουν μέγεθος μέχρι 1ΜΒ",3);
  			}
  		} else {
  			throw new Exception("Τα αρχεία που μπορείτε να ανεβάσετε είναι έγγραφα pdf ή εικόνες",4);
  		}

	}

	function deleteFile($file) {
		try {
			$database = new DB();
			$conn = $database->connect();
  			$sql="SELECT id,filepath FROM upload where student=".$this->getID()." and file=".$file;
  			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			$fileID = $row['id'];
			$filepath = $row['filepath'];

			$sql = "UPDATE upload SET filepath = null, uploadedTime= null, filetype=null,size=null,remoteIP=null where student=".$this->getID()." and file=".$file;
  			if ($conn->query($sql)) {
				$database->disconnect($conn);
				if (unlink($filepath)) {
					return 1;
				} else {
					throw new Exception("Η διαγραφή δεν ολοκληρώθηκε. Προσπαθήστε ξανά.",2);
				}
			} else {
				$database->disconnect($conn);
				throw new Exception("Η διαγραφή δεν ολοκληρώθηκε. Προσπαθήστε ξανά.",2);
			}
  		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	function getDeadline() {
		try {
			$database = new DB();
			$conn = $database->connect();
			$sql="SELECT until FROM deadline where school=".$this->getSchool("code")." and praxis=".$this->getPraxis();
			$result = $conn->query($sql);
			if ($result->num_rows == 0) {
				$database->disconnect($conn);
				throw new Exception("Δεν έχει οριστεί καταληκτική ημερομηνία υποβολής δικαιολογητικών.",1);
			} else {
				$row = $result->fetch_assoc();
				$until = $row['until'];
				$until = explode('-',$until);
				return $until[2].'-'.$until[1].'-'.$until[0];
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	function isTimeOut() {
		try {
			$database = new DB();
			$conn = $database->connect();
			$sql="SELECT until,CURDATE() as current FROM deadline where school=".$this->getSchool("code")." and praxis=".$this->getPraxis();
			$result = $conn->query($sql);
			if ($result->num_rows == 0) {
				$database->disconnect($conn);
				throw new Exception("Δεν έχει οριστεί καταληκτική ημερομηνία υποβολής δικαιολογητικών.",1);
			} else {
				$row = $result->fetch_assoc();
				if ($row['until'] < $row['current']) {
					return true;
				} else {
					return false;
				}
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}

class Employee extends User {
	private $phone;

	function __construct() {
		parent::__construct();
		$this->phone = $_SERVER['telephoneNumber'];
	}

	function getID() {
		try {
			if ($this->isRegistered()) {
				$database = new DB();
				$conn = $database->connect();
  				$sql = "SELECT id FROM employee WHERE username='".$this->getUsername()."'";
  				$result = $conn->query($sql);
				if ($result->num_rows == 0) {
					$conn = $database->connect();
					throw new Exception("Μη εγγεγραμμένος υπάλληλος.",2);
				} else {
					$row = $result->fetch_assoc();
					$id = $row['id'];
					$conn = $database->connect();
					return $id;
  				}
  			} else {
  				throw new Exception("Μη εγγεγραμμένος υπάλληλος.",2);
  			}
  		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	function isRegistered() {
		try {
			$database = new DB();
			$conn = $database->connect();
			if ($this->isEmployee()) {
				$sql = "SELECT id FROM employee WHERE username='".$this->getUsername()."'";
				$result = $conn->query($sql);
				if ($result->num_rows == 0) {
					$database->disconnect($conn);
					return false;
				} else {
					$database->disconnect($conn);
					return true;
				}
			} else {
				$database->disconnect($conn);
  				throw new Exception("Δεν έχετε συνδεθεί ως υπάλληλος.",2);
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}

class Praxis {

	function getTitleByAk($ak) {
		try {
			$database = new DB();
			$conn = $database->connect();
			$sql = "SELECT title FROM praxis WHERE ak=".$ak;
			$result = $conn->query($sql);
			$row = mysqli_fetch_assoc($result);
			$title = $row['title'];
			$database->disconnect($conn);
			return $title;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	function getTitleByID($id) {
		try {
			$database = new DB();
			$conn = $database->connect();
			$sql = "SELECT title FROM praxis WHERE id=".$id;
			$result = $conn->query($sql);
			$row = mysqli_fetch_assoc($result);
			$title = $row['title'];
			$database->disconnect($conn);
			return $title;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	function getOptions($id) {
		try {
			$database = new DB();
			$conn = $database->connect();
			$options="";
			$sql = "SELECT id,title,ak FROM praxis ORDER BY ak DESC";
			$result = $conn->query($sql);
			while($row = $result->fetch_assoc()) {
				if ($row['id']==$id){
					$options.='<option value="'.$row['id'].'" selected>'.$row['title'].'</option>';
				} else {
					$options.='<option value="'.$row['id'].'">'.$row['title'].'</option>';
				}

			}
			$database->disconnect($conn);
			return $options;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	function getSchoolOptions($employeeID) {
		try {
			$database = new DB();
			$conn = $database->connect();
			$options="";
			$sql = "SELECT school.id AS id,school.title AS title FROM school INNER JOIN viewSchool ON school.id = viewSchool.school WHERE viewSchool.employee = ".$employeeID." ORDER BY school.id";
			//$sql = "SELECT id,title FROM school ORDER BY id";
			$result = $conn->query($sql);
			while($row = $result->fetch_assoc()) {
				$options.='<option value="'.$row['id'].'">'.$row['title'].'</option>';
			}
			$database->disconnect($conn);
			return $options;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}

	}

}

class Upload {

	function getStudentUploadedFiles($student) {
		try {
			$database = new DB();
			$conn = $database->connect();
			$files;
			$sql = "SELECT file.id as fileID,file.filename as filename,file.descr as fileDescr,filepath,uploadedTime,filetype,size FROM (upload INNER JOIN student ON upload.student = student.id) INNER JOIN file ON upload.file = file.id WHERE upload.student =".$student." ORDER BY file.id";
			$result = $conn->query($sql);
			if ($result->num_rows == 0) {
				$database->disconnect($conn);
				throw new Exception("Δεν υπάρχουν καταχωρημένα τα απαιτούμενα στοιχεία σχετικά με τα αρχεία υποβολής.",2);
			} else {
				$aa=0;
				while ($row=$result->fetch_assoc()) {
					$aa++;

					$files.='<tr><td>'.$aa.'</td>';
					if (is_null($row['uploadedTime'])){
						//$files.="<td>null</td>";

						$files.='<form method="post" enctype="multipart/form-data" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">
									<td>
										<div class="custom-file">
                          					<input type="file" onchange="ValidateSize(\''.$row['filename'].'\',\'submit_'.$row['filename'].'\')" class="custom-file-input" id="'.$row['filename'].'" name="'.$row['filename'].'">
                          					<input type="hidden" value="'.$row['filename'].'" name="filename">
                          					<label class="custom-file-label small" for="'.$row['filename'].'">'.$row['fileDescr'].'</label>
                        				</div>
                        			</td>
                        			<td></td>
                        			<td>
                        				<div class="d-flex justify-content-center">
                    						<button type="submit" id="submit_'.$row['filename'].'" name="submit_'.$row['filename'].'" class="btn btn-success"><i class="fas fa-save"></i> Αποθήκευση</button>
                  						</div>
                        			</td>
                        			<td></td>
                        		</form>';
					} else {
						$uploadedDateTime = $row['uploadedTime'];
						$uploadedDateTime = explode(' ',$uploadedDateTime);
						$uploadedDate = $uploadedDateTime[0];
						$uploadedTime = $uploadedDateTime[1];
						$uploadedDate = explode('-',$uploadedDate);
						$uploadedDateTime = $uploadedDate[2].'-'.$uploadedDate[1].'-'.$uploadedDate[0].' '.$uploadedTime;
						$files.="	<td>".$row['fileDescr']."</td>
									<td>".$uploadedDateTime."</td>
									<td>
										<div class='d-flex justify-content-center'>
											<form action='download.php' method='post' enctype='multipart/form-data'>
												<input type='hidden' value='".$row['filepath']."' name='path' id='path'>
												<button type='submit' class='btn btn-success'><i class='fas fa-download'></i> Λήψη</button>
											</form>
										</div>
									</td>";
						$files.="	<td>
										<div class='d-flex justify-content-center'>
											<form action='delete.php' method='post' enctype='multipart/form-data'>
												<input type='hidden' value='".$row['fileID']."' name='fileID' id='fileID'>
												<button type='submit' class='btn btn-danger'><i class='fas fa-trash'></i> Διαγραφή</button>
											</form>
										</div>
									</td>";
					}
					$files.="</tr>";
				}
				$database->disconnect($conn);
				return $files;
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}

	}

	function getSchoolUploadedFiles($school,$praxis) {
		try {
			$database = new DB();
			$conn = $database->connect();
			$sql = "SELECT title FROM school WHERE id=".$school;
  			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			$title = $row['title'];

			echo '<div class="border-bottom py-3 my-3">
                     <h4>Σχολή '.$title.'</h4>
                  </div>';
            echo '<div class="shadow p-4 mb-4 bg-white">';
            echo '	<!-- Nav tabs -->
					<ul class="nav nav-tabs">
  						<li class="nav-item">
    						<a class="nav-link active" data-toggle="tab" href="#home">Έγγραφα Έναρξης ΠΑ</a>
  						</li>
  						<li class="nav-item">
    						<a class="nav-link" data-toggle="tab" href="#menu1">Έγγραφα Λήξης ΠΑ</a>
  						</li>
					</ul>';
			echo '	<!-- Tab panes -->
					<div class="tab-content">
  						<div class="tab-pane container active" id="home">';

			$sql = "SELECT file.id as fileID,file.filename as filename,file.descr as fileDescr,filepath,uploadedTime,filetype,size,student.lastname as lastname,student.firstname as firstname, student.fathername as fathername, student.city as city, student.street as street, student.tk as tk, student.idnumber as idnumber, student.idissuedate as idissuedate, student.taxid as taxid, student.taxdivision as taxdivision, student.school as school, student.gender as gender, student.username as username FROM (upload INNER JOIN student ON upload.student = student.id) INNER JOIN file ON upload.file = file.id WHERE student.school =".$school." and student.praxis =".$praxis." ORDER BY student.lastname, student.firstname, fileID";
			$result = $conn->query($sql);
			if ($result->num_rows == 0) {
				$database->disconnect($conn);

				echo "<p>Δεν υπάρχουν δεδομένα</p>";
			} else {
				$sql1 ="SELECT shortdescr from file where period = 1";
				$result1 = $conn->query($sql1);
				echo '
                      	<div class="table-responsive">
                      		<table class="table mb-0">
                        	<thead>
                          		<tr>
                            		<th>Α/Α</th>
                            		<th>Επώνυμο</th>
                            		<th>Όνομα</th>
                            		<th>
                              			<div class="d-flex justify-content-center">Σύμβαση</div>
                            		</th>';
                while ($row1=$result1->fetch_assoc()) {
                	echo '<th>
                             <div class="d-flex justify-content-center">'.$row1['shortdescr'].'</div>
                          </th>';
                      }
                            		/*<th>
                              			<div class="d-flex justify-content-center">Ταυτότητα</div>
                            		</th>
                            		<th>
                              			<div class="d-flex justify-content-center">ΕΦΚΑ</div>
                            		</th>
                            		<th>
                              			<div class="d-flex justify-content-center">ΑΦΜ</div>
                            		</th>
                            		<th>
                              			<div class="d-flex justify-content-center">ΙΒΑΝ</div>
                            		</th>
                            		<th>
                              			<div class="d-flex justify-content-center">Βιβλιάριο</div>
                            		</th>
									<th>
                              			<div class="d-flex justify-content-center">Ε3.5 Έναρξης</div>
                            		</th>
									<th>
                              			<div class="d-flex justify-content-center">Ε3.5 Λήξης</div>
                            		</th>*/
                echo '          		</tr>
                        	</thead>
                        <tbody>';
				$aa=0;
				$prev="";
				echo "<tr>";
				while ($row=$result->fetch_assoc()) {
					if ($prev!=$row['username']) {
						$aa++;
						echo "</tr><tr><td>".$aa."</td><td>".$row['lastname']."</td>"."<td>".$row['firstname']."</td>";
						echo "<td>
								<div class='d-flex justify-content-center'>
									<button type='button' class='btn btn-success' data-toggle='modal' data-target='#{$row['username']}Modal'>
	   	 								<i class='fas fa-download'></i>
	  								</button>
  								</div>
  								<!-- The Modal -->
								  <div class='modal fade' id='{$row['username']}Modal'>
								    <div class='modal-dialog modal-dialog-centered modal-lg'>
								      <div class='modal-content'>

								        <!-- Modal Header -->
								        <div class='modal-header'>
								          <h4 class='modal-title'><div class='text-info'>Σύμβαση Πρακτικής Άσκησης</div></h4>
								          <button type='button' class='close' data-dismiss='modal'>&times;</button>
								        </div>

								        <!-- Modal body -->
								        <div class='modal-body'>
								          	<form method='post' enctype='multipart/form-data' action='tfpdf/ex.php'>
								          		<div class='form-row mt-3'>
								          			<div class='col-md-2 form-group'>
								          				<label for='starts'><b>Από</b></label>
								          				<input type='text' class='form-control' placeholder='Από' name='starts' id='starts' required>
													</div>
													<div class='col-md-2 form-group'>
														<label for='ends'><b>Έως</b></label>
								          				<input type='text' class='form-control' placeholder='Έως' name='ends' id='ends' required>
													</div>
													<div class='col-md-8 form-group'>
														<label for='company'><b>Εταιρεία</b></label>
								          				<input type='text' class='form-control' placeholder='Εταιρεία' name='company' id='company' required>
													</div>
												</div>
								          		<div class='form-row mt-3'>
								          			<div class='col-md-4 form-group'>
								          				<label for='lastname'><b>Επώνυμο</b></label>
								          				<input type='text' class='form-control' name='lastname' id='lastname' value='{$row['lastname']}'>
													</div>
													<div class='col-md-4 form-group'>
														<label for='firstname'><b>Όνομα</b></label>
								          				<input type='text' class='form-control' name='firstname' id='firstname' value='{$row['firstname']}'>
													</div>
													<div class='col-md-4 form-group'>
														<label for='fathername'><b>Πατρώνυμο</b></label>
								          				<input type='text' class='form-control' name='fathername' id='fathername' value='{$row['fathername']}'>
													</div>
												</div>
												<div class='form-row mt-3'>
								          			<div class='col-md-4 form-group'>
								          				<label for='city'><b>Πόλη</b></label>
								          				<input type='text' class='form-control' name='city' id='city' value='{$row['city']}'>
													</div>
													<div class='col-md-5 form-group'>
														<label for='street'><b>Οδός</b></label>
								          				<input type='text' class='form-control' name='street' id='street' value='{$row['street']}'>
													</div>
													<div class='col-md-3 form-group'>
														<label for='tk'><b>Τ.Κ.</b></label>
								          				<input type='text' class='form-control' name='tk' id='tk' value='{$row['tk']}'>
													</div>
												</div>
												<div class='form-row mt-3'>
								          			<div class='col-md-3 form-group'>
								          				<label for='idnumber'><b>Αρ. Ταυτ/τας</b></label>
								          				<input type='text' class='form-control' name='idnumber' id='idnumber' value='{$row['idnumber']}'>
													</div>
													<div class='col-md-3 form-group'>
														<label for='idissuedate'><b>Ημ/νία Έκδοσης</b></label>
								          				<input type='text' class='form-control' name='idissuedate' id='idissuedate' value='{$row['idissuedate']}'>
													</div>
													<div class='col-md-3 form-group'>
														<label for='taxid'><b>ΑΦΜ</b></label>
								          				<input type='text' class='form-control' name='taxid' id='taxid' value='{$row['taxid']}'>
													</div>
													<div class='col-md-3 form-group'>
														<label for='taxdivision'><b>ΔΟΥ</b></label>
								          				<input type='text' class='form-control' name='taxdivision' id='taxdivision' value='{$row['taxdivision']}'>
													</div>
												</div>
												<div>
													<input type='hidden' name='school' value='{$row['school']}'>
													<input type='hidden' name='gender' value='{$row['gender']}'>
												</div>
												<div class='d-flex flex-row-reverse mt-3'>
                          							<div class='btn-group' role='group'>
                            							<button type='button' class='btn btn-danger' data-dismiss='modal'>Άκυρο</button>
                            							<button type='submit' class='btn btn-info mx-2'>Εκτύπωση</button>
                          							</div>
                        						</div>
											</form>
								        </div>
								      </div>
								    </div>
								  </div>
							  </td>";
						$prev=$row['username'];
					}
					if ($row['uploadedTime']==null) {
						echo "<td><div class='d-flex justify-content-center'>---</div></td>";
					} else {
					echo "<td>
										<div class='d-flex justify-content-center'>
											<form action='download.php' method='post' enctype='multipart/form-data'>
												<input type='hidden' value='".$row['filepath']."' name='path' id='path'>
												<button type='submit' class='btn btn-success'><i class='fas fa-download'></i></button>
											</form>
										</div>
									</td>";}

				}
				echo "</tr></tbody></table></div>";
				$database->disconnect($conn);
			}

			echo '</div>
  						<div class="tab-pane container fade" id="menu1">...</div>
					</div>';
			echo '</div>';
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}

	}
}

class DB {
	private $servername;
	private $dbusername;
	private $dbpassword;
	private $database;

	function __construct() {
		$this->loadConfig();
	}

	function loadConfig () {
		if ($file = file_get_contents('/usr/local/etc/config.json')) {
			$obj = json_decode($file);
			$this->servername = $obj->servername;
			$this->dbusername = $obj->dbusername;
			$this->dbpassword = $obj->dbpassword;
			$this->database = $obj->db;
		} else {
			throw new Exception("Σφάλμα κατά την παραμετροποίηση.");
		}
	}

	function connect() {

		$conn = new mysqli($this->servername, $this->dbusername, $this->dbpassword, $this->database);
		if ($conn->connect_error) {
			$this->disconnect($conn);
			throw new Exception("Αδυναμία σύνδεσης στη βάση.",1);
		} else {
			return $conn;
		}
	}

	function disconnect($conn) {
		$conn->close();
	}

}
?>
