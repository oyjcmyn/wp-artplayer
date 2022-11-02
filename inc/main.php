<?php
/**
 * 
 * @authors Your Name (you@example.org)
 * @date    2022-09-22 18:17:51
 * @version $Id$
 */

class ART_MAIN_MI{
    static public function install(){
    	self::create_db();
    	
    }
	static public function create_db(){
		global $wpdb;
        include_once ABSPATH.'/wp-admin/includes/upgrade.php';

        $wpdb->hide_errors();
        $collate = '';
        $prefix = $wpdb->prefix;
        $table = $prefix.'art_danmuku';

        if ( $wpdb->has_cap( 'art_danmuku' ) ) {
            if ( ! empty( $wpdb->charset ) ) {
                $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
            }
            if ( ! empty( $wpdb->collate ) ) {
                $collate .= " COLLATE $wpdb->collate";
            }
        }
        
        $art_danmuku = "
              CREATE TABLE `{$table}` (
              `id` bigint(20) NOT NULL AUTO_INCREMENT,
			  `post_id` bigint(20) DEFAULT NULL,
			  `user_id` int(10) DEFAULT NULL,
			  `text` varchar(512) DEFAULT '',
			  `time` smallint(6) DEFAULT '0',
			  `color` varchar(32) DEFAULT '#ffffff',
			  `border` tinyint(1) DEFAULT '0',
			  `mode` tinyint(1) DEFAULT '0',
			  `date_time` datetime NOT NULL DEFAULT '1980-01-01 00:00:00',
			  `ip` varchar(20) DEFAULT NULL,
			   PRIMARY KEY (id),INDEX post_id_index(post_id),INDEX user_id_index(user_id)) $collate;
        ";
        // var_dump($art_danmuku);die;
        dbDelta($art_danmuku);


	}

	public static function save_options(){

		if (wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mi_artplayer_save_field'] ) ), 'mi_artplayer_save_action' )) {
				$data=$_POST;
				unset($data['mi_artplayer_save_field'],$data['_wp_http_referer']);
				update_option('artdplayerjson',json_encode($data,JSON_UNESCAPED_UNICODE));
		}
	}


	public static function get_danmu(){

	}

	public static function remove_chars($str)
	{	
		$str = strip_tags($str);
	    $str = str_replace('\\','',$str);
	    $str = str_replace('/','',$str);
	    $str = str_replace('*','',$str);
	    $str = str_replace('<','',$str);
	    $str = str_replace('>','',$str);
	    $str = str_replace('"','',$str);
	    $str = str_replace("'",'',$str);
	    $str = str_replace('`', '',$str);
	    $str = str_replace('%', '', $str);
	    $str = str_replace('^', '', $str);
	    $str = str_replace('&', '', $str);
	    return trim($str);
	}

	public static function get_client_ip()
	{
	    static $final;
	    if (!is_null($final)) {
	        return $final;
	    }
	    $ips = array();
	    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
	        $ips[] = $_SERVER['HTTP_CF_CONNECTING_IP'];
	    }
	    if (!empty($_SERVER['HTTP_ALI_CDN_REAL_IP'])) {
	        $ips[] = $_SERVER['HTTP_ALI_CDN_REAL_IP'];
	    }
	    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
	        $ips[] = $_SERVER['HTTP_CLIENT_IP'];
	    }
	    if (!empty($_SERVER['HTTP_PROXY_USER'])) {
	        $ips[] = $_SERVER['HTTP_PROXY_USER'];
	    }
	    $real_ip = getenv('HTTP_X_REAL_IP');
	    if (!empty($real_ip)) {
	        $ips[] = $real_ip;
	    }
	    if (!empty($_SERVER['REMOTE_ADDR'])) {
	        $ips[] = $_SERVER['REMOTE_ADDR'];
	    }
	    // 选第一个最合法的，或最后一个正常的IP
	    foreach ($ips as $ip) {
	        $long = ip2long($ip);
	        $long && $final = $ip;
	        // 排除不正确的IP
	        if ($long > 0 && $long < 0xFFFFFFFF) {
	            $final = long2ip($long);
	            break;
	        }
	    }
	    empty($final) && $final = '0.0.0.0';
	    return $final;
	}

}



