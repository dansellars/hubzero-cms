<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2015 HUBzero Foundation, LLC.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * HUBzero is a registered trademark of Purdue University.
 *
 * @package   hubzero-cms
 * @author    Shawn Rice <zooley@purdue.edu>
 * @copyright Copyright 2005-2015 HUBzero Foundation, LLC.
 * @license   http://opensource.org/licenses/MIT MIT
 */

// No direct access.
defined('_HZEXEC_') or die();

/**
 * Short description for 'WordLevelDiff'
 *
 * Long description (if any) ...
 */
class WordLevelDiff extends MappedDiff
{
	/**
	 * Description for 'AX_LINE_LENGTH'
	 */
	const MAX_LINE_LENGTH = 10000;

	/**
	 * Short description for 'WordLevelDiff'
	 *
	 * Long description (if any) ...
	 *
	 * @param      unknown $orig_lines Parameter description (if any) ...
	 * @param      unknown $closing_lines Parameter description (if any) ...
	 * @return     void
	 */
	public function __construct($orig_lines, $closing_lines)
	{
		list($orig_words, $orig_stripped) = $this->_split($orig_lines);
		list($closing_words, $closing_stripped) = $this->_split($closing_lines);

		parent::__construct($orig_words, $closing_words, $orig_stripped, $closing_stripped);
	}

	/**
	 * Short description for '_split'
	 *
	 * Long description (if any) ...
	 *
	 * @param      array $lines Parameter description (if any) ...
	 * @return     array Return description (if any) ...
	 */
	public function _split($lines)
	{
		$words = array();
		$stripped = array();
		$first = true;
		foreach ($lines as $line)
		{
			// If the line is too long, just pretend the entire line is one big word
			// This prevents resource exhaustion problems
			if ($first)
			{
				$first = false;
			}
			else
			{
				$words[] = "\n";
				$stripped[] = "\n";
			}
			if (strlen($line) > self::MAX_LINE_LENGTH)
			{
				$words[] = $line;
				$stripped[] = $line;
			}
			else
			{
				$m = array();
				if (preg_match_all('/ ([^\S\n]+ | [0-9_A-Za-z\x80-\xff]+ | .) (?: (?!< \n) [^\S\n])? /xs', $line, $m))
				{
					$words = array_merge($words, $m[0]);
					$stripped = array_merge($stripped, $m[1]);
				}
			}
		}
		return array($words, $stripped);
	}

	/**
	 * Short description for 'orig'
	 *
	 * Long description (if any) ...
	 *
	 * @return     unknown Return description (if any) ...
	 */
	public function orig()
	{
		$orig = new _HWLDF_WordAccumulator;

		foreach ($this->edits as $edit)
		{
			if ($edit->type == 'copy')
			{
				$orig->addWords($edit->orig);
			}
			elseif ($edit->orig)
			{
				$orig->addWords($edit->orig, 'del');
			}
		}
		$lines = $orig->getLines();

		return $lines;
	}

	/**
	 * Short description for 'closing'
	 *
	 * Long description (if any) ...
	 *
	 * @return     unknown Return description (if any) ...
	 */
	public function closing()
	{
		$closing = new _HWLDF_WordAccumulator;

		foreach ($this->edits as $edit)
		{
			if ($edit->type == 'copy')
			{
				$closing->addWords($edit->closing);
			}
			elseif ($edit->closing)
			{
				$closing->addWords($edit->closing, 'ins');
			}
		}
		$lines = $closing->getLines();

		return $lines;
	}
}
