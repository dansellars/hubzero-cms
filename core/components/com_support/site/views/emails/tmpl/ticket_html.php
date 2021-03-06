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

$base = rtrim(Request::base(), '/');
if (substr($base, -13) == 'administrator')
{
	$base = rtrim(substr($base, 0, strlen($base)-13), '/');
	$sef = 'support/ticket/' . $this->ticket->get('id');
}
else
{
	$sef = Route::url($this->ticket->link());
}
$link = $base . '/' . trim($sef, '/');

switch ($this->ticket->get('severity'))
{
	case 'critical':
		$bgcolor = '#ffd3d4';
		$bdcolor = '#e9bcbc';
		break;
	case 'major':
		$bgcolor = '#fbf1be';
		$bdcolor = '#e9e1bc';
		break;
	case 'minor':
		$bgcolor = '#d3e3ff';
		$bdcolor = '#bccbe9';
		break;
	case 'trivial':
		$bgcolor = '#d3f9ff';
		$bdcolor = '#bce1e9';
		break;
	case 'normal':
	default:
		$bgcolor = '#f1f1f1';
		$bdcolor = '#e1e1e1';
		break;
}

$usertype = Lang::txt('COM_SUPPORT_UNKNOWN');
if ($this->ticket->submitter()->get('id'))
{
	$gids = array();
	foreach (User::getInstance($this->ticket->submitter->get('id'))->accessgroups() as $g)
	{
		$gids[] = $g->group_id;
	}
	$usertype = implode(', ', $gids);
}

