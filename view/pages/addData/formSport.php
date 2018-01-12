<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 12.01.2018
 * Subject: Sport list
 */
?>

<div class="col-md-4">
    <label>Sport</label>
    <select name="ddSport" id="ddSport">
<?php
        foreach ($sportList as $row){
            if($row['spoActive'] == 1) {
                print '<option value="' . $row['idSport'] . '">' . $row['spoName'] . '</option>'; //Value==id
            }
        }
?>
    </select>
</div>