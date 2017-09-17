<?
	require_once("init.php");
	require_once("functions.php");

	// Main program
	if (isset($_POST['video'])) {
		$video = ROOT . urldecode($_POST['video']);
		$screenshots = get_screenshots($video);
		echo json_encode($screenshots);
	}
?>
