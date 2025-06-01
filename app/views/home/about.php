<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .nav {
            text-align: center;
            margin: 20px 0;
        }
        .nav a {
            margin: 0 10px;
            color: #007bff;
            text-decoration: none;
        }
        .nav a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($title) ?></h1>
        
        <div class="nav">
            <a href="/">Ana Sayfa</a>
            <a href="/about">Hakkında</a>
            <a href="/contact">İletişim</a>
            <a href="/users">Kullanıcılar</a>
        </div>
        
        <p><?= htmlspecialchars($content) ?></p>
        
        <div style="margin-top: 30px;">
            <h3>Framework Yapısı:</h3>
            <pre>
simple-framework/
├── app/
│   ├── controllers/
│   │   └── HomeController.php
│   ├── models/
│   │   └── User.php
│   ├── views/
│   │   └── home/
│   └── config/
│       ├── app.php
│       └── routes.php
├── core/
│   ├── App.php
│   ├── Controller.php
│   ├── Database.php
│   └── Model.php
└── public/
    └── index.php
            </pre>
        </div>
    </div>
</body>
</html>
