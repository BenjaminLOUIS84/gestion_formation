
<?php

    // Pour filtrer les données et ainsi sécuriser le formulaire

    if (isset($_POST['message'])){

    

            $entete = 'MIME-Version:1.0' . "\r\n";
            $entete .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            
            $message = '<h1>Message envoyé depuis la page Inscription de Gestion de Formation</h1>
            <p><b>Nom : </b>' . $nom . '<br>
            <p><b>Prénom : </b>' . $prenom . '<br>
           
            <p><b>Email : </b>' . $mail . '<br>
            <b>Message : </b>' . htmlspecialchars($message) . '</p>';
            
            $retour = mail('benlouisdevweb@gmail.com', 'Envoi depuis la page Inscription', $message, $entete);
            
            // if ($retour)
            // echo 
            // '<section class="accueil">
            //     <h2>Votre message a bien été envoyé.</h2><br>
            //     <div class="button">
            //     <a href="index.html" class="ancre">Retour</a>
            //     </div>
            // </section>';
            
        }else{
            // echo
            // '<section class="accueil">
            //     <h2>Il faut un email, un nom et un message valides pour soumettre le formulaire.</h2><br>
            //     <div class="button">
            //             <a href="index.html" class="ancre">Retour</a>
            //     </div>
            // </section>';
        }

    

?>
