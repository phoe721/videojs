<?
	// Generate time slots
	function generate_time_slots() {
		$time_slots = array();
		$prev = $cur = 0;
		$max = MAX_TIME;
		for ($i = 0; $i < NUM_OF_SCREENSHOTS; $i++) {
			if ($prev != 0) {
				$cur = rand($prev, $max);
				$time_slots[$i] = gmdate("H:i:s", $cur);
			} else {
				$cur = rand(0, $max);
				$time_slots[$i] = gmdate("H:i:s", $cur);
			}

			$prev = $cur;
		}

		return $time_slots;
	}

	// Get video screenshots
	function get_screenshots($video) {
		$screenshots = array();
		if (remove_images()) {
			$time_slots = generate_time_slots();
			for ($i = 0; $i < count($time_slots); $i++) {
				$rid = uniqid();
				$command = "/usr/bin/ffmpeg -ss " . $time_slots[$i] . " -i $video -y -vframes 1 -q:v 2 " . IMG_DIR . "$rid.jpg";
				$output = exec($command);
				$screenshots[$i] = "img/$rid.jpg";
				if (file_exists(IMG_DIR . "$rid.jpg")) {
					logger("Created screenshot " . $screenshots[$i]);
				} else {
					logger("Failed to create screenshot " . $screenshots[$i]);
				}
			}
		}

		return $screenshots;
	}

	// Remove image files
	function remove_images() {
		$files = glob(IMG_DIR . '*');
		$count = count($files);
		logger("There are $count files in " . IMG_DIR);
		foreach ($files as $file) {
			logger("Found $file");
			if (is_file($file)) {
				if (unlink($file)) {
					logger("Removed $file");
				} else {
					logger("Failed to remove $file");
				}
			}
		}


		$files = glob(IMG_DIR . '*');
		$count = count($files);
		logger("There are $count files in " . IMG_DIR);
		if ($count == 0) {
			return true;
		} else {
			return false;
		}
	}

	// Log to logfile
	function logger($msg) {
		// Write to log
		$file = fopen(LOG_FILE, 'a+');
		if ($file) {
			$timestring = date('Y-m-d h:i:s', strtotime('now'));
			$msg = $timestring . ' - ' . $msg . PHP_EOL;
			fwrite($file, $msg);
		} else {
			fwrite($file, "[ERROR] Unable to open file!");
		}
		fclose($file);
	}
?>
