<?php
include 'db_conn.php'; // DB 연결 정보 가져오기
session_start();       // 로그인을 유지하기 위해 세션 시작

// 1. 사용자가 입력한 ID와 Password 낚아채기
$userid = $_POST['userid'];
$userpw = $_POST['userpw'];

// 2. DB에서 해당 아이디와 비밀번호가 일치하는 유저 찾기
$sql = "SELECT * FROM users WHERE id = '$userid' AND password = '$userpw'";
$result = mysqli_query($conn, $sql); /* $conn통해 접속할 데이터 베이스 찾고, $sql명령어 실행 */

// 3. 결과 확인
if (mysqli_num_rows($result) > 0) {
    // [로그인 성공] DB에 일치하는 데이터가 있음
    $row = mysqli_fetch_assoc($result);
    
    // 세션에 유저 아이디 저장 (로그인 상태 유지용)
    $_SESSION['userid'] = $row['id'];
    $_SESSION['username'] = $row['name'];

    echo "<script>
        alert('로그인에 성공했습니다! " . $row['name'] . "님 환영합니다.');
        location.href = 'main.php'; 
    </script>";
} 

else {
    // [로그인 실패] 아이디가 없거나 비번이 틀림
    echo "<script>
        alert('아이디 또는 비밀번호가 일치하지 않습니다.');
        history.back(); // 이전 로그인 화면으로 돌아가기
    </script>";
}
?>