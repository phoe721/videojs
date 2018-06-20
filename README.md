# videojs
VideoJS
# Requirement
1. ffmpeg
2. Create image directory for screenshots
3. Create log directory for log files
4. Create soft link to videos directory

# Command to get subtitle
ffmpeg -i videoname.mkv videoname.srt

# Command to convert mkv to mp4 
ffmpeg -i videoname.mkv -codec copy videoname.mp4

# Allowed logging for SELinux
chcon -t httpd_sys_rw_content_t /var/www/html/phoe721.com/project/videojs/log -R

# Bug fix
1. Need to put backward slash in front of spaces for file paths
