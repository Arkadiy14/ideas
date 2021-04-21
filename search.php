<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ideas</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
<form action="<?=$_SERVER['SCRIPT_NAME'];?>" method="post">
  <input class="search" name="query" placeholder="Type your query" type="search">
  <button name="button" type="submit">Find</button>
</form>
<div class="a"><a href="https://ideashouse.herokuapp.com/">Back</a></div>
	
<?php
if(isset($_POST['button']) && !empty($_POST['query'])) {
    $link = pg_connect("CONNECT");
    $words = explode(' ', $_POST['query']);

    function newString($a) {
	return '%'.$a.'%';
    }

    $words = array_map('newString', $words);
    $words = implode(' ', $words);
    $query = pg_prepare($link, "search_query", 'SELECT * FROM ideas WHERE idea LIKE $1');
    $execute = pg_execute($link, "search_query", array($words));
    while($result = pg_fetch_assoc($execute)) {
        echo '<div class="idea">'.$result['idea'].'<div class="topic">'.$result['topic'].'</div>
		<div class="description">'.$result['description'].'</div></div>';
    }
}
?>
	
<style>
* {
    box-sizing: border-box;
    font-family: 'Roboto', sans-serif;
}

form {
    position: relative;
    width: 600px;
    margin: 0 auto;
    margin-top: 50px;
}

.search {
    width: 100%;
    height: 50px;
    padding-left: 10px;
    border: 2px solid #7BA7AB;
    border-radius: 5px;
    outline: none;
    background: #F9F0DA;
    color: #575555;
    font-size: 18px;
}

button {
    position: absolute; 
    top: 0;
    right: 0px;
    width: 70px;
    height: 50px;
    border: none;
    background: #7BA7AB;
    border-radius: 0 5px 5px 0;
    cursor: pointer;
    color: #575555;
    font-size: 16px;
}

.idea {
    width: 600px;
    margin: 0 auto;
    font-size: 20px;
    margin-top: 30px;
    padding-left: 1px;
}

.description {
    font-size: 14px;
}

.a {
    text-align: center;
    margin-top: 17px;
    margin-bottom: -25px;
}

a {
    font-size: 15px;
    text-decoration: none;
}
	
.topic {
    font-size: 15px;
    float: right;
    margin-top: 3px;
}
</style>
</body>
</html>
