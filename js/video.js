function videoPlay(url) {
    if (flvjs.isSupported()) {
        var videoElement = document.getElementById('videoElement');
        var flvPlayer = flvjs.createPlayer({
            type: 'flv',
            url: url
        });
        flvPlayer._config.referrerPolicy = "no-referrer";
        flvPlayer.attachMediaElement(videoElement);
        flvPlayer.load();
    }
}

function livePlay(url) {
    if(Hls.isSupported()) {
        var hls = new Hls();
        var video = document.getElementById('videoElement');
        hls.loadSource(url);
        hls.attachMedia(video);
        video.play();
    }
}

function livePage() {
    if(Hls.isSupported()) {
        var hls = new Hls();
        var video = document.getElementById('videoElement');
        hls.loadSource("https://hls.cstu.gq/aitc/index.m3u8");
        hls.attachMedia(video);
        hls.on(Hls.Events.ERROR, function (event, data) {
            if (data.fatal && data.type == Hls.ErrorTypes.NETWORK_ERROR) {
                hls.destroy();
                $("#videoContainer").hide();
                $("#videoError").show();
                return;
            }
        });
        video.play();
    }
}