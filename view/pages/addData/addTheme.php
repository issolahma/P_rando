<!-- Author: Maude Issolah
<!-- Place: ETML Lausanne
<!-- Last update: 10.01.2018
<!-- Subject: View to add theme
-->
<div class="container box">
    <div class="table-responsive">
        <div class="box-header">
            <h3 class="box-title">Liste des Thèmes</h3>
        </div>
        <div align="right">
            <button type="button" id="add_button" data-toggle="modal" data-target="#userModal"
                    class="btn btn-info btn-flat">Ajout
            </button>
        </div>
        <br/>
        <table id="user_data" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th width="0%">Id</th> <!-- Hidden col -->
                <th width="35%">Thème</th>
                <th width="10%">Editer</th>
                <th width="10%">Supprimer</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

<!--DEBUG-->
<?php

?>

<div id="userModal" class="modal fade">
    <div class="modal-dialog">
        <form action="index.php?controller=theme&action=formAjax" method="post" id="user_form"
              enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Nouveau Thème</h4>
                </div>
                <div class="modal-body">

                    <!-- form https://bootsnipp.com/snippets/a6nml -->
                    <!-- Select2 https://bootsnipp.com/snippets/8Xjor -->

                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <!-- hidden id -->
                                <input type="hidden" name="theme_id" id="theme_id">
                                <label>Nom du thème</label>
                                <input type="text" id="name" name="name" placeholder="Nom" class="form-control">
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="operation" id="operation"/>
                    <input type="submit" name="action" id="action" class="btn btn-success" value="Ajout"/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript" language="javascript" src="resources/js/addThemeTable.js"></script>