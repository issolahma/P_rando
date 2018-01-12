<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 12.01.2018
 */

echo "<fieldset id=\"animSeason\">";
echo "<label for='season'>Saison</label><br>";

//  <!-- http://www.mredkj.com/tutorials/tableaddrow.html -->
$i=0;
foreach ($seasonList as $row) {
if($row['seaActive'] == 1) {
        print "<input class='chBox' type='checkbox' id='season" . $row['idSeason'] . "' name='season[]' value='" . $row['seaName'] . "'>" . $row['seaName'] . ' ';
    }
    $i++;
}
print "<input type='checkbox' name='seasonInput' value='otherSeason' onclick='printNewInputSeason(this)'>Nouveau";
?>

<!-- Si 'autre' -> afficher input-->
<script>
    function printNewInputSeason(that) {
        if(that.checked) {
            var input = document.createElement("input");
            input.type = "text";
            input.id = 'otherSeason';
            input.name = 'otherSeason';
            input.className = 'form-control';
            var element = document.getElementById('animSeason');
            element.appendChild(input);

            document.getElementById('otherSeason').placeholder = "Season";
        }
        else{
            var element = document.getElementById('animSeason');
            var input = document.getElementById('otherSeason');
            element.removeChild(input);
        }
    }
</script>
</fieldset>