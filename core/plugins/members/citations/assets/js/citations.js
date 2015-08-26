/**
 * @package     hubzero-cms
 * @file        plugins/members/citations/citations.js
 * @copyright   Copyright 2005-2015 Purdue University. All rights reserved.
 * @license     http://www.gnu.org/licenses/lgpl-3.0.html LGPLv3
 */

if (!jq) {
	var jq = $;
}

String.prototype.nohtml = function () {
	if (this.indexOf('?') == -1) {
		return this + '?no_html=1';
	} else {
		return this + '&no_html=1';
	}
};

var _DEBUG = 0;

jQuery(document).ready(function (jq){
	var $ = jq
		manager = $('.author-manager');

	_DEBUG = $('#system-debug').length;

	// Add confirm dialog to delete links
	$('a.delete').on('click', function (e) {
		var res = confirm($(this).attr('data-confirm'));
		if (!res) {
			e.preventDefault();
		}
		return res;
	});

	// toggle download markers.
	$('.checkall-download').click(function() {
		var checked = $(this).prop('checked');
		$('.download-marker').each(function() {
			$(this).prop('checked', checked);
			});
		});
	
	// bulk actions
	$('.bulk').click(function() {
		var citationIDs = $('.download-marker:checked').map(function()
			{
				return $(this).val();
			}).get();
		
		var url = $(this).attr('data-link');
		url = url + '&citationIDs=' + citationIDs.join(',');

		var locked = confirm('Are you sure you want to perform a bulk action?');
		if (locked === true)
		{
			window.location = url;
		 }

	});

	if (manager.length) {
		manager
			.find('button')
			.on('click', function (e){
				e.preventDefault();

				if (_DEBUG) {
					window.console && console.log('Calling: ' + manager.attr('data-add') + '&author=' + $('#field-author').val());
				}

				$.get(manager.attr('data-add').nohtml() + '&author=' + $('#field-author').val(), {}, function(data) {
					manager
						.find('.author-list')
						.html(data);

					manager.find('li>span').click();
				});
			});

		$('.author-list')
			.on('click', 'a.delete', function (e){
				e.preventDefault();

				$.get($(this).attr('href').nohtml(), {}, function(data) {});

				$(this).parent().parent().remove();
			});

		$('.author-list').sortable({
			handle: '.author-handle',
			update: function (e, ui) {
				var col = $(this).sortable("serialize");

				if (_DEBUG) {
					window.console && console.log('Calling: ' + manager.attr('data-update').nohtml() + '&' + col);
				}

				$.get(manager.attr('data-update').nohtml() + '&' + col, function(response) {
					if (_DEBUG) {
						window.console && console.log(response);
					}
				});
			}
		});
	}
});
