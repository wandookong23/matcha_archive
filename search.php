<?php
    include 'db_conn.php';
    session_start();

    $search_title = $_GET['search'] ?? '';
    $category = $_GET['category'] ?? 'title';

    /* 카테고리 -> 실제 DB 컬럼 매핑 */
    if($category == 'title'){
        $column = 'board.title';
        $catname = '제목';
    }
    else if($category == 'name'){
        $column = 'users.name';
        $catname = '작성자';   
    }
    else if($category == 'content'){
        $column = 'board.content';
        $catname = '내용';
    }
    else{
        $column = 'board.title';
    }
    
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'date DESC';

    
    /* 실제 검색 쿼리 실행 */
    $query2 = "SELECT 
                board.*,
                users.name AS username,
                COUNT(board_likes.board_id) AS like_count  /*좋아요수 카운트*/
            FROM board
            JOIN users ON board.author = users.id
            LEFT JOIN board_likes ON board.id = board_likes.board_id /*좋아요 테이블 결합*/
            WHERE $column LIKE '%$search_title%'
            GROUP BY board.id /*게시글별로 묶어줌 (게시글 ID같은것끼리 합친다)*/
            ORDER BY $sort";

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
        padding: 5px;
        box-sizing: border-box; /* 박스너비 계산 시 테두리 안 여백 포함 */
    }

    /* ~에 대한 검색결과 */
    .search-container{
        background-color: #97b486;
        display: flex;
        align-items: center;
        position: relative; /* 원래위치 기준으로 화면 변환된다 */

        font-size: 20px;
        padding-left: 15px;
        
    }

    /* 검색어 부분 */
    .underline{
        display: inline-block;  
        padding-bottom: 6px;     /* 글자와 밑줄 사이 간격 */
        border-bottom: 2px solid black;  /* 밑줄 */
        font-weight: bold;
    }

        /* 검색창 */
        .searchbox-Container{
            padding-top: 60px;
            padding-bottom: 10px;
            padding-left: 15px;
            background-color: #b8d3a8;
            display: flex;
            align-items: center;     /* 세로 중앙 */

        }

        /* form 태그 자체도 flex로 설정하여 내부 요소 간격 조절 */
        .searchbox-Container form {
            display: flex;
            gap: 5px; /* 요소 사이의 미세한 간격 */
            align-items: center;
        }

        /* select, input, button 모두 높이를 40px로 통일 */
        .searchbox-Container select,
        .searchbox-Container input,
        .searchbox-Container .search-button {
            height: 40px;
            padding: 0 15px;
            box-sizing: border-box; /* 패딩이 높이에 영향을 주지 않도록 설정 */
            border: 1px solid #ccc;
            font-size: 18px;
            vertical-align: middle;
        }

        /* 검색어 입력창 가로 너비 (원하는 만큼 조절) */
        .searchbox-Container input {
            width:800px;
        }

        @media (max-width: 1000px) {
            .searchbox-Container input{
                width:auto;
                
            }
            
        }

        /* 카테고리 선택창 가로 너비 */
        .searchbox-Container select {
            width: 100px;
        }

        /* 검색 버튼 */
        .search-button{
            background-color: #97b486;
            color: white;
            border: none;
            cursor: pointer;
            width: 100px;  
          
        }


    /* 게시판 테이블 */
    .board-table {
        border-collapse: collapse; /* 이웃한 셀끼리 합쳐진다 (겹치는 부분 한줄로 표현됨) */
        background-color: white;
    }

    /* 테이블의 헤더 */
    .board-table th {
        border: 2px solid #b8d3a8;
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


    .sort-Container{
        padding-top: 10px;
        padding-bottom: 10px;
        display: flex;
        align-items: center;
        
        background-color: #b8d3a8;
    }

    .sort-Container button{
        padding-top: 10px;
        padding-bottom: 10px;
        background-color: #97b486;
        border: none;
        color: white;
        width: 100px;  
        cursor: pointer;
    }

    .sort-Container a{
        padding-left: 15px;
        font-size: 20px;
        font-weight: bold;
    }

    </style>
</head>

<body>

    <div class="search-container">
        <a class='result'>
            <span class="underline">
                <?php echo htmlspecialchars($search_title); ?>
            </span>
            에 대한 검색결과
        </a>
        </div>

        <div class="searchbox-Container">
        <form action="search.php" method="GET">
            
            <select name="category">
            <option value="title">제목</option>
            <option value="name">글쓴이</option>
            <option value="content">내용</option>
            </select>
        <input name="search" placeholder="검색어 입력">
        <button class="search-button" type="submit">검색</button>
        </form>
        </div>

        
        

        <!--정렬기능 구현-->
        <div class="sort-Container">
        <form action="search.php" method="GET">
            <input type="hidden" name="search" value="<?= htmlspecialchars($search_title) ?>">
            <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
            <a>정렬 |</a>
            <button name="sort" value="date DESC">최신순</button>
            <button name="sort" value="date ASC">오래된순</button>
            <button name="sort" value="title ASC">제목순</button>
            <button name="sort" value="like_count DESC">좋아요순</button>
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
            while($board = mysqli_fetch_assoc($sql2)){
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
                    <?= htmlspecialchars($board['username']) ?>
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
