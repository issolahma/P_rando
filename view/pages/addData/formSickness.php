<?php
/**
 * Created by PhpStorm.
 * User: issolahma
 * Date: 27.09.2017
 * Time: 14:10
 */

echo "<fieldset id=\"cliSick\">";
echo "<label for='sickness'>Maladie</label><br>";

//  <!-- http://www.mredkj.com/tutorials/tableaddrow.html -->
        $i=0;
        foreach ($sickList as $row) {

            print "<input type='checkbox' id='sickness".$row['idSickness']."' name='sickness[]' value='".$row['sicName']."'>".$row['sicName'];
            $i++;
        }
        print "<input type='checkbox' name='sicknessInput' value='otherSick' onclick='printNewInputSick(this)'>Autre";
        ?>

<!-- Si 'autre' -> afficher input-->
<script>
    function printNewInputSick(that) {
        if(that.checked) {
            var input = document.createElement("input");
            input.type = "text";
            input.id = 'otherSick';
            input.name = 'otherSick';
            input.className = 'form-control';
            var element = document.getElementById('cliSick');
            element.appendChild(input);

            document.getElementById('otherSick').placeholder = "Maladie";
        }
        else{
            var element = document.getElementById('cliSick');
            var input = document.getElementById('otherSick');
            element.removeChild(input);
        }
    }
</script>
</fieldset>