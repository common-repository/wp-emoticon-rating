<?php
if( !class_exists( 'WPEmoAdmin' ) ) :
    class WPEmoAdmin {

        public $strErrorMsg;
        public $strSuccessMsg;

        public function __construct() {
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueu_scripts' ) );
            add_action( 'admin_init', array( $this, 'save_settings' ) );
            add_action( 'admin_menu', array( $this, 'add_menus' ) );
        }
        
        public function add_menus() {
            add_options_page( 'Emoticon Rating', 'Emoticon Rating', 'manage_options', 'emo-settings', array( $this, 'setting_page' ) );
        }
        
        public function save_settings() {
            if( isset( $_POST['emo_settings'] ) ) {
                if( '' != $_POST['emo_settings']['enable_post_ids'] && false == $this->valIds( $_POST['emo_settings']['enable_post_ids'] ) ) {
                    $this->strErrorMsg = 'Please enter valid comma separated enabled post ids.';
                    add_action( 'admin_notices', array( $this, 'emo_error_notice' ) );
                    return false;
                }

                if( '' != $_POST['emo_settings']['excluded_post_ids'] && false == $this->valIds( $_POST['emo_settings']['excluded_post_ids'] ) ) {
                    $this->strErrorMsg = 'Please enter valid comma separated excluded post ids2.';
                    add_action( 'admin_notices', array( $this, 'emo_error_notice' ) );
                    return false;
                }


                if( '' != $_POST['emo_settings']['enable_page_ids'] && false == $this->valIds( $_POST['emo_settings']['enable_page_ids'] ) ) {
                    $this->strErrorMsg = 'Please enter valid comma separated excluded page ids.';
                    add_action( 'admin_notices', array( $this, 'emo_error_notice' ) );
                    return false;
                }


                if( '' != $_POST['emo_settings']['excluded_page_ids'] && false == $this->valIds( $_POST['emo_settings']['excluded_page_ids'] ) ) {
                    $this->strErrorMsg = 'Please enter valid comma separated excluded page ids.';
                    add_action( 'admin_notices', array( $this, 'emo_error_notice' ) );
                    return false;
                }

                update_option( 'emo_settings', $_POST['emo_settings'] );
            }
        }

        public function valIds( $strIds ) {
            $boolIsValid = true;

            if( !preg_match( '/^[0-9]+(,[0-9]+)*$/', $strIds ) ) {
                $this->strErrorMsg = 'Please check comma separated strings.';
                $boolIsValid = false;
            }

            return $boolIsValid;
        }

        function emo_error_notice() {
            $class = 'notice notice-error';

            printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $this->strErrorMsg ) );
        }

        public function enqueu_scripts() {
            wp_enqueue_script( 'wp-emoticon-js', WP_EMO_URL . '/js/emoticon-admin.js' );
            wp_enqueue_style( 'wp-emoticon-admin-css', WP_EMO_URL . '/css/emoticon-admin.css' );
        }
        
        public function setting_page() {
            $emo_settings = get_option( 'emo_settings' );

            if( true == isset( $_POST['emo_settings'] ) ) {
                $emo_settings = $_POST['emo_settings'];
            }

		echo '<h1>Emoticon Rating Settings</h1>';
		?>
			<div class="emo-setting-container">
			<form action="" method="post">
    			<table width="100%" cellspacing="0" cellpadding="0">
    				<tr>
        				<th width="15%"><label>Enable for Posts: </label></th>
        				<td width="85%">
        					<input type="checkbox" id="is-enable-for-post" name="emo_settings[is_enable_for_post]" value="1" <?php echo isset( $emo_settings['is_enable_for_post'] ) ? 'checked' : ''; ?> >
    					</td>
    				</tr>
                    <tr id="emoticon-post-setting" <?php echo !isset( $emo_settings['is_enable_for_post'] ) ? 'style="display: none;"' : '' ?> >
                        <th><label>Post Settings: </label></th>
                        <td width="85%">
                            <table>
                                <tr>
                                    <th><label>Enable for all posts:</label></th>
                                    <td><input type="checkbox" value="1" name="emo_settings[is_enable_for_all_posts]" <?php echo isset( $emo_settings['is_enable_for_all_posts'] ) ? 'checked' : ''; ?>  /></td>
                                </tr>
                                <tr>
                                    <th><label>Enable following comma separated post ids:</label></th>
                                    <td>
                                        <textarea name="emo_settings[enable_post_ids]" placeholder="Enter comma separated post ids" rows="3" cols="30"><?php echo $emo_settings['enable_post_ids']; ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Exclude following comma separated post ids:</label></th>
                                    <td>
                                        <textarea value="" name="emo_settings[excluded_post_ids]" placeholder="Enter comma separated post ids" rows="3" cols="30"><?php echo $emo_settings['excluded_post_ids']; ?></textarea>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
    				<tr>
        				<th><label>Enable for Pages: </label></th>
        				<td width="85%">
        					<input type="checkbox" id="is-enable-for-page" name="emo_settings[is_enable_for_page]" value="1" <?php echo isset( $emo_settings['is_enable_for_page'] ) ? 'checked' : ''; ?> >
        				</td>
        			</tr>
    				<tr id="emoticon-page-setting" <?php echo !isset( $emo_settings['is_enable_for_page' ] ) ? 'style="display: none;"' : ''; ?> >
                        <th>Page Settings: </th>
                        <td width="85%">
                            <table>
                                <tr>
                                    <th><label>Enable for all pages:</label></th>
                                    <td><input type="checkbox" value="1" name="emo_settings[is_enable_for_all_pages]" <?php echo isset( $emo_settings['is_enable_for_all_pages']) ? 'checked' : '' ?> /></td>
                                </tr>
                                <tr>
                                    <th><label>Enable following comma separated page ids:</label></th>
                                    <td>
                                        <textarea name="emo_settings[enable_page_ids]" placeholder="Enter comma separated page ids" rows="3" cols="30"><?php echo $emo_settings['enable_page_ids']; ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label>Exclude following comma separated page ids:</label></th>
                                    <td>
                                        <textarea name="emo_settings[excluded_page_ids]" placeholder="Enter comma separated page ids" rows="3" cols="30"><?php echo $emo_settings['excluded_page_ids']; ?></textarea>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr><th>&nbsp;</th></tr>
    				<tr>
    					<th></th>
    					<td>
    						<input type="submit" class="button button-primary" value="Save Settings" name="emo_settings[save_settings]">
    					</td>
    				</tr>
    			</table>
    		</form>
			</div>
		<?php 
        }
    }
endif;
new WPEmoAdmin();