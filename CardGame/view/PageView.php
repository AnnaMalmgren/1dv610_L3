<?php

namespace View;

class PageView {

	private $title;
	private $charset = 'utf-8';

	public function __construct(string $title) {
		$this->title = $title;
	}


	public function setCharset(string $newCharSet) {
		$this->charset = $newCharSet;
	}


	public function echoHTML($gameView) {
		echo ' 
		<!DOCTYPE html>
		<html>
			<head>
				<meta charset=' . $this->charset . '>
				<title>$this->title</title>
			</head>
			<body>
				' . $gameView->render() .'
			</body>
		</html>';

	}
}