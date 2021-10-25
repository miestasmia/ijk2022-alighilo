<?php
  setlocale(LC_ALL, 'eo');
  require('countries.php');
  $countrySelectArr = array_map(
    function ($arr) {
      $emojiRange = array_map(function ($codepoint) {
        return '&#' . $codepoint . ';';
      }, range(127462, 127847));
      $emoji = str_replace(range('A', 'Z'), $emojiRange, $arr[0]);
      return '<option value="' . $arr[0] . '">' . "$emoji " . $arr[1] . '</option>';
    },
    $countries
  );
  array_unshift($countrySelectArr, '<option selected disabled value="">- Bonvolu elekti -</option>');
  $countrySelect = implode('\n', $countrySelectArr);
?>

<!doctype html>
<html lang="eo">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
    <link rel="icon" href="icon-small.png" sizes="32x32">
    <link rel="icon" href="icon.png" sizes="192x192">
    <link rel="apple-touch-icon-precomposed" href="icon-apple.png">
    <title>Aliĝi al IJK 2022 en Nederlando</title>
  </head>
  <body>
      <main>
      <h1>Aliĝilo de la IJK 2022 en De Roerdomp, Westelbeers, Nederlando</h1>
      <form method="post" action="alighi.php">
        <div class="mb-3">
          <label for="kampo-nomo">Via plena nomo</label>
          <input name="nomo" id="kampo-nomo" class="form-control" required maxlength="100">
          <div class="form-text">
            Via plena nomo tiel kiel aperas en via pasporto.
          </div>
        </div>

        <div class="mb-3">
          <label for="kampo-shildnomo">Via ŝildnomo</label>
          <input name="shildnomo" id="kampo-shildnomo" class="form-control" maxlength="30">
          <div class="form-text">
            La nomo kiun vi volas sur via nomŝildo, se alia ol via plena nomo.
          </div>
        </div>

        <div class="mb-3">
          <label for="kampo-retposhtadreso">Via retpoŝtadreso</label>
          <input name="retposhtadreso" id="kampo-retposhtadreso" type="email" class="form-control" maxlength="200" required>
          <div class="form-text">
            Via retpoŝtadreso. Kontrolu ke ĝi estas ĝusta, ĉar ni sendos al ĝi informojn.
          </div>
        </div>

        <div class="mb-3">
          <label for="kampo-naskightago">Via naskiĝtago</label>
          <input name="naskightago" id="kampo-naskightago" type="date" class="form-control" required>
        </div>

        <div class="mb-3">
          <label for="kampo-loghlando">Via loĝlando</label>
          <select name="loghlando" id="kampo-loghlando" class="form-select" required>
            <?php
              echo $countrySelect;
            ?>
          </select>
          <div class="form-text">
            La lando en kiu vi (verŝajne) loĝos en aŭgusto 2022.
          </div>
        </div>

        <div class="mb-3">
          <label for="kampo-shildlando">Via ŝildlando</label>
          <input name="shildlando" id="kampo-shildlando" class="form-control" maxlength="20">
          <div class="form-text">
            Se vi volas aperigi alian landon ol tiu en kiu vi loĝas, skribu tion tie ĉi.
          </div>
        </div>

        <div class="mb-3 form-check form-switch">
          <input name="membreco" id="kampo-membreco" type="checkbox" class="form-check-input">
          <label for="kampo-membreco">Mi estas individua membro (IM) aŭ patrono de TEJO</label>
          <div class="form-text">
            Se vi estas individua membro aŭ patrono de TEJO vi ne ricevos malrabaton de €50. <a href="https://uea.org/alighoj/alighilo">Estas pli malmultekosta aliĝi al TEJO</a> ol ne ricevi la rabaton.
          </div>
        </div>

        <div class="mb-3" id="kamparo-ueakodo">
          <label for="kampo-ueakodo">Via UEA-kodo</label>
          <input name="ueakodo" id="kampo-ueakodo" maxlength="6" class="form-control">
          <div class="form-text">
            Bonvolu enmeti vian UEA-kodon. Se vi ĝin ne scias/memoras lasu la kampon malplena.
          </div>
        </div>

        <div class="mb-3 form-check form-switch">
          <input name="partopreno-plentempa" id="kampo-partopreno-plentempa" type="checkbox" class="form-check-input" checked>
          <label for="kampo-partopreno-plentempa">Mi ĉeestos la tutan tempon (la 20-a ĝis la 27-a de aŭgusto 2022)</label>
        </div>

        <div id="kamparo-partopreno">
          <div class="mb-3">
            <label for="kampo-alveno">Via alventago</label>
            <input name="alveno" id="kampo-alveno" type="date" min="2022-08-20" max="2022-08-27" class="form-control">
          </div>

          <div class="mb-3">
            <label for="kampo-foriro">Via forirtago</label>
            <input name="foriro" id="kampo-foriro" type="date" min="2022-08-20" max="2022-08-27" class="form-control">
          </div>
        </div>

        <div class="mb-3">
          <label for="kampo-loghado">Mi loĝos en …</label>
          <select name="loghado" id="kampo-loghado" class="form-select" required>
            <option value="" selected disabled>- Bonvolu elekti -</option>
            <option value="1">Luksa unuopa ĉambro (€250)</option>
            <option value="2">Luksa duopa ĉambro (€100)</option>
            <option value="kelk">Lukseta ĉambro (4-6 homoj) (€50)</option>
            <option value="amas">Amasĉambro (€35)</option>
            <option value="tipio">Tipio ekstera por 8 personoj (€20)</option>
            <option value="tendo">Propra tendo ekstere (€20)</option>
          </select>
          <div class="form-text">
            Vidu fotojn kaj pliajn informojn pri la loĝebloj <a href="https://ijk2022.tejo.org/logado/" target="_blank">en la retejo</a>.
          </div>
        </div>

        <div class="mb-3">
          <label for="kampo-kunloghado">Mi volas loĝi en ĉambro por …</label>
          <select name="loghado" id="kampo-kunloghado" class="form-select" required>
            <option value="viroj">Viroj</option>
            <option value="virinoj">Virinoj</option>
            <option value="familioj">Familioj</option>
            <option value="glat">GLAT-amika</option>
            <option value="negravas" selected>Ne gravas</option>
          </select>
          <div class="form-text">
            Vidu fotojn kaj pliajn informojn pri la loĝebloj <a href="https://ijk2022.tejo.org/logado/" target="_blank">en la retejo</a>.
          </div>
        </div>

        <div class="mb-3">
          <label for="kampo-kunloghanto">Mi volas loĝi kun …</label>
          <input name="kunloghanto" id="kampo-kunloghanto" class="form-control">
          <div class="form-text">
            Se estas specifa(j) homo(j) kun kiu(j) vi ŝatus kunloĝi, metu ties nomo(j)n ĉi tien.
          </div>
        </div>

        <div class="mb-3">
          <label for="kampo-dormo">Mi planas ekdormi …</label>
          <select name="dormo" id="kampo-dormo" class="form-select" required>
            <option value="frue">Frue</option>
            <option value="malfrue" selected>Malfrue</option>
            <option value="malfruege">Malfruege</option>
          </select>
        </div>

        <div class="mb-3 form-check form-switch">
          <input name="littuko" id="kampo-littuko" type="checkbox" class="form-check-input">
          <label for="kampo-littuko">Mi kunportos miajn proprajn littukojn</label>
        </div>

        <div class="mb-3">
          <label for="kampo-mangho">Mi volas manĝi …</label>
          <select name="mangho" id="kampo-mangho" class="form-select" required>
            <option value="vegane">Vegane</option>
            <option value="vegetare" selected>Vegetare</option>
            <option value="kunviande">Kunviande</option>
          </select>

          <table id="mangho-tabelo" class="table">
            <thead>
              <tr>
                <th>Tago / Manĝo</th>
                <th>Matenmanĝo</th>
                <th>Tagmanĝo</th>
                <th>Vespermanĝo</th>
              </tr>
            </thead>
            <tbody>
              <?php
                for ($i = 20; $i <= 27; $i++) {
                  echo '<tr>';

                  echo "<th>la $i-a</th>";
                  for ($j = 0; $j < 3; $j++) {
                    if (
                      ($i == 20 && $j != 2) ||
                      ($i == 27 && $j != 0)
                    ) {
                      echo '<td></td>';
                      continue;
                    }
                    echo "<td><input class=\"form-check-input\" name=\"kampo-mangho-$i\" type=\"checkbox\" checked></td>";
                  }

                  echo '</tr>';
                }
              ?>
              <tr>
                <th></th>
                <?php
                  for ($i = 0; $i < 3; $i++) {
                    echo '<td><button type="button" class="btn btn-primary">(Mal)elekti ĉiujn</button></td>';
                  }
                ?>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="mb-3">
          <label for="kampo-dieto">Alergio/dietaj bezonoj</label>
          <textarea name="dieto" id="kampo-dieto" class="form-control"></textarea>
          <div class="form-text">
            Se vi havas alergiojn aŭ specialajn dietajn bezonojn (krom esti vegano aŭ vegetarano), bonvolu indiki ilin tie ĉi.
          </div>
        </div>

        <div class="mb-3">
          <label for="kampo-kontribuo">Via kontribuo al la programo</label>
          <textarea name="kontribuo" id="kampo-kontribuo" class="form-control"></textarea>
          <div class="form-text">
            Se vi ŝatus proponi vian propran programeron al la programo de la IJK, bonvolu detale priskribi ĝin tie ĉi. Indiku la titolon, la daŭron, ĉu estas prezento, diskuto, aktivaĵo aŭ ekstera aktivaĵo kaj kion vi bezonas por ĝi.
          </div>
        </div>

        <div class="mb-3 form-check form-switch">
          <input name="novulo" id="kampo-novulo" type="checkbox" class="form-check-input">
          <label for="kampo-novulo">Tiu ĉi estos mia unua internacia Esperanto-renkontiĝo</label>
        </div>

        <div class="mb-3 form-check form-switch">
          <input name="vizo" id="kampo-vizo" type="checkbox" class="form-check-input">
          <label for="kampo-vizo">Mi bezonas vizon por eniri Nederlandon</label>
        </div>

        <div id="kamparo-vizo">
          <div class="mb-3">
            <label for="kampo-vizo-nacieco">Mia nacieco (civitaneco)</label>
            <select name="vizo-nacieco" id="kampo-vizo-nacieco" class="form-select" required>
              <?php
                echo $countrySelect;
              ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="kampo-vizo-pasportnumero">Mia pasportnumero/dokumentnumero</label>
            <input name="vizo-pasportnumero" id="kampo-vizo-pasportnumero" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="kampo-vizo-dato-ek">Mia pasporto estas valida ekde …</label>
            <input name="vizo-dato-ek" id="kampo-vizo-dato-ek" class="form-control" type="date" required>
          </div>

          <div class="mb-3">
            <label for="kampo-vizo-dato-ghis">Mia pasporto eksvalidiĝos je …</label>
            <input name="vizo-dato-ghis" id="kampo-vizo-dato-ghis" class="form-control" type="date" required>
          </div>

          <div class="mb-3">
            <label for="kampo-vizo-adreso">Poŝtadreso inkluzive loĝlandon</label>
            <textarea name="vizo-adreso" id="kampo-vizo-adreso" class="form-control"></textarea>
          </div>
        </div>

        <div class="mb-3 form-check form-switch">
          <input name="volontulo" id="kampo-volontulo" type="checkbox" class="form-check-input">
          <label for="kampo-volontulo">Mi pretas esti volontulo</label>
        </div>

        <div class="mb-3">
          <label for="kampo-chemizo">Mi ŝatus ricevi belegan IJK-ĉemizon (senpaga ĝis la 9-a de novembro, poste €7,50)</label>
          <select name="chemizo" id="kampo-chemizo" class="form-select" required>
            <option value="ne" selected>Ne, dankon</option>
            <option value="S">Grandeco S</option>
            <option value="M">Grandeco M</option>
            <option value="L">Grandeco L</option>
            <option value="XL">Grandeco XL</option>
            <option value="XXL">Grandeco XXL</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="kampo-donaco">Mi donacas al la IJK …</label>
          <div class="input-group mb-3">
            <span class="input-group-text">€</span>
            <input name="donaco" id="kampo-donaco" class="form-control" type="number" step="0.01" min="0" max="10000" value="0">
          </div>
          <div class="form-text">
            Ni dankas pro via donacemo.
          </div>
        </div>

        <div class="mb-3">
          <label for="kampo-pago">Mi antaŭpagas …</label>
          <select name="pago" id="kampo-pago" class="form-select" required>
            <option value="banko">Al la bankkonto de la IJK</option>
            <option value="karto">Per kreditkarto</option>
            <option value="paypal">Per PayPal</option>
            <option value="organizanto">Per fizika mono al organizanto proksima al mi</option>
          </select>
        </div>

        <div class="mb-3 form-check form-switch">
          <input name="listo" id="kampo-listo" type="checkbox" class="form-check-input">
          <label for="kampo-listo">Mi ne volas aperi en la listo de aliĝintoj</label>
        </div>

        <div class="mb-3 form-check form-switch">
          <input name="fotoj" id="kampo-fotoj" type="checkbox" class="form-check-input">
          <label for="kampo-fotoj">Mi ne volas esti fotita</label>
        </div>

        <div class="mb-3 form-check form-switch">
          <input id="kampo-antaupago" type="checkbox" class="form-check-input" required>
          <label for="kampo-antaupago">Mi komprenas, ke mi devas antaŭpagi minimume €20 por ke mia aliĝo estu valida</label>
        </div>

        <div class="mb-3 form-check form-switch">
          <input id="kampo-regularo" type="checkbox" class="form-check-input" required>
          <label for="kampo-regularo">Mi legis, komprenis kaj akceptas la regularon de la IJK</label>
          <div class="form-text">
            <a href="https://ijk2022.tejo.org/reguloj-por-agrable-ijk-umi/">Tie ĉi vi povas legi la regulojn.</a>
          </div>
        </div>

        <button type="submit" class="btn btn-primary">Aliĝi al la IJK!</button>
      </form>
    </main>
    <section id="kotizo">
      <div>
        <h3>Via kotizo:</h3>
      </div>
      <div class="row">
        <div class="col-4">
          Programkotizo: <span id="kotizo-programo">kalkulota</span><br>
          Aliĝperiodo: <span id="kotizo-periodo">kalkulota</span><br>
          Loĝado: <span id="kotizo-loghado">kalkulota</span>
        </div>
        <div class="col-4">
          Manĝoj: <span id="kotizo-manghoj">kalkulota</span><br>
          <span id="kotizo-nemembro"></span>
          <span id="kotizo-chemizo"></span>
        </div>
        <div class="col-4">
          <strong>Via plena kotizo: <span id=kotizo-plena>kalkulota</span></strong>
        </div>
      </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="js/moment.min.js"></script>
    <script src="js/dom.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>
