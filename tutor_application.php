<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != "Tutor") {
	header('Location: login.php');
	exit;
}
$courses = [];
$current_user = null;
$availabilities=[];


try {
    // Change this to your connection info.
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'mytutors';
    // Try and connect using the info above.
    $con = new PDO("mysql:host=$DATABASE_HOST;dbname=$DATABASE_NAME", $DATABASE_USER, $DATABASE_PASS);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     //get all courses
     $stmt = $con->prepare("select * from  `courses`");
     $stmt->execute();
     $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
     //get user tutor infos
     $stmt = $con->prepare("select * from  `users`  WHERE Username =:UserName");
     $stmt->bindParam(':UserName', $UserName);
     $UserName = $_SESSION['Username'] ;
     $stmt->execute();
     $current_user = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
     //get availabilities of user
     $stmt = $con->prepare("select * from  `availabilities`  WHERE Tutor =:UserName");
     $stmt->bindParam(':UserName', $UserName);
     $UserName = $_SESSION['Username'] ;
     $stmt->execute();
     $availabilities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //  var_dump(array_filter($availabilities, function($v) {
    //     return  $v['day'] == 'Mon' && $v['time'] == '9pm';
    // }, ARRAY_FILTER_USE_BOTH));


  } catch(PDOException $e) {
    echo "Erreur de connection a la base de données: " . $e->getMessage();
  }
  $con = null;


  function multi_array_search($array, $search) {
    // Create the result array
    $result = array();

    // Iterate over each array element
    foreach ($array as $key => $value){

      // Iterate over each search condition
      foreach ($search as $k => $v){

        // If the array element does not meet the search condition then continue to the next element
        if (!isset($value[$k]) || $value[$k] != $v){
          continue 2;
        }
      }
      // Add the array element's key to the result array
      $result[] = $key;
    }

    // Return the result array
    return $result;
  }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Application</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        fieldset.custom-border {
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;
    -webkit-box-shadow: 0px 0px 0px 0px #000;
    box-shadow: 0px 0px 0px 0px #000;
}

legend.custom-border {
    width: auto;
    padding: 0 10px;
    border-bottom: none;
}
    </style>