$this->css(
	'@media only screen and (max-device-width: 480px) {
		#ticket-number {
			float: none !important;
			width: auto !important;
		}
		table#ticket-comments>tbody>tr>td {
			padding: 0 !important;
		}
	}'
);
?>
<?php if ($this->delimiter) { ?>
	<!-- Start Header Spacer -->
	<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border: 1px dashed #b5c6b5;">
		<tr style="border-collapse: collapse;">
			<td height="30" style="border-collapse: collapse; color: #9bac9b;">
				<div style="height: 0px; overflow: hidden; color: #fff; visibility: hidden;"><?php echo $this->delimiter; ?></div>
				<div style="text-align: center; font-size: 90%; display: block; padding: 1em;"><?php echo Lang::txt('COM_SUPPORT_EMAIL_REPLY_ABOVE'); ?></div>
			</td>
		</tr>
	</table>
	<!-- End Header Spacer -->

	<!-- Start Spacer -->
	<table class="tbl-spacer" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tbody>
			<tr>
				<td height="30"></td>
			</tr>
		</tbody>
	</table>
	<!-- End Spacer -->
<?php } ?>
	<!-- Start Header -->
	<table class="tbl-header" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tbody>
			<tr>
				<td width="10%" align="left" valign="bottom" nowrap="nowrap" class="sitename">
					<?php echo Config::get('sitename'); ?>
				</td>
				<td width="80%" align="left" valign="bottom" class="tagline mobilehide">
					<span class="home">
						<a href="<?php echo Request::base(); ?>"><?php echo Request::base(); ?></a>
					</span>
					<br />
					<span class="description"><?php echo Config::get('MetaDesc'); ?></span>
				</td>
				<td width="10%" align="right" valign="bottom" nowrap="nowrap" class="component">
					<?php echo Lang::txt('COM_SUPPORT_CENTER'); ?>
				</td>
			</tr>
		</tbody>
	</table>
	<!-- End Header -->

	<!-- Start Spacer -->
	<table class="tbl-spacer" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tbody>
			<tr>
				<td height="30"></td>
			</tr>
		</tbody>
	</table>
	<!-- End Spacer -->

	<table id="ticket-info" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse; border: 1px solid <?php echo $bdcolor; ?>; background: <?php echo $bgcolor; ?>; font-size: 0.9em; line-height: 1.6em; background-image: -webkit-gradient(linear, 0 0, 100% 100%,
										color-stop(.25, rgba(255, 255, 255, .075)), color-stop(.25, transparent),
										color-stop(.5, transparent), color-stop(.5, rgba(255, 255, 255, .075)),
										color-stop(.75, rgba(255, 255, 255, .075)), color-stop(.75, transparent),
										to(transparent));
	background-image: -webkit-linear-gradient(-45deg, rgba(255, 255, 255, .075) 25%, transparent 25%,
									transparent 50%, rgba(255, 255, 255, .075) 50%, rgba(255, 255, 255, .075) 75%,
									transparent 75%, transparent);
	background-image: -moz-linear-gradient(-45deg, rgba(255, 255, 255, .075) 25%, transparent 25%,
									transparent 50%, rgba(255, 255, 255, .075) 50%, rgba(255, 255, 255, .075) 75%,
									transparent 75%, transparent);
	background-image: -ms-linear-gradient(-45deg, rgba(255, 255, 255, .075) 25%, transparent 25%,
									transparent 50%, rgba(255, 255, 255, .075) 50%, rgba(255, 255, 255, .075) 75%,
									transparent 75%, transparent);
	background-image: -o-linear-gradient(-45deg, rgba(255, 255, 255, .075) 25%, transparent 25%,
									transparent 50%, rgba(255, 255, 255, .075) 50%, rgba(255, 255, 255, .075) 75%,
									transparent 75%, transparent);
	background-image: linear-gradient(-45deg, rgba(255, 255, 255, .075) 25%, transparent 25%,
									transparent 50%, rgba(255, 255, 255, .075) 50%, rgba(255, 255, 255, .075) 75%,
									transparent 75%, transparent);
									-webkit-background-size: 30px 30px;
									-moz-background-size: 30px 30px;
									background-size: 30px 30px;">
		<thead class="mobilehide">
			<tr>
				<th style="font-weight: normal; border-bottom: 1px solid <?php echo $bdcolor; ?>; padding: 8px; text-align: left" align="left">
					<?php echo Lang::txt('COM_SUPPORT_NEW_TICKET'); ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td width="100%" style="padding: 8px;">
					<div id="ticket-number" style="float: left; width: 5em; font-size: 2.5em; font-weight: bold; text-align: center; padding: 30px;" align="center">
						<a href="<?php echo $link; ?>">#<?php echo $this->ticket->get('id'); ?></a>
					</div>
					<?php if (!$this->config->get('email_terse')) { ?>
						<table style="border-collapse: collapse; font-size: 0.9em;" cellpadding="0" cellspacing="0" border="0">
							<tbody>
								<tr>
									<th style="text-align: right; padding: 0 0.5em; font-weight: bold; white-space: nowrap;" align="right"><?php echo Lang::txt('COM_SUPPORT_TICKET_DETAILS_CREATED'); ?>:</th>
									<td style="text-align: left; padding: 0 0.5em;" align="left"><?php echo Lang::txt('COM_SUPPORT_TICKET_CREATED', $this->ticket->created('time'), $this->ticket->created('date')); ?></td>
								</tr>
								<tr>
									<th style="text-align: right; padding: 0 0.5em; font-weight: bold; white-space: nowrap;" align="right"><?php echo Lang::txt('COM_SUPPORT_TICKET_DETAILS_CREATED_BY'); ?>:</th>
									<td style="text-align: left; padding: 0 0.5em;" align="left"><?php echo $this->ticket->get('name', Lang::txt('COM_SUPPORT_UNKNOWN')); ?> <?php echo $this->ticket->get('login') ? '(' . $this->ticket->get('login') . ')' : ''; ?></td>
								</tr>
								<tr>
									<th style="text-align: right; padding: 0 0.5em; font-weight: bold; white-space: nowrap; vertical-align: top;" align="right"><?php echo Lang::txt('COM_SUPPORT_TICKET_DETAILS_USERTYPE'); ?>:</th>
									<td style="text-align: left; padding: 0 0.5em;" align="left"><?php echo $usertype; ?></td>
								</tr>
								<tr>
									<th style="text-align: right; padding: 0 0.5em; font-weight: bold; white-space: nowrap; vertical-align: top;" align="right"><?php echo Lang::txt('COM_SUPPORT_EMAIL'); ?>:</th>
									<td style="text-align: left; padding: 0 0.5em;" align="left"><?php echo $this->escape($this->ticket->get('email')); ?></td>
								</tr>
								<tr>
									<th style="text-align: right; padding: 0 0.5em; font-weight: bold; white-space: nowrap; vertical-align: top;" align="right"><?php echo Lang::txt('COM_SUPPORT_IP_HOSTNAME'); ?>:</th>
									<td style="text-align: left; padding: 0 0.5em;" align="left"><?php echo $this->escape($this->ticket->get('ip')) . ' (' . $this->escape($this->ticket->get('hostname')) . ')'; ?></td>
								</tr>
								<tr>
									<th style="text-align: right; padding: 0 0.5em; font-weight: bold; white-space: nowrap; vertical-align: top;" align="right"><?php echo Lang::txt('COM_SUPPORT_OS'); ?>:</th>
									<td style="text-align: left; padding: 0 0.5em;" align="left"><?php echo $this->escape($this->ticket->get('os')); ?></td>
								</tr>
								<tr>
									<th style="text-align: right; padding: 0 0.5em; font-weight: bold; white-space: nowrap; vertical-align: top;" align="right"><?php echo Lang::txt('COM_SUPPORT_BROWSER'); ?>:</th>
									<td style="text-align: left; padding: 0 0.5em;" align="left"><?php echo $this->escape($this->ticket->get('browser')); ?></td>
								</tr>
								<tr>
									<th style="text-align: right; padding: 0 0.5em; font-weight: bold; white-space: nowrap; vertical-align: top;" align="right"><?php echo Lang::txt('COM_SUPPORT_UAS'); ?>:</th>
									<td style="text-align: left; padding: 0 0.5em;" align="left"><?php echo $this->escape($this->ticket->get('uas')); ?></td>
								</tr>
								<tr>
									<th style="text-align: right; padding: 0 0.5em; font-weight: bold; white-space: nowrap; vertical-align: top;" align="right"><?php echo Lang::txt('COM_SUPPORT_REFERRER'); ?>:</th>
									<td style="text-align: left; padding: 0 0.5em;" align="left"><?php echo $this->escape($this->ticket->get('referrer')); ?></td>
								</tr>
								<?php /*<tr>
									<th style="text-align: right; padding: 0 0.5em; font-weight: bold; white-space: nowrap; vertical-align: top;" align="right"><?php echo Lang::txt('COM_SUPPORT_TICKET_DETAILS_LINK'); ?>:</th>
									<td style="text-align: left; padding: 0 0.5em;" align="left"><a href="<?php echo $link; ?>"><?php echo $link; ?></a></td>
								</tr>*/ ?>
							</tbody>
						</table>
					<?php } else { ?>
						<table style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">
							<tbody>
								<tr>
									<td style="text-align: left; padding: 0 0.5em;" align="left"><?php echo Lang::txt('COM_SUPPORT_NOTIFY_TICKET_CREATED', $this->ticket->get('id'), $link); ?></td>
								</tr>
							</tbody>
						</table>
					<?php } ?>
				</td>
			</tr>
		</tbody>
	</table>

