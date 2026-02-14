<?php
include 'db_conn.php';
session_start();

$userid  = $_SESSION['userid'] ?? null;
$board_id = $_GET['id'] ?? null;

/* 이미 좋아요 눌렀는지 확인 */
$like_check_sql = "
SELECT * 
FROM board_likes 
WHERE board_id='".intval($board_id)."' 
AND user_id='".mysqli_real_escape_string($conn,$userid)."'
";
$like_check = mysqli_query($conn, $like_check_sql);
$already_like = mysqli_fetch_assoc($like_check);



if ($already_like) {

    // 좋아요 수 감소 + 기록 삭제
   $sql = "
        DELETE FROM board_likes 
        WHERE board_id='".intval($board_id)."' 
        AND user_id='".mysqli_real_escape_string($conn,$userid)."';
    ";

    mysqli_multi_query($conn, $sql);

    echo "<script>
        alert('좋아요를 취소했습니다.');
        location.href='read.php?id=".$board_id."';
    </script>";
    exit;
}



$sql = "
    INSERT INTO board_likes (board_id, user_id)
    VALUES ('".intval($board_id)."', '".mysqli_real_escape_string($conn,$userid)."');
";

mysqli_multi_query($conn, $sql);

echo "<script>
    alert('좋아요를 눌렀습니다.');
    location.href='read.php?id=".$board_id."';
</script>";
exit;
?>
