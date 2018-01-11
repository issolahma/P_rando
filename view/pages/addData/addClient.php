<!-- Author: Maude Issolah
<!-- Place: ETML Lausanne
<!-- Last update: 08.01.2018
<!-- Subject: View to add client
-->

<div class="container box">
    <div class="table-responsive">
        <div class="box-header">
            <h3 class="box-title">Liste des clients</h3>
        </div>
        <div align="right">
            <button type="button" id="add_button" data-toggle="modal" data-target="#userModal"
                    class="btn btn-info btn-flat">Ajout
            </button>
        </div>
        <br/>
        <!-- Client table -->
        <table id="user_data" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th width="0%">Id</th> <!-- Hidden col -->
                <th width="35%">Nom</th>
                <th width="35%">Prénom</th>
                <th width="35%">Ville</th>
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
<!--/DEBUG-->

<!-- Client pop-up (update and add) -->
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
                                <input type="text" id="firstname" name="firstname" placeholder="Prénom"
                                       class="form-control">
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Nom</label>
                                <input type="text" id="lastname" name="lastname" placeholder="Nom" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>Rue</label>
                                <input type="text" id="street" name="street" placeholder="Rue" class="form-control">
                            </div>
                            <!-- hidden id -->
                            <input type="hidden" name="client_id" id="client_id">
                            <div class="col-sm-6 form-group">
                                <label>N°</label>
                                <input type="text" id="streetNb" name="streetNb" placeholder="N°" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>Ville</label>
                                <input type="text" id="city" name="city" placeholder="ville" class="form-control">
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>NPA</label>
                                <input type="text" id="npa" name="npa" placeholder="npa" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>Téléphone</label>
                                <input type="text" id="cliPhone" name="cliPhone" placeholder="téléphone"
                                       class="form-control">
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Numéro en cas d'urgence</label>
                                <input type="text" id="urgencyPhone" name="urgencyPhone" placeholder="téléphone urgence"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Addresse mail</label>
                            <input type="text" id="email" name="email" placeholder="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <!-- Maladie client -->
                            <?php include_once 'view/pages/addData/formSickness.php'; ?>
                        </div>
                        <div class="form-group">
                            <!-- Médicament client -->
                            <?php include_once 'view/pages/addData/formMedicament.php'; ?>
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

<script type="text/javascript" language="javascript" src="resources/js/addClientTable.js"></script>