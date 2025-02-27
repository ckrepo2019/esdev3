<!DOCTYPE html>
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <style>
    @font-face {
      font-family: chinese;
      src: url('{{base_path()}}/public/DroidSansFallback.ttf') format('truetype');
    }
    .chineseLanguage { font-family: chinese; }
      body {font-family: DejaVu Sans, sans-serif;}
   </style>
</head>
<body>
    Chinese
    <div class='chineseLanguage'>
        {{$get_chinese[0]->systart}} 
    </div>
    
</body>
</html>