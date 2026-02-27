<?php 
include 'db_conn.php';
session_start();

$userid = $_SESSION['userid'] ?? null;

$user_sql = "SELECT * FROM users WHERE id = '$userid'";
$user_result = mysqli_query($conn, $user_sql);
$user = mysqli_fetch_assoc($user_result);


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Matcha Project</title>

    <style>
    html, body {
            height: 100%;
            margin: 0;
        }

    body {
            min-height: 100vh;
            background-color: #ddd;
            
        }

     h1 {
            color: #ffffff;
            padding-top: 50px;
            padding-bottom: 50px;
            margin: 0;
            font-size: 50px;
            text-align: center;

            background-color: #a3c191;
            
        }

        /* 프로필 */
        .profile-container {
            padding-top: 50px;
            padding-bottom: 50px;

            background-color: #b8d3a8;
            display: flex;
            align-items: center;
            position: relative; /* 원래위치 기준으로 화면 변환된다 */
        }

       
        /* 프로필 원 */
        .profile-circle {
            width: 80px;
            height: 80px;
            background-color: #ffffff;
            border-radius: 50%; /* 원형으로 설정 */
            margin-right: 15px;
            margin-left: 15px;
            display: grid;
            place-items: center;

            color: #818181;
        }

        /* 닉네임 */
        .Pname {
            color: #ffffff;
            font-size: 24px;
            font-weight: bold;
            margin-right: 10px;
        }

    
       /* 마이홈, 게시글 작성 버튼 */
        .sumit{
            display: flex;
            gap: 10px;          /* 버튼 사이 간격 */
            position: absolute; /* 부모 요소 기준으로 배치 */
            right: 20px;
            margin: 20px;

        }

        .personal-sumit, .create-sumit{
            background-color: #97b486;
            color: white;
            border: none;
            cursor: pointer;
 
            width: 200px;  
            height: 50px;
            padding: 10px 15px;
            font-size: 18px;

            font-weight: bold;
            white-space: nowrap;
        }

        /* 게시판 버튼 */
        .board-sumit{
            display: flex;
            justify-content: center; /* 가로 중앙 */
            align-items: center;     /* 세로 중앙 */

            gap: 100px;          /* 버튼 사이 간격 */
            margin: 0px;

            padding-top: 50px;
            padding-bottom: 50px;
        
            background-color: #e6ebd1;
        }

        .greentea-sumit, .matcha-sumit, .hoji-sumit{
            color: black;
            border: none;
            cursor: pointer;
 
            width: 300px;  
            height: 60px;
            padding: 10px 15px;

            font-weight: bold;
            white-space: nowrap;
            font-size: 20px;
        }

        .greentea-sumit{
             background-color: #b8d3a8;
        }

        .matcha-sumit{
            background-color: #97b486;
        }

        .hoji-sumit{
            background-color: #d8c695;
        }

        /* 검색창 */
        .searchbox-Container{
            padding-top: 100px;
            padding-bottom: 100px;

            background-color: #ffffff;
            display: flex;
            justify-content: center; /* 가로 중앙 */
            align-items: center;     /* 세로 중앙 */

        }

        /* form 태그 자체도 flex로 설정하여 내부 요소 간격 조절 */
        .searchbox-Container form {
            display: flex;
            gap: 5px; /* 요소 사이의 미세한 간격 */
            align-items: center;
        }

        /* select, input, button 모두 높이를 40px로 통일 */
        .searchbox-Container select,
        .searchbox-Container input,
        .searchbox-Container .search-button {
            height: 50px;
            padding: 0 15px;
            box-sizing: border-box; /* 패딩이 높이에 영향을 주지 않도록 설정 */
            border: 1px solid #ccc;
            font-size: 18px;
            vertical-align: middle;
        }

        /* 검색어 입력창 가로 너비 (원하는 만큼 조절) */
        .searchbox-Container input {
            width:800px;
  
        }

        @media (max-width: 1000px) {
            .searchbox-Container input{
                width:auto;
                
            }
            
        }

        /* 카테고리 선택창 가로 너비 */
        .searchbox-Container select {
            width: 120px;
        }

        /* 검색 버튼 */
        .search-button{
            background-color: #97b486;
            color: white;
            border: none;
            cursor: pointer;
            width: 120px;  
          
        }

    </style>
</head>

<body>
    <h1>Matcha Archive</h1>

    <div class='profile-container'>
        <div class="profile-circle">사진</div>
        <span class="Pname"><?= htmlspecialchars($user['name']) ?></span>
        <div class='sumit'>
        <button type="button" class="personal-sumit" onclick="location.href='personal.php'">마이홈</button>
        <button type="button" class="create-sumit" onclick="location.href='create.php'">게시글작성</button>
        </div>
    </div>

    <div class="board-sumit">
    <button type="button" class="greentea-sumit" onclick="location.href='greentea.php'">녹차 게시판</button>
    <button type="button" class="matcha-sumit" onclick="location.href='matcha.php'">말차 게시판</button>
    <button type="button" class="hoji-sumit" onclick="location.href='hoji.php'">호지차 게시판</button>
    </div>


    <div class="searchbox-Container">
        <form action="search.php" method="GET">
            
            <select name="category">
            <option value="title">제목</option>
            <option value="name">글쓴이</option>
            <option value="content">내용</option>
            </select>
        <input name="search" placeholder="검색어 입력">
        <button class="search-button" type="submit">검색</button>
        </form>
    </div>
</body>

