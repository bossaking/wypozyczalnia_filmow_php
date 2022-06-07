<?php
include_once('header.php');

$conn = OpenConnection();
$getMovies = "SELECT * FROM Film INNER JOIN GatunekFilmu ON Film.id_gatunek = GatunekFilmu.id_gatunek";

if (isset($_GET['gatunek'])) {
    $type = $_GET['gatunek'];

    $getMovies = "SELECT * FROM Film WHERE id_gatunek = '$type' INNER JOIN GatunekFilmu ON Film.id_gatunek = GatunekFilmu.id_gatunek";
}

$result = $conn->query($getMovies);
$movies = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($movies, $row);
    }
}

?>

<div class="movies-container">
    <?php
    foreach ($movies as $movie) {
        ?>

        <div class="movie-card">
            <header>
                <span><?= $movie['tytul'] ?></span>
                <div class="subtitle">
                    <p class="movie-type"><?= $movie['nazwa'] ?></p>
                    <?php
                    if ($movie['czy_wypozyczony'] == 'T') {
                        echo '<p class="not-available">Niedostępny</p>';
                    } else {
                        echo '<p class="available">Dostępny</p>';
                    }
                    ?>
                </div>
            </header>
            <?php
            if ($movie['czy_wypozyczony'] == 'T') {
                echo '<hr class="red-hr">';
            } else {
                echo '<hr class="green-hr">';
            }
            ?>
            <section class="movie-card-body">
                <p><?= $movie['opis'] ?></p>
            </section>
            <?php
            if ($movie['czy_wypozyczony'] == 'T') {
                echo '<hr class="red-hr">';
            } else {
                echo '<hr class="green-hr">';
            }
            ?>
            <footer>
                <p class="movie-director"><?=$movie['rezyser']?></p>
                <p class="movie-price"><?php
                        echo substr($movie['cena'], 0, -2) . " zł";
                    ?></p>
            </footer>
        </div>

        <?php
    }
    ?>
</div>


<?php include_once('footer.php') ?>
