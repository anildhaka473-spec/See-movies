<?php

session_start();

if (empty($_SESSION["logged_in"])) {

    header("Location: login.php");

    exit;
}

$movie = trim($_GET["movie"] ?? "");

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Movie Search</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<header class="topbar">

    <h1>🎬 Movie Search</h1>

    <a href="logout.php">Logout</a>

</header>


<main class="container">

    <form class="search-form" method="GET" action="index.php">

        <input
            type="text"
            name="movie"
            value="<?= htmlspecialchars($movie) ?>"
            placeholder="Enter movie name..."
            required
        >

        <button type="submit">
            Search
        </button>

    </form>


<?php

if ($movie !== "") {

    /*
       API key Render ke Environment Variables
       se li jayegi.
    */

    $apiKey = getenv("OMDB_API_KEY");


    if (!$apiKey) {

        echo '
        <div class="message error">
            OMDB API key is not configured.
        </div>';

    } else {

        $url =
            "https://www.omdbapi.com/?" .
            "apikey=" . urlencode($apiKey) .
            "&t=" . urlencode($movie) .
            "&plot=full";


        $response = @file_get_contents($url);


        if ($response === false) {

            echo '
            <div class="message error">
                Could not connect to movie API.
            </div>';

        } else {

            $data = json_decode($response, true);


            if (!$data || ($data["Response"] ?? "False") !== "True") {

                echo '
                <div class="message error">
                    ' .
                    htmlspecialchars(
                        $data["Error"] ?? "Movie not found"
                    )
                    .
                    '
                </div>';

            } else {

                $poster = $data["Poster"] ?? "";

?>

<section class="movie-card">

    <div class="poster">

        <?php if ($poster && $poster !== "N/A"): ?>

            <img
                src="<?= htmlspecialchars($poster) ?>"
                alt="<?= htmlspecialchars($data["Title"]) ?>"
            >

        <?php else: ?>

            <div class="no-poster">
                No Poster
            </div>

        <?php endif; ?>

    </div>


    <div class="details">

        <h2>
            <?= htmlspecialchars($data["Title"]) ?>
        </h2>


        <p>
            <strong>Year:</strong>
            <?= htmlspecialchars($data["Year"] ?? "N/A") ?>
        </p>


        <p>
            <strong>Genre:</strong>
            <?= htmlspecialchars($data["Genre"] ?? "N/A") ?>
        </p>


        <p>
            <strong>Director:</strong>
            <?= htmlspecialchars($data["Director"] ?? "N/A") ?>
        </p>


        <p>
            <strong>Actors:</strong>
            <?= htmlspecialchars($data["Actors"] ?? "N/A") ?>
        </p>


        <p>
            <strong>Runtime:</strong>
            <?= htmlspecialchars($data["Runtime"] ?? "N/A") ?>
        </p>


        <p>
            <strong>IMDb Rating:</strong>
            <?= htmlspecialchars($data["imdbRating"] ?? "N/A") ?>
        </p>


        <p>
            <strong>Language:</strong>
            <?= htmlspecialchars($data["Language"] ?? "N/A") ?>
        </p>


        <p>
            <strong>Plot:</strong>
            <?= htmlspecialchars($data["Plot"] ?? "N/A") ?>
        </p>

    </div>

</section>

<?php

            }
        }
    }
}

?>

</main>

</body>

</html>
