<?php
/**
 * Created by PhpStorm.
 * User: issolahma
 * Date: 27.09.2017
 * Time: 14:10
 */

echo "<fieldset id=\"routeDiff\">";
echo "<label for='diff'>Difficulté</label><br>";

//  <!-- http://www.mredkj.com/tutorials/tableaddrow.html -->
$i=0;
foreach ($diffList as $row) {

    print "<input type='checkbox' id='diff".$row['idSickness']."' name='diff[]' value='".$row['difLevel']."'>".$row['difLevel'];
    $i++;
}
print "<input type='checkbox' name='diffInput' value='otherDiff' onclick='printNewInputDiff(this)'>Autre";
?>

<!-- Si 'autre' -> afficher input-->
<script>
    function printNewInputDiff(that) {
        if(that.checked) {
            var input = document.createElement("input");
            input.type = "text";
            input.id = 'otherDiff';
            input.name = 'otherDiff';
            input.className = 'form-control';
            var element = document.getElementById('routeDiff');
            element.appendChild(input);

            document.getElementById('otherDiff').placeholder = "Difficulté";
        }
        else{
            var element = document.getElementById('routeDiff');
            var input = document.getElementById('otherDiff');
            element.removeChild(input);
        }
    }
</script>
</fieldset>