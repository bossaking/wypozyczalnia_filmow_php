<?php
include_once('header.php');

$conn = OpenConnection();

$movie = null;

if (isset($_GET['id'])) {

    $id = $_GET['id'];
    $select = "SELECT * FROM Film WHERE id_film = '$id'";

    $result = $conn->query($select);

    if ($result->num_rows == 0) {
        header('Location: index.php');
    }

    $movie = $result->fetch_assoc();

} else {
    header('Location: index.php');
}

?>

<div class="movie-container">
    <form class="movie">
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
            ?>
            <button type="submit" class="button primary-button"><?php
                echo "Wypożycz (" . substr($movie['cena'], 0, -2) . " zł)";
                ?></button>
            <?php
        }
        ?>
    </form>
</div>

<?php
include_once('footer.php');
?>
