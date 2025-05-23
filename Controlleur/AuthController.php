<?php
require_once '../DAO/UtilisateurDAO.php';

if ($_GET) {
    $action = $_GET['logout'];
    if ($action == true) {
        session_start();
        session_destroy();
        header('Location: ../Vues/Authentification.php');
        exit();
    }
}
if (isset($_POST['inscription'])) {
        $login = $_POST['login'];
        $password = $_POST['password'];
        $nom = $_POST['nom'];
        $email = $_POST['email'];
        $adresse = $_POST['adresse'];
        $tel = $_POST['tel'];
        echo $login . $password . $nom . $email . $adresse . $tel;

        try {
            UtilisateurDAO::AjouterUtilisateur($login, 
                                                $password, 
                                                $nom, 
                                                $email, 
                                                $adresse, 
                                                $tel, 
                                                Client::$code); // client par defaut
            header('Location: ../Vues/Authentification.php');
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            exit;
        }
} 
if (isset($_POST['login_btn'])) {
    $login = $_POST['loginL'];
    $password = $_POST['passwordL'];
    echo $login . $password;
    if ($login == "" || $password == "") {
        header('Location: ../Vues/Authentification.php?status=false?errcode=2');
    }
    try {
        $utilisateurs = UtilisateurDAO::FindAll();
        if (empty($utilisateurs)) {
            echo "Aucun utilisateur trouvé.";
            exit;
        }
        $trouve = false;
        foreach ($utilisateurs as $utilisateur) {
            echo $utilisateur->login;
            if ($utilisateur->login == $login && $utilisateur->password == $password) {
                $trouve = true;
                session_start();
                $_SESSION['client'] = $utilisateur->id;
                $_SESSION['login'] = $login;
                header('Location: ../Vues/index.php');
                // switch (get_class($utilisateur)) {
                //     case 'Client':
                //         header('Location: ../Vues/Accueil.php');
                //         break;
                //     case 'Technicien':
                //         header('Location: ../Vues/InterfaceTechnicien.php');
                //         break;
                //     case 'Admin':
                //         header('Location: ../Vues/Administration.php');
                //         break;
                // }

            }
        }
        if ($trouve == false) {
            header('Location: ../Vues/Authentification.php?status=false&errcode=3');
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}
?>