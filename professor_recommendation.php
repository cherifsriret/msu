<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != "Professor") {
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
    <title>Professor Recommendation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>

    </style>
</head>
<body>
<div class="row">
    <div class="col px-5 ">
        <h3 class="text-center mt-5">Professor Recommendation</h3>
        
    <form method="Post" action="add_professor_recommendation.php">
        <div class="row">
                    <div class="col-8 offset-2">
                        <div class="mb-3 row">
                            <label for="staticEmail" class="col-sm-3 col-form-label">Student ID</label>
                            <div class="col-sm-5">
                                <input class="form-control" name="ID" id="ID"  value="<?php echo $_POST['ID']??""; ?>">
                            </div>
                        </div>
                    </div>
                </div>
        <div class="row">
            <div class="col-8 offset-2">
                <div class="mb-3 row">
                    <label for="Description" class="col-sm-3 col-form-label">Description Evaluation</label>
                    <div class="col-sm-9">
                       <textarea class="form-control" name="Description" id="Description" rows="5"></textarea>
                    </div>
                </div>
            </div>
        </div> 
        <div class="row">
            <div class="col-8 offset-2">
                <div class="mb-3 row">
                    <label for="Description" class="col-sm-3 col-form-label">Numeric Evaluation</label>
                    <div class="col-sm-9">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="Evaluation" id="exampleRadios1" value="4" checked>
                            <label class="form-check-label" for="exampleRadios1">
                                4 Highly recommend
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="Evaluation" id="exampleRadios2" value="3">
                            <label class="form-check-label" for="exampleRadios2">
                                3 Recommend
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="Evaluation" id="exampleRadios2" value="2">
                            <label class="form-check-label" for="exampleRadios2">
                                2 Recommend with reservations
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="Evaluation" id="exampleRadios2" value="1">
                            <label class="form-check-label" for="exampleRadios2">
                                1 Do Not Recommend
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div> 

        <div class="row">
            <div class="col-8 offset-2">
            <button type="submit" class="col-sm-3 btn btn-outline-success">Ok</button>
            </div>
        </div>
        </form>
    </div>
</div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
</body>
</html>