<!DOCTYPE html>

<?php
    //DB接続　
    $dsn='データベース名';
    $user='ユーザ名';
    $password='パスワード';
    $pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    /*
    //テーブル削除
    $sql = 'DROP TABLE mission5';
    $stmt = $pdo->query($sql);
    */
    //テーブルの作成
    $sql = "CREATE TABLE IF NOT EXISTS mission5"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date TEXT,"
    . "password char(10)"
    .");";
    
    $stmt = $pdo->query($sql);
    
    /*
    //テーブル参照
    $sql ='SHOW CREATE TABLE mission5';
    $result = $pdo -> query($sql);
    foreach ($result as $row){
        echo $row[1];
    }
    echo "<hr>";
    */
    
    //データ登録（INSERT文）(新規投稿)　
    if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password"]) && empty($_POST["now_editnum"])){
        $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':password', $password, PDO::PARAM_STR);
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $date = date("Y/m/d H:i:s");
        $password = $_POST["password"];
        
        $sql -> execute();
    }
    
    //投稿編集フォームの呼び出し　
    if(!empty($_POST["editnum"]) && !empty($_POST["edit_pass"])){
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $date = date("Y/m/d H:i:s");
         //編集対象番号
        $editnum = $_POST["editnum"];
        $edit_pass = $_POST["edit_pass"];
        
        $sql = 'SELECT * FROM mission5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            $id = $row['id'];
            $password = $row['password'];
            
            if($id == $editnum && $password == $edit_pass){
                $edit_name = $row['name'];
                $edit_comment = $row['comment'];
            }
        }
    }
    
    //UPDATE文（投稿編集）
    if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password"]) && !empty($_POST["now_editnum"])){
        //編集実行
            //データ受け取りの変数
            $id = $_POST["now_editnum"]; //編集番号
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $password = $_POST["password"];
            
            $sql = 'UPDATE mission5 SET name=:name,comment=:comment,password=:password WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            $stmt->execute();
    }
    
    //DELETE文（投稿削除）
    if(!empty($_POST["delnum"]) && !empty($_POST["del_pass"])){
        //削除番号の取得
        $delnum = $_POST["delnum"];
        $del_pass = $_POST["del_pass"];
        
        $sql = 'SELECT * FROM mission5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            $id = $row['id'];
            $password = $row['password'];
            
            if($delnum == $id && $del_pass == $password){
                $sql = 'delete from mission5 where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $delnum, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    }
?>

<html lang="ja">
<head>
<meta charset="UTF-8">
<title>Mission_5-1</title>
</head>
<body>
    <h1>お気に入りのアニメ</h1>
    <form action = "" method = "POST">
        
        <!--投稿フォーム-->
        <h1>投稿</h1>
        <input type = "text" name = "name" placeholder = "名前を入力" value=<?php if(!empty($edit_name)){echo $edit_name;} ?>><br/>
        <input type = "text" name = "comment" placeholder = "コメントを入力" value=<?php if(!empty($edit_comment)){echo $edit_comment;} ?>><br/>
        <input type = "text" name = "password" placeholder = "パスワードを入力"><br/>
        <input type = "submit" name = "submit" value = "投稿"><br/>
        
        <!--削除フォーム-->
        <h1>削除</h1>
        <input type = "text" name = "delnum" placeholder = "削除対象番号を入力"><br/>
        <input type = "text" name = "del_pass" placeholder = "パスワードを入力"><br/>
        <input type = "submit" name = "delete" value = "削除"><br/>
        
        <!--編集フォーム-->
        <h1>編集</h1>
        <input type = "text" name = "editnum" placeholder = "編集対象番号を入力"><br>
        <input type = "text" name = "edit_pass" placeholder = "パスワードを入力"><br>
        <input type = "submit" name = "edit" value = "編集"><br/>
        <input type = "hidden" name = "now_editnum" value = <?php if(!empty($editnum)){echo $editnum;}?>>
        
    </form>
    </body>
</html>
    
<?php
    //表示用
    $sql = 'SELECT * FROM mission5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['date'].',';
            echo $row['password'].',';
        echo "<hr>";
        }
?>