<?php include("header.php") ?>

<body>

    <div class="container my-2">
        <div class="alert alert-primary">
            <h3 class="text-center text-primary">APP CRUD AJAX POO</h3>
        </div>

        <div class="my-2">
            <div class="row">
                <div class="col-md-3">
                    <button class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#ajouterPlayer">AJOUTER <i class="fa fa-user"></i></button>
                </div>

                <div class="col-md-9">
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span>
                        <input type="text" class="form-control" placeholder="Rechercher..." aria-label="Rechercher..." aria-describedby="basic-addon1">
                    </div>
                </div>
            </div>
        </div>

        <div class="my-2">
            <table class="table table-striped table-hover" id="playerTable">
                <thead>
                    <tr>
                        <th>PHOTO</th>
                        <th>NOM</th>
                        <th>EMAIL</th>
                        <th>TELEPHONE</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>PHOTO</th>
                        <th>NOM</th>
                        <th>EMAIL</th>
                        <th>TELEPHONE</th>
                        <th>ACTIONS</th>
                    </tr>
                </tfoot>

                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <div class="d-flexjustify-content-between">
                                <button class="btn btn-success profile" data-bs-toggle="modal" data-bs-target="#afficherPlayer"><i class="fa fa-address-card"></i></button>
                                <button class="btn btn-warning edituser" data-bs-toggle="modal" data-bs-target="#editerPlayer"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger deluser"><i class="fa fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        <div class="my-2">
            <nav id="pagination" aria-label="Page navigation example">

            </nav>

            <input type="hidden" name="currentPage" id="currentPage" value="1">
        </div>
    </div>



    <!-- MODALS -->


    <!-- Modal Ajouter Player -->

    <div class="modal fade" id="ajouterPlayer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ajouterPlayerLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ajouterPlayerLabel"><i class="fa fa-plus"></i> Ajout d'un nouvel utilisateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" id="addform" enctype="multipart/form-data">
                    <div class="my-2 px-3">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="nom"><i class="fa fa-user"></i></span>
                            <input type="text" name="nom" id="nom" class="form-control" placeholder="NOM ET PRENOMS" aria-label="NOM ET PRENOMS" aria-describedby="nom">
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text" id="email"><i class="fa fa-envelope"></i></span>
                            <input type="email" name="email" id="email" class="form-control" placeholder="ADRESSE EMAIL" aria-label="ADRESSE EMAIL" aria-describedby="email">
                        </div>


                        <div class="input-group mb-3">
                            <span class="input-group-text" id="tel"><i class="fa fa-phone"></i></span>
                            <input type="text" name="tel" id="tel" class="form-control" placeholder="TELEPHONE" aria-label="TELEPHONE" aria-describedby="tel">
                        </div>


                        <div class="input-group mb-3">
                            <span class="input-group-text" id="img"><i class="fa fa-user"></i></span>
                            <input type="file" name="img" id="img" class="form-control" aria-label="IMAGE" aria-describedby="img">
                        </div>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="addUser">Understood</button>
                        <input type="hidden" name="action" value="adduser">
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Modal Afficher Player  -->

    <div class="modal fade" id="afficherPlayer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="afficherPlayerLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="afficherPlayerLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container" id="userProfile">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>





    <!-- Modal Editer Player -->

    <div class="modal fade" id="editerPlayer" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editerPlayerLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editerPlayerLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" id="editform" enctype="multipart/form-data">
                    <div class="my-2 px-3">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="nom"><i class="fa fa-user"></i></span>
                            <input type="text" name="nom" id="nomEdit" class="form-control" placeholder="NOM ET PRENOMS" aria-label="NOM ET PRENOMS" aria-describedby="nom">
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text" id="email"><i class="fa fa-envelope"></i></span>
                            <input type="email" name="email" id="emailEdit" class="form-control" placeholder="ADRESSE EMAIL" aria-label="ADRESSE EMAIL" aria-describedby="email">
                        </div>


                        <div class="input-group mb-3">
                            <span class="input-group-text" id="tel"><i class="fa fa-phone"></i></span>
                            <input type="text" name="tel" id="telEdit" class="form-control" placeholder="TELEPHONE" aria-label="TELEPHONE" aria-describedby="tel">
                        </div>


                        <div class="input-group mb-3">
                            <span class="input-group-text" id="img"><i class="fa fa-user"></i></span>
                            <input type="file" name="img" id="imgEdit" class="form-control" aria-label="IMAGE" aria-describedby="img">
                        </div>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="addUser">Mettre à jour</button>
                        <input type="hidden" name="action" value="edituser">
                        <input type="hidden" name="userid" id="userId">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include("footer.php") ?>


</body>

</html>