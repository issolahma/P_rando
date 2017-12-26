<?php
/**
 * Created by PhpStorm.
 * User: issolahma
 * Date: 06.12.2017
 * Time: 15:37
 */
?>
<div class="container box">
    <div class="table-responsive">
        <div class="box-header">
            <h3 class="box-title">Liste des accompagnateurs</h3>
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
                <th width="35%">Nom</th>
                <th width="35%">Prénom</th>
                <th width="35%">Login</th>
                <th width="35%">Droit</th>
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
        <form action="index.php?controller=client&action=formAjax" method="post" id="user_form"
              enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Nouveau client</h4>
                </div>
                <div class="modal-body">

                    <!-- form https://bootsnipp.com/snippets/a6nml -->
                    <!-- Select2 https://bootsnipp.com/snippets/8Xjor -->

                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>Prénom</label>
                                <input type="text" id="firstname" name="firstname" placeholder="Prénom" class="form-control">
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Nom</label>
                                <input type="text" id="lastname" name="lastname" placeholder="Nom" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>login</label>
                                <input type="text" id="login" name="login" placeholder="Login" class="form-control">
                            </div>
                            <div class="col-sm-6 form-group" id="accPwd">
                                <label>password</label>
                                <input type="password" id="password" name="password" placeholder="password" class="form-control">
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Droit (user=0/admin=1)</label>
                                <input type="text" id="right" name="right" placeholder="Droit" class="form-control">
                            </div>
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

<script type="text/javascript" language="javascript" src="resources/js/addAccompanistTable.js"></script>