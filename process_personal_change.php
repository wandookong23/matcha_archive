<?php
include 'db_conn.php';
session_start();

$userid = $_SESSION['userid'];
$username = mysqli_real_escape_string($conn, $_POST['username']);
$userpw = $_POST['userpw'];
$userprofile = mysqli_real_escape_string($conn, $_POST['userprofile']);

if(!empty($_POST['userpw'])){

$hashed_pw = password_hash($userpw, PASSWORD_DEFAULT);
    $sql = "UPDATE users 
            SET name='$username',
                password='$hashed_pw',
                profile='$userprofile'
            WHERE id='$userid'";

}
else{
    $sql = "UPDATE users 
    SET name='$username',
        profile='$userprofile'
    WHERE id='$userid'";

}

if (mysqli_query($conn, $sql)) {
      
    echo "<script>
    alert('회원정보 수정 완료');
    location.href='personal.php?id=".htmlspecialchars($userid)."';
    </script>";
} else {
    echo "DB 오류: " . mysqli_error($conn);
}

?>