</head>
<body>
<div class="row">
    <div class="col px-5 ">
        <h3 class="text-center mt-5">Tutor Application</h3>
        
    <form method="Post" action="application.php">
        <div class="card my-5">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-4 col-form-label">ID</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="ID" id="ID" disabled value="<?php echo $current_user['Username']; ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="FirstName" class="col-sm-4 col-form-label">First Name</label>
                            <div class="col-sm-7">
                            <input class="form-control" name="FirstName" id="FirstName" value="<?php echo $current_user['FirstName']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="LastName" class="col-sm-4 col-form-label">Last Name</label>
                            <div class="col-sm-7">
                            <input class="form-control" name="LastName" id="LastName" value="<?php echo $current_user['LastName']; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="Email" class="col-sm-4 col-form-label">Email</label>
                            <div class="col-sm-7">
                            <input class="form-control" name="Email" id="Email" value="<?php echo $current_user['Email']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="Phone" class="col-sm-4 col-form-label">Phone</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="Phone" id="Phone" value="<?php echo $current_user['Phone']; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="GPA" class="col-sm-4 col-form-label">GPA</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="GPA" id="GPA" value="<?php echo $current_user['GPA']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3 row">
                            <div class="col-sm-7 offset-sm-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="Graduation" id="exampleRadios1" value="0" <?php if (!$current_user['Gradulate']) echo "checked"; ?>>
                                    <label class="form-check-label" for="exampleRadios1">
                                       Undergraduate
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="Graduation" id="exampleRadios2" value="1"<?php if ($current_user['Gradulate']) echo "checked"; ?>>
                                    <label class="form-check-label" for="exampleRadios2">
                                        Graduate
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
            </div>
        </div>
        <h5 class="my-2">Courses for Tutoring</h5>
        <div class="mb-3 row">
            <div class="col-sm-4">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">School</th>
                        <th scope="col">Number</th>
                        <th scope="col">GTA</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $key => $course) {
                        $checked = false;

                        try {
                            $con = new PDO("mysql:host=$DATABASE_HOST;dbname=$DATABASE_NAME", $DATABASE_USER, $DATABASE_PASS);
                            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            //get course tutor 
                            $stmt = $con->prepare("select * from  `gta`  WHERE Tutor =:UserName AND Course=:Course_number");
                            $stmt->bindParam(':UserName', $UserName);
                            $UserName = $_SESSION['Username'] ;
                            $stmt->bindParam(':Course_number', $Course_number);
                            $Course_number = $course['Number'] ;
                            $stmt->execute();
                            $checked = $stmt->rowCount() > 0;
                          } catch(PDOException $e) {
                            echo "Erreur de connection a la base de données: " . $e->getMessage();
                          }
                          $con = null;
                      ?>
                    <tr>
                        <td><?php echo $course['School']; ?></td>
                        <td><?php echo $course['Number']; ?></td>
                        <td>
                        <div>
                            <input class="form-check-input" <?php if($checked) echo "checked"; ?> type="checkbox" name="Courses[]" value="<?php echo $course['Number']; ?>">
                        </div>
                        </td> 
                    </tr>
                      <?php
                    }
                    ?>
                  
                </tbody>
                </table>
            </div>
            <label for="staticEmail" class="col-sm-8 col-form-label"><b>Check the GTA box if you have been a graduate TA for the course</b></label>
        </div>
        
        <h5>Available Days/Times</h5>
            <fieldset class="custom-border">
                <legend class="custom-border">Monday</legend>
                <div class="form-group">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">9am</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Mon][]" value="9am" <?php if(count( multi_array_search($availabilities, array('day' => 'Mon', 'time' => '9am')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">10am</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Mon][]" value="10am"  <?php if(count( multi_array_search($availabilities, array('day' => 'Mon', 'time' => '10am')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">11am</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Mon][]" value="11am"  <?php if(count( multi_array_search($availabilities, array('day' => 'Mon', 'time' => '11am')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">12am</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Mon][]" value="12am"  <?php if(count( multi_array_search($availabilities, array('day' => 'Mon', 'time' => '12am')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">1pm</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Mon][]" value="1pm"  <?php if(count( multi_array_search($availabilities, array('day' => 'Mon', 'time' => '1pm')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">2pm</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Mon][]" value="2pm"  <?php if(count( multi_array_search($availabilities, array('day' => 'Mon', 'time' => '2pm')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">3pm</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Mon][]" value="3pm"  <?php if(count( multi_array_search($availabilities, array('day' => 'Mon', 'time' => '3pm')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">4pm</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Mon][]" value="4pm"  <?php if(count( multi_array_search($availabilities, array('day' => 'Mon', 'time' => '4pm')))) echo "checked" ?>>
                    </div>
                </div>
            </fieldset>
            <fieldset class="custom-border">
                <legend class="custom-border">Tuesday</legend>
                <div class="form-group">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">9am</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Tue][]" value="9am" <?php if(count( multi_array_search($availabilities, array('day' => 'Tue', 'time' => '9am')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">10am</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Tue][]" value="10am" <?php if(count( multi_array_search($availabilities, array('day' => 'Tue', 'time' => '10am')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">11am</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Tue][]" value="11am" <?php if(count( multi_array_search($availabilities, array('day' => 'Tue', 'time' => '11am')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">12am</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Tue][]" value="12am" <?php if(count( multi_array_search($availabilities, array('day' => 'Tue', 'time' => '12am')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">1pm</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Tue][]" value="1pm" <?php if(count( multi_array_search($availabilities, array('day' => 'Tue', 'time' => '1pm')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">2pm</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Tue][]" value="2pm" <?php if(count( multi_array_search($availabilities, array('day' => 'Tue', 'time' => '2pm')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">3pm</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Tue][]" value="3pm" <?php if(count( multi_array_search($availabilities, array('day' => 'Tue', 'time' => '3pm')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">4pm</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Tue][]" value="4pm" <?php if(count( multi_array_search($availabilities, array('day' => 'Tue', 'time' => '4pm')))) echo "checked" ?>>
                    </div>
                </div>
            </fieldset>
            <fieldset class="custom-border">
                <legend class="custom-border">Wednesday</legend>
                <div class="form-group">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">9am</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Wed][]" value="9am" <?php if(count( multi_array_search($availabilities, array('day' => 'Wed', 'time' => '9am')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">10am</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Wed][]" value="10am" <?php if(count( multi_array_search($availabilities, array('day' => 'Wed', 'time' => '10am')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">11am</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Wed][]" value="11am" <?php if(count( multi_array_search($availabilities, array('day' => 'Wed', 'time' => '11am')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">12am</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Wed][]" value="12am" <?php if(count( multi_array_search($availabilities, array('day' => 'Wed', 'time' => '12am')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">1pm</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Wed][]" value="1pm" <?php if(count( multi_array_search($availabilities, array('day' => 'Wed', 'time' => '1pm')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">2pm</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Wed][]" value="2pm" <?php if(count( multi_array_search($availabilities, array('day' => 'Wed', 'time' => '2pm')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">3pm</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Wed][]" value="3pm" <?php if(count( multi_array_search($availabilities, array('day' => 'Wed', 'time' => '3pm')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">4pm</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Wed][]" value="4pm" <?php if(count( multi_array_search($availabilities, array('day' => 'Wed', 'time' => '4pm')))) echo "checked" ?>>
                    </div>
                </div>
            </fieldset>
            <fieldset class="custom-border">
                <legend class="custom-border">Thursday</legend>
                <div class="form-group">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">9am</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Thu][]" value="9am" <?php if(count( multi_array_search($availabilities, array('day' => 'Thu', 'time' => '9am')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">10am</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Thu][]" value="10am" <?php if(count( multi_array_search($availabilities, array('day' => 'Thu', 'time' => '10am')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">11am</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Thu][]" value="11am" <?php if(count( multi_array_search($availabilities, array('day' => 'Thu', 'time' => '11am')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">12am</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Thu][]" value="12am" <?php if(count( multi_array_search($availabilities, array('day' => 'Thu', 'time' => '12am')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">1pm</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Thu][]" value="1pm" <?php if(count( multi_array_search($availabilities, array('day' => 'Thu', 'time' => '1pm')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">2pm</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Thu][]" value="2pm" <?php if(count( multi_array_search($availabilities, array('day' => 'Thu', 'time' => '2pm')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">3pm</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Thu][]" value="3pm" <?php if(count( multi_array_search($availabilities, array('day' => 'Thu', 'time' => '3pm')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">4pm</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Thu][]" value="4pm" <?php if(count( multi_array_search($availabilities, array('day' => 'Thu', 'time' => '4pm')))) echo "checked" ?>>
                    </div>
                </div>
            </fieldset>
            <fieldset class="custom-border">
                <legend class="custom-border">Friday</legend>
                <div class="form-group">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">9am</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Fri][]" value="9am" <?php if(count( multi_array_search($availabilities, array('day' => 'Fri', 'time' => '9am')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">10am</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Fri][]" value="10am" <?php if(count( multi_array_search($availabilities, array('day' => 'Fri', 'time' => '10am')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">11am</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Fri][]" value="11am" <?php if(count( multi_array_search($availabilities, array('day' => 'Fri', 'time' => '11am')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">12am</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Fri][]" value="12am" <?php if(count( multi_array_search($availabilities, array('day' => 'Fri', 'time' => '12am')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">1pm</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Fri][]" value="1pm" <?php if(count( multi_array_search($availabilities, array('day' => 'Fri', 'time' => '1pm')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">2pm</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Fri][]" value="2pm" <?php if(count( multi_array_search($availabilities, array('day' => 'Fri', 'time' => '2pm')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">3pm</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Fri][]" value="3pm" <?php if(count( multi_array_search($availabilities, array('day' => 'Fri', 'time' => '3pm')))) echo "checked" ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">4pm</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="availability[Fri][]" value="4pm" <?php if(count( multi_array_search($availabilities, array('day' => 'Fri', 'time' => '4pm')))) echo "checked" ?>>
                    </div>
                </div>
            </fieldset>

        <div class="row">
            <div class="col">
                <button type="submit" class="btn btn-outline-primary">Ok</button>
            </div>
        </div>
    </form>
    </div>
</div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
</body>
</html>