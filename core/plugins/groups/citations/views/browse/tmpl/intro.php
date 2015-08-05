<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2014 Purdue University. All rights reserved.
 *
 * This file is part of: The HUBzero(R) Platform for Scientific Collaboration
 *
 * The HUBzero(R) Platform for Scientific Collaboration (HUBzero) is free
 * software: you can redistribute it and/or modify it under the terms of
 * the GNU Lesser General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * HUBzero is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * HUBzero is a registered trademark of Purdue University.
 *
 * @package   hubzero-cms
 * @author	Shawn Rice <zooley@purdue.edu>, Kevin Wojkovich <kevinw@purdue.edu>
 * @copyright Copyright 2005-2014 Purdue University. All rights reserved.
 * @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPLv3
 */

// No direct access
defined('_HZEXEC_') or die();

$this->css('citations.css')
	 ->js();

$base = 'index.php?option=com_groups&cn=' . $this->group->get('cn') . '&active=citations';

if (isset($this->messages))
{
	foreach ($this->messages as $message)
	{
		echo "<p class=\"{$message['type']}\">" . $message['message'] . "</p>";
	}
}
?>

<div id="content-header-extra"><!-- Citation management buttons -->
	<?php if ($this->isManager) : ?>
		<a class="btn icon-add" href="<?php echo Route::url($base. '&action=add'); ?>">
			<?php echo Lang::txt('PLG_GROUPS_CITATIONS_SUBMIT_CITATION'); ?>
		</a>
		<a class="btn icon-upload" href="<?php echo Route::url($base. '&action=import'); ?>">
			<?php echo Lang::txt('PLG_GROUPS_CITATIONS_IMPORT_CITATION'); ?>
		</a>
		<a class="btn icon-settings" href="<?php echo Route::url($base. '&action=settings'); ?>">
			<?php echo Lang::txt('PLG_GROUPS_CITATIONS_SET_FORMAT'); ?>
		</a>
	<?php endif; ?>
</div><!-- / Citations management buttons -->

<div id="intro-container">
<div id="citations-introduction">
	<div class="instructions">
	<h2 id="instructions-title">Group Citations</h2>
	<p id="who">A group manager may:</p>
	<ul>
		<li>
			<div class="instruction">
			<a class="btn icon-add" href="<?php echo Route::url($base. '&action=add'); ?>">
				<?php echo Lang::txt('PLG_GROUPS_CITATIONS_SUBMIT_CITATION'); ?>
		</a>
		<span class="description">  Manually enter a citation.</span>
		</div>
	</li>
 		<li>
		<div class="instruction">
		<a class="btn icon-upload" href="<?php echo Route::url($base. '&action=import'); ?>">
			<?php echo Lang::txt('PLG_GROUPS_CITATIONS_IMPORT_CITATION'); ?>
		</a>
		<span class="description">  Import a list of citations.</span>
		</div>
		</li>
		<li>
		<span class="or">or</span>
		</li>
 		<li>
		<div class="instruction">
			<a class="btn icon-settings" href="<?php echo Route::url($base. '&action=settings'); ?>">
			<?php echo Lang::txt('PLG_GROUPS_CITATIONS_SET_FORMAT'); ?>
		 </a>
		<span class="description">Set group-level options for citations.</span>
		</div>
			</li>
 </ul>
</div><!-- / .instructions -->
	<div class="questions">
	<p><strong>What is a group citation?</strong></p>
	<p>Within a group, a citation is a listing of a product resulting in work done by a group or a group member.
	As a group manager, you can choose to display citations curated by a group manager-only or citations that were produced by members of your group.</p>
	</div>
	</div>

</div> <!-- /#intro-container --> 
