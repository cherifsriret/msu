<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != "Tutor") {
	header('Location: login.php');
	exit;
}
$my_schedules = [];
$current_user =null;

if(isset($_POST['ID']))
{

    try {
        // Change this to your connection info.
        $DATABASE_HOST = 'localhost';
        $DATABASE_USER = 'root';
        $DATABASE_PASS = '';
        $DATABASE_NAME = 'mytutors';
        // Try and connect using the info above.
        $con = new PDO("mysql:host=$DATABASE_HOST;dbname=$DATABASE_NAME", $DATABASE_USER, $DATABASE_PASS);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         //get schedules of user
         $stmt = $con->prepare("select `schedules`.`Day`, `schedules`.`Time`, `courses`.`Number`, `courses`.`School`, `student`.`FirstName`, `student`.`LastName`, `student`.`Email`, `student`.`Phone` from `schedules` inner join `Courses` on `schedules`.`Course` = `Courses`.`Number` inner join `Users` as `student`  on `schedules`.`Student` = `student`.`Username`  WHERE Tutor =:UserName");
         $stmt->bindParam(':UserName', $UserName);
         $UserName = $_POST['ID'] ;
         $stmt->execute();
         $my_schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
          //get user tutor infos
        $stmt = $con->prepare("select * from  `users`  WHERE Username =:UserName");
        $stmt->bindParam(':UserName', $UserName);
        $UserName = $_POST['ID'] ;
        $stmt->execute();
        $current_user = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];      
            
      } catch(PDOException $e) {
        echo "Erreur de connection a la base de données: " . $e->getMessage();
      }
      $con = null;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Tutor Schedule</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>

    </style>
</head>
<body>
<div class="row">
    <div class="col px-5 ">
        <h3 class="text-center mt-5">Find Tutor Schedule</h3>
        
    <form method="Post" action="schedules.php">
        <div class="row">
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-3 col-form-label">Enter Tutor ID</label>
                            <div class="col-sm-5">
                                <input class="form-control" name="ID" id="ID"  value="<?php echo $_POST['ID']??""; ?>">
                            </div>
                            <button type="submit" class="col-sm-3 btn btn-outline-success">Ok</button>
                        </div>
                    </div>
                </div>
                </form>
        <h5 class="my-5 text-center">Tutor Schedule for <?php echo $current_user['FirstName']??""; ?> <?php echo $current_user['LastName']??""; ?></h5>
        
        <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Day</th>
                        <th scope="col">Time</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Course</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($my_schedules as $key => $my_schedule) {
                      ?>
                    <tr>
                        <td><?php echo $my_schedule['Day']; ?></td>
                        <td><?php echo $my_schedule['Time']; ?></td>
                        <td><?php echo $my_schedule['FirstName']; ?></td>
                        <td><?php echo $my_schedule['LastName']; ?></td>
                        <td><?php echo $my_schedule['Email']; ?></td>
                        <td><?php echo $my_schedule['School'].' '.$my_schedule['Number']; ?></td>
                        
                    </tr>
                      <?php
                    }
                    ?>
                  
                </tbody>
            </table>

        <div class="row">
            <div class="col">
                <a href="home.php" class="btn btn-outline-primary">Ok</a>
            </div>
        </div>
    
    </div>
</div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
</body>
</html>