<div class="container my-4"> <a href="<?= $router->generate('teacher-add')?>" class="btn btn-success float-right">Ajouter</a>

        <h2>Liste des Profs</h2>
        <table class="table table-hover mt-4">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Prénom</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Titre</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($teachers as $profs): ?>
                <tr>
                    <th scope="row"><?= $profs->getId() ?></th>
                    <td><?= $profs->getFirstname() ?></td>
                    <td><?= $profs->getLastname() ?></td>
                    <td><?= $profs->getJob() ?></td>
                    <td class="text-right">
                        <a href="edit.html" class="btn btn-sm btn-warning">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#" data-toggle="dropdown">Oups !</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>   
            </tbody>
        </table>
    </div>