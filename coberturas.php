<?php
	require_once 'lib/Folders.php';
	$Folder  = new Folders(getcwd(), $argv);
	$Folder->coverages();
?>