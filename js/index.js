var path = "videos/";
var fileExt1 = ".mp4";
var fileExt2 = ".mkv";
$(document).ready(function() {
    $.ajax({
        url: path,
        contentType: "application/x-www-form-urlencoded;charset=utf-8",
        success: function(data) {
            $(data).find("a:contains(" + fileExt1 + ")").each(function() {
                var filename = $(this).text();
                var filepath = encodeURI(path + filename);
                $("#file-list").append("<option value=" + filepath + ">" + filename + "</option>");
            });

            $(data).find("a:contains(" + fileExt2 + ")").each(function() {
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
