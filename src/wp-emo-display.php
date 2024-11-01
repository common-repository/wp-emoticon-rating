<?php
add_action('wp_enqueue_scripts', 'wp_emo_scripts');

function wp_emo_scripts() {
    wp_enqueue_script( 'jquery' );
    wp_enqueue_style( 'emo-style', WP_EMO_URL . '/css/style.css' );
    wp_enqueue_script( 'emo-script', WP_EMO_URL . '/js/script.js', array(), '1.0.0', true );
}

add_filter('the_content', 'emo_display' );

function emo_display( $content ) {
    if( false == is_single() ) {
        return $content;
    }
    $post = $GLOBALS['post'];

    if( $post->post_type == 'post' && true == isEnabledForPost( $post->ID ) ) {
       return $content . render_emo( $post->ID );
    } else if( $post->post_type == 'page' && true == isEnabledForPage( $post->ID ) ) {
       return $content . render_emo( $post->ID );        
    }
    return $content;
}

function isEnabledForPost( $intPostId ) {
    $emo_settings = get_option( 'emo_settings' );

    $arrintEnabledPostIds = explode( ',', $emo_settings['enable_post_ids'] );
    $arrintExcludedPostIds = explode( ',', $emo_settings['excluded_post_ids'] );

    if( false == isset( $emo_settings['is_enable_for_post'] ) ) {
        return false;
    }

    if( true == in_array( $intPostId, $arrintEnabledPostIds ) ) {
        return true;
    }

    if( true == in_array( $intPostId, $arrintExcludedPostIds ) ) {
        return false;
    }

    if( true == isset( $emo_settings['is_enable_for_all_posts'] ) ) {
        return true;
    }

    return false;
}

function isEnabledForPage( $intPageId ) {
    $emo_settings = get_option( 'emo_settings' );

    $arrintEnabledPageIds = explode( ',', $emo_settings['enable_page_ids'] );
    $arrintExcludedPageIds = explode( ',', $emo_settings['excluded_page_ids'] );

    if( false == isset( $emo_settings['is_enable_for_page'] ) ) {
        return false;
    }

    if( true == in_array( $intPageId, $arrintEnabledPageIds ) ) {
        return true;
    }

    if( true == in_array( $intPageId, $arrintExcludedPageIds ) ) {
        return false;
    }

    if( true == isset( $emo_settings['is_enable_for_all_pages'] ) ) {
        return true;
    }

    return false;
}


function render_emo( $post_id ) {
    $emo_data = get_post_meta( $post_id, 'emo_data' ); 
    $emo_data = $emo_data[0];
    return '<div id="emoticon-container" data-post_id="' . $post_id . '" >
<ul>
<li class="emo-icon" id="smile">
	<span>'. ( isset( $emo_data['smile'] ) ? $emo_data['smile'] : '0' ) . '</span>
</li>
<li class="emo-icon" id="naughty-smile">
	<span>'. ( isset( $emo_data['naughty-smile'] ) ? $emo_data['naughty-smile'] : '0' ) . '</span>
</li>
<li class="emo-icon" id="lough">
	<span>'. ( isset( $emo_data['lough'] ) ? $emo_data['lough'] : '0' ) . '</span>
</li>
<li class="emo-icon" id="chive">
	<span>'. ( isset( $emo_data['chive'] ) ? $emo_data['chive'] : '0' ) . '</span>
</li>
<li class="emo-icon" id="shocked">
	<span>'. ( isset( $emo_data['shocked'] ) ? $emo_data['shocked'] : '0' ) . '</span>
</li>
<li class="emo-icon" id="sad">
	<span>'. ( isset( $emo_data['sad'] ) ? $emo_data['sad'] : '0' ) . '</span>
</li>
<li class="emo-icon" id="cry">
	<span>'. ( isset( $emo_data['cry'] ) ? $emo_data['cry'] : '0' ) . '</span>
</li>
<li class="emo-icon" id="angry">
	<span>'. ( isset( $emo_data['angry'] ) ? $emo_data['angry'] : '0' ) . '</span>
</li>
<li class="emo-icon" id="heart">
	<span>'. ( isset( $emo_data['heart'] ) ? $emo_data['heart'] : '0' ) . '</span>
</li>
</ul>
</div>
';
}

add_action( 'wp_ajax_emo_clicked', 'emo_clicked' );
add_action( 'wp_ajax_nopriv_emo_clicked', 'emo_clicked' );

function emo_clicked() {
    // Handle request then generate response using WP_Ajax_Response
    check_ajax_referer('emo_ajax_verify', 'security');
    $emo_data = get_post_meta( $_POST['post_id'], 'emo_data' );
    $emo_data = $emo_data[0];
    if( isset( $emo_data[$_POST['emotion']] ) ) {
        $emo_data[$_POST['emotion']]++;
    } else {
        $emo_data = array(
            'smile'         => 0,
            'naughty-smile' => 0,
            'lough'         => 0,
            'chive'         => 0,
            'shocked'       => 0,
            'sad'           => 0,
            'cry'           => 0,
            'angry'         => 0,
            'heart'         => 0
        );
        $emo_data[$_POST['emotion']]++;
    }
    update_post_meta($_POST['post_id'], 'emo_data', $emo_data );
    echo 'success';
    die();
}

add_action('wp_head','emo_ajaxurl');
function emo_ajaxurl() {
    ?>
<script type="text/javascript">
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
var ajaxnounce = '<?php echo wp_create_nonce( 'emo_ajax_verify' ); ?>';
</script>
<?php
}
