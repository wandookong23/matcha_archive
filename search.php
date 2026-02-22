<?php
    include 'db_conn.php';
    session_start();

    $search_title = $_GET['search'];
    $category = $_GET['category'];

    /* 카테고리 -> 실제 DB 컬럼 매핑 */
    if($category == 'title'){
        $column = 'title';
        $catname = '제목';
    }
    else if($category == 'author'){
        $column = 'author';   
        $catname = '작성자';
    }
    else if($category == 'content'){
        $column = 'content';
        $catname = '내용';
    }
    else{
        $column = 'title';
    }

    /* 실제 검색 쿼리 실행 */
    $query2 = "SELECT * FROM board 
            WHERE $column LIKE '%$search_title%' 
            ORDER BY date DESC";

    $sql2 = mysqli_query($conn, $query2);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>검색 결과 페이지</title>

    <style>
    
    /* 기본 초기화 */
    body {
        background-color: #ddd;
        margin: 0;
    }

    /* 전체 100% 비율로*/
    .search-container,
    .board-table {
        width: 100%;
        padding: 25px;
        box-sizing: border-box; /* 박스너비 계산 시 테두리 안 여백 포함 */
    }

    /* 상단 */
    .search-container{
        background-color: #b8d3a8;
        display: flex;
        align-items: center;
        position: relative; /* 원래위치 기준으로 화면 변환된다 */
    }

    /* 검색어 부분 */
    .underline{
        display: inline-block;  
        padding-bottom: 6px;     /* 글자와 밑줄 사이 간격 */
        border-bottom: 2px solid black;  /* 밑줄 */
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
    .author { width: 15%; text-align: center; }
    .date { width: 20%; text-align: center; }
    .category { width: 10%; text-align: center;}

    </style>
</head>

<body>
    <div class="search-container">
    <h1>
        <span class="underline">
            <?php echo htmlspecialchars($search_title); ?>
        </span>
        에 대한 검색결과
    </h1>
    </div>
        <div class="searchboxContainer">
        <form action="search.php" method="GET">
            
            <select name="category">
            <option value="title">제목</option>
            <option value="author">글쓴이</option>
            <option value="content">내용</option>
            </select>
        <input name="search" placeholder="검색어 입력">
        <button class="search-button" type="submit">검색</button>
        </form>
        </div>

    
        <table class="board-table">
        <thead>
            <tr>
                <th class="title">제목</th>
                <th class="Bname">작성자</th>
                <th class="date">작성일</th>
                <th class="category">게시판</th>
            </tr>
        </thead>

     
        <tbody>  
        <?php
        if(mysqli_num_rows($sql2) > 0){
            while($board = $sql2->fetch_array()){
                /* 제목 꺼내기 */
                $title = $board["title"];
            
                /* 제목 길면 자르기 (30자보다 크면) */
                if(mb_strlen($title,"utf-8") > 30){
                    $title = mb_substr($title,0,30,"utf-8")."...";
                }

        ?>
        <tr>
            <!--제목 클릭 시 페이지 읽기 처리-->
                <td class="title">
                    <a href="read.php?id=<?php echo $board['id']; ?>">
                        <?php echo htmlspecialchars($title); ?>
                    </a>
                </td>

                <td class="author">
                    <?php echo htmlspecialchars($board['author']); ?>
                </td>

                <td class="date">
                    <?php echo htmlspecialchars($board['date']); ?>
                
                </td>
                <td class=" category">
                    <?php echo htmlspecialchars($board['category']); ?>
                </td>
        </tr>
                <?php } 
            } else{ ?>
            <tr>
                <!--데이터 없을 시 칸 합치고, 텍스트 중앙정렬한다 -->
                <td colspan="4" style="text-align:center;">
                    작성한 게시글이 없습니다.
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>

</body>
</html>
