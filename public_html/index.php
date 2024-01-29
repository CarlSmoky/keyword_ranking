<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keyword Ranking Checker</title>
</head>

<style type="text/css">
    <?php include "./css/style.css"; ?>
</style>

<body>
    <section class="section_wrapper">
        <div class="input_wrapper">
            <form method="post" action="">
                <label for="keyword">Enter Keyword:</label>
                <input type="text" name="keyword" placeholder="Your keyword" required>

                <label for="website">Enter Website:</label>
                <input type="url" name="website" placeholder="example.com" required>

                <button type="submit">Research</button>
            </form>
        </div>
    </section>

    <?php
    require './google-search-results.php';
    require './restclient.php';

    $env = parse_ini_file('../private/config.php');
    $API = $env["SERPAPI_KEY"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $keyword = urlencode($_POST["keyword"]);
        $website = rawurldecode($_POST["website"]);
        $display_keyword = urldecode($keyword);

        $query = [
            "q" => "{$keyword}",
            "location" => "Toronto, Ontario, Canada",
            "hl" => "en",
            "gl" => "ca",
            "google_domain" => "google.ca",
        ];

        $client = new GoogleSearch($API);
        $json_results = $client->get_json($query);

        echo "
            <table>
                <tr>
                <td>keyword: </td>
                <td>{$display_keyword}</td>
                </tr>
                <tr>
                <td>website: </td>
                <td>{$website}</td>
                </tr>
                </table>";
        foreach ($json_results->organic_results as $value) {
            $pos = strpos($value->link, $website);
            if ($pos !== false) {
                echo "<p>The ranking is {$value->position}!</p>";
                return;
            }
        }
        echo "<p>Unable to retrieve ranking. Please check your inputs.</p>";
    }



    ?>
</body>

</html>