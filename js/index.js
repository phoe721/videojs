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
		myPlayer.pause();
		myPlayer.src('');
		if (this.value) {
			var video = this.value;
			var subtitle = decodeURI("videos/subs/" + baseName(video)) + ".vtt";
			$.ajax({
				url: subtitle,
				type: 'HEAD',
				error: function() {
					console.log('Subtitle does not exist!');
				},
				success: function() {
					let caption = {
						kind: 'captions',
						srclang: 'en',
						label: 'English',
						src: subtitle
					};
					myPlayer.addRemoteTextTrack(caption, true);
					let tracks = myPlayer.textTracks();
					for (let i = 0; i < tracks.length; i++) {
						let track = tracks[i];
						if (track.kind === 'captions' && track.language === 'en') track.mode = 'showing';
					}
				}
			});

			$.post('script/index.php', {video: video}, function(data) { 
				for (var i = 0; i < data.length; i++) {
					$("#screenshots").append("<img src='" + data[i] + "' width='320px'>");
				}
			}, "json");

			myPlayer.pause();
			myPlayer.src(video);
			myPlayer.load();
			//myPlayer.play();
		}
    });
});

function baseName(str) {
	var base = new String(str).substring(str.lastIndexOf('/') + 1); 
	if (base.lastIndexOf(".") != -1)       
		base = base.substring(0, base.lastIndexOf("."));
	return base;
}
