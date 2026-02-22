<?php include 'db_conn.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Matcha Project</title>
</head>

<body>

    <button type="button" class="personal-sumit" onclick="location.href='personal.php'">마이홈</button>
    <button type="button" class="create-sumit" onclick="location.href='create.php'">게시글작성</button>

    <div class="searchboxContainer">
    <form action="search.php" method="GET">
        
        <select name="category">
        <option value="title">제목</option>
        <option value="author">글쓴이</option>
        <option value="content">내용</option>
        </select>
    <input name="search" placeholder="검색 입력">
    <button class="search-button" type="submit">검색</button>
    </form>
    </div>
</body>

