<?php 
include 'db_conn.php';
session_start();

$userid = $_SESSION['userid'] ?? null;

$user_sql = "SELECT * FROM users WHERE id = '$userid'";
$user_result = mysqli_query($conn, $user_sql);
$row = mysqli_fetch_assoc($user_result);


// 변수에 데이터 저장 (htmlspecialchars로 보안 처리)
$article = array(
    'userpw' => htmlspecialchars($row['password']),
    'username' => htmlspecialchars($row['name']),
    'userprofile' => htmlspecialchars($row['profile']),
);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>회원정보 수정 화면</title>

    <style>  

    * {
        box-sizing: border-box;     /* 크기 계산 방식 초기화 */
        margin: 0; 
    }

    #join-box input {
        padding-top: 10px;     /* 위쪽 여백 */
        padding-bottom: 50px;  /* 아래쪽 여백 */
        vertical-align: top;   /* 글자가 위쪽부터 써지도록 설정 (필요시) */
    }

    body{
        
        display: grid;
        place-items: center;
        min-height: 100vh;
        background-color: #a3c191;
 
    }

    h1{
        display: grid;
        place-items: center;
        color: white;
        font-size: 70px;
        margin: 20;
        padding: 20px;
    }


   input, .join-submit {
    width: 400px;   
    padding: 15px;              /* 안쪽 여백 통일 */
    border-radius: 8px;         /* 둥글기 통일 */
    font-size: 25px;
    font-weight: bold;          /* 글자 두껍게 */   

    
    }

    .join-submit{
           
    
        background-color: #7D8F6B;   
        border: none;                   /* 테두리 없애기 */
        cursor: pointer;                /*버튼에 커서 올릴 시 손가락으로 바뀜*/
        color: #000000;
        margin-top: 20px;               /* 버튼 위쪽 여백 */
    }

        input {
        color: #090909; 
        background-color: #ffffff;    /*  상자 내부 배경색 */
        border: 2px solid #A1B989;    /*  테두리 두께, 스타일, 색상 */
        outline: none;                  /* 클릭했을 때 생기는 기본 파란 테두리 제거 */
        
    }

        form {
        display: grid;           /* grid 대신 flex를 쓰면 세로 정렬이 더 쉽다 */
        flex-direction: column;  /* 요소들을 세로로 쌓기 */
        gap: 15px;              /* 입력창과 버튼들 사이에 15px씩 간격을 줌 */
        align-items: center;
       
    }


    </style>

</head>
<body>

    
    <form action="process_personal_change.php" method="POST">
        <h1>회원정보 수정</h1>

        <input type="text" name="username" placeholder="name" required value="<?=$article['username']?>">
        <input type="password" name="userpw"  placeholder="새 비밀번호 (변경 시 입력)" >

        <div id="join-box">
        <input type="text" name="userprofile" placeholder="한줄소개" required value="<?=$article['userprofile']?>">
        </div>

        <button type="submit" class="join-submit">수정완료</button>
    
</form>

</body>
</html>