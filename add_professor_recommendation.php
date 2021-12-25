<?php
session_start();

try {
$semester = "Fall";
$year = date('Y');
if(date('Y-m-d h:i:s')>date('Y').'06-21 00:00:00' && date('Y-m-d h:i:s')<date('Y').'09-20 23:59:59')
    $semester = "Summer";
else if(date('Y-m-d h:i:s')>date('Y').'03-21 00:00:00' && date('Y-m-d h:i:s')<date('Y').'06-20 23:59:59')
    $semester = "Spring";

    // Change this to your connection info.
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'mytutors';
    // Try and connect using the info above.
    $con = new PDO("mysql:host=$DATABASE_HOST;dbname=$DATABASE_NAME", $DATABASE_USER, $DATABASE_PASS);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //get professor recommendation 
    $stmt = $con->prepare("SELECT * FROM professor_recommendations  WHERE Professor =:UserName AND Tutor=:Tutor AND Year=:year AND Semester=:semester");
    $stmt->bindParam(':Tutor', $Tutor);
    $Tutor = $_POST['ID'] ;
    $UserName = $_SESSION['Username'] ;
    $stmt->bindParam(':UserName', $UserName);
    $stmt->bindParam(':year', $year);
    $stmt->bindParam(':semester', $semester);
    $stmt->execute();
    if($stmt->rowCount()>0){

    //update
    $stmt = $con->prepare("Update  `professor_recommendations` set 	Evaluation=:Evaluation , Description=:Description   WHERE Professor =:UserName AND Tutor=:Tutor AND Year=:year AND Semester=:semester");
    $stmt->bindParam(':Evaluation', $Evaluation);
    $Evaluation = $_POST['Evaluation'] ;
    $stmt->bindParam(':Description', $Description);
    $Description = $_POST['Description'] ;
    $stmt->bindParam(':Tutor', $Tutor);
    $Tutor = $_POST['ID'] ;
    $UserName = $_SESSION['Username'] ;
    $stmt->bindParam(':UserName', $UserName);
    $stmt->bindParam(':year', $year);
    $stmt->bindParam(':semester', $semester);
    $stmt->execute();
    }
    else{

        //insert
        $stmt = $con->prepare("INSERT INTO `professor_recommendations` (`Evaluation`,`Description`, `Professor`, `Tutor`, `Year`, `Semester`) VALUES ( :Evaluation,:Description,:UserName,:Tutor,:year,:semester)");
        $stmt->bindParam(':Evaluation', $Evaluation);
        $Evaluation = $_POST['Evaluation'] ;
        $stmt->bindParam(':Description', $Description);
        $Description = $_POST['Description'] ;
        $stmt->bindParam(':Tutor', $Tutor);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':semester', $semester);
        $Tutor = $_POST['ID'] ;
        $UserName = $_SESSION['Username'] ;
        $stmt->bindParam(':UserName', $UserName);
        $stmt->execute();
    }
  //redirect to main menu
  header('Location: home.php');
  } catch(PDOException $e) {
    echo "Erreur de connection a la base de données: " . $e->getMessage();
  }
  $con = null;
?>