<?php
include 'db_conn.php';
session_start();
// 폼에서 전송된 데이터 받기
$title = mysqli_real_escape_string($conn, $_POST['title']);
$content = mysqli_real_escape_string($conn, $_POST['content']);
$star = (int)$_POST['star']; // 별점 숫자로 변환 (1~5)
$userid = $_SESSION['userid'];
$category = mysqli_real_escape_string($conn, $_POST['category']);

// 이미지 업로드 로직 시작
$targetDir = "uploads/";
    if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
    }

$fileName = basename($_FILES["image"]["name"]); // 폼의 input name="image" 기준
$targetFile = $targetDir . $fileName;
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// 이미지 유효성 검사 (getimagesize 등)
$check = getimagesize($_FILES["image"]["tmp_name"]);
if ($check === false) {
    echo "이미지 파일이 아닙니다.<br>";
    $uploadOk = 0;
}

// 용량 및 형식 제한
if ($_FILES["image"]["size"] > 5000000) { $uploadOk = 0; }
if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) { $uploadOk = 0; }



// 검사를 통과했다면 파일 이동 및 DB 저장
if ($uploadOk == 1) {
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        
        // --- 여기서 DB 저장을 해야 '읽기'가 가능해집니다 ---
        $sql = "INSERT INTO board (title, content, category, star, author, file_name, date) 
                VALUES ('$title', '$content', '$category', '$star','$userid', '$fileName', NOW())";
        
        if(mysqli_query($conn, $sql)) {
             echo "<script>
                alert('게시글과 이미지가 성공적으로 저장되었습니다.');
                location.href = 'main.php'; 
            </script>";
        } else {
            echo "DB 저장 에러: " . mysqli_error($conn);
        }

    } else {
        echo "파일 업로드 중 오류가 발생했습니다.";
    }
}

?>