<?php if (!$this->config->get('email_terse')) { ?>
	<table width="100%" id="ticket-comments" style="border-collapse: collapse; margin: 2em 0 0 0; padding: 0" cellpadding="0" cellspacing="0" border="0">
		<tbody>
			<tr>
				<td style="padding: 0 2em;">
					<p style="line-height: 1.6em; margin: 1em 0; padding: 0; text-align: left;"><?php echo $this->ticket->content; ?></p>
					<?php if ($this->ticket->attachments->count()) { ?>
						<div class="comment-attachments" style="margin: 2em 0 0 0; padding: 0; text-align: left;">
							<?php
							foreach ($this->ticket->attachments as $attachment)
							{
								if (!trim($attachment->get('description')))
								{
									$attachment->set('description', $attachment->get('filename'));
								}
								echo '<p class="attachment" style="margin: 0.5em 0; padding: 0; text-align: left;"><a class="' . ($attachment->isImage() ? 'img' : 'file') . '" data-filename="' . $attachment->get('filename') . '" href="' . $base . '/' . ltrim(Route::url($attachment->link()), '/') . '">' . $attachment->get('description') . '</a></p>';
							}
							?>
						</div><!-- / .comment-body -->
					<?php } ?>
				</td>
			</tr>
		</tbody>
	</table>
<?php } ?>

	<!-- Start Spacer -->
	<table class="tbl-spacer" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tbody>
			<tr>
				<td height="30"></td>
			</tr>
		</tbody>
	</table>
	<!-- End Spacer -->

	<!-- Start Footer -->
	<table class="tbl-footer" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tbody>
			<tr>
				<td align="left" valign="bottom">
					<span><?php echo Lang::txt('COM_SUPPORT_EMAIL_WHY_NOTFIED', Config::get('sitename'), $link, '#' . $this->ticket->get('id'), $base, $base); ?></span>
				</td>
			</tr>
		</tbody>
	</table>
	<!-- End Footer -->