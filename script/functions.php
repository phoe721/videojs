<?
	require_once("init.php");

	// Get Video Time
	function get_video_duration($video) {
		$command = "/usr/bin/ffmpeg -i $video 2>&1 | grep Duration | cut -d ' ' -f 4 | sed s/,//";
		logger("[DEBUG] Video duration command: $command");
		$time_str = exec($command);
		$time_str = preg_replace('/\..*/', '', $time_str);
		$time_token = explode(":", $time_str);
		$time = ($time_token[0] * 3600) + ($time_token[1] * 60) + $time_token[2];

		logger("[DEBUG] Video duration in seconds: $time");
		return $time;
	}

	// Generate time slots
	function generate_time_slots($duration) {
		$time_slots = array();
		$prev = $cur = 0;
		$duration = $duration - 600; // Remove movie endings
		for ($i = 0; $i < NUM_OF_SCREENSHOTS; $i++) {
			if ($prev != 0) {
				$cur = rand($prev, $duration);
				$time_slots[$i] = gmdate("H:i:s", $cur);
				logger("[DEBUG] The $i slot has time " . $time_slots[$i]);
			} else {
				$cur = rand(0, $duration);
				$time_slots[$i] = gmdate("H:i:s", $cur);
				logger("[DEBUG] The $i slot has time " . $time_slots[$i]);
			}

			$prev = $cur;
		}

		logger("[NOTICE] Random time slots are generated");
		return $time_slots;
	}

	// Get video screenshots
	function get_screenshots($video) {
		$screenshots = array();
		if (remove_images()) {
			$duration = get_video_duration($video);
			$time_slots = generate_time_slots($duration);
			for ($i = 0; $i < count($time_slots); $i++) {
				$rid = uniqid();
				$command = "/usr/bin/ffmpeg -ss " . $time_slots[$i] . " -i $video -y -vframes 1 -q:v 2 " . IMG_DIR . "$rid.jpg";
				logger("[DEBUG] Screenshot command: $command");
				$output = exec($command);
				$screenshots[$i] = "img/$rid.jpg";
				if (file_exists(IMG_DIR . "$rid.jpg")) {
					logger("[DEBUG] Created screenshot " . $screenshots[$i]);
				} else {
					logger("[ERROR] Failed to create screenshot " . $screenshots[$i]);
				}
			}
		}

		logger("[NOTICE] Movie screenshots are generated");
		return $screenshots;
	}

	// Remove image files
	function remove_images() {
		$files = glob(IMG_DIR . '*');
		$count = count($files);
		logger("[DEBUG] There are $count files in " . IMG_DIR);
		foreach ($files as $file) {
			logger("[DEBUG] Found $file");
			if (is_file($file)) {
				if (unlink($file)) {
					logger("[DEBUG] Removed $file");
				} else {
					logger("[ERROR] Failed to remove $file");
				}
			}
		}


		$files = glob(IMG_DIR . '*');
		$count = count($files);
		logger("[DEBUG] There are $count files in " . IMG_DIR);
		if ($count == 0) {
			logger("[DEBUG] All screenshots are removed");
			return true;
		} else {
			logger("[ERROR] Failed to remove screenshots");
			return false;
		}
	}

	// Logger
	function logger($msg) {
		if (LOG_LEVEL == 0) {
		} elseif (LOG_LEVEL == 1) {
			$file = fopen(LOG_FILE, 'a+');
			if ($file) {
				$timestring = date('Y-m-d h:i:s', strtotime('now'));
				$msg = $timestring . " - " . $msg . PHP_EOL;
				fwrite($file, $msg);
			} else {
				$timestring = date('Y-m-d h:i:s', strtotime('now'));
				$msg = $timestring . " - [ERROR] Unable to open $file" . PHP_EOL;
				fwrite($file, $msg);
			}
			fclose($file);
		}
	}
?>
