<?php
include_once('header.php');

$name = "";
$surname = "";
$login = "";
$address = "";
$dateOfBirth = date("d.m.Y");
$phone = "";
$email = "";
$password = "";
$repeatPassword = "";

if (isset($_SESSION['errors'])) {
    unset($_SESSION['errors']);
}

if (isset($_POST['register'])) {

    $errors = array();

    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $login = $_POST['login'];
    $address = $_POST['address'];
    $dateOfBirth = $_POST['dateOfBirth'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repeatPassword = $_POST['repeatPassword'];

    if (strcmp($password, $repeatPassword) === 0) {
        $conn = OpenConnection();
        $checkUniqueEmail = "SELECT * FROM Klient WHERE email = '$email'";
        $checkUniqueLogin = "SELECT * FROM Klient WHERE login = '$login'";
        if($conn->query($checkUniqueEmail)->num_rows != 0){
            array_push($errors, "Podany adres e-mail już zarejestrowany");
        }
        if($conn->query($checkUniqueLogin)->num_rows != 0){
            array_push($errors, "Podany login jest już zarejestrowany");
        }

        if(count($errors) == 0){
            $insert = "INSERT INTO Klient (imie, nazwisko, login, email, adres, haslo, data_urodzenia, telefon) VALUES 
                ('$name', '$surname', '$login', '$email', '$address', '$password', '$dateOfBirth', '$phone')";
            if($conn->query($insert) === true){
                $_SESSION['user']['name'] = $name;
                $_SESSION['user']['surname'] = $surname;
                header('Location:index.php');
            }else{
                array_push($errors, $conn->error);
            }
        }

    } else {
        array_push($errors, "Hasła nie są ze soba zgodne");
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
        <div class="form-row">
            <div class="form-field">
                <label for="name">Imię</label>
                <input type="text" name="name" required id="name" placeholder="Jan" value="<?=$name?>">
            </div>
            <div class="form-field">
                <label for="surname">Nazwisko</label>
                <input type="text" name="surname" required id="surname" placeholder="Kowalski" value="<?=$surname?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-field">
                <label for="email">Adres e-mail</label>
                <input type="email" name="email" required id="email" placeholder="jan.kowalski@o2.pl" value="<?=$email?>">
            </div>
            <div class="form-field">
                <label for="phone">Telefon</label>
                <input type="number" name="phone" required id="phone" placeholder="123456789" value="<?=$phone?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-field">
                <label for="address">Adres</label>
                <input type="text" name="address" required id="address" placeholder="ul. Warszawska 31" value="<?=$address?>">
            </div>
            <div class="form-field">
                <label for="dateOfBirth">Data urodzenia</label>
                <input type="date" name="dateOfBirth" required id="dateOfBirth" value="<?=$dateOfBirth?>">
            </div>
        </div>
        <div class="form-field">
            <label for="login">Login</label>
            <input type="text" name="login" required id="login" placeholder="JKowalski" value="<?=$login?>">
        </div>
        <div class="form-row">
            <div class="form-field">
                <label for="password">Hasło</label>
                <input type="password" name="password" required id="password" value="<?=$password?>">
            </div>
            <div class="form-field">
                <label for="repeatPassword">Powtóż hasło</label>
                <input type="password" name="repeatPassword" required id="repeatPassword" value="<?=$repeatPassword?>">
            </div>
        </div>

        <button type="submit" class="primary-button button" name="register">Zarejestruj się</button>
    </form>

    <div class="sign-question">
        <span>Masz już konto?</span>
        <a href="login.php">Przejdź do logowania!</a>
    </div>

</div>


<?php include_once('footer.php') ?>
