<?php
include 'db_conn.php';
session_start();

/* 보안처리 */
$filtered = array(
    'id'=>mysqli_real_escape_string($conn, $_POST['id']),
);

$sql = "
DELETE
FROM board
WHERE id='{$filtered['id']}' 
";

$result = mysqli_query($conn, $sql);

if($result === false){
  echo '저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요';
  error_log(mysqli_error($conn));
} else {
  echo "<script>
                alert('게시글이 삭제되었습니다.');
                location.href = 'personal.php'; 
            </script>";
}
?>