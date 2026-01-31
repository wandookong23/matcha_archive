<?php 
include 'db_conn.php';
session_start();

// URL에서 게시글 고유 번호(id)를 가져옵니다.
$post_id = $_GET['id'] ?? null;
$userid = $_SESSION['userid'] ?? null;

$user_sql = "SELECT * FROM users WHERE id = '$userid'";
$user_result = mysqli_query($conn, $user_sql);
$user = mysqli_fetch_assoc($user_result);

/* 본인이 작성한 게시글 */
$post_sql = "
    SELECT 
        board.*,
        users.name AS username
    FROM board
    JOIN users ON board.author = users.id
    WHERE board.id='$post_id'
";
$post_result = mysqli_query($conn, $post_sql);
$row = mysqli_fetch_assoc($post_result);

if (!$row) {
    echo "<script>alert('존재하지 않는 게시글입니다.'); location.href='mypage.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>게시글 읽기 페이지</title>
    <style>
        :root {
            --head-color: #b8d3a8; /* 헤더배경 */
            --box-color: #ddeed3; /* 프로필, 상자 배경 */
        }

        /* 기본 초기화 */
        body {
            background-color: #ffffff;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* 전체 100% 비율로*/
        .read-table {
            width: 800px;
            margin: 0 auto; /* 좌우 여백을 자동으로 설정하여 중앙 정렬 */
            padding: 25px;
            box-sizing: border-box; /* 박스너비 계산 시 테두리 안 여백 포함 */
        }

        /* 제목 스타일 */
        h1 {
            color: #000000;
            padding-top: 30px;
            margin-top: 0;
            margin-bottom: 30px; /* 제목과 입력창 사이 간격 */
            font-size: 50px;
            padding-bottom: 10px;
            text-align: center;
        }

        /* 상단 헤더*/
        .profile-container {
            width: 100%;
            background-color: var(--head-color);
            margin-bottom: 20px;
        }

        /* 상단 프로필 라인 */
        .profile-top {
            height: 100px; 
            width: 100%;
            padding-left: 15px;
            background-color: var(--box-color);
            display: flex;
            align-items: center;
            position: relative; /* 원래위치 기준으로 화면 변환된다 */
        }

        /* 프로필 원 */
        .profile-circle {
            width: 80px;
            height: 80px;
            background-color: #b8d3a8;
            border-radius: 50%; /* 원형으로 설정 */
            margin-right: 15px;
            display: grid;
            place-items: center;
        }

        /* 닉네임 */
        .Pname {
            font-size: 24px;
            font-weight: bold;
            margin-right: 10px;
        }

        /* 게시글 테이블 */
        .read-container {
            width: 800px;
            padding: 20px;
            display: flex;
            flex-direction: column; /* 박스 안 요소를 세로로 나열 */
            gap: 15px;
            align-items: center;
        }


        /* 중간 레이아웃 (사진 + 본문) */
        .content-box {
            display: flex;
            width: 800px;        /* 전체 너비 고정 */
            height: 400px; /* 높이를 350px로 고정 */
            align-items: stretch; /* 자식 요소들이 부모 높이를 꽉 채우도록 설정 */
            gap: 20px;
        }

        /* 사진 영역 */
        .img-box {
            flex: 1; /* 남은공간 차지하는 비율 */
            height: 400px; /* 고정 높이 */
            background-color: var(--box-color); 
            overflow: hidden; /* 영역을 벗어나는 이미지 숨김 */

            display: flex; /* 사진 중앙 정렬 */
            flex-direction: column;
            justify-content: center;
        }

        .img-box img {
            display: block; /* 사진 블록으로 지정 */
            width: 100%;    /* 부모 너비에 맞춤 */
            height: 100%;
            object-fit: cover; /* 사진 비율 깨지지 않게 한다 */
        }

        /* 본문 입력창 */
        .text-section {
            text-align: left;
            flex: 1.3;
            background-color: var(--box-color);
            border: none;
            padding: 15px;
            font-size: 20px;
            
            height: 400px; /* 사진과 동일하게 고정 */
            box-sizing: border-box; /* 패딩이 높이에 영향을 주지 않도록 설정 */
            overflow-y: auto; /* 내용이 길어지면 스크롤 생성 */
        }

        /* 별점 컨테이너 */
        .rating-display {
            display: flex;
            flex-direction: row-reverse; /* 별을 오른쪽부터 채우는 방식 */
            justify-content: center;    /* 중앙 정렬 */
            align-items: center;
            gap: 5px;
            margin-top: 10px;
        }

        .rating-display .star {
            font-size: 35px;
            color: #ccc; /* 기본 회색 */
            line-height: 1;
        }

        .rating-display .star.active {
            color: #ffc107; /* 채워진 노란색 */
        }

        /* 게시판 */
        .settings-select {
            width: 50%;
            height: 35px;
            background-color: var(--box-color);
            border: none;
            display: flex;
            justify-content: center; /* 가로 중앙 */
            align-items: center;     /* 세로 중앙 */
        }
    </style>
</head>

<body>
    <table class="read-table">
        <div class="profile-container">
            <h1><?= htmlspecialchars($row['title']) ?></h1>
            <div class="profile-top">
                <div class="profile-circle">사진</div>
                <span class="Pname"><?= htmlspecialchars($user['name']) ?></span>
            </div>
        </div>

        <tbody class="read-container">  
            

            <tr>
                
                <td class="content-box">
                    <div class="img-box">
                        <img src="uploads/<?= $row['file_name'] ?>" alt="첨부이미지">
                    </div>
                    <div class="text-section">
                        <?= nl2br(htmlspecialchars($row['content'])) ?>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="rating-display">
                        <?php
                        $star_count = (int)$row['star'];
                        for ($i = 5; $i >= 1; $i--) {
                            $active = ($i <= $star_count) ? 'active' : '';
                            echo "<span class='star $active'>★</span>";
                        }
                        ?>
                    </div>
                </td>
            </tr>

            <tr class="settings-select">
                <td><?= htmlspecialchars($row['category']) ?>게시판</td>
            </tr>
        </tbody>
    </table>
</body>
</html>