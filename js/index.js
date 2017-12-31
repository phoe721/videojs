var path = "videos/";
var fileExt = ".mp4";
$(document).ready(function() {
    $.ajax({
        url: path,
        contentType: "application/x-www-form-urlencoded;charset=utf-8",
        success: function(data) {
            $(data).find("a:contains(" + fileExt + ")").each(function() {
                var filename = $(this).text();
                var filepath = encodeURI(path + filename);
                $("#file-list").append("<option value=" + filepath + ">" + filename + "</option>");
            });
        }
    });

    var myPlayer = videojs('my-video');
    $('select').on('change', function() {
		$("#screenshots").empty();
		if (this.value) {
			var video = this.value;
			var subtitle = video.replace('mp4', 'vtt');
			let caption = {
				kind: 'captions',
				srclang: 'en',
				label: 'English',
				src: subtitle
			};
			$.post('script/index.php', {video: video}, function(data) { 
				for (var i = 0; i < data.length; i++) {
					$("#screenshots").append("<img src='" + data[i] + "' width='320px'>");
				}
			}, "json");

			myPlayer.pause();
			myPlayer.addRemoteTextTrack(caption);
			myPlayer.src(video);
			myPlayer.load();
			//myPlayer.play();
		}
    });
});
