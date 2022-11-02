<div class='wrap' id='dplayer-options'>
	<h2>Artplaye <?php _e('Player Settings','wpartplayer'); ?></h2>
	<form id='dplayer-for-wordpress' name='dplayer-for-wordpress' action='' method='POST'>
	    <?php wp_nonce_field( 'mi_artplayer_save_action', 'mi_artplayer_save_field', true ); ?>
	<table class='form-table'>
		<?php  
        if ( isset( $_POST['mi_artplayer_save_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mi_artplayer_save_field'] ) ), 'mi_artplayer_save_action' )) {
            echo "<div class='updated settings-error' id='etting-error-settings_updated'><p><strong>".__('Save successfully','wpartplayer')."</strong></p></div>\n";
            ART_MAIN_MI::save_options();
        }
        $artdplayerjson=get_option('artdplayerjson');
		$artdplayer=json_decode($artdplayerjson,true);
		?>

		<tr>
		<th scope="row"><?php _e('Enable','wpartplayer'); ?> hls.js</th>
		<td> <fieldset><legend class="screen-reader-text"></legend>
			<label for="enable_hls">
				<input name="enable_hls" type="checkbox" id="enable_hls" value="1" <?php if(isset( $artdplayer['enable_hls']) && $artdplayer['enable_hls']==1)echo 'checked'; ?>>
			<?php _e('Live video (HTTP Live Streaming, M3U8 format) support','wpartplayer'); ?></label>
		</fieldset></td>
		</tr>

		<tr>
		<th scope="row"><?php _e('Enable','wpartplayer'); ?> flv.js</th>
		<td> <fieldset><legend class="screen-reader-text"></legend>
			<label for="enable_flv">
				<input name="enable_flv" type="checkbox" id="enable_flv" value="1" <?php if(isset( $artdplayer['enable_flv']) && $artdplayer['enable_flv']==1)echo 'checked'; ?>>
			 <?php _e('Support FLV format','wpartplayer'); ?></label>
		</fieldset></td>
		</tr>

		<tr>
		<th scope="row"><?php _e('Live mode','wpartplayer'); ?></th>
		<td> <fieldset><legend class="screen-reader-text"></legend>
			<label for="isLive">
				<input name="isLive" type="checkbox" id="isLive" value="1" <?php if(isset( $artdplayer['isLive']) && $artdplayer['isLive']==1)echo 'checked'; ?>>
			 <?php _e('Using live mode will hide the progress bar and play time','wpartplayer'); ?></label>
		</fieldset></td>
		</tr>

		<tr>
		<th scope="row"><?php _e('Danmuku','wpartplayer'); ?></th>
		<td> <fieldset><legend class="screen-reader-text"></legend>
			<label for="danmuku">
				<input name="danmuku" type="checkbox" id="danmuku" value="1" <?php if(isset( $artdplayer['danmuku']) && $artdplayer['danmuku']==1)echo 'checked'; ?>>
			 <?php _e('Enable Danmuku, Danmuku data will be stored in the database','wpartplayer'); ?></label>
		</fieldset></td>
		</tr>

		<tr>
		<th scope="row"><label for="poster"><?php _e('Cover of the video','wpartplayer'); ?></label></th>
		<td><input name="poster" type="text" id="poster" value="<?php if(isset( $artdplayer['poster']))echo $artdplayer['poster']; ?>" class="regular-text"><p class="description"><?php _e('The cover of the video, which will only appear when the player is initialized and not playing','wpartplayer'); ?></p></td>
		
		</tr>

		<tr>
		<th scope="row"><label for="theme"><?php _e('Player theme colors','wpartplayer'); ?></label></th>
		<td><input name="theme" type="text" id="theme" value="<?php if(isset( $artdplayer['theme'])){echo $artdplayer['theme'];}else{echo '#ffad00';} ?>" class="regular-text"><p class="description"><?php _e('Player theme color, currently only works on the progress bar. e.g #ffad00','wpartplayer'); ?></p></td>
		
		</tr>

		<tr>
		<th scope="row"><label for="volume"><?php _e('Default volume','wpartplayer'); ?></label></th>
		<td><input name="volume" type="text" id="volume" value="<?php if(isset( $artdplayer['volume'])){echo $artdplayer['volume'];}else{echo '0.7';} ?>" class="regular-text"><p class="description"><?php _e('Default volume of the player: 0-1 range','wpartplayer'); ?></p></td>
		</tr>

		<tr>
		<th scope="row"><?php _e('Enable on the mute','wpartplayer'); ?></th>
		<td> <fieldset><legend class="screen-reader-text"></legend>
			<label for="muted">
				<input name="muted" type="checkbox" id="muted" value="1" <?php if(isset( $artdplayer['muted']) && $artdplayer['muted']==1)echo 'checked'; ?>>
			<?php _e('Enable on the mute','wpartplayer'); ?></label>
		</fieldset></td>
		</tr>

		<tr>
		<th scope="row"><?php _e('Autoplay','wpartplayer'); ?></th>
		<td> <fieldset><legend class="screen-reader-text"></legend>
			<label for="autoplay">
				<input name="autoplay" type="checkbox" id="autoplay" value="1" <?php if(isset( $artdplayer['autoplay']) && $artdplayer['autoplay']==1)echo 'checked'; ?>>
			 <?php _e('Video autoplay when enabled','wpartplayer'); ?></label>
		</fieldset></td>
		</tr>

		<tr>
		<th scope="row"><?php _e('Loop Play','wpartplayer'); ?></th>
		<td> <fieldset><legend class="screen-reader-text"></legend>
			<label for="loop">
				<input name="loop" type="checkbox" id="loop" value="1" <?php if(isset( $artdplayer['loop']) && $artdplayer['loop']==1)echo 'checked'; ?>>
			<?php _e('Loop Play','wpartplayer'); ?></label>
		</fieldset></td>
		</tr>


		<tr>
		<th scope="row"><?php _e('Mini Play mode','wpartplayer'); ?></th>
		<td> <fieldset><legend class="screen-reader-text"></legend>
			<label for="autoMini">
				<input name="autoMini" type="checkbox" id="autoMini" value="1" <?php if(isset( $artdplayer['autoMini']) && $artdplayer['autoMini']==1)echo 'checked'; ?>>
			 <?php _e('When the player scrolls outside the browser viewport, automatically enter the mini play mode','wpartplayer'); ?></label>
		</fieldset></td>
		</tr>

		<tr>
		<th scope="row"><?php _e('Display playback speed','wpartplayer'); ?></th>
		<td> <fieldset><legend class="screen-reader-text"></legend>
			<label for="playbackRate">
				<input name="playbackRate" type="checkbox" id="playbackRate" value="1" <?php if(isset( $artdplayer['playbackRate']) && $artdplayer['playbackRate']==1)echo 'checked'; ?>>
			<?php _e('Whether the video playback speed function is displayed, it will appear in the setting panel and contextmenu','wpartplayer'); ?></label>
		</fieldset></td>
		</tr>

		<tr>
		<th scope="row"><?php _e('Screenshot','wpartplayer'); ?></th>
		<td> <fieldset><legend class="screen-reader-text"></legend>
			<label for="screenshot">
				<input name="screenshot" type="checkbox" id="screenshot" value="1" <?php if(isset( $artdplayer['screenshot']) && $artdplayer['screenshot']==1)echo 'checked'; ?>>
			<?php _e('Whether to display video screenshots in the bottom control bar','wpartplayer'); ?></label>
		</fieldset></td>
		</tr>

		<tr>
		<th scope="row"><?php _e('PIP','wpartplayer'); ?></th>
		<td> <fieldset><legend class="screen-reader-text"></legend>
			<label for="pip">
				<input name="pip" type="checkbox" id="pip" value="1" <?php if(isset( $artdplayer['pip']) && $artdplayer['pip']==1)echo 'checked'; ?>>
			<?php _e('Whether to display the PIP switch button in the bottom control bar','wpartplayer'); ?></label>
		</fieldset></td>
		</tr>

		<tr>
		<th scope="row"><?php _e('Mini Progress Bar','wpartplayer'); ?></th>
		<td> <fieldset><legend class="screen-reader-text"></legend>
			<label for="miniProgressBar">
				<input name="miniProgressBar" type="checkbox" id="miniProgressBar" value="1" <?php if(isset( $artdplayer['miniProgressBar']) && $artdplayer['miniProgressBar']==1)echo 'checked'; ?>>
			<?php _e('Mini progress bar, only appears when the player loses focus and is playing','wpartplayer'); ?></label>
		</fieldset></td>
		</tr>

		<tr>
		<th scope="row"><?php _e('Lock button','wpartplayer'); ?></th>
		<td> <fieldset><legend class="screen-reader-text"></legend>
			<label for="lock">
				<input name="lock" type="checkbox" id="lock" value="1" <?php if(isset( $artdplayer['lock']) && $artdplayer['lock']==1)echo 'checked'; ?>>
			<?php _e('Whether to display a lock button on the mobile side to hide the bottom control bar','wpartplayer'); ?></label>
		</fieldset></td>
		</tr>

		<tr>
		<th scope="row"><?php _e('Long press video fast forward','wpartplayer'); ?></th>
		<td> <fieldset><legend class="screen-reader-text"></legend>
			<label for="fastForward">
				<input name="fastForward" type="checkbox" id="fastForward" value="1" <?php if(isset( $artdplayer['fastForward']) && $artdplayer['fastForward']==1)echo 'checked'; ?>>
			<?php _e('Whether to add fast forward function on the mobile, when long press the video','wpartplayer'); ?></label>
		</fieldset></td>
		</tr>

		<tr>
		<th scope="row"><?php _e('Automatic playback','wpartplayer'); ?></th>
		<td> <fieldset><legend class="screen-reader-text"></legend>
			<label for="autoPlayback">
				<input name="autoPlayback" type="checkbox" id="autoPlayback" value="1" <?php if(isset( $artdplayer['autoPlayback']) && $artdplayer['autoPlayback']==1)echo 'checked'; ?>>
		<?php _e('Whether to use auto playback','wpartplayer'); ?></label>
		</fieldset></td>
		</tr>

		<tr>
		<th scope="row"><?php _e('Mobile Spin Player','wpartplayer'); ?></th>
		<td> <fieldset><legend class="screen-reader-text"></legend>
			<label for="autoOrientation">
				<input name="autoOrientation" type="checkbox" id="autoOrientation" value="1" <?php if(isset( $artdplayer['autoOrientation']) && $artdplayer['autoOrientation']==1)echo 'checked'; ?>>
			<?php _e('Whether to rotate the player according to the video size and viewport size, when the player web-fullscreen in mobile','wpartplayer'); ?></label>
		</fieldset></td>
		</tr>

		<tr>
		<th scope="row">airplay</th>
		<td> <fieldset><legend class="screen-reader-text"></legend>
			<label for="airplay">
				<input name="airplay" type="checkbox" id="airplay" value="1" <?php if(isset( $artdplayer['airplay']) && $artdplayer['airplay']==1)echo 'checked'; ?>>
			<?php _e('Whether to display the AirPlay button, only available in Safari currently','wpartplayer'); ?></label>
		</fieldset></td>
		</tr>

		<tr>
		<th scope="row"><?php _e('Player Language','wpartplayer'); ?></th>
		<td> <fieldset><legend class="screen-reader-text"></legend>
			<select name="lang" id="lang">
				<option value="1" selected="selected"><?php _e('Automatic language selection','wpartplayer'); ?></option>
				<option value="zh-cn"><?php _e('Simplified Chinese','wpartplayer'); ?></option>
				<option value="zh-tw"><?php _e('Traditional Chinese(Taiwan Region)','wpartplayer'); ?></option>
				<option value="en"><?php _e('English','wpartplayer'); ?></option>
				<option value="cs"><?php _e('Czech','wpartplayer'); ?></option>
				<option value="pl"><?php _e('Polish','wpartplayer'); ?></option>
				<option value="es"><?php _e('Spanish','wpartplayer'); ?></option>
			</select>
		</fieldset></td>
		</tr>

		
		<caption class='screen-reader-text'>Artplayer<?php _e('setting','wpartplayer'); ?></caption>
 	</table>
	<p class="submit"><input type="submit" class="button button-primary" value="<?php _e('Save setting','wpartplayer'); ?>"/></p>
	</form>
</div>

	
