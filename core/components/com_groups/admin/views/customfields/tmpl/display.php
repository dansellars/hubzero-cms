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
 * @copyright Copyright 2005-2015 HUBzero Foundation, LLC.
 * @license   http://opensource.org/licenses/MIT MIT
 */

// No direct access
defined('_HZEXEC_') or die();

$canDo = Components\Careerplans\Helpers\Permissions::getActions('fieldset');

Toolbar::title(Lang::txt('COM_CAREERPLANS_TITLE'), 'form');
if ($canDo->get('core.delete'))
{
	Toolbar::deleteList();
}
if ($canDo->get('core.edit'))
{
	Toolbar::editList();
}
if ($canDo->get('core.create'))
{
	Toolbar::addNew();
}
Toolbar::spacer();
Toolbar::help('forms');

Html::behavior('tooltip');
?>
<script type="text/javascript">
function submitbutton(pressbutton)
{
	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		submitform(pressbutton);
		return;
	}
	// do field validation
	submitform(pressbutton);
}
</script>

<form action="<?php echo Route::url('index.php?option=' . $this->option . '&controller=' . $this->controller); ?>" method="post" name="adminForm">
	<fieldset id="filter-bar">
		<label for="filter_search"><?php echo Lang::txt('JSEARCH_FILTER'); ?>:</label>
		<input type="text" name="search" id="filter_search" value="<?php echo $this->escape($this->filters['search']); ?>" placeholder="<?php echo Lang::txt('COM_CAREERPLANS_FILTER_SEARCH_PLACEHOLDER'); ?>" />

		<input type="submit" value="<?php echo Lang::txt('COM_CAREERPLANS_GO'); ?>" />
	</fieldset>

	<table class="adminlist">
		<thead>
			<tr>
				<th><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->rows);?>);" /></th>
				<th scope="col" class="priority-5"><?php echo Html::grid('sort', 'COM_CAREERPLANS_COL_ID', 'id', @$this->filters['sort_Dir'], @$this->filters['sort']); ?></th>
				<th scope="col"><?php echo Html::grid('sort', 'COM_CAREERPLANS_COL_COMMENT', 'content', @$this->filters['sort_Dir'], @$this->filters['sort']); ?></th>
				<th scope="col" class="priority-2"><?php echo Html::grid('sort', 'COM_CAREERPLANS_COL_CREATOR', 'created_by', @$this->filters['sort_Dir'], @$this->filters['sort']); ?></th>
				<th scope="col" class="priority-3"><?php echo Html::grid('sort', 'COM_CAREERPLANS_COL_ANONYMOUS', 'state', @$this->filters['sort_Dir'], @$this->filters['sort']); ?></th>
				<th scope="col" class="priority-4"><?php echo Html::grid('sort', 'COM_CAREERPLANS_COL_CREATED', 'created', @$this->filters['sort_Dir'], @$this->filters['sort']); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="6"><?php
				// Initiate paging
				echo $this->rows->pagination;
				?></td>
			</tr>
		</tfoot>
		<tbody>
		<?php
		$k = 0;
		$i = 0;
		foreach ($this->rows as $row)
		{
			if (!$row->get('anonymous'))
			{
				$calt = Lang::txt('JOFF');
				$cls2 = 'off';
				$state = 1;
			}
			else
			{
				$calt = Lang::txt('JON');
				$cls2 = 'on';
				$state = 0;
			}
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<input type="checkbox" name="id[]" id="cb<?php echo $i; ?>" value="<?php echo $row->get('id'); ?>" onclick="isChecked(this.checked, this);" />
				</td>
				<td class="priority-5">
					<?php echo $row->get('id'); ?>
				</td>
				<td>
					<?php echo $row->get('treename'); ?>
					<?php if ($canDo->get('core.edit')) { ?>
						<a href="<?php echo Route::url('index.php?option=' . $this->option . '&controller=' . $this->controller . '&task=edit&id=' . $row->get('id')); ?>">
							<?php echo \Hubzero\Utility\Str::truncate($this->escape(strip_tags($row->content)), 90); ?>
						</a>
					<?php } else { ?>
						<span>
							<?php echo \Hubzero\Utility\Str::truncate($this->escape(strip_tags($row->content)), 90); ?>
						</span>
					<?php } ?>
				</td>
				<td class="priority-2">
					<?php echo $this->escape(stripslashes($row->creator->get('name'))); ?>
				</td>
				<td class="priority-3">
					<a class="state <?php echo $cls2; ?>" href="<?php echo Route::url('index.php?option=' . $this->option . '&controller=' . $this->controller . '&task=anonymous&state=' . $state . '&id=' . $row->get('id') . '&' . Session::getFormToken() . '=1'); ?>">
						<span><?php echo $calt; ?></span>
					</a>
				</td>
				<td class="priority-4">
					<time datetime="<?php echo $row->get('created'); ?>">
						<?php echo $row->created('date'); ?>
					</time>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
			$i++;
		}
		?>
		</tbody>
	</table>

	<input type="hidden" name="option" value="<?php echo $this->option ?>" />
	<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
	<input type="hidden" name="task" value="" autocomplete="off" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->filters['sort']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->filters['sort_Dir']; ?>" />

	<?php echo Html::input('token'); ?>
</form>
