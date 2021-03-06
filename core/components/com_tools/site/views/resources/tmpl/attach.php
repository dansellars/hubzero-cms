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

$allowupload = ($this->version=='current' or !$this->status['published']) ? 1 : 0;
?>
	<div class="explaination">
		<h4><?php echo Lang::txt('COM_TOOLS_ATTACH_WHAT_ARE_ATTACHMENTS'); ?></h4>
		<p><?php echo Lang::txt('COM_TOOLS_ATTACH_EXPLANATION'); ?></p>
	</div>
	<fieldset>
		<legend><?php echo Lang::txt('COM_TOOLS_ATTACH_ATTACHMENTS'); ?></legend>
		<div class="field-wrap">
			<iframe width="100%" height="200" frameborder="0" name="attaches" id="attaches" src="index.php?option=<?php echo $this->option; ?>&amp;controller=attachments&amp;rid=<?php echo $this->row->id; ?>&amp;tmpl=component&amp;type=7&amp;allowupload=<?php echo $allowupload; ?>"></iframe>
		</div>
	</fieldset><div class="clear"></div>

	<div class="explaination">
		<h4><?php echo Lang::txt('COM_TOOLS_ATTACH_WHAT_ARE_SCREENSHOTS'); ?></h4>
		<p><?php echo Lang::txt('COM_TOOLS_ATTACH_SCREENSHOTS_EXPLANATION'); ?></p>
	</div>
	<fieldset>
		<legend><?php echo Lang::txt('COM_TOOLS_ATTACH_SCREENSHOTS'); ?></legend>
		<div class="field-wrap">
			<iframe width="100%" height="400" frameborder="0" name="screens" id="screens" src="index.php?option=<?php echo $this->option; ?>&amp;controller=screenshots&amp;rid=<?php echo $this->row->id; ?>&amp;tmpl=component&amp;version=<?php echo $this->version; ?>"></iframe>
		</div>
	</fieldset><div class="clear"></div>