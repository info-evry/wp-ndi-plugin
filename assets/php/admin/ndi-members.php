<?php

if (!function_exists('ndi_set_teams_page')) {
	function ndi_set_teams_page()
	{
		add_menu_page(
			"Equipes de la Nuit de l'Info",
			'Equipes',
			'manage_options',
			'ndi_teams',
			'ndi_teams_page',
			'dashicons-buddicons-buddypress-logo'
		);
	}
}
add_action('admin_menu', 'ndi_set_teams_page');

if (!function_exists('ndi_teams_page')) {
	function ndi_teams_page()
	{
		?><div class="wrap">
			<style>
				table,
				td {
					border: 1px solid black;
					border-collapse: collapse;
				}
				.team-table {
					margin-bottom: 1rem;
				}
				.team-part {
					background-color: white;
					border: 2px solid #00000020;
					border-radius: .5rem;
					padding: 1rem 2rem 2rem;
				} 
				.team-part:first-child,
				* + .team-part {
					margin: 2rem 0 2rem;
				}
				* + tr {
					padding-top: 1rem;
				}
				td {
					padding: .5rem;
				}
				.header-row {
					font-weight: bold;
				}
			</style>
			<h1 class="h1"><?php esc_html_e("Equipes de la Nuit de l'Info", 'ndi'); ?></h1>
			<div id="post-body" class="metabox-holder columns-7">
				<div id="post-body-content">
					<?php
					
					$teams = get_ndi_teams();
					$members = get_ndi_members();


					foreach( $teams as $team ) {
						
						?><div class="team-part">
						<h2>Equipe <span class="team-id"><?= $team->id ?></span> : [&nbsp;<span><?= $team->teamName ?></span>&nbsp;]</h2><?php
						?><p><span>Description</span> : <span><?= $team->teamDesc ?></span></p><?php

						?><table class="wp-list-table widefat team-table" data-team-id="<?= $team->id ?>">
							<tbody class="the-list">
							<tr class="header-row">
								<td>ID</td>
								<td>Prénom</td>
								<td>Nom</td>
								<td>Email</td>
								<td>Post-Bac</td>
								<td>Chef d'Equipe</td>
								<td>Régime</td>
							</tr>
						<?php
						foreach ( $members as $member ) {
							if ( $member->teamid === $team->id ) {
								?>
								<tr class="team-member">
									<td><?= $member->id ?></td>
									<td><?= $member->firstname ?></td>
									<td><?= $member->lastname ?></td>
									<td><?= $member->email ?></td>
									<td><?= $member->baclevel ?></td>
									<td><?= $member->ismanager ?></td>
									<td><?= $member->fooddiet ?></td>
								</tr>
								<?php
							}
						}
						?></tbody></table>

					</div>
						<hr><?php
					}
					
					?>
				</div>
			</div>
			<br class="clear">
		</div><?php
					}
				}
