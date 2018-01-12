<?php
/**
 * Author: Maude Issolah
 * Place: ETML
 * Last update: 12.01.2018
 * Subject: Difficulty list
 */
?>

<div class="col-md-4">
    <label>Difficulté</label>
    <select name="ddDiff" id="ddDiff">
        <?php
        foreach ($diffList as $row){
            if($row['difActive'] == 1) {
                print '<option value="' . $row['idDifficulty'] . '">' . $row['difLevel'] . '</option>'; //Value==id
            }
        }
        ?>
    </select>
</div>