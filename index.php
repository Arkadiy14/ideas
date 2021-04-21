<?php
$link = pg_connect("CONNECT");

$topics = [
    'art' => ['movie' => 'movie', 'book' => 'book', 'literature' => 'literature', 'film' => 'film', 'theatre' => 'theatre', 'dance' => 'dance', 'painting' => 'painting', 'music' => 'music'], 

    'science' => ['biology' => 'biology', 'chemistry' => 'chemistry', 'maths' => 'maths', 'math' => 'math', 'linguistics' => 'linguistics', 'physics' => 'physics', 'geography' => 'geography'],

    'programming' => ['program' => 'program', 'script' => 'script', 'bot' => 'bot', 'code' => 'code', 'array' => 'array', 'value' => 'value'],
];// here are our topics

if(isset($_POST['button'])) {
    $query = pg_prepare($link, "my_query", 'INSERT INTO ideas (idea, description) VALUES ($1, $2)');
    $info = explode(':', $_POST['idea']);

    if(isset($info[0]) && isset($info[1])) {
	$idea = $info[0];
	$description = $info[1];
	$idea_words = str_word_count($idea, 1); // here we have everything from user's idea
	$descr_words = str_word_count($description, 1); // here we have everything from user's description

        $values = array_merge($topics['art'], $topics['science'], $topics['programming']);

        $same_idea = array_intersect($idea_words, $values); // looking for identical words using idea
        $same_descr = array_intersect($descr_words, $values); // looking for identical words using descr

        $idea_word = explode(' ', implode(' ', $same_idea));
        $descr_word = explode(' ', implode(' ', $same_descr));

        $topic = 0; // main topic
	    
	foreach($topics as $k => $v) {
	    if($topics[$k][$idea_word[0]] && $topics[$k][$descr_word[0]]) {
	        $topic = $k;
	        break;
            }else {
	        $topic = 'other';
	    }

        }

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
    width: 600px;
    margin: 0 auto;
    margin-top: 50px;
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
