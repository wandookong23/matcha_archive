<?php
include 'db_conn.php'; // DB 연결 정보 가져오기
session_start();       // 로그인을 유지하기 위해 세션 시작

$userid = $_POST['userid'];
$userpw = $_POST['userpw'];
$username = $_POST['username'];
$userprofile = $_POST['userprofile'];


$check_sql = "SELECT * FROM users WHERE id = '$userid'" ;
$result = mysqli_query($conn, $check_sql); 


if (mysqli_num_rows($result) > 0) {
    echo "<script>alert('이미 존재하는 아이디입니다.'); history.back();</script>";
    exit;
}

else{
    $sql = "INSERT INTO users (id, password, name, profile) 
        VALUES ('$userid', '$userpw', '$username', '$userprofile')";        /*INSERT로 DB에 정보 저장 */
}


if (mysqli_query($conn, $sql)) {
    echo "<script>
        alert('회원가입이 완료되었습니다!');
        location.href = 'login.php'; 
    </script>";
} else {
    echo "오류 발생: " . mysqli_error($conn);
}

?>