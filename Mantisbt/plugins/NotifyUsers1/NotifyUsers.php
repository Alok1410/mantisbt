<?php

class NotifyUsersPlugin extends MantisPlugin {

	function register() {
		$this->name = 'NotifyUsers';
		$this->description = 'Allows a reporter to signify multiple users to notify via email upon issue creation.';
		$this->page = '';

		$this->version = '1.0';
		$this->requires = array (
			'MantisCore' => '2.2.0'
			);
			
		$this->author = 'Richard Tafoya';
		$this->contact = 'RichRoc17@gmail.com';
		$this->url = '';
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
		<select name="notify[]" multiple="multiple" size="7" style="width:500px">
			<?php echo print_project_user_option_list() ?>
		</select>
		</td>
		</tr>
<?php

	}	

	function notify_users_email( $p_event, $p_bug_data ){
		if ( count($_REQUEST['notify']) != 0 ) {
			$t_email_users = gpc_get_int_array( 'notify' );
			$t_bug_id = $p_bug_data->id;
			email_generic( $t_bug_id, 'new', plugin_lang_get('notify_subject'), '', $t_email_users );
		}
						
	}
}
