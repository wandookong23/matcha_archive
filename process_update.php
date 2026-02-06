<?php
include 'db_conn.php';
session_start();

$id = (int)$_POST['id'];
$title = mysqli_real_escape_string($conn, $_POST['title']);
$content = mysqli_real_escape_string($conn, $_POST['content']);
$star = (int)$_POST['star'];
$category = mysqli_real_escape_string($conn, $_POST['category']);
$userid = $_SESSION['userid'];

// 이미지 업로드 폴더
$targetDir = "uploads/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}
// $targetDir -> 만약 이미지 폴더 없으면 만들어라, 0777-> 권한(읽,쓰,실 가능), true -> 위 폴더까지 한꺼번에 만들어야 할 때, 중간 폴더들도 자동으로 다 만들라는 옵션

if (!empty($_FILES['image']['name'])) {

    $fileName = basename($_FILES['image']['name']);
    $targetFile = $targetDir . $fileName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));


    // 이미지 포함 UPDATE
    $sql = "UPDATE board 
            SET title='$title',
                content='$content',
                category='$category',
                star='$star',
                file_name='$fileName'
            WHERE id=$id";

} 

else {

    // 이미지 변경 안 함
    $sql = "UPDATE board 
            SET title='$title',
                content='$content',
                category='$category',
                star='$star'
            WHERE id=$id";
}


if (mysqli_query($conn, $sql)) {
    echo "<script>
        alert('게시글 수정 완료');
        location.href='read.php?id=$id';
    </script>";
} else {
    echo "DB 오류: " . mysqli_error($conn);
}
?>
