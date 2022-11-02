<?php 

//将插件在左侧菜单中显示
function artplayer_menu(){
 	add_menu_page('artplayer','ArtPlayer', 'manage_options', 'artplayerset' ,'artplayerset', 'dashicons-media-text', 80);
    add_submenu_page('artplayerset',__('Danmuku','wpartplayer'),__('Danmuku','wpartplayer'), 'manage_options', 'art_danmuku' ,'art_danmuku_set');
	
}
add_action("admin_menu","artplayer_menu");

function artplayerset(){
	include WP_ARTDPLAYER_INC_PATH . 'admin.php';
}

function art_danmuku_set(){
	include WP_ARTDPLAYER_INC_PATH . 'class/DanmulistTable.php';
    $DanmuListTable = new DanmuListTable();
    $DanmuListTable->prepare_items();
    ?>
    <div class="wrap">
        <div id="icon-edit" class="icon32 icon32-posts-dlm_download"><br/></div>

         <h2><?php _e('Danmuku List','wpartplayer'); ?><a onclick="return confirm(<?php _e('Sure you want to delete all the danmuku?','wpartplayer'); ?>);" href="<?php echo sprintf('?page=%s&action=%s&_wpnonce=%s',$_REQUEST['page'],'delall',wp_create_nonce('delall')); ?>" class="add-new-h2 del_mi"><?php _e('Delete all danmuku','wpartplayer'); ?></a></h2><br/>
        <form id="sousuo" method="get" action="<?php echo admin_url('admin.php?page=art_danmuku');?>">
            <p class="search-box">
                <label class="screen-reader-text" for="post-search-input"><?php _e('Danmuku Content:','wpartplayer'); ?></label>
                <input type="search" id="post-search-input" name="text" value="" placeholder="<?php _e('Input Danmuku Content','wpartplayer'); ?>">
                <input type="hidden" id="id" name="page" value="art_danmuku">
                <?php if(isset($_GET['paged']) ){
                    echo '<input type="hidden" name="paged" value="1">';
                } ?>
                <input type="submit" id="search-submit" class="button" value="<?php _e('Search Danmuku','wpartplayer'); ?>">
            </p>
        </form>
        <form id="resorder" method="post">
            <?php $DanmuListTable->display() ?>
        </form>
    </div>
 <?php 
}