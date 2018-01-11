<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 11.01.2018
 */

echo "<fieldset id=\"sportRoute\">";
echo "<label for='sport'>Sport</label><br>";

//  <!-- http://www.mredkj.com/tutorials/tableaddrow.html -->
$i=0;
foreach ($sportList as $row) {

    print "<input type='checkbox' id='sport".$row['idSickness']."' name='sport[]' value='".$row['spoName']."'>".$row['spoName'];
    $i++;
}
print "<input type='checkbox' name='sportInput' value='otherSport' onclick='printNewInputSport(this)'>Autre";
?>

<!-- Si 'autre' -> afficher input-->
<script>
    function printNewInputSport(that) {
        if(that.checked) {
            var input = document.createElement("input");
            input.type = "text";
            input.id = 'otherSport';
            input.name = 'otherSport';
            input.className = 'form-control';
            var element = document.getElementById('sportRoute');
            element.appendChild(input);

            document.getElementById('otherSport').placeholder = "Sport";
        }
        else{
            var element = document.getElementById('sportRoute');
            var input = document.getElementById('otherSport');
            element.removeChild(input);
        }
    }
</script>
</fieldset>