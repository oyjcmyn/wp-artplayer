<?php
    $artdplayerjson=get_option('artdplayerjson');
    $artdplayer=json_decode($artdplayerjson,true);
    $art=$artdplayer;
 
 ?>
<div id="artplayer<?php echo $atts['_id']; ?>" class="artplayerbox" style="width:<?php echo $atts['width']; ?>;height:<?php echo $atts['height']; ?>"></div>
<script type="text/javascript">
    var art = new Artplayer({
        id:"'art<?php echo $atts['_id']; ?>",
        container: "#artplayer<?php echo $atts['_id']; ?>",
        url: "<?php echo $atts['url']; ?>",
        theme: '<?php echo $artdplayer['theme']; ?>',
        <?php if(isset($art['poster']) && !empty($art['poster'])){ echo 'poster:"'.$art['poster'].'",'; } ?>
        <?php if(isset($art['volume']) && !empty($art['volume'])){ echo 'volume:'.$art['volume'].','; } ?>
        <?php if(isset($art['muted']) && !empty($art['muted'])){ echo 'muted:true,'; } ?>
        <?php if(isset($art['autoplay']) && !empty($art['autoplay'])){ echo 'autoplay:true,'; } ?>
        <?php if(isset($art['loop']) && !empty($art['loop'])){ echo 'loop:true,'; } ?>
        <?php if(isset($art['autoMini']) && !empty($art['autoMini'])){ echo 'autoMini:true,'; } ?>
        <?php if(isset($art['playbackRate']) && !empty($art['playbackRate'])){ echo 'playbackRate:true,'; } ?>
        <?php if(isset($art['screenshot']) && !empty($art['screenshot'])){ echo 'screenshot:true,'; } ?>
        <?php if(isset($art['pip']) && !empty($art['pip'])){ echo 'pip:true,'; } ?>
        <?php if(isset($art['miniProgressBar']) && !empty($art['miniProgressBar'])){ echo 'miniProgressBar:true,'; } ?>
        <?php if(isset($art['lock']) && !empty($art['lock'])){ echo 'lock:true,'; } ?>
        <?php if(isset($art['fastForward']) && !empty($art['fastForward'])){ echo 'fastForward:true,'; } ?>
        <?php if(isset($art['autoPlayback']) && !empty($art['autoPlayback'])){ echo 'autoPlayback:true,'; } ?>
        <?php if(isset($art['autoOrientation']) && !empty($art['autoOrientation'])){ echo 'autoOrientation:true,'; } ?>
        <?php if(isset($art['airplay']) && !empty($art['airplay'])){ echo 'airplay:true,'; } ?>
        hotkey: true,
        fullscreen: true,
        setting: true,
        whitelist: ['*'],
        lang: <?php if(isset($art['lang']) && $art['lang']!=1){ echo '"'.$art['lang'].'"'; }else{echo 'navigator.language.toLowerCase()';} ?>,
        type: "<?php echo $atts['type']; ?>",
  
        <?php if($atts['type']=='hls'){ ?>
            customType: {
                hls: function (video, url) {
                    if (Hls.isSupported()) {
                        const hls = new Hls();
                        hls.loadSource(url);
                        hls.attachMedia(video);
                    } else {
                        const canPlay = video.canPlayType('application/vnd.apple.mpegurl');
                        if (canPlay === 'probably' || canPlay == 'maybe') {
                            video.src = url;
                        } else {
                            art.notice.show = "<?php _e('Unsupported playback format: m3u8','wpartplayer'); ?>";
                        }
                    }
                },
            },
       <?php } ?>
        
        plugins: [
        <?php if(isset($art['danmuku']) && !empty($art['danmuku'])){ ?>
        artplayerPluginDanmuku({
            danmuku: function () {
                return new Promise((resovle) => {
                    var httpRequest = new XMLHttpRequest();
                    var gourl='<?php echo admin_url('admin-ajax.php?vid='.$atts['_id']); ?>&action=get_artdanmuku';
                        httpRequest.open('GET', gourl, true);
                        httpRequest.send();
                        httpRequest.onreadystatechange = function () {
                            if (httpRequest.readyState == 4 && httpRequest.status == 200) {
                                var json = httpRequest.responseText;
                                // console.log(JSON.parse(json));
                                return resovle(JSON.parse(json).result)
                            }
                        };
                });
            },
            speed: 8, // ??????????????????????????????????????????[1 ~ 10]
            opacity: 1, // ???????????????????????????[0 ~ 1]
            fontSize: 25, // ???????????????????????????????????????
            color: '#FFFFFF', // ??????????????????
            mode: 0, // ???????????????0-?????????1-??????
            margin: [10, '25%'], // ?????????????????????????????????????????????
            antiOverlap: true, // ???????????????
            useWorker: true, // ???????????? web worker
            synchronousPlayback: false, // ???????????????????????????
            filter: (danmu) => danmu.text.length < 50, // ??????????????????????????? true ???????????????
            lockTime: 5, // ?????????????????????????????????????????????[1 ~ 60]
            maxLength: 100, // ?????????????????????????????????????????????[0 ~ 500]
            minWidth: 200, // ?????????????????????????????????[0 ~ 500]?????? 0 ???????????????
            maxWidth: 0, // ?????????????????????????????????[0 ~ Infinity]?????? 0 ?????? 100% ??????
            theme: 'dark', // ??????????????????????????????????????????????????? dark????????????????????? light
            beforeEmit: (danmu) => !!danmu.text.trim(), // ?????????????????????????????????????????? true ???????????????
        }),
    <?php } //??????????????? ?>
    ],
   



    });
    
     <?php if(isset($art['danmuku']) && !empty($art['danmuku'])){ ?>
        art.on('artplayerPluginDanmuku:emit', (danmu) => {
        var result = "";
        danmu.vid=<?php echo $atts['_id']; ?>;
        danmu.action='get_artdanmuku';
        <?php if(is_user_logged_in()){ echo 'danmu.user_id='.get_current_user_id().';';} ?>
        for (let name in danmu) {
            if (typeof danmu[name] != 'function') {
                result += "&" + name + "=" + encodeURI(danmu[name]);
            }
        }
        var httpRequest = new XMLHttpRequest();//?????????????????????????????????
            httpRequest.open('POST', "<?php echo admin_url('admin-ajax.php'); ?>", true); //????????????????????????
            httpRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");//??????????????? ??????post??????????????????????????????????????????????????????????????????
            httpRequest.send(result.substring(1));//???????????? ??????????????????send???
            
    });
    <?php } //??????????????? ?>

</script>
