<div class="wrap">
    <h2>Developer Profiles</h2>
	<?php
	if ( isset( $message ) ) {
		echo "<div class='updated'><p>$message</p></div>";
	}
	?>
	<div id="create-profile">
		<h3>Create a dev profile with the current theme and plugins</h3>

		<form method="POST" action="">
			<label for="profile-name"><?php _e( 'Profile Name: ' ); ?></label>
			<input type="text" name="profile-name" id="profile-name"/>
			<input type="submit" class="button" name="create-dev-profile" value="Create Profile"/>
		</form>
	</div>
	<div id="activate-profile">
		<h3>Enable a dev profile</h3>

		<form method="POST" action="">
			<label for="select-profile"><?php _e( 'Select a Profile: ' ); ?></label>
			<select id="select-profile" name="select-profile">
				<option value=""></option>
				<?php
				foreach ( $profiles as $name => $profile ) {
					echo "<option value='$name'>$name</option>";
				}
				?>
			</select>
			<input type="submit" class="button" name="enable-dev-profile" value="Enable Profile"/>
		</form>
	</div>
	<div id="dev-profile-info">
	</div>
	<script type="text/javascript">
		jQuery(document).ready(function ($) {
			var $selectProfile = $('#select-profile');
			var profiles = <?php echo json_encode( $profiles ) ?>;

			$selectProfile.change(function (element) {
				var profile = profiles[$selectProfile.val()];
				$('#dev-profile-info').empty().append(
						"<h4>Profile Details: " + $selectProfile.val() + "</h4>" +
								"<p><strong>Theme: </strong>" + profile['theme'] + "</p>" +
								"<p><strong>Plugins:</strong></p>" +
								"<ul><li>" +
								profile['plugins'].join("</li><li>") +
								"</li></ul>"
				);
			});
		});
	</script>
</div>
