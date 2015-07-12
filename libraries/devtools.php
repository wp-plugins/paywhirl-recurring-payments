<?php defined('ABSPATH') or die();


// Devtools Library


class devtools {

	static function clear_file($file) {

		// Open file
		$file = fopen($file, 'w');

		// Clear file
		fwrite($file, '');

		// Close file
		fclose($file);
	}

	static function write_file($file, $text) {

		// If text is an array or an object
		if(is_array($text) || is_object($text)) {

			// Covert text to string
			$text = json_encode($text, JSON_PRETTY_PRINT);
		}

		// Open file
		$file = fopen($file, 'a');

		// Write text to file
		fwrite($file, $text);

		// Close file
		fclose($file);
	}

}