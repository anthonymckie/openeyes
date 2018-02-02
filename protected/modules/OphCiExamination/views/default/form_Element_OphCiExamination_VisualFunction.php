<?php
/**
 * OpenEyes.
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2013
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @link http://www.openeyes.org.uk
 *
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2011-2013, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/agpl-3.0.html The GNU Affero General Public License V3.0
 */
?>

<div class="element-fields flex-layout full-width element-eyes ">
	<?php echo $form->hiddenInput($element, 'eye_id', false, array('class' => 'sideField')); ?>
	<div class="element-eye right-eye left side <?php if (!$element->hasRight()) { ?> inactive <?php } ?> " data-side="right">
		<div class="active-form field-row flex-layout">
      <a class="remove-side"><i class="oe-i remove-circle small"></i></a>
      <div class="cols-9">
				<div class="cols-full">
					<?php echo $form->radioButtons($element, 'right_rapd', array(
                            0 => 'Not Checked',
                            1 => 'Yes',
                            2 => 'No',
                        ),
                        ($element->right_rapd !== null) ? $element->right_rapd : 0,
                        false,
                        false,
                        false,
                        false,
                        array(
                            'text-align' => 'right',
                            'nowrapper' => false,
                        ));
                    ?>
				</div>
        <div class="field-row-pad-top" style="display: none;">
            <?php echo $form->textArea($element, 'right_comments', array('rows' => 1, 'nowrapper' => true), false, array('placeholder' => $element->getAttributeLabel('right_comments'))) ?>
        </div>
			</div>
      <!-- Cols -->
      <div class="flex-item-bottom">
        <button class="button js-add-comments" data-input="#pupils-right-comments">
          <i class="oe-i comments small-icon"></i>
        </button>
        <button class="button hint green js-add-select-search" type="button">
          <i class="oe-i plus pro-theme"></i>
        </button>
      </div>
      <!--flex bottom-->
		</div>
    <!-- active form-->
		<div class="inactive-form" style="display: none">
			<div class="add-side">
				<a href="#">
					Add right side <span class="icon-add-side"></span>
				</a>
			</div>
		</div>
	</div>
	<div class="element-eye left-eye right side <?php if (!$element->hasLeft()) { ?> inactive <?php } ?>" data-side="left">
		<div class="active-form field-row flex-layout">
      <a class="remove-side"><i class="oe-i remove-circle small"></i></a>
      <div class="cols-9">
				<div class="cols-full">
					<?php echo $form->radioButtons($element, 'left_rapd', array(
                            0 => 'Not Checked',
                            1 => 'Yes',
                            2 => 'No',
                        ),
                        ($element->left_rapd !== null) ? $element->left_rapd : 0,
                        false,
                        false,
                        false,
                        false,
                        array(
                            'text-align' => 'right',
                            'nowrapper' => false,
                        ));
                    ?>
				</div>
        <div class="field-row-pad-top" style="display:none">
            <?php echo $form->textArea($element, 'left_comments', array('rows' => 1, 'nowrapper' => true), false, array('placeholder' => $element->getAttributeLabel('left_comments')) ); ?>
        </div>
			</div>
      <!-- Cols -->
      <div class="flex-item-bottom">
        <button class="button js-add-comments" data-input="#pupils-right-comments">
          <i class="oe-i comments small-icon"></i>
        </button>
        <button class="button hint green js-add-select-search" type="button">
          <i class="oe-i plus pro-theme"></i>
        </button>
      </div>
      <!--flex bottom-->
		</div>
		<div class="inactive-form" style="display: none">
			<div class="add-side">
				<a href="#">
					Add left side <span class="icon-add-side"></span>
				</a>
			</div>
		</div>
	</div>
</div>