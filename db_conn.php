<?php
$servername = "localhost";
$username = "root";      // XAMPP 기본 아이디
$password = "";          // XAMPP 기본 비번은 공백
$dbname = "siss"; // 본인이 만든 DB 이름 입력

// 연결 생성
$conn = mysqli_connect($servername, $username, $password, $dbname,);



// 연결 확인
if (!$conn) {
    die("연결 실패: " . mysqli_connect_error());
}

?>