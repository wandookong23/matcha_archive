<?php 
include 'db_conn.php';
session_start();

$userid = $_SESSION['userid'] ?? null;


/* users 테이블 */
$user_sql = "SELECT * FROM users WHERE id = '$userid'";
$user_result = mysqli_query($conn, $user_sql);
$user = mysqli_fetch_assoc($user_result);

/* 본인이 작성한 게시글 */
$post_sql = "
SELECT 
    board.id,
    board.title,
    board.date,
    board.category,
    users.name AS username
FROM board
JOIN users ON board.author = users.id
WHERE board.author = '$userid'
ORDER BY board.date DESC
";
$post_result = mysqli_query($conn, $post_sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>마이페이지</title>

<style>
/* 기본 초기화 */
body {
    background-color: #ddd;
    margin: 0;
}

/* 전체 100% 비율로*/
.profile-container,
.board-table {
    width: 100%;
    padding: 25px;
    box-sizing: border-box; /* 박스너비 계산 시 테두리 안 여백 포함 */
}

/* 상단 프로필 */
.profile-container {
    background-color: #b8d3a8;
}

/* 상단 라인 */
.profile-top {
    display: flex;
    align-items: center;
    position: relative; /* 원래위치 기준으로 화면 변환된다 */
}

/* 프로필 원 */
.profile-circle {
    width: 80px;
    height: 80px;
    background-color: #7D8F6B;
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

/* 버튼 */
.personal-submit {
    position: absolute; /* 위치 오른쪽에 고정*/
    right: 0;
    padding: 8px 15px; /* 상.하 8px, 좌.우 15px 패딩값*/
    background-color: #7D8F6B;
    color: white;
    border: none;
    cursor: pointer;
}

/* 소개글 */
.intro-box {
    font-size: 18px;
    margin-top: 20px;
    background-color: white;
    height: 40px;
    display: flex;
    align-items: center;
    padding-left: 15px;
    font-weight: bold;
}

/* 게시판 테이블 */
.board-table {
    border-collapse: collapse; /* 이웃한 셀끼리 합쳐진다 (겹치는 부분 한줄로 표현됨) */
    background-color: white;
}

/* 테이블의 헤더 */
.board-table th {
    border: 2px solid #a3c191;
    padding: 12px;
    font-size: 18px;
}

/* 테이블의 바디 */
.board-table td {
    padding: 12px;
    border-bottom: 1.5px solid #ddd;
    border-right: 1.5px solid #ddd;
}

/* 테이블 바디의 마지막 줄 */
.board-table td:last-child {
    border-right: none;
}

/* 컬럼 비율과 위치 */
.title { width: 55%; text-align: left; }
.Bname { width: 15%; text-align: center; }
.date { width: 20%; text-align: center; }
.category { width: 10%; text-align: center;}

</style>
</head>

<body>

<?php //개인페이지 헤더 부분 ?>
<div class="profile-container">
    <div class="profile-top">
        <div class="profile-circle">사진</div>
        <span class="Pname"><?= htmlspecialchars($user['name']) ?></span>
        <div class="Pid"><?=htmlspecialchars($user['id'])?></div>
        <button class="personal-submit" onclick="location.href='personal_change.php'">
            회원정보 변경
        </button>
    </div>

    <div class="intro-box">
        <?= htmlspecialchars($user['profile']) ?>
    </div>
</div>

<?php //표 만들기 (b바디 부분) ?>
<table class="board-table">

<?php //테이블의 헤더 부분 ?>
<thead>
<tr>
    <th class="title">제목</th>
    <th class="Bname">작성자</th>
    <th class="date">작성일</th>
    <th class="category">게시판</th>
</tr>
</thead>

<?php //테이블의 데이터(본문) ?>
<tbody>  

<?php //데이터가 하나라도 있는지 검증 ->  데이터 한 행씩 꺼내와 row에 담는다?>
<?php if (mysqli_num_rows($post_result) > 0): ?> 
    <?php while ($row = mysqli_fetch_assoc($post_result)): ?>
        <tr> 
            <td class="title">
                <a href="read.php?id=<?= $row['id'] ?>">
                    <?= htmlspecialchars($row['title']) ?>
                </a>
            </td>
            <td class="Bname"><?= htmlspecialchars($row['username']) ?></td>
            <td class="date"><?= htmlspecialchars($row['date']) ?></td>
            <td class="category"><?= htmlspecialchars($row['category']) ?></td>
        </tr>
<?php endwhile; ?>
<?php else: ?>
    <tr>
        <?php //데이터 없을 시 칸 합치고, 텍스트 중앙정렬한다 ?>
        <td colspan="4" style="text-align:center;">
            작성한 게시글이 없습니다.
        </td>
    </tr>
<?php endif; ?>

</tbody>
</table>

</body>
</html>
