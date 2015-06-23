<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2015 Purdue University. All rights reserved.
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
 * @author    Sam Wilson <samwilson@purdue.edu>
 * @copyright Copyright 2005-2015 Purdue University. All rights reserved.
 * @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPLv3
 */

// No direct access.
defined('_HZEXEC_') or die();

Toolbar::title(Lang::txt('CMS Updater: database'));
Toolbar::custom('migrate', 'purge', '', 'Run pending migrations', false);

$this->css();
?>

<form action="<?php echo Route::url('index.php?option=' . $this->option . '&controller=' . $this->controller); ?>" method="post" name="adminForm" id="updateRepositoryForm">
	<table id="tktlist" class="adminlist">
		<thead>
			<tr>
				<th scope="col">Component</th>
				<th scope="col">Date</th>
				<th scope="col">Status</th>
				<th scope="col">Description</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="4">
					<?php
					echo $this->pagination(
						$this->total,
						$this->filters['start'],
						$this->filters['limit']
					);
					?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($this->rows as $row) : ?>
				<?php $item      = ltrim($row['entry'], 'Migration'); ?>
				<?php $date      = Date::of(strtotime(substr($item, 0, 14).'UTC'))->format('Y-m-d g:i:sa'); ?>
				<?php $component = substr($item, 14, -4); ?>
				<?php require_once PATH_CORE . DS . 'migrations' . DS . $row['entry']; ?>
				<?php $class     = new ReflectionClass(substr($row['entry'], 0, -4)); ?>
				<?php $desc      = trim(rtrim(ltrim($class->getDocComment(), "/**\n *"), '**/')); ?>
				<tr>
					<td><?php echo $component; ?></td>
					<td><?php echo $date; ?></td>
					<td class="status">
						<?php if ($row['status'] == 'pending') : ?>
							<a href="<?php echo Route::url('index.php?option='.$this->option.'&controller='.$this->controller.'&task=migrate&file='.$row['entry']); ?>">
						<?php endif; ?>
							<span class="state <?php echo ($row['status'] == 'complete') ? 'published' : $row['status']; ?>">
								<span class="text"><?php echo $row['status']; ?></span>
							</span>
						<?php if ($row['status'] == 'pending') : ?>
							</a>
						<?php endif; ?>
					</td>
					<td><?php echo $desc; ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<input type="hidden" name="option" value="<?php echo $this->option ?>" />
	<input type="hidden" name="controller" value="<?php echo $this->controller ?>" />
	<input type="hidden" name="task" value="" />

	<?php echo Html::input('token'); ?>
</form>