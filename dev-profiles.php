<?php
/*
 * Plugin Name: Developer Profiles
 * Plugin URI: http://www.itsananderson.com/plugins/dev-profiles/
 * Description: Save and activate theme and plugin profiles for different projects
 * Plugin Author: Will Anderson
 * Author URI: http://www.itsananderson.com/
 */


add_action('admin_menu', 'add_dev_profile_menu');

function add_dev_profile_menu() {
	add_submenu_page('tools.php', 'Dev Profiles', 'Dev Profiles', 'administrator', __FILE__, 'dev_profile_page');
}

function dev_profile_page() {
	$profiles = get_option('dev_profiles', array());
	if ( 'Create Profile' == $_POST['submit'] ) {
		$theme = get_option('current_theme', 'Default');
		$template = get_option('template', 'default');
		$stylesheet = get_option('stylesheet', 'default');
		$plugins = get_option('active_plugins', array('dev-profiles/dev-profiles.php'));
		$profiles[$_POST['profile_name']] = array('theme' => $theme, 'template' => $template, 'stylesheet' => $stylesheet, 'plugins' => $plugins);
		update_option('dev_profiles', $profiles);
		$message = "Profile '{$_POST['profile_name']}' successfully created";
	} elseif ( 'Enable Profile' == $_POST['submit'] ) {
		if ('' == $_POST['select_profile']) {
			$message = "Oops, you need to select a Dev Profile before it can be enabled";
		} else {
			$profile = $profiles[$_POST['select_profile']];
			update_option('current_theme', $profile['theme']);
			update_option('template', $profile['template']);
			update_option('stylesheet', $profile['stylesheet']);
			update_option('active_plugins', $profile['plugins']);
			$message = "Dev profile '{$_POST['select_profile']}' successfully enabled";
		}
	}
?>
<div class="wrap">
	<h2>Dev Profiles</h2>
	<?php 
	if ( isset($message) ) {
		echo "<div class='updated'><p>$message</p></div>";
	}
	?>
	<div id="create-profile">
		<h3>Create Dev Profile Using Current Theme and Plugins</h3>
		<form method="POST">
			<input type="text" name="profile_name" placeholder="Profile Name" />
			<input type="submit" class="button" name="submit" value="Create Profile" />
		</form>
	</div>
	<div id="activate-profile">
		<h3>Enable a Dev Profile</h3>
		<form method="POST">
			<select id="select_profile" name="select_profile">
				<option value=""></option>
			<?php
			foreach ( $profiles as $name => $profile ) {
				echo "<option value='$name'>$name</option>";
			}
			?>
			</select>
			<input type="submit" class="button" name="submit" value="Enable Profile" />
		</form>
	</div>
	<div id="dev-profile-info">
	</div>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			var profiles = <?php echo json_encode($profiles) ?>;
			
			$('#select_profile').change(function(element) {
				var profile = profiles[$('#select_profile').val()];
				$('#dev-profile-info').empty().append(
					"<h4>Profile Details: " + $('#select_profile').val() + "</h4>" +
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
<?php
}