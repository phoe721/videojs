<?
	require_once("functions.php");

	// Main program
	if (isset($_POST['video'])) {
		$video = ROOT . urldecode($_POST['video']);
		$video = str_replace(" ", "\ ", $video);
		$screenshots = get_screenshots($video);
		echo json_encode($screenshots);
	}
?>
