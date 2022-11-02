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
            speed: 8, // 弹幕持续时间，单位秒，范围在[1 ~ 10]
            opacity: 1, // 弹幕透明度，范围在[0 ~ 1]
            fontSize: 25, // 字体大小，支持数字和百分比
            color: '#FFFFFF', // 默认字体颜色
            mode: 0, // 默认模式，0-滚动，1-静止
            margin: [10, '25%'], // 弹幕上下边距，支持数字和百分比
            antiOverlap: true, // 是否防重叠
            useWorker: true, // 是否使用 web worker
            synchronousPlayback: false, // 是否同步到播放速度
            filter: (danmu) => danmu.text.length < 50, // 弹幕过滤函数，返回 true 则可以发送
            lockTime: 5, // 输入框锁定时间，单位秒，范围在[1 ~ 60]
            maxLength: 100, // 输入框最大可输入的字数，范围在[0 ~ 500]
            minWidth: 200, // 输入框最小宽度，范围在[0 ~ 500]，填 0 则为无限制
            maxWidth: 0, // 输入框最大宽度，范围在[0 ~ Infinity]，填 0 则为 100% 宽度
            theme: 'dark', // 输入框自定义挂载时的主题色，默认为 dark，可以选填亮色 light
            beforeEmit: (danmu) => !!danmu.text.trim(), // 发送弹幕前的自定义校验，返回 true 则可以发送
        }),
    <?php } //弹幕库结束 ?>
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
        var httpRequest = new XMLHttpRequest();//第一步：创建需要的对象
            httpRequest.open('POST', "<?php echo admin_url('admin-ajax.php'); ?>", true); //第二步：打开连接
            httpRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");//设置请求头 注：post方式必须设置请求头（在建立连接后设置请求头）
            httpRequest.send(result.substring(1));//发送请求 将情头体写在send中
            
    });
    <?php } //弹幕库结束 ?>

</script>
