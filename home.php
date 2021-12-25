<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Menu</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
<div class="row">

    <div class="offset-3 col-6">
    <h3 class="text-center mt-5">Academic Year <?php echo date("Y"); ?></h3>
    <?php  if($_SESSION['Position'] == "Student") {?>
    <h5 class="py-3">Student Options</h5>
    <div class="row">
        <div class="col">
            <a href="search_schedule_tutor.php" class="btn btn-outline-primary">Search/Schedule Tutor</a>
        </div>
        <div class="col">
            <a href="student_recommendation.php" class="btn btn-outline-success">Rate a Tutor</a>
        </div>
    </div>
    <?php  } ?>
    <?php  if($_SESSION['Position'] == "Tutor") {?>
    <h5 class="py-3">Tutor Options</h5>
    <div class="row">
        <div class="col">
            <a href="tutor_application.php" class="btn btn-outline-primary">Apply</a>
        </div>
        <div class="col">
            <a href="schedules.php" class="btn btn-outline-success">Find my Schedule</a>
        </div>
    </div>
    <?php  } ?>
    <?php  if($_SESSION['Position']== "Professor") {?>
    <h5 class="py-3">Professor Options</h5>
    <div class="row">
        <div class="col">
            <a href="professor_recommendation.php" class="btn btn-outline-primary">Add recommendation</a>
        </div>
    </div>
    <?php  } ?>
    <?php  if($_SESSION['Position']=="Administrator") {?>
    <h5 class="py-3">Administrator Options</h5>
    <div class="row">
        <div class="col">
            <a href="summary_1.php" class="btn btn-outline-primary">Summary 1</a>
        </div>
        <div class="col">
            <a href="summary_2.php" class="btn btn-outline-success">Summary 2</a>
        </div>
    </div>
    <?php  } ?>
    <div class="row py-5">
        <div class="col text-right">
            <a href="logout.php" class="btn btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Exit</a>
        </div>
    </div>
    </div>
</div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
</body>
</html>