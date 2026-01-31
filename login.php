<?php include 'db_conn.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>로그인화면</title>

    <style>  
    
    * {
        box-sizing: border-box;     /* 크기 계산 방식 초기화 */
        margin: 0; 
    }

    body{
        
        display: grid;
        place-items: center;
        min-height: 100vh;
        background-color: #a3c191;

    }


    input, .login-submit {
    width: 400px;                 /* 적당한 너비 */
    padding: 15px;              /* 안쪽 여백 통일 */
    border-radius: 8px;         /* 둥글기 통일 */
    font-size: 25px;
    font-weight: bold;          /* 글자 두껍게 */   
    
    }

    .login-submit{                        /* 로그인 버튼 */
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
        display: flex;           /* grid 대신 flex를 쓰면 세로 정렬이 더 쉽다 */
        flex-direction: column;  /* 요소들을 세로로 쌓기 */
        align-items: center;
        gap: 15px;              /* 입력창과 버튼들 사이에 15px씩 간격을 줌 */
       
    }

    h1{
        color: white;
        font-size: 70px;
        margin: 20;
        padding: 20px;
    }

    .join-submit{
        margin-top: 40px;
        color: white;
        text-decoration: none;  /* 밑줄 제거 */
        font-size: 25px;
        font-weight: bold;
        text-align: center;

    }

    </style>

</head>
<body>
    
    <form action="login_check.php" method="POST">
        <h1>Matcha Archive</h1>
        <input type="text" name="userid" placeholder="ID" required> 
        <input type="password" name="userpw" placeholder="Password" required >
        <button type="submit" class="login-submit">로그인</button>
        <a href="join.php" class="join-submit">회원가입</a>



</body>
</html>