<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != "Student") {
	header('Location: login.php');
	exit;
}

$courses = [];
$availabilities=[];
$tutors=[];


try {
    // Change this to your connection info.
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'mytutors';
    // Try and connect using the info above.
    $con = new PDO("mysql:host=$DATABASE_HOST;dbname=$DATABASE_NAME", $DATABASE_USER, $DATABASE_PASS);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     //get course info
     $stmt = $con->prepare("select * from  `courses` Where Number=:Course");
     $stmt->bindParam(':Course', $Course);
     $Course = $_GET['Course'];
     $stmt->execute();
     $course_obj = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
     $stmt = $con->prepare("SELECT `Users`.`FirstName`,`Users`.`LastName`,`Users`.`Email`,`availabilities`.`id`, `availabilities`.`Day`,`availabilities`.`Time` FROM `availabilities` inner join `Users` on `Users`.`Username` = `availabilities`.`Tutor` inner join `GTA` on `availabilities`.`Tutor` = `GTA`.`Tutor` inner join `Courses` on `Courses`.`Number` = `GTA`.`Course` WHERE `Courses`.`Number` = :Course Order By `availabilities`.`Tutor`");
     $stmt->bindParam(':Course', $Course);
     $stmt->execute();
     $availabilities = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Schudule a tutor for a Course</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
<div class="row">
    <div class="col px-5 ">
        <h3 class="text-center mt-5">Schudule a tutor for a Course</h3>
        <form action="schedule_tutor_save.php" method="POST">
            <h5 class="my-5 text-center">Select Your Tutor For <?php echo $course_obj['Number'].' '.$course_obj['School'] ?></h5>
            <input type="hidden" name="Course" value="<?php echo$course_obj['Number']; ?>">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Day</th>
                        <th scope="col">Time</th>
                        <th scope="col">Select</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($availabilities as $key => $tutor) {

                      ?>
                    <tr>
                        <td><?php echo $tutor['FirstName']; ?></td>
                        <td><?php echo $tutor['LastName']; ?></td>
                        <td><?php echo $tutor['Email']; ?></td>
                        <td><?php echo $tutor['Day']; ?></td>
                        <td><?php echo $tutor['Time']; ?></td>
                        <td>
                        <div>
                            <input class="form-check-input" type="radio" name="Tutor_schudule"value="<?php echo $tutor['id']; ?>" aria-label="...">
                        </div>
                        </td> 
                    </tr>
                      <?php
                    }
                    ?>
                </tbody>
            </table>
        
        <h5 class="my-5">NOTE : Only 1 box under the Select column may be checked</h5>
            <div class="row">
                <div class="col">
                    <button type="submit"class="btn btn-outline-primary">Ok</button>
                </div>
                <div class="col">
                    <a href="home.php" class="btn btn-outline-danger">Cancel</a>
                </div>
            </div>
        
        </form>
        
     
     
    </div>
</div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>

    <script>

    </script>

</body>
</html>