<!-- Author: Maude Issolah
<!-- Place: ETML Lausanne
<!-- Last update: 11.01.2018
<!-- Subject: View to add route
-->

<div class="container box">
    <div class="table-responsive">
        <div class="box-header">
            <h3 class="box-title">Liste des itinéraires</h3>
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
                <th width="30%">Nom</th>
                <th width="20%">Lieu</th>
                <th width="15%">Nombre de clients</th>
                <th width="35%">Sport</th>
                <th width="35%">Difficulté</th>
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
        <form action="index.php?controller=route&action=formAjax" method="post" id="user_form"
              enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">

                    <!-- form https://bootsnipp.com/snippets/a6nml -->
                    <!-- Select2 https://bootsnipp.com/snippets/8Xjor -->

                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6 form-group">
                                <label>Nom de l'itinéraire</label>
                                <input type="text" id="name" name="name" placeholder="Nom" class="form-control">
                            </div>
                            <div class="col-sm-6 form-group">
                                <label>Lieu</label>
                                <input type="text" id="place" name="place" placeholder="Lieu" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label>Description</label>
                                <textarea type="text" id="describ" name="describ" placeholder="Description de l'itinéraire" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 form-group">
                                <label>Dénivelé posotif</label>
                                <input type="text" id="dropPos" name="dropPos" placeholder="Dénivelé positif" class="form-control">
                            </div>
                            <div class="col-sm-4 form-group">
                                <label>Dénivelé négatif</label>
                                <input type="text" id="dropNeg" name="dropNeg" placeholder="Dénivelé négatif" class="form-control">
                            </div>
                            <div class="col-sm-4 form-group">
                                <label>Altitude maximum</label>
                                <input type="text" id="maxAlt" name="maxAlt" placeholder="Altitude maximum" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 form-group">
                                <label>Nombre de clients</label>
                                <input type="text" id="nbClient" name="nbClient" placeholder="nombre de client" class="form-control">
                            </div>
                            <div class="col-sm-8 form-group">
                                <label>Fichier GPS</label>
                                <input type="text" id="gps" name="gps" placeholder="Fichier GPS" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 form-group">
                                <label>Durée</label>
                                <input type="text" id="duration" name="duration" placeholder="Durée" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <!-- hidden id -->
                            <input type="hidden" name="id" id="id">
                            <div class="col-sm-12 form-group">
                                <label>Danger</label>
                                <textarea type="text" id="danger" name="danger" placeholder="danger" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label>Itinéraire alternatif</label>
                                <textarea type="text" id="altern" name="altern" placeholder="Itinéraire bis" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <!-- route sport -->
                            <?php include_once 'view/pages/addData/formSport.php'; ?>
                        </div>
                        <div class="form-group">
                            <!-- route difficulty -->
                            <?php include_once 'view/pages/addData/formDifficulty.php'; ?>
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

<script type="text/javascript" language="javascript" src="resources/js/addRouteTable.js"></script>