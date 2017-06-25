<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	5.7.0
 * @author	acyba.com
 * @copyright	(C) 2009-2017 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><div id="acy_content">
	<div id="iframedoc"></div>
	<?php if(!$this->app->isAdmin()){ ?>
	<fieldset>
		<div class="acyheader icon-48-stats" style="float: left;"><?php echo acymailing_translation('GLOBAL_STATISTICS'); ?></div>
		<div class="toolbar" id="acytoolbar" style="float: right;">
			<table>
				<tr>
					<td id="acybutton_stats_exportglobal"><a onclick="javascript:submitbutton('exportglobal'); return false;" href="#" ><span class="icon-32-acyexport" title="<?php echo acymailing_translation('ACY_EXPORT'); ?>"></span><?php echo acymailing_translation('ACY_EXPORT'); ?></a></td>
					<?php if(acymailing_isAllowed($this->config->get('acl_statistics_delete','all'))){ ?><td id="acybutton_stats_delete"><a onclick="javascript:if(document.adminForm.boxchecked.value==0){alert('<?php echo acymailing_translation('PLEASE_SELECT',true);?>');}else{if(confirm('<?php echo acymailing_translation('ACY_VALIDDELETEITEMS',true); ?>')){submitbutton('remove');}} return false;" href="#" ><span class="icon-32-delete" title="<?php echo acymailing_translation('ACY_DELETE'); ?>"></span><?php echo acymailing_translation('ACY_DELETE'); ?></a></td><?php } ?>
				</tr>
			</table>
		</div>
	</fieldset>
	<?php } ?>
	<form action="index.php?option=<?php echo ACYMAILING_COMPONENT ?>&amp;ctrl=<?php echo JRequest::getCmd('ctrl'); ?>" method="post" name="adminForm" id="adminForm">
		<table class="acymailing_table_options">
			<tr>
				<td>
					<?php acymailing_listingsearch($this->pageInfo->search); ?>
				</td>
				<td class="tablegroup_options">
					<span class="statistics_filter" id="statfilter" align="left"><?php echo $this->filterMsg; ?></span>
					<?php if(!empty($this->filterTag)){ ?><span class="statistics_filter" id="statfilter" align="left"><?php echo $this->filterTag; ?></span><?php } ?>
				</td>
			</tr>
		</table>
		<?php if(!$this->app->isAdmin()) echo '<div class="acyslide">'; ?>
		<table class="acymailing_table" cellpadding="1">
			<thead>
			<tr>
				<?php if($this->menuparams->get('number', '1') == 1){ ?>
					<th class="title titlenum">
						<?php echo acymailing_translation('ACY_NUM'); ?>
					</th>
				<?php } ?>
				<th class="title titlebox">
					<input type="checkbox" name="toggle" value="" onclick="acymailing_js.checkAll(this);"/>
				</th>
				<th class="title statsubjectsenddate">
					<?php echo JHTML::_('grid.sort', acymailing_translation('JOOMEXT_SUBJECT'), 'b.subject', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value, 'listing').' - '.JHTML::_('grid.sort', acymailing_translation('SEND_DATE'), 'a.senddate', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value, 'listing'); ?>
				</th>
				<?php if($this->menuparams->get('opens', '1') == 1){ ?>
					<th class="title titletoggle">
						<?php echo JHTML::_('grid.sort', acymailing_translation('OPEN'), 'openprct', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value, 'listing'); ?>
					</th>
				<?php } ?>
				<?php if(acymailing_level(1)){ ?>
					<?php if($this->menuparams->get('clicks', '1') == 1){ ?>
						<th class="title titletoggle">
							<?php echo JHTML::_('grid.sort', acymailing_translation('CLICKED_LINK'), 'clickprct', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value, 'listing'); ?>
						</th>
					<?php } ?>
					<?php if($this->menuparams->get('efficiency', '1') == 1){ ?>
						<th class="title titletoggle">
							<?php echo JHTML::_('grid.sort', acymailing_translation('ACY_CLICK_EFFICIENCY'), 'efficiencyprct', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value, 'listing'); ?>
						</th>
					<?php } ?>
				<?php } ?>
				<?php if($this->menuparams->get('unsubscribe', '1') == 1){ ?>
					<th class="title titletoggle">
						<?php echo JHTML::_('grid.sort', acymailing_translation('UNSUBSCRIBE'), 'unsubprct', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value, 'listing'); ?>
					</th>
				<?php } ?>
				<?php if(acymailing_level(1) && $this->menuparams->get('forward', '1') == 1){ ?>
					<th class="title titletoggle">
						<?php echo JHTML::_('grid.sort', acymailing_translation('FORWARDED'), 'a.forward', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value, 'listing'); ?>
					</th>
				<?php } ?>
				<?php if($this->menuparams->get('sent', '1') == 1){ ?>
					<th class="title titletoggle">
						<?php echo JHTML::_('grid.sort', acymailing_translation('ACY_SENT'), 'totalsent', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value, 'listing'); ?>
					</th>
				<?php } ?>
				<?php if(acymailing_level(3) && $this->menuparams->get('bounces', '1') == 1){ ?>
					<th class="title titletoggle">
						<?php echo JHTML::_('grid.sort', acymailing_translation('BOUNCES'), 'bounceprct', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value, 'listing'); ?>
					</th>
				<?php } ?>
				<?php if($this->menuparams->get('failed', '1') == 1){ ?>
					<th class="title titletoggle">
						<?php echo JHTML::_('grid.sort', acymailing_translation('FAILED'), 'a.fail', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value, 'listing'); ?>
					</th>
				<?php } ?>
				<?php if(acymailing_level(3) && $this->app->isAdmin()){ ?>
					<th class="title titletoggle" style="font-size: 12px;">
						<?php echo acymailing_translation('STATS_PER_LIST'); ?>
					</th>
				<?php } ?>
				<?php if($this->menuparams->get('id', '1') == 1){ ?>
					<th class="title titleid titletoggle">
						<?php echo JHTML::_('grid.sort', acymailing_translation('ACY_ID'), 'a.mailid', $this->pageInfo->filter->order->dir, $this->pageInfo->filter->order->value, 'listing'); ?>
					</th>
				<?php } ?>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<td colspan="14">
					<?php echo $this->pagination->getListFooter();
					echo $this->pagination->getResultsCounter();
					if(ACYMAILING_J30) echo '<br />'.$this->pagination->getLimitBox(); ?>
				</td>
			</tr>
			</tfoot>
			<tbody>
			<?php
			$k = 0;

			for($i = 0, $a = count($this->rows); $i < $a; $i++){
				$row =& $this->rows[$i];
				$row->subject = Emoji::Decode($row->subject);
				if(acymailing_level(3)){
					$cleanSent = $row->senthtml + $row->senttext - $row->bounceunique;
				}else{
					$cleanSent = $row->senthtml + $row->senttext;
				}
				?>
				<tr class="<?php echo "row$k"; ?>">
					<?php if($this->menuparams->get('number', '1') == 1){ ?>
						<td align="center" style="text-align:center">
							<?php echo $this->pagination->getRowOffset($i); ?>
						</td>
					<?php } ?>
					<td align="center" style="text-align:center">
						<?php echo JHTML::_('grid.id', $i, $row->mailid); ?>
					</td>
					<td>
						<?php if(acymailing_level(2)){ ?><a class="modal" href="<?php echo acymailing_completeLink(($this->app->isAdmin() ? '' : 'front').'diagram&task=mailing&mailid='.$row->mailid, true) ?>" rel="{handler: 'iframe', size: {x: 800, y: 590}}"><i class="acyicon-statistic"></i><?php } ?>
							<?php echo '<span class="acy_stat_subject">'.acymailing_tooltip('<b>'.acymailing_translation('JOOMEXT_ALIAS').' : </b>'.$row->alias, ' ', '', $row->subject).'</span>'; ?>
							<?php if(acymailing_level(2)){ ?></a><?php } ?>
						<?php echo '<br /><span class="acy_stat_date"><b>'.acymailing_translation('SEND_DATE').' : </b>'.acymailing_getDate($row->senddate).'</span>'; ?>
					</td>
					<?php if($this->menuparams->get('opens', '1') == 1){ ?>
						<td align="center" style="text-align:center">
							<?php
							if(!empty($row->senthtml)){
								$text = '<b>'.acymailing_translation('OPEN_UNIQUE').' : </b>'.$row->openunique.' / '.$cleanSent;
								$text .= '<br /><b>'.acymailing_translation('OPEN_TOTAL').' : </b>'.$row->opentotal;
								$pourcent = ($cleanSent == 0 ? '0%' : (substr($row->openunique / $cleanSent * 100, 0, 5)).'%');
								$title = acymailing_translation_sprintf('PERCENT_OPEN', $pourcent);
								echo acymailing_tooltip($text, $title, '', $pourcent, acymailing_completeLink(JRequest::getCmd('ctrl').'&task=detaillisting&filter_status=open&filter_mail='.$row->mailid));
							}
							?>
						</td>
					<?php } ?>
					<?php if(acymailing_level(1)){ ?>
						<?php if($this->menuparams->get('clicks', '1') == 1){ ?>
							<td align="center" style="text-align:center">
								<?php
								if(!empty($row->senthtml)){
									$text = '<b>'.acymailing_translation('UNIQUE_HITS').' : </b>'.$row->clickunique.' / '.$cleanSent;
									$text .= '<br /><b>'.acymailing_translation('TOTAL_HITS').' : </b>'.$row->clicktotal;
									$pourcent = ($cleanSent == 0 ? '0%' : (substr($row->clickunique / $cleanSent * 100, 0, 5)).'%');
									$title = acymailing_translation_sprintf('PERCENT_CLICK', $pourcent);
									echo acymailing_tooltip($text, $title, '', $pourcent, acymailing_completeLink(($this->app->isAdmin() ? '' : 'front').'statsurl&filter_mail='.$row->mailid));
								}
								?>
							</td>
						<?php } ?>
						<?php if($this->menuparams->get('efficiency', '1') == 1){ ?>
							<td align="center" style="text-align:center">
								<?php
								if(!empty($row->senthtml)){
									$text = '<b>'.acymailing_translation('UNIQUE_HITS').' : </b>'.$row->clickunique.' / '.$row->openunique;
									$text .= '<br /><b>'.acymailing_translation('OPEN_UNIQUE').' : </b>'.$row->openunique;
									$pourcentEfficiency = ($row->openunique == 0 ? '0%' : (substr($row->clickunique / $row->openunique * 100, 0, 5)).'%');
									$title = acymailing_translation_sprintf('ACY_CLICK_EFFICIENCY_DESC', $pourcentEfficiency);
									echo acymailing_tooltip($text, $title, '', $pourcentEfficiency, acymailing_completeLink(($this->app->isAdmin() ? '' : 'front').'statsurl&filter_mail='.$row->mailid));
								}
								?>
							</td>
						<?php } ?>
					<?php } ?>
					<?php if($this->menuparams->get('unsubscribe', '1') == 1){ ?>
						<td align="center" style="text-align:center">
							<?php echo '<a class="modal" href="'.acymailing_completeLink(JRequest::getCmd('ctrl').'&task=unsubchart&mailid='.$row->mailid, true).'" rel="{handler: \'iframe\', size: {x: 800, y: 590}}"><i class="acyicon-statistic"></i></a> '; ?>
							<?php $pourcent = ($cleanSent == 0) ? '0%' : (substr($row->unsub / $cleanSent * 100, 0, 5)).'%';
							$text = $row->unsub.' / '.$cleanSent;
							$title = acymailing_translation('UNSUBSCRIBE');
							echo '<a class="modal" href="'.acymailing_completeLink(JRequest::getCmd('ctrl').'&start=0&task=unsubscribed&filter_mail='.$row->mailid, true).'" rel="{handler: \'iframe\', size: {x: 800, y: 590}}">'.acymailing_tooltip($text, $title, '', $pourcent).'</a>'; ?>
						</td>
					<?php } ?>
					<?php if(acymailing_level(1) && $this->menuparams->get('forward', '1') == 1){ ?>
						<td align="center" style="text-align:center">
							<?php echo '<a class="modal" href="'.acymailing_completeLink(JRequest::getCmd('ctrl').'&start=0&task=forward&filter_mail='.$row->mailid, true).'" rel="{handler: \'iframe\', size: {x: 800, y: 590}}">'.$row->forward.'</a>'; ?>
						</td>
					<?php } ?>
					<?php if($this->menuparams->get('sent', '1') == 1){ ?>
						<td align="center" style="text-align:center">
							<?php $text = '<b>'.acymailing_translation('HTML').' : </b>'.$row->senthtml;
							$text .= '<br /><b>'.acymailing_translation('JOOMEXT_TEXT').' : </b>'.$row->senttext;
							$title = acymailing_translation('ACY_SENT');
							echo acymailing_tooltip($text, $title, '', $row->senthtml + $row->senttext, acymailing_completeLink(JRequest::getCmd('ctrl').'&task=detaillisting&filter_status=0&filter_mail='.$row->mailid)); ?>
						</td>
					<?php } ?>
					<?php if(acymailing_level(3) && $this->menuparams->get('bounces', '1') == 1){ ?>
						<td align="center" style="text-align:center" nowrap="nowrap">
							<?php echo '<a class="modal" href="'.acymailing_completeLink(($this->app->isAdmin() ? '' : 'front').'bounces&task=chart&mailid='.$row->mailid, true).'" rel="{handler: \'iframe\', size: {x: 800, y: 590}}"><i class="acyicon-statistic"></i></a> ';
							$text = $row->bounceunique.' / '.($row->senthtml + $row->senttext);
							$title = acymailing_translation('BOUNCES');
							$pourcent = (empty($row->senthtml) AND empty($row->senttext)) ? '0%' : (substr($row->bounceunique / ($row->senthtml + $row->senttext) * 100, 0, 5)).'%';
							echo acymailing_tooltip($text, $title, '', $pourcent, acymailing_completeLink(JRequest::getCmd('ctrl').'&task=detaillisting&filter_status=bounce&filter_mail='.$row->mailid)); ?>
						</td>
					<?php } ?>
					<?php if($this->menuparams->get('failed', '1') == 1){ ?>
						<td align="center" style="text-align:center">
							<a href="<?php echo acymailing_completeLink(JRequest::getCmd('ctrl').'&task=detaillisting&filter_status=failed&filter_mail='.$row->mailid); ?>">
								<?php echo $row->fail; ?>
							</a>
						</td>
					<?php } ?>
					<?php if(acymailing_level(3) && $this->app->isAdmin()){ ?>
						<td align="center" style="text-align:center">
							<?php echo '<a class="modal" href="'.acymailing_completeLink(JRequest::getCmd('ctrl').'&task=mailinglist&mailid='.$row->mailid, true).'" rel="{handler: \'iframe\', size: {x: 800, y: 590}}"><i class="acyicon-statistic"></i></a>'; ?>
						</td>
					<?php } ?>
					<?php if($this->menuparams->get('id', '1') == 1){ ?>
						<td align="center" style="text-align:center">
							<?php echo $row->mailid; ?>
						</td>
					<?php } ?>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			</tbody>
		</table>
		<?php if(!$this->app->isAdmin()) echo '</div>'; ?>

		<input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT; ?>"/>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="ctrl" value="<?php echo JRequest::getCmd('ctrl'); ?>"/>
		<input type="hidden" name="boxchecked" value="0"/>
		<input type="hidden" name="filter_order" value="<?php echo $this->pageInfo->filter->order->value; ?>"/>
		<input type="hidden" name="filter_order_Dir" value="<?php echo $this->pageInfo->filter->order->dir; ?>"/>
		<?php echo JHTML::_('form.token'); ?>
	</form>
</div>
