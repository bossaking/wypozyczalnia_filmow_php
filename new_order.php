<?php
ob_start();
include_once('header.php');

$movie = null;

if(!isset($_GET['id'])){
    header("Location: index.php");
    exit();
}else{
    $id = $_GET['id'];
    $select = "SELECT * FROM Film WHERE id_film = '$id'";

    $conn = OpenConnection();
    $movie = $conn->query($select)->fetch_assoc();
}

if(isset($_POST['payment_type'])){

    $conn = OpenConnection();

    $paymentType = $_POST['payment_type'];
    $days = $_POST['days_count'];

    $username = $_SESSION['user']['username'];

    $price = $movie['cena'];
    $paymentStatus = "";

    switch ($paymentType){
        case "Karta":
            $price = $movie['cena'];
            $paymentType = 0;
            $paymentStatus = "Zakończona";
            break;
        case "Podzielona":
            $price = ($price + 0.01) / 2;
            $paymentType = 1;
            $paymentStatus = "Zakończona";
            break;
        case "Gotówka":
            $price = 0;
            $paymentType = 2;
            $paymentStatus = "W trakcie";
            break;
    }

    $billId = createBill($price, $paymentType, $conn);

    $getUserId = "SELECT * FROM Klient WHERE login = '$username'";
    $userId = $conn->query($getUserId)->fetch_assoc()['id_klient'];

    $paymentId = createPayment($userId, $price, $billId, $paymentStatus, $conn);

    $orderId = createOrder($userId, $movie['id_film'], $paymentId, $days, $conn);

    $movieId = $movie['id_film'];

    $update = "UPDATE Film SET czy_wypozyczony = 'T' WHERE id_film = '$movieId'";
    $conn -> query($update);

    header("Location: movie.php?id=".$movieId);
    exit();

}

function createBill($price, $paymentType, $conn){

    $number = bin2hex(random_bytes(5));
    $date = date("Y/m/d");

    $create = "INSERT INTO Rachunek (sposob_platnosci, data_wystawienia, kwota, numer) VALUES ('$paymentType', '$date', '$price', '$number')";

    $conn->query($create);
    return $conn->insert_id;
}

function createPayment($idUser, $price, $idBill, $status, $conn){

    $date = date("Y/m/d");

    $create = "INSERT INTO Platnosc (id_klient, data_platnosci, wartosc, id_rachunek, status) VALUES ('$idUser', '$date', '$price', '$idBill', '$status')";
    $conn->query($create);
    return $conn->insert_id;

}

function createOrder($idUser, $idMovie, $idPayment, $days, $conn){

    $date = date("Y/m/d");
    $create = "INSERT INTO Rezerwacja (id_klient, id_film, data_wyporzyczenia, ile_dni, id_platnosci) VALUES ('$idUser', '$idMovie', '$date', '$days', '$idPayment')";
    $conn->query($create);

    return $conn->insert_id;
}


?>

<script src="js/new_order.js"></script>

<div class="order-container">
    <input type="hidden" value="<?=$movie['cena']?>" id="price">
    <h2><?=$movie['tytul']?></h2>
    <span style="color: #14C38E"><?php
        echo "Cena: " . substr($movie['cena'], 0, -2) . " zł";
        ?></span>


    <div class="summary">
        <span id="summary">
        </span>
    </div>

    <form class="order-form" style="margin-top: 2rem" method="post">
        <div class="form-field">
            <label for="payment_type">Metoda płatności</label>
            <select id="payment_type" name="payment_type">
                <option value="Karta">Kartą płatniczą</option>
                <option value="Podzielona">Płatność 50 / 50</option>
                <option value="Gotówka">Płatność gotówką na miejscu</option>
            </select>
        </div>
        <div class="form-field">
            <label for="days_count">Ilość dni</label>
            <select id="days_count" name="days_count">
                <option value="1">1</option>
                <option value="3">3</option>
                <option value="5">5</option>
            </select>
        </div>

        <div class="form-row">
            <button type="submit" class="button accept-button">Zatwierdź</button>
            <button type="button" class="button cancel-button" id="cancel">Anuluj</button>
        </div>

    </form>

</div>

<?php
include_once('footer.php');
?>
