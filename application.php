<?php
session_start();




try {
    // Change this to your connection info.
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'mytutors';
    // Try and connect using the info above.
    $con = new PDO("mysql:host=$DATABASE_HOST;dbname=$DATABASE_NAME", $DATABASE_USER, $DATABASE_PASS);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //update user infos
    $stmt = $con->prepare("Update  `users` set FirstName=:FirstName , LastName=:LastName , Email=:Email , Phone=:Phone , GPA=:GPA , Gradulate=:Graduation  WHERE Username =:UserName");
    $stmt->bindParam(':FirstName', $FirstName);
    $FirstName = $_POST['FirstName'] ;
    $stmt->bindParam(':LastName', $LastName);
    $LastName = $_POST['LastName'] ;
    $stmt->bindParam(':Email', $Email);
    $Email = $_POST['Email'] ;
    $stmt->bindParam(':Phone', $Phone);
    $Phone = $_POST['Phone'] ;
    $GPA = $_POST['GPA'] ;
    $stmt->bindParam(':GPA', $GPA);
    $Graduation = $_POST['Graduation'] ;
    $stmt->bindParam(':Graduation', $Graduation);
    $UserName = $_SESSION['Username'] ;
    $stmt->bindParam(':UserName', $UserName);
    $stmt->execute();

    //delete all gta courses
     $stmt = $con->prepare("Delete From `gta`  WHERE Tutor =:UserName");
     $UserName = $_SESSION['Username'] ;
     $stmt->bindParam(':UserName', $UserName);
     $stmt->execute();

    //attach new gta courses
    foreach ($_POST['Courses'] as $key => $course) {
        $stmt = $con->prepare("INSERT INTO `gta` (`Course`, `Tutor`) VALUES ( :course,:UserName)");
        $UserName = $_SESSION['Username'] ;
        $stmt->bindParam(':UserName', $UserName);
        $stmt->bindParam(':course', $course);
        $stmt->execute();
    }

    //delete all availabilites day time
    $stmt = $con->prepare("Delete From `availabilities`  WHERE Tutor =:UserName");
    $UserName = $_SESSION['Username'] ;
    $stmt->bindParam(':UserName', $UserName);
    $stmt->execute();

    //attach all availabilites day time
    foreach ($_POST['availability'] as $key_day => $day) {
      foreach ($day as $key => $hour) {
        $stmt = $con->prepare("INSERT INTO `availabilities` (`day`,`time`, `Tutor`) VALUES ( :key_day,:hour,:UserName)");
        $UserName = $_SESSION['Username'] ;
        $stmt->bindParam(':UserName', $UserName);
        $stmt->bindParam(':key_day', $key_day);
        $stmt->bindParam(':hour', $hour);
        $stmt->execute();
      }
       
       
    }

  //redirect to main menu
  header('Location: home.php');
    
    

  } catch(PDOException $e) {
    echo "Erreur de connection a la base de données: " . $e->getMessage();
  }
  $con = null;





?>