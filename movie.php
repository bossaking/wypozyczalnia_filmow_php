<?php
ob_start();
include_once('header.php');

$conn = OpenConnection();

$movie = null;
$order = null;

if (isset($_GET['id'])) {

    $id = $_GET['id'];
    $select = "SELECT * FROM Film WHERE id_film = '$id'";

    $result = $conn->query($select);

    if ($result->num_rows == 0) {
        header('Location: index.php');
        exit();
    }

    $movie = $result->fetch_assoc();

    if ($movie['czy_wypozyczony'] == 'T') {
        $getOrder = "SELECT * FROM Film f INNER JOIN Rezerwacja r ON f.id_film = r.id_film INNER JOIN Klient k ON r.id_klient = k.id_klient";
        $order = $conn->query($getOrder)->fetch_assoc();
    }

} else {
    header('Location: index.php');
    exit();
}

?>

<div class="movie-container">
    <form class="movie" method="get" action="new_order.php">
        <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
        <header>
            <h2><?= $movie['tytul'] ?></h2>
            <?php
            if ($movie['czy_wypozyczony'] == 'T') {
                echo '<p class="not-available">Niedostępny</p>';
            } else {
                echo '<p class="available">Dostępny</p>';
            }
            ?>
        </header>
        <?php
        if ($movie['czy_wypozyczony'] == 'T') {
            echo '<hr class="red-hr">';
        } else {
            echo '<hr class="green-hr">';
        }
        ?>
        <div class="movie-body">
            <?= $movie['opis'] ?>
        </div>
        <?php
        if (!isset($_SESSION['user'])) {
            ?>
            <div class="sign-question">
                <span>Chcesz wypożyczyć ten film?</span>
                <a href="login.php">Zaloguj się!</a>
            </div>
            <?php
        } else {

            if ($order == null) {

                ?>
                <button type="submit" class="button primary-button"><?php
                    echo "Wypożycz (" . substr($movie['cena'], 0, -2) . " zł)";
                    ?></button>
                <?php
            } else {
                ?>
                    <hr>
                <span style="margin: auto">
                    <?php
                        echo "Wypożyczony przez " . $order['imie'] . " " . $order['nazwisko'];
                    ?>
                </span>
                <?php
            } ?>
            <?php
        }
        ?>
    </form>
</div>

<?php
include_once('footer.php');
?>
