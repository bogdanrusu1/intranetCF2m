<?php

/*
 * Importation des modules accessible à l'administrateur technique
 */

if(!empty($_GET)){


// gestion de lafiliere
require_once "../controller/modules/gestionLafiliere.php";

// gestion de lasession
require_once "../controller/modules/gestionLasession.php";


// Delete, Update, Insert conditions for congés

if (isset($_GET['confirmationdeleteleconge']) && ctype_digit($_GET['confirmationdeleteleconge'])) {
    $idDeleteConge = (int) $_GET['confirmationdeleteleconge'];

    if(!isset($_GET['ok'])) {
        $afficheconge = $lecongeM->lecongeSelectByld($idDeleteConge);
        echo $twig->render("leconge/leconge_supprimer.html.twig",["afficheconge"=>$afficheconge]);

    } else {
        $lecongeM->deleteConge($idDeleteConge);
        header('Location: ./?viewleconge');
    }
// Update
} else if (isset($_POST['idleconge']) && ctype_digit($_POST['idleconge']) && isset($_POST['debut']) && isset($_POST['fin']) && isset($_POST['letype']) && ctype_digit($_POST['letype']) && isset($_POST['lasession_idlasession']) && ctype_digit($_POST['lasession_idlasession'])) {
    $leconge = new leconge($_POST);
    $lecongeM->updateConge($leconge);
// INSERT
} else if (isset($_POST['debut']) && isset($_POST['fin']) && isset($_POST['letype']) && ctype_digit($_POST['letype']) && isset($_POST['lasession_idlasession']) && ctype_digit($_POST['lasession_idlasession'])) {
    $leconge = new leconge($_POST);
    $lecongeM->lecongeCreate($leconge);
	header('Location: ./?viewleconge');
}
// Insert conditions for inscriptions
if (isset($_POST['debut']) && isset($_POST['fin']) && isset($_POST['utilisateurIdutilisateur']) && ctype_digit($_POST['utilisateurIdutilisateur']) && isset($_POST['lasessionIdsession']) && ctype_digit($_POST['lasessionIdsession'])) {
    $linscription = new linscription($_POST);
    $linscriptionM->linscriptionCreate($linscription);
}
 
// Display views for conges
if (isset($_GET['viewleconge'])) {
	$paginConge = (isset($_GET['pgConge'])?(int)$_GET['pgConge']:1);
    $nbConge = $lecongeM->selectCongeCountById();
    $nbPageConge = $lecongeM->selectCongeWithLimit($paginConge,NB_PG);
    $PaginationConge = pagination::pagine($nbConge,NB_PG,$paginConge,"viewleconge&pgConge");
	
	echo $twig->render("leconge/leconge_afficherliste.html.twig", ['detailConge' => $nbPageConge,"pagination"=>$PaginationConge]);
	
}
elseif (isset($_GET['updateleconge']) && ctype_digit($_GET['updateleconge']))
{
    echo $twig->render("leconge/leconge_modifier.html.twig", ['detailConge' => $lecongeM->lecongeSelectByld($_GET['updateleconge']), "sessions" => $lasessionM->sessionSelectALL()]);
}
elseif (isset($_GET['insertleconge']))
{
    echo $twig->render("leconge/leconge_ajouter.html.twig", ["sessions" => $lasessionM->sessionSelectALL()]);


    //View le role
} elseif (isset($_GET['viewlerole'])){
    // page actuelle
    $pageactu = (isset ($_GET['pg']))?(int)$_GET['pg']:1;
    // nombre de rôles totaux à afficher
    $nbRoles = $leroleM->selectRoleCountById();
    // on va récupérer les rôles de la page actuelle

    $articlesPageActu = $leroleM->selectRoleWithLimit($pageactu,3);


    // création de la pagination
    $affichePagination = pagination::pagine($nbRoles,3,$pageactu,"viewlerole&pg");


    echo $twig->render('lerole/lerole_afficherliste.html.twig', [ "detailrole"=>$articlesPageActu,"pagination"=>$affichePagination]);



    //insert un nouveau rôle
} elseif(isset($_GET['insertLeRole'])){
    if(!empty($_POST)){
        $newLeRole = new lerole($_POST);
        echo $twig->render('lerole/lerole_ajouter.html.twig',['lintitule'=>$leroleM->insertLerole($newLeRole)]);
      header('Location: ./?viewlerole');
        }
          
    
    else{
        echo $twig->render('lerole/lerole_ajouter.html.twig');
    
    }
//update un rôle
    
}elseif(isset($_GET['updateLeRole']) && ctype_digit($_GET['updateLeRole'])){
    if(isset($_POST['idlerole'])){
        $updateLeRole = new lerole($_POST);
        $leroleM->updateLerole($updateLeRole);
        header("Location: ./?viewlerole");
    }else{
        echo $twig->render('lerole/lerole_modifier.html.twig',['section'=>$leroleM->roleSelectById($_GET['updateLeRole'])]);
    }
//delete le role
}elseif(isset($_GET['deleteLeRole']) && ctype_digit($_GET['deleteLeRole'])){
    $idDeleteRole = (int)$_GET['deleteLeRole'];
    if(isset($_GET['ok'])){
        $leroleM->deleteLerole($idDeleteRole);
        header("Location: ./?viewlerole");
      
      
      }else{
    echo $twig->render('lerole/lerole_delete.html.twig',['id'=>$idDeleteRole]);
    }
  
  
//linscription
}elseif (isset($_GET["viewlinscription"])) {
    $pageLinscription=(isset($_GET['pglinscription']))?(int)$_GET['pglinscription']:1;
    $nbLinscription =$linscriptionM->selectLinscriptionCountById();
    $vuelinscription =$linscriptionM->inscriptionAllSelectWithUser($pageLinscription,NB_PG);

    $pagesLinsciption=pagination::pagine($nbLinscription,NB_PG,$pageLinscription,"viewlinscription&pglinscription");

    echo $twig->render("linscription/linscription_afficherliste.html.twig", ['detailinscription' => $vuelinscription,"pagination"=>$pagesLinsciption]);

}elseif (isset($_GET["ajouterlinscription"])) {
    if(!empty($_POST)){
        $newlinscription = new linscription($_POST);

        // insertion
        $insert=$linscriptionM->linscriptionCreate($newlinscription);
        header("Location: ./?viewlinscription");
        }

else{
    echo $twig->render("linscription/linscription_ajouter.html.twig", ['detailUsers' => $linscriptionM->SelectAllInscription()]);

}

//update linscription
}elseif(isset($_GET['updatelinscription'])&& ctype_digit($_GET['updatelinscription'])) {

    $afficheInscription = $linscriptionM->SelectAllInscriptionWithUserAndSession($_GET['updatelinscription']);

    if(empty($_POST)){

        echo $twig->render('linscription/linscription_modifier.html.twig',['afficheinscription'=>$afficheInscription]);

        
    }else{

        $modifLinscription = new linscription($_POST);

        $update=$linscriptionM->updateLinscription($modifLinscription);

            header("Location: ./?viewlinscription");

    }


//delete linscription
}elseif(isset($_GET['deleteLinscription']) && ctype_digit($_GET['deleteLinscription'])){
    $idDeleteLinscription = (int)$_GET['deleteLinscription'];
    if(isset($_GET['ok'])){
        $linscriptionM->deleteLinscription($idDeleteLinscription);
        header("Location: ./?viewlinscription");
      
      
      }else{
    echo $twig->render('linscription/linscription_supprimer.html.twig',['id'=>$idDeleteLinscription]);
}
    

}elseif (isset($_GET['updatelinscription'])&& ctype_digit($_GET['updatelinscription'])){

}
//Update lutilisateur
    elseif (isset($_GET['updatelutilisateur'])&& ctype_digit($_GET['updatelutilisateur'])){


        $recuperationUtilisateur = $lutilisateurM->SelectUserByRoleid($_GET['updatelutilisateur']);

        $recuperationRole = $leroleM->SelectAllRoles();


        if(empty($_POST)){


            echo $twig->render('lutilisateur/lutilisateur_modifier.html.twig',["afficheuser"=>$recuperationUtilisateur,"afficheroles"=>$recuperationRole]);


        }else{

			$_POST['lenomutilisateur'] = $_POST['lenom'] . '.' . $_POST['leprenom'];
			
            $userUpdate = new lutilisateur($_POST);

            $idroleUpdate = (isset($_POST['idlerole'])) ? $_POST['idlerole'] : [];

            $udateUtilisateur = $lutilisateurM->updateUserandlore($userUpdate,$idroleUpdate);



            if($udateUtilisateur){

                header("Location: ./?viewutilisateur");
            }
        }
//Delete l'utilisateur
}elseif (isset($_GET['deleteuser'])&& ctype_digit($_GET['deleteuser'])){

    $idUtilisateur = (int) $_GET['deleteuser'];


    if(!isset($_GET['ok'])){



       $deleteuserok =  $lutilisateurM->SelectUserByRoleid($idUtilisateur);

       echo $twig->render("lutilisateur/lutilisateur_supprimer.html.twig",["afficheuser"=>$deleteuserok]);

    }else {
        $lutilisateurM->UserDelete($idUtilisateur);

        header("Location: ./?viewutilisateur");
    }

}
elseif (isset($_GET['viewutilisateur'])){
     $pageLutisateur=(isset($_GET['pglutilisateur']))?(int)$_GET['pglutilisateur']:1;
    $nblutilisateur =$lutilisateurM->selectLutilisateurCountById();
    $vuelutilisateur =$lutilisateurM->selectlutilisateurWithLimit($pageLutisateur,NB_PG);
    $pagesLutisateur=pagination::pagine($nblutilisateur,NB_PG,$pageLutisateur,"viewutilisateur&pglutilisateur");

   
 echo $twig->render('lutilisateur/lutilisateur_afficher_presence.html.twig',["lutilisateur"=> $vuelutilisateur,"pagination"=>$pagesLutisateur]);




} elseif(isset($_GET['insertutilisateur'])) {
	
		if(empty($_POST)){
          
          $recupRoles =$leroleM->selectAllLerole();
        
          
          echo $twig->render("lutilisateur/lutilisateur_ajouter.html.twig",["roles"=> $recupRoles]);
          
          
        } else {
			
			$_POST['lenomutilisateur'] = $_POST['lenom'] . '.' . $_POST['leprenom'];
			
            $newlutilisateur = new lutilisateur($_POST);

            $role=(int) $_POST['role'];

			$insert =$lutilisateurM->lutilisateurCreate($newlutilisateur,$role);

			if($insert){
				header("Location: ./?viewutilisateur");
			}
		}
 

}

}else{
    // si on vient de se connecter la variable de session n'existe pas (donc affuchage du bandeau)
    if(!isset($_SESSION['bandeau'])){
        $pourEntree = true;
        $_SESSION['bandeau']=true;
    }else{
        $pourEntree = false;
    }
    echo $twig->render('roles/admin/admin_homepage.html.twig', ['entree' => $pourEntree,"session"=>$_SESSION]); 

    }

