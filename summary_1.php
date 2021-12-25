<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != "Administrator") {
	header('Location: login.php');
	exit;
}
$course_reports =[];



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
        $course_reports = $stmt->fetchAll(PDO::FETCH_ASSOC);      
            
      } catch(PDOException $e) {
        echo "Erreur de connection a la base de données: " . $e->getMessage();
      }
      $con = null;


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Courses with Student/Tutor Summart Data for current Academic Year</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>

    </style>
</head>
<body>
<div class="row">
    <div class="col px-5 py-5">
        <h3 class="text-center mt-5">List Courses with Student/Tutor Summart Data for current Academic Year</h3>
        
    <form method="Post" action="summary_1.php">

        <div class="row p-5">
        <b class="col">Academic Year <?php echo date('Y'); ?></b>    
        <div class="col form-group">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">Fall</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="Filters[]" value="Fall" <?php if(isset($_POST['Filters']) && in_array('Fall',$_POST['Filters'])) echo "checked"; ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">Spring</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="Filters[]" value="Spring"  <?php if(isset($_POST['Filters']) && in_array('Spring',$_POST['Filters'])) echo "checked"; ?>>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="inlineCheckbox1">Summer</label>
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="Filters[]" value="Summer" <?php if(isset($_POST['Filters']) && in_array('Summer',$_POST['Filters'])) echo "checked"; ?> >
                    </div>
        </div>
        <div class="col">
                <button type="submit" class="col-sm-3 btn btn-outline-success">Ok</button>
            </div>
        </div>
        </form>

       
        <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Course</th>
                        <th scope="col">Semester</th>
                        <th scope="col"># Students</th>
                        <th scope="col"># Tutors</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                     
                    if(isset($_POST['Filters'])){

                  
                    foreach ($course_reports as $key => $course) {
                        $first=true;
                        $total_students = 0;
                        $total_tutors = 0;
                        foreach ($_POST["Filters"] as $key => $filter) {
                           //calculate student and tutors

                           $students = 0;
                           $tutors = 0;

                           $year = date('Y');

                       try {
                           $con = new PDO("mysql:host=$DATABASE_HOST;dbname=$DATABASE_NAME", $DATABASE_USER, $DATABASE_PASS);
                           $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                           //get course tutor 
                           $stmt = $con->prepare("SELECT Tutor FROM `gta`  WHERE Course =:Course AND Year=:year AND Semester=:semester");
                           $stmt->bindParam(':Course', $Course);
                           $stmt->bindParam(':year', $year);
                           $stmt->bindParam(':semester', $filter);
                           $Course = $course['Number'];
                           $stmt->execute();
                           $tutors = $stmt->rowCount();
                           $total_tutors +=$tutors ;

                            //get course students 
                            $stmt = $con->prepare("SELECT Student FROM `schedules`  WHERE Course =:Course AND Year=:year AND Semester=:semester");
                            $stmt->bindParam(':Course', $Course);
                            $stmt->bindParam(':year', $year);
                            $stmt->bindParam(':semester', $filter);
                            $Course = $course['Number'];
                            $stmt->execute();
                            $students = $stmt->rowCount();
                            $total_students +=$students ;
   
                       } catch(PDOException $e) {
                           echo "Erreur de connection a la base de données: " . $e->getMessage();
                       }
                       $con = null;
                        
                      ?>
                    <tr>
                        <td><?php if($first==true){ echo $course['School'].' '.$course['Number']; $first = false;}?></td>
                        <td><?php echo $filter; ?></td>
                        <td><?php echo $students; ?></td>
                        <td><?php echo $tutors; ?></td>
                    </tr>
                      <?php
                    }
                    ?>
                    <tr>
                        <td></td>
                        <td>Total </td>
                        <td><?php echo $total_students; ?></td>
                        <td><?php echo $total_tutors; ?></td>
                    </tr>

                    <?php
                }
            }
                    ?>
                </tbody>
            </table>
            <div class="row p-5">
            <div class="col">
                <a href="home.php" class="btn btn-outline-danger">Ok</a>
            </div>
        </div>

    </div>
</div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
</body>
</html>