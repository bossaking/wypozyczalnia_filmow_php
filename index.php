<?php
ob_start();
include_once('header.php');

$conn = OpenConnection();
$getMovies = "SELECT * FROM Film f INNER JOIN GatunekFilmu gf ON f.id_gatunek = gf.id_gatunek";

if (isset($_GET['gatunek'])) {
    $type = $_GET['gatunek'];
    $getMovies = $getMovies . " WHERE f.id_gatunek = '$type'";
    if(isset($_GET['dostepny'])){
        $available = $_GET['dostepny'];
        $getMovies = $getMovies . " AND f.czy_wypozyczony = 'N'";
    }
}else{
    if(isset($_GET['dostepny'])){
        $available = $_GET['dostepny'];
        $getMovies = $getMovies . " WHERE f.czy_wypozyczony = 'N'";
    }
}

$result = $conn->query($getMovies);
$movies = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($movies, $row);
    }
}

$getTypes = "SELECT * FROM GatunekFilmu";
$types = array();
$result = $conn->query($getTypes);
while ($row = $result->fetch_assoc()) {
    array_push($types, $row);
}
?>

<div class="filter-container">
    <select name="type" id="filter-select">
        <?php
        if (!isset($_GET['gatunek'])) {
            echo '<option value="0" selected>Wszystkie gatunki</option>';
        } else {
            echo '<option value="0">Wszystkie gatunki</option>';
        }
        foreach ($types as $type) {
            if (isset($_GET['gatunek']) && $_GET['gatunek'] == $type['id_gatunek']) {
                echo '<option value="' . $type['id_gatunek'] . '" selected>' . $type['nazwa'] . '</option>';
            } else {
                echo '<option value="' . $type['id_gatunek'] . '">' . $type['nazwa'] . '</option>';
            }
        }
        ?>
    </select>
    <select name="type" id="filter-available-select">
        <?php
        if (!isset($_GET['dostepny'])) {
            echo '<option value="0" selected>Wszystkie filmy</option>';
            echo '<option value="1">Tylko dostępne</option>';
        } else {
            echo '<option value="0">Wszystkie filmy</option>';
            echo '<option value="1" selected>Tylko dostępne</option>';
        }
        ?>
    </select>
</div>

<div class="movies-container">

    <script src="js/index.js"></script>

    <?php
    foreach ($movies as $movie) {
        ?>

        <div class="movie-card" id="<?= $movie['id_film'] ?>">
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
                <p class="movie-director"><?= $movie['rezyser'] ?></p>
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
