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
     //get all courses
     $stmt = $con->prepare("select * from  `courses`");
     $stmt->execute();
     $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
     if(isset($_POST['Course']))
     {
        $stmt = $con->prepare("SELECT `availabilities`.`Day`,`availabilities`.`Time` FROM `availabilities` inner join `Users` on `Users`.`Username` = `availabilities`.`Tutor` inner join `GTA` on `availabilities`.`Tutor` = `GTA`.`Tutor` inner join `Courses` on `Courses`.`Number` = `GTA`.`Course` WHERE `Courses`.`Number` = :Course GROUP By `availabilities`.`Day`,`availabilities`.`Time`");
        $stmt->bindParam(':Course', $Course);
        $Course = $_POST['Course'];
        $stmt->execute();
        $availabilities = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt = $con->prepare("SELECT `Users`.`Username`,`Users`.`FirstName`,`Users`.`LastName`,`Users`.`Email` FROM  `Users` inner join `GTA` on `Users`.`Username` = `GTA`.`Tutor` inner join `Courses` on `Courses`.`Number` = `GTA`.`Course` WHERE `Courses`.`Number` = :Course GROUP By `Users`.`Username`,`Users`.`FirstName`,`Users`.`LastName`,`Users`.`Email`");
        $stmt->bindParam(':Course', $Course);
        $Course = $_POST['Course'];
        $stmt->execute();
        $tutors = $stmt->fetchAll(PDO::FETCH_ASSOC);
     }

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
    <title>List Availables Tutors for a Course</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
<div class="row">
    <div class="col px-5 ">
        <h3 class="text-center mt-5">List Availables Tutors for a Course</h3>
        <form action="search_schedule_tutor.php" method="POST">
        <div class="mb-3 row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Course</label>
            <div class="col-sm-10">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">School</th>
                        <th scope="col">Number</th>
                        <th scope="col">#</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($courses as $key => $course) {
                      ?>
                    <tr>
                        <td><?php echo $course['School']; ?></td>
                        <td><?php echo $course['Number']; ?></td>
                        <td>
                        <div>
                            <input class="form-check-input course-radio" <?php if(isset($_POST['Course']) && $_POST['Course']==$course['Number'] ) echo "checked"; ?> type="radio" name="Course" value="<?php echo $course['Number']; ?>">
                        </div>
                        </td> 
                    </tr>
                      <?php
                    }
                    ?>
                </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <button type="submit" class="btn btn-outline-primary">Ok</button>
            </div>
        </div>
        </form>
        <h5 class="my-5">Availability: Note - tutor sessions can only be scheduled for 1 hour per week for given course</h5>
     
        <div class="mb-3 row">
            <div class="col-sm-10 offset-2">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Day</th>
                        <th scope="col">Time</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($availabilities as $key => $availability) {
                      ?>
                    <tr>
                        <td><?php echo $availability['Day']; ?></td>
                        <td><?php echo $availability['Time']; ?></td>
                    </tr>
                      <?php
                    }
                    ?>
                </tbody>
            </table>
            </div>
        </div>

        <form action="schudule_tutor.php" method="get">
        <h4 class="text-center my-5">Available Tutors</h4>
        <input type="hidden" name="Course" value="<?php echo $_POST['Course']??""; ?>">
        <table class="table">
                <thead>
                    <tr>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Avg Prof Rating</th>
                        <th scope="col"># Professors</th>
                        <th scope="col">Avg Student Rating</th>
                        <th scope="col"># Students</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($tutors as $key => $tutor) {
                        $professor_recommendations = null;
                        $student_recommendations = null;
                    try {
                        $con = new PDO("mysql:host=$DATABASE_HOST;dbname=$DATABASE_NAME", $DATABASE_USER, $DATABASE_PASS);
                        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        //get course tutor 
                        $stmt = $con->prepare("SELECT COUNT(*) as Professors,AVG(Evaluation) as professor_recommendations FROM `professor_recommendations`  WHERE Tutor =:Tutor");
                        $stmt->bindParam(':Tutor', $Tutor);
                        $Tutor = $tutor['Username'];
                        $stmt->execute();
                        $professor_recommendations = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];

                        $stmt = $con->prepare("SELECT COUNT(*) as Students,AVG(Evaluation) as student_recommendations FROM `student_recommendations`  WHERE Tutor =:Tutor");
                        $stmt->bindParam(':Tutor', $Tutor);
                        $Tutor = $tutor['Username'];
                        $stmt->execute();
                        $student_recommendations = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];


                    } catch(PDOException $e) {
                        echo "Erreur de connection a la base de données: " . $e->getMessage();
                    }
                    $con = null;



                      ?>
                    <tr>
                        <td><?php echo $tutor['FirstName']; ?></td>
                        <td><?php echo $tutor['LastName']; ?></td>
                        <td><?php echo $tutor['Email']; ?></td>
                        <td><?php echo number_format($professor_recommendations['professor_recommendations'],2) ; ?></td>
                        <td><?php echo $professor_recommendations['Professors']; ?></td>
                        <td><?php echo number_format($student_recommendations['student_recommendations'],2) ; ?></td>
                        <td><?php echo $student_recommendations['Students']; ?></td>
                    </tr>
                      <?php
                    }
                    ?>
                </tbody>
            </table>



        <div class="row">
            <div class="col">
                <button type="submit"class="btn btn-outline-primary">Schedule a Tutor</button>
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