<!doctype html>
<html lang="eo">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="icon-small.png" sizes="32x32">
    <link rel="icon" href="icon.png" sizes="192x192">
    <link rel="apple-touch-icon-precomposed" href="icon-apple.png">
    <title>Listo de aliĝintoj al IJK 2022 en Nederlando</title>
    <meta property="og:site_name" content="IJK 2022">
    <meta name="description" content="La 78a IJK de TEJO okazos en De Roerdomp – Westelbeers, Nederlando, inter la 20-a kaj la 27-a de aŭgusto 2022. Ĝin organizos la Nederlanda Esperanto-Junularo.">
    <meta property="og:description" content="La 78a IJK de TEJO okazos en De Roerdomp – Westelbeers, Nederlando, inter la 20-a kaj la 27-a de aŭgusto 2022. Ĝin organizos la Nederlanda Esperanto-Junularo.">
</head>
<body>
    <main>
        <table>
        <tr>
            <th>#</th>
            <th>Nomo</th>
            <th>Lando</th>
        </tr>
        <?php

        require('countries.php');

        $data = @file_get_contents("alighoj.csv");

        if (!is_string($data)) {
            exit;
        }

        $data = explode("\n", trim($data));

        $i = 1;

        foreach ($data as $value) {
            $value = @json_decode($value, true);

            if (!is_array($value)) {
                continue;
            }

            if (!$value['listo']) {
	            $name = !empty($value['shildnomo']) ? $value['shildnomo'] : $value['nomo'];
            } else {
                $name = 'Anonima aliĝinto';
            }

            $residenceCountry = $value['shildlando'];

            if (empty($residenceCountry)) {
                foreach ($countries as $country) {
                    if ($country[0] === $value['loghlando']) {
                        $residenceCountry = $country[1];
                        break;
                    }
                }
            }

            echo "<tr>";
            echo "<td>" . $i++ . "</td>";
            echo "<td>" . $name . "</td>";
            echo "<td>" . $residenceCountry . "</td>";
            echo "</tr>";
        }

        ?>
        </table>
    </main>
</body>
</html>
