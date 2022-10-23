<?php
    require_once '_connec.php';
    $pdo = new \PDO(DSN, USER, PASS);
    $query = "SELECT * FROM friend";
    $statement = $pdo->query($query);
    $friends = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amis</title>
</head>
<body>
    <ul>
        <?php foreach ($friends as $friend) : ?>
            <li><?= $friend['firstname'] . ' ' . $friend['lastname'] ?> </li>
        <?php endforeach; ?>
    </ul>
    <br> <br> <br>
    <form action="" method="post">
        <h1>Nouveaux amis !</h1>
        <div>
            <label for="firstname">Nom</label>
            <input type="text" name="friend_firstname" id="firstname" required>
        </div>
        <br>
        <div>
            <label for="lastname">Prenom</label>
            <input type="text" name="friend_lastname" id="lastname" required>
        </div>
        <br><br>
        <button type="submit">Envoyer</button>
    </form>
</body>
</html>
<?php
require_once '_connec.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = htmlentities($data);
        return $data;
    }
    $errors = [];
    foreach ($_POST as $key => $value) {
        $data[$key] = test_input($value);
        if (!isset($data[$key])) {
            $errors[] = "$data[$key] is required";
        } elseif (strlen($data[$key]) > 45) {
            $errors[] = "$data[$key] cannot exceed 45 characters.";
        }
    }
    if ($errors) {
        var_dump($errors);
    } else {
        $pdo = new \PDO(DSN, USER, PASS);
        $lastname = $data['friend_lastname'];
        $firstname = $data['friend_firstname'];
        $query = "INSERT INTO friend (lastname, firstname) VALUES (:lastname, :firstname);";
        $statement = $pdo->prepare($query);
        $statement->bindValue(':lastname', $lastname, \PDO::PARAM_STR);
        $statement->bindValue(':firstname', $firstname, \PDO::PARAM_STR);
        $statement->execute();
        $friends = $statement->fetchAll();
        header("Location: index.php");
    }
}