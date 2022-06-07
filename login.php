<?php
include_once('header.php');

if (isset($_SESSION['errors'])) {
    unset($_SESSION['errors']);
}

if(isset($_POST['login'])){
    $errors = array();

    $login = $_POST['login'];
    $password = $_POST['password'];

    $conn = OpenConnection();

    $checkQuery = "SELECT * FROM Klient WHERE email = '$login' OR login = '$login'";
    $result = $conn->query($checkQuery);
    if($result->num_rows == 0){
        array_push($errors, "Niepoprawna nazwa użytkownika lub adres e-mail");
    }else{
        $user = $result->fetch_assoc();
        if(strcmp($user['haslo'], $password) !== 0){
            array_push($errors, "Niepoprawne hasło");
        }else{
            $_SESSION['user']['name'] = $user['imie'];
            $_SESSION['user']['surname'] = $user['nazwisko'];
            header('Location:index.php');
        }

    }
    $_SESSION['errors'] = $errors;
}

?>


<div class="form-container">

    <?php
    if (isset($_SESSION['errors'])) {
        ?>
        <div class="form-errors">
            <ul>
                <?php
                foreach ($_SESSION['errors'] as $error) {
                    echo '<li>' . $error . '</li>';
                }
                ?>
            </ul>
        </div>
        <?php
    }
    ?>


    <form method="post">

        <div class="form-field">
            <label for="login">Login lub adres e-mail</label>
            <input type="text" name="login" required id="login">
        </div>
        <div class="form-field">
            <label for="password">Hasło</label>
            <input type="password" name="password" required id="password">
        </div>

        <button type="submit" class="primary-button button">Zaloguj się</button>
    </form>

    <div class="sign-question">
        <span>Nie masz konta w naszym serwisie?</span>
        <a href="registration.php">Przejdź do rejestracji!</a>
    </div>

</div>


<?php include_once('footer.php') ?>
