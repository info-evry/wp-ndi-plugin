<?php


function ndi_handler()
{
    $validity = check_ajax_referer('ndi_nonce');
    $team =     json_decode( urldecode( $_REQUEST['ndi_team'] ) );
    $members =  json_decode( urldecode( $_REQUEST['ndi_members'] ) );

    if ( $validity !== false ) {

        $id = 0;
		$teamName = '';

        if ( $team->teamId < 0 ) {
            $teamName = sanitize_text_field( utf8_encode( base64_decode( $team->teamName ) ) );
            $teamDesc = sanitize_text_field( utf8_encode( base64_decode( $team->teamDesc ) ) );
            $id = add_ndi_team( $teamName, $teamDesc );
            if ( $id === false ) {
                echo -3;
                die();
            }
        } else {
            $id = $team->teamId;
			$teamName = sanitize_text_field( utf8_encode( base64_decode( $team->teamName ) ) );
        }

        if ($id > 0) {

            foreach ($members as $member) {

                $firstname =    $member->firstname !== NULL ? sanitize_text_field( utf8_encode( base64_decode( $member->firstname ) ) ) : NULL;
                $lastname =     $member->lastname !== NULL ? sanitize_text_field( utf8_encode( base64_decode( $member->lastname ) ) ) : NULL;
                $email =        $member->email !== NULL ? sanitize_email( utf8_encode( base64_decode( $member->email ) ) ) : NULL;
                $baclevel =     $member->baclevel !== NULL ? sanitize_text_field( $member->baclevel ) : NULL;
                $fooddiet =     $member->fooddiet !== NULL ? sanitize_text_field( utf8_encode( base64_decode( $member->fooddiet ) ) ) : NULL;
                $ismanager =    $member->ismanager === true ? '1' : '0';

                if ( add_ndi_member($firstname, $lastname, $email, $fooddiet, $baclevel, $ismanager, $id) === false ) {
                    echo -2;
                    die();
                }
				else {
					$subject = 'Team ' . $teamName . ' - Inscription à la Nuit de l\'Info';
					$body = "Bonjour $firstname,\r\nNous avons retenu votre inscription à la Nuit de l'Info dans l'équipe ". $teamName ." ! Bienvenue !\r\nVous retrouverez d'autres informations à l'adresse : https://asso.info-evry.fr/nuit-de-linfo/.\r\nVoici un résumé des informations renseignées :\r\n\r\n\t- Equipe : " . $teamName . "\r\n\t- Prénom : " . $firstname . "\r\n\t- Nom : " . $lastname . "\r\n\t- Email : " . $email . "\r\n\t- Niveau Post-Bac : " . $baclevel . "\r\n\t- Régime Alimentaire : " . $fooddiet . "\r\n\t- Chef d'équipe : " . ($ismanager === true ? "Oui" : "Non") . "\r\n\r\nSi vous n'êtes pas à l'origine de cette démarche ou que vous ne l'avez pas demandée à votre chef d'équipe, répondez directement à cet email pour nous faire part des actions que vous souhaitez que nous réalisions.\r\n\r\nEn vous souhaitant une bonne journée,\r\n\r\nL'Equipe Organisatrice,\r\nAssociation Info Evry\r\nasso@info-evry.fr";
					$headers = array(
						'Content-Type: text/plain; charset=UTF-8',
						'From: Association Info Evry <asso@info-evry.fr>',
						'Reply-To: Association Info Evry <contact@info-evry.fr>'
					);
 
					wp_mail( $email, $subject, $body, $headers );
					
					$subjectAdmin = 'Nouvelle Inscription - Team ' . $teamName . ' - Inscription à la Nuit de l\'Info';
					$bodyAdmin = "Bonjour,\r\n Une personne vient de s'inscrire via le formulaire d'inscription du site, voici un résumé des informations renseignées :\r\n\r\n\t- Equipe : " . $teamName . "\r\n\t- Prénom : " . $firstname . "\r\n\t- Nom : " . $lastname . "\r\n\t- Email : " . $email . "\r\n\t- Niveau Post-Bac : " . $baclevel . "\r\n\t- Régime Alimentaire : " . $fooddiet . "\r\n\t- Chef d'équipe : " . ($ismanager === true ? "Oui" : "Non") . "\r\n\r\nSi vous n'êtes pas à l'origine de cette démarche ou que vous ne l'avez pas demandée à votre chef d'équipe, répondez directement à cet email pour nous faire part des actions que vous souhaitez que nous réalisions.\r\n\r\nEn vous souhaitant une bonne journée,\r\n\r\nL'Equipe Organisatrice,\r\nAssociation Info Evry\r\nasso@info-evry.fr";
					$headersAdmin = array(
						'Content-Type: text/plain; charset=UTF-8',
						'From: Association Info Evry <asso@info-evry.fr>',
					);
 
					wp_mail( get_bloginfo( 'admin_email' ), $subjectAdmin, $bodyAdmin, $headersAdmin );
					
				}
            }
            echo 1;
            die();
        }

        echo 0;
        die();
    }
    echo -1;
    die();
}

add_action('wp_ajax_ndi_handler', 'ndi_handler');
add_action('wp_ajax_nopriv_ndi_handler', 'ndi_handler');