class Artplayer_Admin_Media{

    static $add_script;
	public function __construct() {
		add_action( 'media_buttons', array( $this, 'media_buttons' ), 20 );
		add_shortcode( 'artplayer', array( $this, 'artplayer_shortcode' ));
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'add_script' ) );
		add_action('wp_ajax_get_artdanmuku', array( $this, 'get_artdanmuku' ));
		add_action('wp_ajax_nopriv_get_artdanmuku', array( $this, 'get_artdanmuku' ));
	}

	public function media_buttons( $editor_id = 'content' ) {
		global $post;
		?>
		<a href="#TB_inline?width=auto&height=auto&inlineId=add_artplaybox" class="button thickbox" title="<?php _e('Add Artplayer player','wpartplayer'); ?>"><?php _e('Add Artplayer player','wpartplayer'); ?></a>
		<div id="add_artplaybox" style="display:none;">
			<form id="insert-artplayer-shortcode" method="post">
				<table class="form-table">
					<tbody>
						<tr>
						<th scope="row"><label for="url"><?php _e('Video URL','wpartplayer'); ?></label></th>
						<td><input name="url" type="text" id="artmi_url" value="" class="regular-text" placeholder="<?php _e('Video Play URL','wpartplayer'); ?>"><a class="button addsurl"><?php _e('Media Library','wpartplayer'); ?></a></td>
						</tr>
						<tr>
						<th scope="row"><label for="url"><?php _e('Video Type','wpartplayer'); ?></label></th>
						<td>
							<select name="mitype" id="artmilang">
								<option value="hls" selected="selected">hls</option>
								<option value="mp4">MP4</option>
								<?php if(isset($artdplayer['enable_flv']) && $artdplayer['enable_flv']==1){
									echo '<option value="flv">flv</option>';
								} ?>
								<!-- <option value="ts">ts</option> -->
								<!-- <option value="auto">自动识别</option> -->
							</select>
						</td>
						</tr>
						<tr>
							<?php 
							$artdplayerjson=get_option('artdplayerjson');
					        $artdplayer=json_decode($artdplayerjson,true);
					        $artdplayer['height']=isset($artdplayer['height'])?$artdplayer['height']:'500px';
					        $artdplayer['width']=isset($artdplayer['width'])?$artdplayer['width']:'100%';
					        ?>
						<th scope="row"><label for="url"><?php _e('Player height','wpartplayer'); ?></label></th>
						<td><input name="height" type="text" id="miheight" value="<?php echo $artdplayer['height'];?>" placeholder="<?php _e('Player height','wpartplayer'); ?>"></td>
						</tr>
						<tr>
						<th scope="row"><label for="url"><?php _e('Player Width','wpartplayer'); ?></label></th>
						<td><input name="width" type="text" id="miwidth" value="<?php echo $artdplayer['width'];?>" placeholder="<?php _e('Player Width','wpartplayer'); ?>"></td>
						</tr>
					</tbody>
				</table>
				<a class="button button-primary button-large incsbtn"><?php _e('Insert Player Shortcode','wpartplayer'); ?></a>
			</form>
			<script>
				jQuery( function ($) {
					var win = window.dialogArguments || opener || parent || top;
					jQuery( '.incsbtn' ).click( function () {
							var url = jQuery( '#artmi_url' ).val();
							var shortcode = '[artplayer url="' + url + '"';
							var type = $("#artmilang").val();
							shortcode = shortcode +' type="'+type+'"';

							var height = jQuery( '#miheight' ).val();
							var width = jQuery( '#miwidth' ).val();
							shortcode = shortcode +' height="'+height+'"';
							shortcode = shortcode +' width="'+width+'"';

							shortcode = shortcode + ']';
							win.send_to_editor(shortcode);
						} );

					var ashu_upload_frame;   
				    var value_id;
				    var thisa;
				    jQuery('body.wp-admin').on('click','.addsurl',function(event){ 
				    	thisa=jQuery( this ); 
				        event.preventDefault();   
				        if( ashu_upload_frame ){   
				            ashu_upload_frame.open();   
				            return;   
				        }   
				        ashu_upload_frame = wp.media({   
				            title: '<?php _e('Add video','wpartplayer'); ?>',   
				            button: {   
				                text: '<?php _e('Add video','wpartplayer'); ?>',  
				            },   
				        }); 

				        ashu_upload_frame.on('select',function(){  
               			attachment = ashu_upload_frame.state().get('selection').first().toJSON();   //选择单个文件 
			            // attachment = ashu_upload_frame.state().get('selection').toJSON();   
			            
			            jQuery('#artmi_url').val(attachment.url).trigger('change'); 
			            
			        
			        });   
			           
			        ashu_upload_frame.open();  



				     });


				});
			 
			</script>
		</div>
	<?php 
	}


    public function artplayer_shortcode( $atts, $content = null ) {
		$atts = shortcode_atts( array(
			'_id'        => get_the_ID(),
			'url'     => '',
			'type' => '',
			'width' =>'',
			'height' =>'',
		), $atts );

		
		ob_start();
		include WP_ARTDPLAYER_PATH.'/templates/artplayer.php';
		return ob_get_clean();
	}
	

	public static function add_script() {
        if (!self::$add_script) {
        	$artdplayerjson=get_option('artdplayerjson');
        	if(empty($artdplayerjson)){
        		return;
        	}
        	$artdplayer=json_decode($artdplayerjson,true);
        	if((isset($artdplayer['enable_hls']) && $artdplayer['enable_hls']==1) || (isset($artdplayer['isLive']) && $artdplayer['isLive']==1)){ 
        		wp_enqueue_script( 'arthls', WP_ARTDPLAYER_URL.'/assets/js/hls.min.js', false, false, false);
        	}
            wp_enqueue_script( 'artplayer', WP_ARTDPLAYER_URL.'/assets/js/artplayer.js', false, false, false);
            // var_dump();
            if(isset($artdplayer['danmuku']) && $artdplayer['danmuku']==1){ 
        		wp_enqueue_script( 'artplayerdanmuku', WP_ARTDPLAYER_URL.'/assets/js/artplayer-plugin-danmuku.js', false, false, false);
        	}
            if(isset($artdplayer['enable_flv']) && $artdplayer['enable_flv']==1){ 
        		wp_enqueue_script( 'artflv', WP_ARTDPLAYER_URL.'/assets/js/flv.min.js', false, false, false);
        	}
            self::$add_script = true;
        } 
    }

    public function get_artdanmuku(){
    	global $wpdb;
        $data = array();
        $table=$wpdb->prefix.'art_danmuku';
        $data=array();
    	if($_SERVER['REQUEST_METHOD']==='GET'){
    		$postid=(int)$_GET['vid'];
    		$sql="SELECT `text`,`time`,`color`,`border`,`mode` FROM `{$table}` WHERE `post_id` ={$postid}";
    		$data = $wpdb->get_results( $sql, 'ARRAY_A' );
    		foreach ($data as $key => $value) {
    			$data[$key]['time']=(int)$value['time'];
	            $data[$key]['mode']=(int)$value['mode'];
	            $data[$key]['border']=false;
	        }
    	}elseif($_SERVER['REQUEST_METHOD']==='POST'){
    		if(empty($_POST['vid']) || empty($_POST['text'])){
    			echo json_encode(array('status'=>2001,'result'=>0));
    			exit;
    		}
    		if($_POST['border']==true){
	            $border=1;
	        }else{
	            $border=0;
	        }
    		$indata = array(
	            'post_id'=>(int)$_POST['vid'],
	            'user_id'=>(int)$_POST['user_id'],
	            'text'=>ART_MAIN_MI::remove_chars($_POST['text']),
	            'time'=>(int)$_POST['time'],
	            'color'=>ART_MAIN_MI::remove_chars($_POST['color']),
	            'border'=>$border,
	            'mode'=>(int)$_POST['mode'],
	            'ip' =>ART_MAIN_MI::get_client_ip(),
	            'date_time'=>current_time('Y-m-d H:i:s')
	        );
	        var_dump($indata);
	        $data = $wpdb->insert($table,$indata);
    	}
    	
    	header('Content-Type:application/json; charset=utf-8');
        echo json_encode(array('status'=>200,'result'=>$data),JSON_UNESCAPED_UNICODE);
        exit;
    }

}

new Artplayer_Admin_Media();
