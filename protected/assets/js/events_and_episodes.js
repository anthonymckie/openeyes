/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2013
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2011-2013, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/agpl-3.0.html The GNU Affero General Public License V3.0
 */

$(document).ready(function(){
	$collapsed = true;

	$(document).keydown(function(event){
		if(event.keyCode == 13 && $(event.target).is(':not(textarea)')) {
			event.preventDefault();
			return false;
		}
	});

	$('label').die('click').live('click',function() {
		if ($(this).prev().is('input:radio')) {
			$(this).prev().click();
		}
	});

	$(this).undelegate('select.dropDownTextSelection','change').delegate('select.dropDownTextSelection','change',function() {
		if ($(this).val() != '') {
			var target = $('#' + $(this).attr('id').replace(/^dropDownTextSelection_/,''));
			var currentVal = target.val();

			if($(this).hasClass('delimited')) {
				var newText = $(this).val();
			} else {
				var newText = $(this).children('option:selected').text();
			}

			if (currentVal.length > 0 && !$(this).hasClass('delimited')) {
				if (newText.toUpperCase() == newText) {
					newText = ', ' + newText;
				} else {
					newText = ', ' + newText.charAt(0).toLowerCase() + newText.slice(1);
				}
			} else if (currentVal.length == 0 && $(this).hasClass('delimited')) {
				newText = newText.charAt(0).toUpperCase() + newText.slice(1);
			} else if ($(this).hasClass('delimited') && currentVal.slice(-1) != ' ') {
				newText = ' ' + newText;
			}

			target.val(currentVal + newText);
			target.trigger('autosize');

			$(this).val('');
		}
	});

    $(this).delegate('#js-event-audit-trail-btn', 'click', function () {
        $("#js-event-audit-trail").toggle();
        $(this).toggleClass("active");
    });

	// Handle form fields that have linked fields to show/hide
	$(this).on('change', 'select.linked-fields', function() {

		var fields = $(this).data('linked-fields').split(',');
		var values = $(this).data('linked-values').split(',');

		if ($(this).hasClass('MultiSelectList')) {
			var element_name = $(this).parent().prev('input').attr('name').replace(/\[.*$/,'');
		} else {
			var element_name = $(this).attr('name').replace(/\[.*$/,'');

			for (var i in fields) {
				hide_linked_field(element_name,fields[i]);
			}
		}

		if (inArray($(this).children('option:selected').text(),values)) {
			var vi = arrayIndex($(this).children('option:selected').text(),values);

			for (var i in fields) {
				if (values.length == 1 || i == vi) {
					show_linked_field(element_name,fields[i],i==0);
				}
			}
		}
	});

	$(this).on('click', 'input[type="radio"].linked-fields', function() {
		var element_name = $(this).attr('name').replace(/\[.*$/,'');

		var fields = $(this).data('linked-fields').split(',');
		var values = $(this).data('linked-values').split(',');

		if (inArray($(this).parent().text().trim(),values)) {
			for (var i in fields) {
				show_linked_field(element_name,fields[i],i==0);
			}
		} else {
			for (var i in fields) {
				hide_linked_field(element_name,fields[i]);
			}
		}
	});

	$(this).on('click', 'input[type="checkbox"].linked-fields', function() {
		var element_name = $(this).attr('name').replace(/\[.*$/,'');

		var fields = $(this).data('linked-fields').split(',');

		if ($(this).is(':checked')) {
			for (var i in fields) {
				show_linked_field(element_name,fields[i],i==0);
			}
		} else {
			for (var i in fields) {
				hide_linked_field(element_name,fields[i]);
			}
		}
	});

    $(this).on('click', '.js-remove-element', function (e) {
        e.preventDefault();
        var parent = $(this).parent().parent();
        var parent_id = parent.data('element-type-id');
        var children_elements = $('section[data-element-parent-id="'+parent_id+'"]');
        removeElement(parent);
        for (var i=0; i < children_elements.length; i++){
          removeElement($(children_elements[i]));
        }
    });

    $(this).on('click', '.js-add-comments', function (e) {
        e.preventDefault();
        var container = $(this).attr('data-input');
        $(container).show();
        $(this).hide();
    });

	$(this).on('mouseout', '.js-has-tooltip', function (e) {
    $('body').find( ".oe-tooltip" ).remove();
  });

  $(this).on('mouseover', '.js-has-tooltip', function (e) {
    var text = $(this).data('tooltip-content');
    var offset = $(this).offset();
    var leftPos = offset.left - 94; // tooltip is 200px (and center on the icon)

    // add, calculate height then show (remove 'hidden')
    var tip = $("<div></div>", {
      "class": "oe-tooltip",
      "style": "position:fixed; left:" + leftPos + "px; top:0;"
    });

    // add the tip:
    tip.html(text);
    $('body').append(tip);
    var tooltip = $('.oe-tooltip');
    // update position and show
    var top = offset.top - tooltip.height() - 20;
    tooltip.css({"top": top + "px"});

  });
});

function WidgetSlider() {if (this.init) this.init.apply(this, arguments); }

WidgetSlider.prototype = {
	init : function(params) {
		for (var i in params) {
			this[i] = params[i];
		}

		var thiz = this;

		$(document).ready(function() {
			thiz.bindElement();
		});
	},

	bindElement : function() {
		var thiz = this;

		$('#'+this.range_id).change(function() {
			thiz.handleChange($(this));
		});
	},

	handleChange : function(element) {
		var val = element.val();

		for (var value in this.remap) {
			if (val == value) {
				val = this.remap[value];
			}
		}

		if (this.null.length >0) {
			if (val.match(/\./)) {
				val = String(parseFloat(val) - 1);
			} else {
				val = String(parseInt(val) - 1);
			}
		}

		var min = $('#'+this.range_id).attr('min');

		if (min.match(/\./)) {
			min = parseFloat(min);
		} else {
			min = parseInt(min);
		}

		if (val < min && this.null.length >0) {
			val = this.null;
		} else {
			if (this.force_dp) {
				if (!val.match(/\./)) {
					val += '.';
					for (var i in this.force_dp) {
						val += '00';
					}
				} else {
					var dp = val.replace(/^.*?\./,'');

					while (dp.length < this.force_dp) {
						dp += '0';
						val += '0';
					}

					while (dp.length > this.force_dp) {
						dp = dp.replace(/.$/,'');
						val = val.replace(/.$/,'');
					}
				}
			}

			if (this.prefix_positive && parseFloat(val) >0) {
				var val = this.prefix_positive + val;
			}
		}

		$('#'+this.range_id+'_value_span').text(val+this.append);
	}
}

function WidgetSliderTable() {if (this.init) this.init.apply(this, arguments); }

WidgetSliderTable.prototype = {
	init : function(params) {
		for (var i in params) {
			this[i] = params[i];
		}

		var thiz = this;

		$(document).ready(function() {
			thiz.bindElement();
		});
	},

	bindElement : function() {
		var thiz = this;

		$('#'+this.range_id).change(function() {
			thiz.handleChange($(this));
		});
	},

	handleChange : function(element) {
		var val = element.val();

		$('#'+this.range_id+'_value_span').text(this.data[val]);
	}
}

function show_linked_field(element_name,field_name,focus)
{
	$('fieldset#'+element_name+'_'+field_name).show();
	$('#div_'+element_name+'_'+field_name).show();
	if (focus) {
		$('#'+element_name+'_'+field_name).focus();
	}
}

function hide_linked_field(element_name,field_name)
{
	$('fieldset#'+element_name+'_'+field_name).hide();
	$('#div_'+element_name+'_'+field_name).hide();

	$('input[name="'+element_name+'['+field_name+']"][type="radio"]').removeAttr('checked');
	$('input[name="'+element_name+'['+field_name+']"][type="text"]').val('');
	$('select[name="'+element_name+'['+field_name+']"]').val('');

	if ($('#'+field_name).hasClass('MultiSelectList')) {
		$('.multi-select-remove[data-name="'+field_name+'[]"]').map(function() {
			$(this).click();
		});
	}
}

function scrollToElement(element) {
    $('html, body').animate({
        scrollTop: parseInt(element.offset().top - 100)
    }, 1);
}

/**
 * Sets ups all the event listeners for adders
 * @param adderDiv div(or any other element) that is the adder
 * @param selectMode can more than one item be selected per list
 * 	- multi: Multiple elements in a list can be selected simultaneously
 * 	- single: When a second item in a list is selected the rest are deselected (in that one list)
 * 	- return: Immediately return and call the callback with selected item
 * @param callback function to call when the adder exits
 * @param openButtons divs that on click will open the adderDiv
 * @param addButtons divs that on click will close the adder and call the callback
 * @param closeButtons divs that on click will close the adderDiv without callback
 */
function setUpAdder(adderDiv = null, selectMode = 'single', callback = null, openButtons = null, addButtons = null, closeButtons = null){
    if (adderDiv === null){
        console.warn('no div sent to setUpAdder');
        return;
    }

    if (openButtons !== null){
        openButtons.click(function showAdder() {
            adderDiv.show();
        });
    }

    if(addButtons !== null){
        addButtons.click(function closeAndAdd(){
            adderDiv.hide();
            callback();
        });
    }

    if(closeButtons !== null){
        closeButtons.click(function closeAdder() {
            adderDiv.hide();
        });
    }

    //set up select class on clicks
    if(selectMode === 'return'){
        adderDiv.find('li').click(function () {
			$(this).addClass('selected');
			adderDiv.hide();
			callback($(this));
        });
	} else {
        adderDiv.find('li').click(function () {
            if(!$(this).hasClass('selected')){
                if(selectMode !== 'multi'){
                    $(this).parent('ul').find('li').removeClass('selected');
                }
                $(this).addClass('selected');
            }else {
                $(this).removeClass('selected');
            }
        });
	}
}