<?php
$link = pg_connect("CONNECT");

if(isset($_POST['button'])) {
    $info = explode(':', trim($_POST['idea']));

    if(!empty($info[0]) && !empty($info[1]) && !is_numeric($info[0]) && !is_numeric($info[1])) {
	$idea = $info[0];
	$description = $info[1];
	$topic = $_POST['topics'];

	$query = pg_prepare($link, "my_query", 'INSERT INTO ideas (idea, description, topic) VALUES ($1, $2, $3)');
	$result = pg_execute($link, "my_query", array($idea, $description, $topic));
        header('location: https://'.$_SERVER['HTTP_HOST']);	
    }else {
	header('location: https://'.$_SERVER['HTTP_HOST']);	
    }
}
?>

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
  <select name="topics">
   <option value="art">Art</option>
   <option value="science">Science</option>
   <option value="programming">Programming</option>
   <option value="other">Other</option>
  </select>
  <input class="add" name="idea" placeholder="Idea:description" type="text">
  <button name="button" type="submit">Add</button>
</form>
<div class="a"><a href="search.php">Want to find any idea? Click here!</a></div>
	
<?php
$query = pg_query($link, "SELECT * FROM ideas;");

while($result = pg_fetch_assoc($query)) {
echo '<div class="idea">'.$result['idea'].'<div class="topic">'.$result['topic'].'</div><div class="description">'.$result['description'].'</div></div>';
}
?>
	
<style>
* {
    box-sizing: border-box;
    font-family: 'Roboto', sans-serif;
}

form {
    position: relative;
    width: 700px;
    margin: 0 auto;
    margin-top: 50px;
}

select {
    position: absolute;
    top: 0;
    left: 0;
    height: 50px;
    width: 135px;
    border: 2px solid #7BA7AB;
    border-radius: 5px;
    outline: none;
    background: #F9F0DA;
    color: #575555;
    font-size: 18px;
}	

.add {
    width: 100%;
    height: 50px;
    padding-left: 10px;
    border: 2px solid #7BA7AB;
    border-radius: 5px;
    outline: none;
    background: #F9F0DA;
    color: #575555;
    font-size: 18px;
    padding-left: 138px;
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
    margin-top: 20px;
    margin-bottom: -40px;
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
