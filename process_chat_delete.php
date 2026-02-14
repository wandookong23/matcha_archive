<?php
include 'db_conn.php';
session_start();

$filtered = array(
    'id' => mysqli_real_escape_string($conn, $_POST['chat_id']),
);

// 1. 삭제 전 board_id 먼저 가져오기 (변수명 $select_sql로 일치시켜야 함)
$select_sql = "SELECT board_id FROM chattable WHERE id='{$filtered['id']}'";
$result = mysqli_query($conn, $select_sql); 
$chat = mysqli_fetch_assoc($result);
$board_id = $chat['board_id'];

// 2. 실제 삭제 수행
$sql = "DELETE FROM chattable WHERE id='{$filtered['id']}'";
$result = mysqli_query($conn, $sql);

if ($result === false){
    echo '삭제 중 오류 발생';
} else {
    echo "<script>
        alert('댓글이 삭제되었습니다.');
        location.href = 'read.php?id=".$board_id."';
    </script>";
}
?>