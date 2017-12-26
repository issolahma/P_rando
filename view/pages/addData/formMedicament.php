<?php
/**
 * Created by PhpStorm.
 * User: issolahma
 * Date: 27.09.2017
 * Time: 14:13
 */

echo "<fieldset id=\"cliMedicament\">";
echo "<label for='medicament'>Médicament</label><br>";

echo '<div class="form-group">';

	//  <!-- http://www.mredkj.com/tutorials/tableaddrow.html -->
	foreach ($medicList as $row) {
   	    print "<input type='checkbox' class='minimal' name='medicament[]' id='medicament".$row['idMedicament']."' value='".$row['medName']."'> ".$row['medName'];
	}	
	print "<input type='checkbox' class='minimal' name='medicamentInput' value='otherMed' onclick='printNewInputMed(this)'>Autre";
?>
</div>

<!-- Si 'autre' -> afficher input-->
<script>
    function printNewInputMed(that) {
        if(that.checked) {
            var input = document.createElement("input");
            input.type = "text";
            input.id = 'otherMed';
            input.name = 'otherMed';
            input.className = 'form-control';
            var element = document.getElementById('cliMedicament');
            element.appendChild(input);

            document.getElementById('otherMed').placeholder = "Médicament";
        }
        else{
            var element = document.getElementById('cliMedicament');
            var input = document.getElementById('otherMed');
            element.removeChild(input);
        }
    }
</script>
</fieldset>
