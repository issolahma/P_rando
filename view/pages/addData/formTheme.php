<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 12.01.2018
 */

echo "<fieldset id=\"animTheme\">";
echo "<label for='theme'>Thème</label><br>";

//  <!-- http://www.mredkj.com/tutorials/tableaddrow.html -->
$i=0;
foreach ($themeList as $row) {
    if($row['theActive'] == 1) {
        print "<input class='chBox' type='checkbox' id='theme" . $row['idTheme'] . "' name='theme[]' value='" . $row['theName'] . "'>" . $row['theName'] . ' ';
    }
    $i++;
}
print "<input type='checkbox' name='themeInput' value='otherTheme' onclick='printNewInputTheme(this)'>Nouveau";
?>

<!-- Si 'autre' -> afficher input-->
<script>
    function printNewInputTheme(that) {
        if(that.checked) {
            var input = document.createElement("input");
            input.type = "text";
            input.id = 'otherTheme';
            input.name = 'otherTheme';
            input.className = 'form-control';
            var element = document.getElementById('animTheme');
            element.appendChild(input);

            document.getElementById('otherTheme').placeholder = "Thème";
        }
        else{
            var element = document.getElementById('animTheme');
            var input = document.getElementById('otherTheme');
            element.removeChild(input);
        }
    }
</script>
</fieldset>