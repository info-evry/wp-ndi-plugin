<?php

if (!function_exists('ndi_shortcode_init')) {
    function ndi_shortcode_init()
    {
        function ndi_shortcode()
        {

            ob_start();
			
?>
<h2>Equipes Inscrites<span class="screen-reader-text"> à la Nuit de l'Info</span></h2>
<div id="ndi-valid-teams" class="ndi-valid-teams"></div>
<div style="height:50px" aria-hidden="true" class="wp-block-spacer"></div>
<h2>Formulaire<span class="screen-reader-text"> d'Inscription à la Nuit de l'Info</span></h2>
<div class="ndi-form">
<form id="ndi-form" method="post" accept-charset="utf-8" autocomplete="off">
    <h3>Equipe&nbsp;<span class="required">*</span></h3>
    <div class="ndi-validation-text">
        <p>Pour vous inscrire, vous devez créer une équipe :</p>
    </div>
    <div class="ndi-team">
        <div class="ndi-team-opt">
            <h4>Créez une équipe</h4>
            <div class="ndi-team-part">
                <div class="ndi-team-name input-block">
                    <label for="ndi-team-name">Nom de l'équipe</label>
                    <input type="text" id="ndi-team-name" maxlength="128" name="ndi-team-name" required>
                </div>
            </div>
            <div class="ndi-team-part">
                <div class="ndi-team-desc input-block">
                    <label for="ndi-team-desc">Description / Devise</label>
                    <input type="text" id="ndi-team-desc" maxlength="256" name="ndi-team-desc">
                </div>
            </div>
        </div>
    </div>
    <div class="ndi-validation-text">
        <p>Ou intégrer une équipe déjà existante – en accord avec les membres de l'équipe bien sûr :</p>
    </div>
    <div class="ndi-team">
        <div class="ndi-team-opt">
            <div class="ndi-form-part">
                <div class="ndi-team-selector">
                    <label for="ndi-team-selector"><h4>Intégrez une équipe</h4></label>
                    <select id="ndi-team-selector" required>
                        <option class="no-option-selected" value="-1" selected>Aucune équipe séléctionée</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <h3>Membres</h3>
    <div class="ndi-validation-text">
    <p>Pour chacun des membres de l'équipe, les informations suivantes sont nécessaires :
        <ul>
            <li>un prénom,</li>
            <li>un nom de famille,</li>
            <li>une adresse email,</li>
            <li>et le niveau actuel d'étude après le BAC</li>
            <!--<li>et si particulier, le régime alimentaire suivi<br><em style="opacity:.7;">Cette information ne sera en aucun cas communiquée à un tiers.</em></li>-->
		</ul>
	</p>

		<p><span style="color: orange;">&#x26A0;</span>&nbsp;Un membre ne peut appartenir à deux équipes ou être inscrit deux fois dans la même équipe.</p>
        <p><span style="color: orange;">&#x26A0;</span>&nbsp;Une équipe ne peut être constituée que d'un maximum de 5 participants.</p>
    </div>
    <div class="ndi-validation-text">
		<p style="color: red;">Les informations de contacts seront exclusivement utilisées pour vous inscrire officiellement sur le site de la Nuit de l'Info en tant qu'étudiant à l'Université d'Evry.</p>
    </div>
    <p id="ndi-member-counter">Aucune équipe sélectionée.</p>
	<div style="height:50px" aria-hidden="true" class="wp-block-spacer"></div>
    <div class="ndi-member invalid">
        <div class="ndi-member-part">
            <div class="ndi-member-ismanager input-block">
                <label for="ndi-member-0-ismanager">Chef d'Equipe</label>
                <input type="checkbox" id="ndi-member-0-ismanager" class="switch-checkbox" name="ndi-member-0-ismanager">
            </div>
        </div>
        <div class="ndi-member-part">
            <div class="ndi-member-firstname input-block">
                <label for="ndi-member-0-firstname">Prénom&nbsp;<span class="required">*</span></label>
                <input type="name" id="ndi-member-0-firstname" name="ndi-member-0-firstname" class="invalid" required>
            </div>
            <div class="ndi-member-lastname input-block">
                <label for="ndi-member-0-lastname">Nom&nbsp;<span class="required">*</span></label>
                <input type="name" id="ndi-member-0-lastname" name="ndi-member-0-lastname" class="invalid" required>
            </div>
        </div>
        <div class="ndi-member-part">
            <div class="ndi-member-email input-block">
                <label for="ndi-member-0-email">Email&nbsp;<span class="required">*</span></label>
                <input type="email" id="ndi-member-0-email" name="ndi-member-0-email" class="invalid" required>
            </div>
            <div class="ndi-member-baclevel input-block">
                <label for="ndi-member-0-baclevel">Niveau post-bac (année en cours)&nbsp;<span class="required">*</span></label>
                <input type="number" min="0" max="10" id="ndi-member-0-baclevel" name="ndi-member-0-baclevel" class="invalid" required>
            </div>
        </div>
        <div class="ndi-member-part">
            <div class="ndi-member-fooddiet input-block">
                <label for="ndi-member-0-fooddiet">Régime Alimentaire Particulier</label>
                <input type="text" id="ndi-member-0-fooddiet" name="ndi-member-0-fooddiet" placeholder="Végétarien, pas de porc, ...">
            </div>
        </div>
        <div class="ndi-member-actions">
            <button id="ndi-member-0-add" class="ndi-add-member" title="Ajouter un membre" disabled>Ajouter un membre</button>
            <button id="ndi-member-0-remove" class="ndi-remove-member" title="Supprimer le membre" disabled>Supprimer</button>
        </div>

    </div>

    <div class="ndi-form-validation">
        <input type="submit" id="ndi-form-validation" value="Valider l'inscription" disabled>
    </div>
</form>
</div>

<?php
            return ob_get_clean();
        }
        add_shortcode('ndi', 'ndi_shortcode');
    }
}
add_action('init', 'ndi_shortcode_init');
