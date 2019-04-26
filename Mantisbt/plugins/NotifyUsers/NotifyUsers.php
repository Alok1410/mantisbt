<?php

class NotifyUsersPlugin extends MantisPlugin {

	function register() {
		$this->name = 'NotifyUsers';
		$this->description = 'Allows a reporter to signify multiple users to notify via email upon issue creation.';
		$this->page = 'config';

		$this->version = '2.0';
		$this->requires = array (
			'MantisCore' => '2.15.0'
			);
			
		$this->author = 'Alok Sharma';
		$this->contact = 'alok14101989@gmail.com';
		$this->url = '';
	}

	
	function config() {
		return array(
			'select_threshold' => DEVELOPER,
			);
	}
	function hooks() {
		return array(
			'EVENT_REPORT_BUG_FORM' => 'notify_users_select',
			'EVENT_REPORT_BUG' => 'notify_users_email',
		);
	}
		
	function notify_users_select( $p_event ) {
?>
		<tr <?php echo helper_alternate_class() ?>>
		<td class="category">
		<?php echo plugin_lang_get( 'notify_users_category' ) ?>
		</td>
		<td>
		<select name="notify[]" multiple="multiple" size="5" style="width:500px">
		
		 /**
		 print_project_user_option_list_2() is in print_api.php
 * prints the list of a project's users with specific usertype
 * if no project is specified uses the current project
 * @param integer $p_project_id A project identifier.
 * @return void
 */
			<?php echo print_project_user_option_list_2(null, DEVELOPER) ?>
		</select>
		</td>
		</tr>
<?php

	}	

	function notify_users_email( $p_event, $p_bug_data ){
		if ( count($_REQUEST['notify']) != 0 ) {
			$t_email_users = gpc_get_int_array( 'notify' );
			$t_header_optional_params = null;
			$t_bug_id = $p_bug_data->id;
			email_generic( $t_bug_id, 'new', plugin_lang_get('notify_subject'), $t_header_optional_params, $t_email_users );
		}
						
	}
}
