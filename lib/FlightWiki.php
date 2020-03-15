<?php

require_once dirname(__FILE__) . '/Parsedown.php';


/**
 * FlightWiki
 *
 * Handles some special operations, like checking for spam, rendering Markdown as HTML and rendering a pages cloud
 *
 */
class FlightWiki {

	/**
	 * Check a string for spam keywords
	 *
	 * @param $str
	 *
	 * @return bool
	 */
	public static function isSpam ($str) {
		// Check for these words:
		$spamWords = [];
		$spamWords[] = "porn"; // Just
		$spamWords[] = "nude"; // some
		$spamWords[] = "fuck"; // examples
		// -> Add more if you want!

		foreach ($spamWords as $spam) {
			if (strpos($str, $spam) !== false)
				return true;
		}

		return false;
	}

	/**
	 * Returns html string for a given markdown string.
	 * Also converts double brackets wiki links into html links.
	 *
	 * @param string $str markdown content with [[wiki links]]
	 *
	 * @return string
	 */
	public static function renderMarkdownHtml ($str) {
		$regexps = [];
		$replacements = [];
		$refs = self::getPagesReferences($str);

		foreach ($refs as $ref) {
			$regexps[] = '/\[\[ *' . preg_quote($ref) . ' *\]\]/';

			$link = '<a href="/';
			$link .= $ref;
			$link .= '">';
			$link .= htmlspecialchars(str_replace('_', '\_', $ref));
			$link .= '</a>';
			if (!FlightWikiPage::exists($ref)) $link .= '<sup>(?)</sup>';
			$replacements[] = $link;
		}

		return Parsedown::instance()
		                ->setUrlsLinked(false)
		                ->text(preg_replace($regexps, $replacements, $str));
	}

	/**
	 * Parse a string and returns found [[pages links]]
	 *
	 * @param string $str
	 *
	 * @return array
	 */
	public static function getPagesReferences ($str) {
		$refs = [];
		$linkRegex = '/\[\[ *(.*?) *\]\]/';

		preg_match_all($linkRegex, $str, $matches, PREG_SET_ORDER);

		foreach ($matches as $match) {
			$refs[] = $match[1];
		}

		return $refs;
	}

	/**
	 * Returns html pages cloud
	 *
	 * @param string $separator separator between links
	 *
	 * @return string
	 */
	public static function renderPagesCloud ($separator = ' - ') {
		$files = FlightWikiPage::findAll();
		$pagesNames = [];

		foreach ($files as $file) {
			$content = file_get_contents(Flight::filePath(Flight::get('pages_dir'), $file));
			$refs = array_map('strtolower', self::getPagesReferences($content));

			foreach ($refs as $ref) {
				if (!array_key_exists($ref, $pagesNames))
					$pagesNames[$ref] = 0;

				$pagesNames[$ref] += 1;
			}
		}

		$maxScore = max($pagesNames);
		$minScore = min($pagesNames);

		$htmlLinks = [];
		foreach ($pagesNames as $pageName => $score) {
			$size = self::getPercentSize($maxScore, $minScore, $score);
			$htmlLinks[] = '<a href="/' . $pageName . '" style="font-size: ' . $size . '%;">' . htmlspecialchars($pageName) . '</a>';
		}

		shuffle($htmlLinks);

		return implode($htmlLinks, $separator);
	}

	/**
	 * Returns a percent size (for the pages cloud)
	 *
	 * @access private
	 *
	 * @param string $maxScore
	 * @param string $minScore
	 * @param string $currentValue
	 * @param int    $minSize
	 * @param int    $maxSize
	 *
	 * @return int
	 */
	private static function getPercentSize ($maxScore, $minScore, $currentValue, $minSize = 90, $maxSize = 200) {
		if ($minScore < 1)
			$minScore = 1;

		$spread = $maxScore - $minScore;

		if ($spread == 0)
			$spread = 1;

		$step = ($maxSize - $minSize) / $spread;

		return $minSize + (($currentValue - $minScore) * $step);
	}

}