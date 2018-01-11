<div class="jumbotron jumbotron-fluid">
    <div class="container">
        <h1>Liste des utilisateurs</h1>
        <a>Vous êtes : </a><?php echo $current_user->getName() ?>
        <a href="disconnect.php">Déconnexion</a>
    </div>
</div>


<!-- Tableau : Liste des utilisateurs -->
<div class="container">
    <div class="row">
        <div class="col">
            <p>
                <a href="usr_new.php" class="btn btn-primary">Créer un utilisateur </a>
            </p>
            <table class="table table-dark">
                <!-- Ligne titre -->
                <thead class="thead-light">
                <tr>
                    <th> Utilisateur</th>
                    <th> Profil</th>
                    <th> Mot de passe</th>
                    <th> Droits</th>
                    <th> Date de création</th>
                    <th> Actif O/N</th>
                    <th> Action</th>
                </tr>
                </thead>
                <?php
                foreach ($users as $ligne) {

                    $userInLine = new User();
                    $userInLine->parseUserInfo($ligne);

// <span style="font-style:italic;">
                    ?>
                    <tr <?php if ($current_user == $userInLine) {
                        echo 'class="bg-primary"';
                    }; ?> >
                        <td><?= $userInLine->getName(); ?></td>
                        <td><?= $userInLine->getLogin(); ?></td>
                        <td><?= $userInLine->getPassword(); ?></td>
                        <td align='center'><?= $userInLine->getRights()== User::RIGHTS_ADMIN ? "Administrateur" : "Utilisateur"; ?></td>
                        <td align='center'><?= $userInLine->getCreationDateForDisplay(); ?></td>
                        <td align='center'><?= $userInLine->getEnable() ? "actif" : "off" ?></td>
                        <td>
                            <div class="dropdown show">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                   id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                   aria-expanded="false">

                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item"
                                       href='index.php?controller=user&action=viewUsr&Id=<?=$userInLine->getId(); ?>&modAct=view'>Voir</a>
                                    <?php
                                    $canEdit = $current_user->canEdit($userInLine->getId());

                                    if ($canEdit) {

                                        echo '<a class="dropdown-item" href=';
                                        echo '"index.php?controller=user&action=viewUsr&Id='. $userInLine->getId() . '&modAct=edit"';
                                        echo '>Edit</a>';
                                    }

                                    if ($canEdit) {
                                        echo '<a class="dropdown-item" href=';
                                        echo '"index.php?id=' . $userInLine->getId() . '&modAct=suppr"';
                                        echo '>Delete</a>';
                                    }
                                    ?>
                                </div>
                            </div>


                        </td>
                    </tr>

                    <?php
                }
                ?>
            </table>
        </div>
    </div>
</div>
