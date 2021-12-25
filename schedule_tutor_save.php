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
   
    //get schudule
    $stmt = $con->prepare("SELECT * FROM availabilities  WHERE id =:id ");
    $stmt->bindParam(':id', $id);
    $id = $_POST['Tutor_schudule'] ;
    $stmt->execute();
    $current_schedule = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]; 
    var_export($current_schedule);


    //insert schedule
     $stmt = $con->prepare("INSERT INTO `schedules` (`Day`,`Time`, `Student`,`Course`, `Tutor`, `Year`, `Semester`) VALUES ( :Day,:Time,:Student,:Course,:Tutor,:year,:semester)");
     $stmt->bindParam(':Day', $Day);
     $Day = $current_schedule['day'] ;
     $stmt->bindParam(':Time', $Time);
     $Time = $current_schedule['time'] ;
     $stmt->bindParam(':Tutor', $Tutor);
     $Tutor = $current_schedule['Tutor'] ;
     $Student = $_SESSION['Username'] ;
     $stmt->bindParam(':Student', $Student);
     $Course = $_POST['Course'] ;
     $stmt->bindParam(':Course', $Course);
     $stmt->bindParam(':year', $year);
     $stmt->bindParam(':semester', $semester);
     $stmt->execute();


  //redirect to main menu
  header('Location: home.php');
  } catch(PDOException $e) {
    echo "Erreur de connection a la base de données: " . $e->getMessage();
  }
  $con = null;
?>