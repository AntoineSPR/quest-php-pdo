<?php 
require_once "_connec.php";
$pdo = new \PDO(DSN, USER, PASS);

$statement = $pdo->query("SELECT * FROM friend");
$myFriends = $statement->fetchAll(\PDO::FETCH_ASSOC);

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $names = array_map("htmlentities", array_map("trim", $_POST));
    
    $firstname = $names["firstname"];
    $lastname = $names["lastname"];

    if (empty($firstname) || empty($lastname)) {
        $errors[] = "You dare mess with the Inspector! The Old Ones shall not forgive this insult.";
    }

    if (strlen($firstname) > 45 || strlen($lastname) > 45) {
        $errors[] = "You can stop adding titles to your friend's name now...";
    }

    if (empty($errors)) {
        $statement = $pdo->prepare("INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname);");
        $statement->bindValue(':firstname', $firstname, \PDO::PARAM_STR);
        $statement->bindValue(':lastname', $lastname, \PDO::PARAM_STR);
        $statement->execute();
        header('Location: index.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Friends!</title>
    </head>
    <body>
        <h1>Here are my friends</h1>
        <!-- 
            Cthulhu The Sleeper of R'lyeh
            Nyarlathotep The Messenger
            Dagon Lord of the Abyss
            Azathoth The Dreamer
        -->
        <?php foreach($myFriends as $myFriend) : ?>
            <ul>
                <li> <?= $myFriend['firstname'] . ' ' . $myFriend['lastname'] ?> </li>
            </ul>
        <?php endforeach ?>
        <h2>Did you make more friends today?</h2>
        <form action="" method="post">
        <label for="firstname">Your friend's firstname</label>
        <input type="text" name="firstname" id="firstname" required><br>
        <label for="lastname">Your friend's lastname</label>
        <input type="text" name="lastname" id="lastname" required><br>
        <input type="submit" value="Add a friend">
        </form>
        <?php foreach($errors as $error) : ?>
        <p>It looks like you made a mistake in the face of the Old Ones!</p>
        <ul>
            <li> <?= $error ?> </li>
        </ul>
        <?php endforeach ?>
    </body>
</html>
