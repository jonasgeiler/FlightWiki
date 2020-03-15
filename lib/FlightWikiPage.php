<?php


/**
 * FlightWikiPage
 *
 * Handles pages and files for FlightWiki
 *
 */
class FlightWikiPage {
	/**
	 * File Name
	 *
	 * @var string
	 */
	public $name = null;

	/**
	 * Content
	 *
	 * @var string
	 */
	public $content = null;

	/**
	 * Constructor
	 */
	public function __construct () {
		// Just leaving this empty
	}

	/**
	 * Find the file in pages directory
	 *
	 * @param string $pageName
	 *
	 * @return FlightWikiPage|false Instance if file is found, false if not
	 */
	public static function find ($pageName) {
		$file = self::filepath($pageName);

		if (!file_exists($file))
			return false;

		$page = new self();

		$page->name($pageName);
		$page->content(file_get_contents($file));

		return $page;
	}

	/**
	 * Find all files in pages directory
	 *
	 * @return array files name
	 */
	public static function findAll () {
		$files = [];

		if ($handle = opendir(Flight::get('pages_dir'))) {
			while (($file = readdir($handle)) !== false) {
				if ($file[0] != "." && $file != "..")
					$files[] = $file;
			}

			closedir($handle);
		}

		return $files;
	}

	/**
	 * Returns a string with all spaces converted to underscores (by default), accented
	 * characters converted to non-accented characters, and non word characters removed.
	 *
	 * @param string $name
	 * @param string $replacement
	 *
	 * @return string
	 */
	public static function slug ($name, $replacement = '_') {
		$map = [
			'/à|á|å|â/'   => 'a',
			'/è|é|ê|ẽ|ë/' => 'e',
			'/ì|í|î/'     => 'i',
			'/ò|ó|ô|ø/'   => 'o',
			'/ù|ú|ů|û/'   => 'u',
			'/ç/'         => 'c',
			'/ñ/'         => 'n',
			'/ä|æ/'       => 'ae',
			'/ö/'         => 'oe',
			'/ü/'         => 'ue',
			'/Ä/'         => 'Ae',
			'/Ü/'         => 'Ue',
			'/Ö/'         => 'Oe',
			'/ß/'         => 'ss',
			'/[^-\w\s]/'  => ' ',
			'/\\s+/'      => $replacement,
		];

		return preg_replace(array_keys($map), array_values($map), $name);
	}

	/**
	 * Returns a string with $name and $ext concatenated
	 *
	 * @param string $name
	 * @param string $ext
	 *
	 * @return string
	 */
	public static function filename ($name, $ext = '.md') {
		return self::slug($name) . $ext;
	}

	/**
	 * Returns filepath for $name
	 *
	 * @param string $name
	 *
	 * @return string
	 */
	public static function filepath ($name) {
		return Flight::filePath(Flight::get('pages_dir'), self::filename($name));
	}

	/**
	 * Checks if a page exists or not
	 *
	 * @param string $name Name of the page
	 *
	 * @return bool
	 */
	public static function exists ($name) {
		return file_exists(self::filepath($name));
	}

	/**
	 * Return or set page name
	 *
	 * @param string $name
	 *
	 * @return string
	 */
	public function name ($name = null) {
		if (!is_null($name))
			$this->name = $name;

		return $this->name;
	}

	/**
	 * Return or set page content
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	public function content ($content = null) {
		if (!is_null($content))
			$this->content = $content;

		return $this->content;
	}

	/**
	 * Write the file to the pages directory
	 *
	 * @return int the number of bytes if file written
	 */
	public function save () {
		return file_put_contents(self::filepath($this->name), $this->content);
	}

	/**
	 * Remove the file from the pages directory
	 *
	 * @return bool true if file deleted
	 */
	public function destroy () {
		return unlink(self::filepath($this->name));
	}

}