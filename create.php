<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>게시글 작성 페이지</title>
    <style>
        :root {
            
            --box-color: #ffffff; /* 입력창 배경색 */
            --button-color: #96ae83; /* 버튼 색상 */
        }

        * {
            box-sizing: border-box;     /* 크기 계산 방식 초기화 */
            margin: 0; 
             
        }

      
        body {
            background-color: #a3c191; /* 배경색 */
            display: flex;
            flex-direction: column; /* 요소를 세로로 나열 */
            justify-content: center; /* 가로 중앙정렬 */
            align-items: center;  /* 세로 중앙정렬 */
            min-height: 100vh;
            margin: 0;
        }

        /* 헤더 스타일 추가 */
        h1 {
            color: #ffffff;
            margin-bottom: 30px; /* 제목과 입력창 사이 간격 */
            font-size: 50px;
            padding-bottom: 10px;
        }

        .write-container {
            width: 800px;
            padding: 20px;
            display: flex;
            flex-direction: column; /* 박스 안 요소를 세로로 나열 */
            gap: 15px;
            align-items: center; /* 박스 안 요소를 세로 정렬 -> 버튼 때문에 */
        }

        /* 제목 입력창 */
        .title-input {
            width: 100%;
            height: 40px;
            background-color: var(--box-color);
            border: none;
            padding: 5px 15px;
            font-size: 20px;
            text-align: left;
        }

        /* 중간 레이아웃 (사진 + 본문) */
        .content-wrapper {
            display: flex;
            width: 100%;
            height: 350px; /* 박스높이 고정 */
            gap: 20px;
        }

        /* 사진 영역 */
        .image-section {
            flex: 1; /* 남은공간 차지하는 비율 */
            background-color: var(--box-color); /* 미리만들어둔 박스 컬러 불러온다 */
            display: flex; /*사진 중앙 정렬  */
            flex-direction: column;
            justify-content: center;
            align-items: center; 
            overflow: hidden; /* 박스보다 큰 내용물은 박스크기에 맞춰 자른다 */
            position: relative;
            cursor: pointer;
        }

        #preview { /* 이미지 미리보기 부분 */
            max-width: 100%;
            max-height: 100%;
            object-fit: contain; /* 사진 비율 깨지지 않게 한다 */
        }

        .image-placeholder {
            font-size: 20px;
            color: #6b6b6b;
        }

        /* 본문 입력창 */
        .text-section {
            text-align: left;
            flex: 1.3;
            background-color: var(--box-color);
            border: none;
            padding: 15px;
            resize: none; /*사용자가 입력창 크기를 마음대로 조절하지 못하게 고 */
            font-size: 20px;
        }

        /* 별점 스타일 인터넷에서 복붙했다... */
        .rating { display: flex; flex-direction: row-reverse; justify-content: center; }
        .rating input { display: none; }
        .rating label { cursor: pointer; font-size: 35px; color: #ccc; }
        .rating input:checked ~ label { color: #ffc107; }
        .rating label:hover, .rating label:hover ~ label { color: #ffdb70; }

        /* 게시판 */
        .settings-select {
            width: 50%;
            height: 35px;
            background-color: var(--box-color);
            border: none;
            text-align-last: center; /* text-align는 문단 전체 정렬이라 한줄정렬 이용 */
        }

        .submit-btn {
            width: 200px;
            height: 50px;
            background-color: var(--button-color);
            border: none;
            color: white;
            font-size: 25px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }

        /* 파일 입력창 숨기기 */
        #file-input { display: none; }
    </style>
</head>

<body>
  <h1>게시글 입력 페이지</h1>

    <form action="process_create.php" method="POST" enctype="multipart/form-data" class="write-container">
        <input type="text" name="title" class="title-input" placeholder="제목">

        <div class="content-wrapper">
          
            <label for="file-input" class="image-section" >
                <img id="preview" src="" alt=""> <?php // 이미지 파일의 경로와 이미지 대체 텍스트 비워둔다 ?>
                <div class="image-placeholder" id="placeholder-text">사진 (클릭하여 업로드)</div>
                <input type="file" id="file-input" name="image" accept="image/*" required onchange="readURL(this);">
            </label>

            <textarea name="content" class="text-section" placeholder="본문"></textarea>
        </div>

        <div class="rating">
            <input type="radio" name="star" value="5" id="5"><label for="5">★</label>
            <input type="radio" name="star" value="4" id="4"><label for="4">★</label>
            <input type="radio" name="star" value="3" id="3"><label for="3">★</label>
            <input type="radio" name="star" value="2" id="2"><label for="2">★</label>
            <input type="radio" name="star" value="1" id="1" checked><label for="1">★</label>
        </div>

        <select name="category" class="settings-select" required>
            <option value="" disabled selected>게시판 설정</option>
            <option value="말차">말차</option>
            <option value="호지차">호지차</option>
            <option value="녹차">녹차</option>
        </select>

        <button type="submit" class="submit-btn">작성완료</button>
    </form>

    <script>
        // 이미지 미리보기 함수
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('placeholder-text').style.display = 'none';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>