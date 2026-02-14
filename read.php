<?php 
include 'db_conn.php';
session_start();

// URL에서 게시글 고유 번호(id)를 가져옵니다.
$post_id = $_GET['id'] ?? null;
$userid = $_SESSION['userid'] ?? null;

//id통해 아이디와 일치하는 유저정보와 게시글 정보를 데이터베이스에서 가져옵니다
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


        /* 수정, 삭제 버튼 */
        .sumit{
            display: flex;
            gap: 10px;          /* 버튼 사이 간격 */
            position: absolute; /* 부모 요소 기준으로 배치 */
            right: 20px;
            margin: 20px;
           
        }

        .delete-sumit, .update-sumit{
            background-color: #7D8F6B;
            color: white;
            border: none;
            cursor: pointer;

            width: 50px;  
            height: 40px;

            font-weight: bold;
        }

        /* 댓글 확인 목록 */
        .chat-item{
            width: 800px;
            max-width: none;   /* 제한 제거 */
            margin: 20px auto;       /* 상하 여백 및 중앙 정렬 */
            padding: 15px;
            border-bottom: 1px solid #b8d3a8; /* 댓글 사이 직선 */
            text-align: left; /* 내용 정렬 */
            

        }

        /* 댓글 작성자 */
        .chat-name {
            font-weight: bold;
            font-size: 14px;
            background : var(--box-color);
        }

        .comment-form{
            margin: 40px auto;       /* 상하 여백 및 중앙 정렬 */
            width: 100%;
            display: flex;
            justify-content: center;   /* 정렬 */
        }

        /* 댓글 입력 부분 */
        .comment-form form{
            width: 800px;
            max-width: none;   /* 제한 제거 */
            display: flex;           /* 가로로 나열 */
            justify-content: center;  /* 가로 중앙 정렬 */
            align-items: center;      /* 세로 높이 맞춤 */
            gap: 10px;               /* 입력창과 버튼 사이 간격 */
           
        }

        .comment-form textarea {
            width: 80%;              /* 입력창 너비 조정 */
            height: 40px;
            padding: 10px;
            resize: none;            /* 크기 조절 비활성화 */
            border: 1px solid #ccc;
        }

        /* 댓글 등록 버튼 */
        .chat_sumit{
            background-color: #7D8F6B;
            color: white;
            border: none;
            cursor: pointer;
            width: 60px;  
            height: 40px;
            font-weight: bold;
            flex-shrink: 0;
        }

        /* 댓글내용 + 삭제버튼 가로정렬 */

        .chat-row{
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .chat-del button{
            background-color: #7D8F6B;
            color: white;
            border: none;
            cursor: pointer;
            width: 60px;
            height: 32px;
            font-size: 12px;
        }

        .like{
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;   /* ← 이게 제일 깔끔한 방법 */
        }


    </style>
</head>

<body>
    <table class="read-table">
        <div class="profile-container">
            <h1><?= htmlspecialchars($row['title']) ?></h1>
            <div class="profile-top">
                <div class="profile-circle">사진</div>
                <span class="Pname"><?= htmlspecialchars($row['username']) ?></span>
                <div class="sumit">
                    <form action="process_delete.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="delete-sumit" onclick="return confirm('정말 이 게시글을 삭제하시겠습니까?');">삭제</button>
                    </form>
                    <form action="update.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="update-sumit" >수정</button>
                    </form>
                </div>
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

             <tr>
                <td>
                    <div>
                        <?php 
                        // 현재 게시글/로그인 사용자 ID 설정
                        $board_id = intval($post_id);
                        $login_id = $userid;

                        // 1. like DB 검증 (데이터가 있는지 확인)
                        $check_url2 = "SELECT user_id FROM board_likes WHERE board_id='$board_id' AND user_id='$login_id'";
                        $sql_check2 = mysqli_query($conn, $check_url2);
                        
                        // mysqli_num_rows를 쓰면 데이터가 몇 줄인지 알 수 있습니다. (0이면 안 누름, 1이면 누름)
                        $is_liked = mysqli_num_rows($sql_check2) > 0;
                        echo "<div class='like'>";
                        // 2. 좋아요 상태에 따라 하트 출력
                        if ($is_liked) {
                            // [상태: 이미 좋아요 누름] -> 채워진 하트 출력 (누르면 process_like.php로 이동)
                            echo '
                            <form action="./process_like.php?id='.$board_id.'" method="POST">
                                <input type="hidden" name="board_id" value="'.$board_id.'">
                                <input type="image" style="cursor: pointer; width:30px;height:30px;" 
                                    src="./image/채워진하트.png" alt="좋아요 취소">
                            </form>';
                        } else {
                            // [상태: 아직 안 누름] -> 빈 하트 출력
                            echo '
                            <form action="./process_like.php?id='.$board_id.'" method="POST">
                                <input type="hidden" name="board_id" value="'.$board_id.'">
                                <input type="image" style="cursor: pointer; width:30px;height:30px;" 
                                    src="./image/비어있는하트.png" alt="좋아요 하기">
                            </form>';
                        }
                        
                        // 3. 해당 게시글의 총 좋아요 개수 세기
                        $count_query = "SELECT COUNT(*) as cnt FROM board_likes WHERE board_id='$board_id'";
                        $count_res = mysqli_query($conn, $count_query);
                        $count_row = mysqli_fetch_array($count_res);
                        $total_likes = $count_row['cnt'];

                        // 출력
                        echo ' 좋아요 수 : ' . $total_likes;
                        echo '</div>';

                        ?>
                    </div>
                </td>
            </tr>


            <tr>
                <td>
                    <div>
                    <?php
                    if (isset($_POST['chat-submit'])){
                        $chat_content = $_POST['comment'];
                        $user_id = $_SESSION['userid'];
                        $board_id = $post_id;
                    
                    $sql = "
                        INSERT INTO chattable (content, user_id, board_id)
                        VALUES ('$chat_content', '$user_id', '$board_id')
                    ";
                    mysqli_query($conn, $sql);
                    }  

                        $chat_sql = "
                        SELECT 
                            chattable.*, users.name
                        FROM chattable 
                        
                        JOIN users ON chattable.user_id = users.id
                        WHERE chattable.board_id =  '".intval($post_id)."'
                    ";
                    $chat_result = mysqli_query($conn, $chat_sql);
                
                    while ($chat_row = mysqli_fetch_assoc($chat_result)) {
                        echo "<div class='chat-item'>";
                        echo "  <div class='chat-name'>".htmlspecialchars($chat_row['name'])."</div>";
                        echo "  <div class='chat-row'>";  
                        echo "  <div class='chat-content'>".nl2br(htmlspecialchars($chat_row['content']))."</div>";
                        
                        if ($chat_row['user_id'] == $userid) {
                            echo "<div class='chat-del'>";
                            echo "<form action='process_chat_delete.php' method='post'>";
                            echo "<input type='hidden' name='chat_id' value='".$chat_row['id']."'>";
                            echo "<button type='submit'>삭제</button>";
                            echo " </form>";
                            echo "</div>";
                            
                        }

                        echo "</div>";
                        echo "</div>";

                    }

                    
                            
                    ?>
                    </div>
            
                
                    <div class="comment-form">
                        <form id="commentForm" method="post" action="">
                            <textarea name="comment" placeholder="댓글을 입력하세요"></textarea>
                            <button class="chat_sumit" name="chat-submit" type="submit">등록</button>
                        </form>
                    </div>
                </div>
        
        </td>
        </tr>
        </tbody>
    </table>
</body>
</html>