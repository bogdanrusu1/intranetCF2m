<?php

// Delete, Update, Insert conditions
if( isset($_GET['confirmationdeletelasession']) && ctype_digit($_GET['confirmationdeletelasession']) ) {
	$lasessionM->sessionDelete($_GET['confirmationdeletelasession']);
} 
else if( isset($_POST['idlasession']) && ctype_digit($_POST['idlasession']) && isset($_POST['lenom']) && isset($_POST['lacronyme']) && isset($_POST['lannee']) && ctype_digit($_POST['lannee']) && isset($_POST['lenumero']) && ctype_digit($_POST['lenumero']) && isset($_POST['letype']) && ctype_digit($_POST['letype']) && isset($_POST['debut']) && isset($_POST['fin']) && isset($_POST['lafiliere_idfiliere']) && ctype_digit($_POST['lafiliere_idfiliere']) ) {
	$lasession = new lasession($_POST);
	$lasessionM->sessionUpdate($lasession);
} 
else if( isset($_POST['lenom']) && isset($_POST['lacronyme']) && isset($_POST['lannee']) && ctype_digit($_POST['lannee']) && isset($_POST['lenumero']) && ctype_digit($_POST['lenumero']) && isset($_POST['letype']) && ctype_digit($_POST['letype']) && isset($_POST['debut']) && isset($_POST['fin']) && isset($_POST['lafiliere_idfiliere']) && ctype_digit($_POST['lafiliere_idfiliere']) ) {
	$lasession = new lasession($_POST);
	$lasessionM->sessionCreate($lasession);
}

// view all filieres
if (isset($_GET['viewlafiliere'])) {
    
    echo $twig->render('lafiliere/lafiliere_afficherliste.html.twig', ['detailfiliere' => $lafiliereM->filiereSelectAll()]);

// insert a filiere    
} elseif (isset($_GET['insertlafiliere'])) {

    if (isset($_POST['lenom'])) {
        
        $newfiliere = new lafiliere($_POST);

        if(!empty($_FILES)){

            $newfiliere->setLepicto($_FILES['lepicto']['name']);

        $upload = $lafiliereM->uploadFichier($_FILES['lepicto']);
        }
        
        $lafiliereM->filiereCreate($newfiliere);

       var_dump($newfiliere,$_POST,$_FILES);
        header("Location: ./?viewlafiliere");

    
    }else{

    echo $twig->render('lafiliere/lafiliere_ajouter.html.twig');
    
    }



// delete a filiere    
} elseif (isset($_GET['deletelafiliere']) && ctype_digit($_GET['deletelafiliere'])) {

    $idlafiliere = (int) $_GET['deletelafiliere'];
    
    // validated delete
    if (isset($_GET['ok'])) {
        
        $lafiliereM->filiereDelete($idlafiliere);

        header("Location: ./?viewlafiliere");
        
    } else {
        
        echo $twig->render('lafiliere/lafiliere_delete.html.twig', ['id' => $idlafiliere]);
    }

// update a filiere    
} else if (isset($_GET["updatelafiliere"]) && ctype_digit($_GET["updatelafiliere"])) {
    
    // submit updating filiere
    if(isset($_POST['idlafiliere'])){
        
        $updatelafiliere = new lafiliere($_POST);
        
        $lafiliereM->filiereUpdate($updatelafiliere,$_GET["updatelafiliere"]);
        
        header("Location: ./?viewlafiliere");
        
    }else{
		
        echo $twig->render('lafiliere/lafiliere_modifier.html.twig', ['section' => $lafiliereM->filiereSelectById($_GET['updatelafiliere'])]);
		
    }


        


	
// Display views for sessions
} elseif( isset($_GET['viewlasession']) ) {
	echo $twig->render("lasession/lasession_afficherliste.html.twig", ['detailsession'=>$lasessionM->sessionSelectALL()]);
} 
elseif ( isset($_GET['updatelasession']) && ctype_digit($_GET['updatelasession']) ) {
	echo $twig->render("lasession/lasession_modifier.html.twig", ['detailsession'=>$lasessionM->sessionSelectByID($_GET['updatelasession']), "filieres" => $lafiliereM->filiereSelectAll()]);
} 
elseif ( isset($_GET['insertlasession']) ) {
	echo $twig->render("lasession/lasession_ajouter.html.twig", ["filieres" => $lafiliereM->filiereSelectAll()]);
}
else {
    echo $twig->render('roles/admin/admin_homepage.html.twig', ['session' => $_SESSION]);
}
