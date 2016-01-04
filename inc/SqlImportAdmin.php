<?php
/**
 *
 */

namespace Inpsyde\SearchReplace\inc;

class SqlImportAdmin extends Admin {

	public function show_page() {

		if ( isset ( $_POST[ 'action' ] ) && $_POST[ 'action' ] == "sql_import" ) {
			$this->handle_sql_import_event();

		}
		$this->display_errors();
		require_once( 'templates/sql_import.php' );
	}

	/**
	 *displays the html for the submit button
	 */
	protected function show_submit_button() {

		wp_nonce_field( 'sql_import', 'insr_nonce' );

		$html = '	<input type="hidden" name="action" value="sql_import" />';
		echo $html;
		submit_button( __( 'Import SQL file', 'insr' ) );

	}

	private function handle_sql_import_event() {
		if ($_FILES ['file_to_upload']['error'] == 0 ) {

			$sql_source = file_get_contents( $_FILES [ 'file_to_upload' ][ 'tmp_name' ] );
			$success = $this->dbm->import_sql( $sql_source );
			if ($success === false ) {
				$this->errors->add( 'sql_import_error', __( 'The file does not seem to be a valid SQL file. Import not possible.','insr'));
			}
			else {
				echo '<div class = "updated notice is-dismissible">';
				echo '<p>'.__('The SQL file was successfully imported.','insr').'</p></div>';
			}
		}
		else
		{//show error
			$this->errors->add( 'upload_error', __( 'Upload Error. Error Code: '.$_FILES['file_to_upload']['error'], 'insr' ) );
		}


	}

}