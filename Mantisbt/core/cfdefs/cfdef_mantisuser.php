<?php
# MantisBT - a php based bugtracking system

# Copyright (C) 2000 - 2002  Kenzaburo Ito - kenito@300baud.org
# Copyright (C) 2002 - 2010  MantisBT Team - mantisbt-dev@lists.sourceforge.net

# MantisBT is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 2 of the License, or
# (at your option) any later version.
#
# MantisBT is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with MantisBT.  If not, see <http://www.gnu.org/licenses/>.

/************************************************************

 Adds a Custom Field type of 'Mantis User', including support
 for a minimum user level.
 
 Written by djcarr for Mantis 1.2.4.

 To Use:
 -------
 1. Go to 'Manage', 'Manage Custom Fields', and add a new Custom Field.
 2. On the 'Edit custom field' page, select Type 'Mantis User'
 3. Optionally, a minimum user level can be specified in the "Possible Values" property. 
    This can be a  known constant (DEVELOPER,UPDATER,MANAGER,etc) or a number (50,60,70).
    Only users at or above that user level will be available for this Custom Field.
 4. Modify the config value $g_custom_field_mantisuser_filter_show_all if desired.
 
************************************************************/

$g_custom_field_type_definition[ CUSTOM_FIELD_TYPE_MANTISUSER ] = array (
	'#display_possible_values' => TRUE,
	'#display_valid_regexp' => FALSE,
	'#display_length_min' => FALSE,
	'#display_length_max' => FALSE,
	'#display_default_value' => FALSE,
	'#function_return_distinct_values' => $g_custom_field_mantisuser_filter_show_all ? 'cfdef_prepare_mantisuser_all_values' : null,
	'#function_value_to_database' => null,
	'#function_database_to_value' => null,
	'#function_print_input' => 'cfdef_input_mantisuser',
	'#function_string_value' => 'cfdef_prepare_mantisuser_value',
	'#function_string_value_for_email' => 'cfdef_prepare_mantisuser_value',
);
	
/**
 * Function to generate a combo box of Mantis Users.
 * Compatible with : $g_custom_field_type_definition['#function_print_input'] 
 * This is used to generate the Custom Field on the Update Issue page.
 * @param array $p_field_def the Custom Field as defined in Mantis 
 * @param array $t_custom_field_value contains the currently selected user id, if any
 * @access public
 */	
function cfdef_input_mantisuser($p_field_def, $t_custom_field_value) {
	// generate list of valid users
	$t_users_array = array();
	$t_project_id = helper_get_current_project();
	$t_users_array = project_get_all_user_rows( $t_project_id, get_mantisuser_threshold( $p_field_def ) );
	$t_users_array = sort_user_rows( $t_users_array );
	// generate list of already selected user(s)
	$t_selected_values = explode( '|', $t_custom_field_value );
	$t_list_size = 0;	# for this the size is always 0
	echo '<select ', helper_get_tab_index(), ' name="custom_field_' . $p_field_def['id'] . '" size="' . $t_list_size . '">';
	// a blank option so an unselected field works properly
	echo '<option value="0"></option>';
    foreach ($t_users_array as $t_user){
		$t_user_id = $t_user['id'];
		$t_selected_substring = '';
		if( in_array( $t_user_id, $t_selected_values, true ) ) {
			$t_selected_substring = ' selected="selected" ';
		}
		echo '<option value="'.$t_user_id.'"'.$t_selected_substring.'> '.cfdef_prepare_mantisuser_value($t_user_id).'</option>';
	}
	echo '</select>';
}

/**
 * Function to produce a printable string from a user ID
 * Compatible with : $g_custom_field_type_definition['#function_string_value'] 
 * @param array $p_value a user ID
 * @access public
 */	
function cfdef_prepare_mantisuser_value($p_value) {
	// convert a user id into the user's name
	if ( user_exists( $p_value ) ) {
		$t_show_real_names = config_get( 'show_realname' ) == ON;
		$t_user_realname = user_get_realname( $p_value );
		if ($t_show_real_names && !is_blank( $t_user_realname ) ) {
			return $t_user_realname;
		} else {
			return user_get_name( $p_value );
		}
	} else {
		return '';
	}
}

/**
 * Function to retrieve an array of all valid user ids
 * Compatible with : $g_custom_field_type_definition['#function_return_distinct_values'] 
 * This is used to generate the filter option.
 * @param array $p_field_def the Custom Field as defined in Mantis 
 * @access public
 */	
function cfdef_prepare_mantisuser_all_values($p_field_def) {
	$t_project_id = helper_get_current_project();
	$t_users_array = array();
	$t_users_array = project_get_all_user_rows( $t_project_id, get_mantisuser_threshold( $p_field_def ) );
	$t_users_array = sort_user_rows( $t_users_array );
	$t_return_arr = array();
    foreach ($t_users_array as $t_user){
		$t_user_id = $t_user['id'];
		array_push( $t_return_arr, $t_user_id );
	}
	return $t_return_arr;
}

/**
 * Function to extract the minimum userlevel threshold  from the Custom Field property 'Possible Values'.
 * This value can be entered as a constant (DEVELOPER,UPDATER,MANAGER,etc) or a number (50,60,70).
 * @param array $p_field_def the Custom Field as defined in Mantis 
 * @return int representing the User Level threshold 
 * @access public
 */	
function get_mantisuser_threshold($p_field_def) {
	$t_threshold = ANYBODY;
	$t_possible_values = explode( '|', $p_field_def['possible_values'] );
	// the first possible value is the minimum userlevel for the mantis user
	if ( count( $t_possible_values ) > 0 ) {
		$t_threshold = constant_replace( $t_possible_values[0] );
	}
	return $t_threshold;
}

/** 
 * Check if the passed string is a constant and return its value
 * Code pilfered from adm_config_set.php.
 * @access private
 */
function constant_replace( $p_name ) {
	$t_result = $p_name;
	if ( is_string( $p_name ) && defined( $p_name ) ) {
		// we have a constant
		$t_result = constant( $p_name );
	}
	return $t_result;
}
	
/** 
 * Sort the user rows returned by project_get_all_user_rows() 
 * Code pilfered from print_user_option_list.php.
 * @access private
 */
function sort_user_rows( $p_user_rows ) {
	$t_users = $p_user_rows;
	$t_display = array();
	$t_sort = array();
	$t_show_realname = ( ON == config_get( 'show_realname' ) );
	$t_sort_by_last_name = ( ON == config_get( 'sort_by_last_name' ) );
	foreach( $t_users as $t_user ) {
		$t_user_name = string_attribute( $t_user['username'] );
		$t_sort_name = strtolower( $t_user_name );
		if( $t_show_realname && ( $t_user['realname'] <> "" ) ) {
			$t_user_name = string_attribute( $t_user['realname'] );
			if( $t_sort_by_last_name ) {
				$t_sort_name_bits = split( ' ', strtolower( $t_user_name ), 2 );
				$t_sort_name = ( isset( $t_sort_name_bits[1] ) ? $t_sort_name_bits[1] . ', ' : '' ) . $t_sort_name_bits[0];
			} else {
				$t_sort_name = strtolower( $t_user_name );
			}
		}
		$t_display[] = $t_user_name;
		$t_sort[] = $t_sort_name;
	}
	array_multisort( $t_sort, SORT_ASC, SORT_STRING, $t_users, $t_display );
	return $t_users;
}

?>