<?php 
include 'db_conn.php';
session_start();

// 호지차 카테고리의 게시글을 가져온다.
$post_sql = "
    SELECT 
        board.id,
        board.title,
        board.date,
        board.category,
        users.name AS username
    FROM board
    JOIN users ON board.author = users.id
    WHERE board.category  = '호지차'
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

h1{
    color: #ffffff;
    padding-top: 50px;
    margin: 0;
    padding-bottom: 50px;
    text-align: center;
    font-size: 50px;
    background-color: #d8c695;
}

/* 게시판 테이블 */
.board-table {
    border-collapse: collapse; /* 이웃한 셀끼리 합쳐진다 (겹치는 부분 한줄로 표현됨) */
    background-color: white;
    width: 100%;
    padding: 25px;
    box-sizing: border-box; /* 박스너비 계산 시 테두리 안 여백 포함 */
}

/* 테이블의 헤더 */
.board-table th {
    border: 2px solid #d8c695;
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

<h1>호지차 게시판</h1>


<!--//표 만들기 (b바디 부분) -->
<table class="board-table">

<!-- //테이블의 헤더 부분 -->
<thead>
<tr>
    <th class="title">제목</th>
    <th class="Bname">작성자</th>
    <th class="date">작성일</th>
    <th class="category">게시판</th>
</tr>
</thead>

<!--테이블의 데이터(본문) -->
<tbody>  

<!--데이터가 하나라도 있는지 검증 ->  데이터 한 행씩 꺼내와 row에 담는다 -->
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
        <!-- 데이터 없을 시 칸 합치고, 텍스트 중앙정렬한다 -->
        <td colspan="4" style="text-align:center;">
            작성한 게시글이 없습니다.
        </td>
    </tr>
<?php endif; ?>

</tbody>
</table>

</body>
</html>