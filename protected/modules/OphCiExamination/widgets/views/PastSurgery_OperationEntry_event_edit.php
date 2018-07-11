<?php
/**
 * OpenEyes
 *
 * (C) OpenEyes Foundation, 2017
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2017, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/agpl-3.0.html The GNU Affero General Public License V3.0
 */

use OEModule\OphCiExamination\models\PastSurgery_Operation;

?>

<?php
if (!isset($values)) {
    $values = array(
        'id' => $op->id,
        'operation' => $op->operation,
        'side_id' => $op->side_id,
        'side_display' => $op->side ? $op->side->adjective : 'None',
        'date' => $op->date,
        'date_display' => $op->getDisplayDate(),
        'had_operation' => $op->had_operation,
    );
}
$required = isset($required) ? $required : false;

if (isset($values['date']) && strtotime($values['date'])) {
    list($sel_year, $sel_month, $sel_day) = explode('-', $values['date']);
} else {
    $sel_day = $sel_month = null;
    $sel_year = date('Y');
}

?>
<tr class="row-<?=$row_count;?><?php if($removable){ echo " read-only"; } ?>"
    <?php if($removable){ echo "data-key='{$row_count}'"; } ?>
>
    <td>

        <?php if(!$removable) :?>
            <?= $values['operation'] ?>
        <?php else:?>

            <input type="hidden" name="<?= $field_prefix ?>[id]" value="<?=$values['id'] ?>" />

            <?php if(!$required): ?>
                <?php echo CHtml::dropDownList(null, '',
                    CHtml::listData(CommonPreviousOperation::model()->findAll(
                        array('order' => 'display_order asc')), 'id', 'name'),
                    array('empty' => '- Select -', 'class' => $model_name . '_operations'))?>
                <br />
            <?php endif; ?>

            <?php $html_options = array(
                    'placeholder' => 'Select from above or type',
                    'autocomplete' => Yii::app()->params['html_autocomplete'],
                    'class' => 'common-operation',
                );
                if($required){
                    $html_options['readonly'] = true;
                }
            ?>
            <?php echo CHtml::textField($field_prefix . '[operation]', $values['operation'], $html_options); ?>

        <?php endif; ?>

    </td>

    <td id="<?= $model_name ?>_operations_<?=$row_count?>" class="past-surgery-entry has-operation">
        <label class="inline">
            <?php echo CHtml::radioButton($field_prefix . '[had_operation]', $posted_not_checked, array('value' => PastSurgery_Operation::$NOT_CHECKED)); ?>
            Not checked
        </label>
        <label class="inline">
            <?php echo CHtml::radioButton($field_prefix . '[had_operation]', $values['had_operation'] === (string) PastSurgery_Operation::$PRESENT, array('value' => PastSurgery_Operation::$PRESENT)); ?>
            yes
        </label>
        <label class="inline">
            <?php echo CHtml::radioButton($field_prefix . '[had_operation]', $values['had_operation'] === (string) PastSurgery_Operation::$NOT_PRESENT, array('value' => PastSurgery_Operation::$NOT_PRESENT)); ?>
            no
        </label>
    </td>

    <td class="<?= $model_name ?>_sides" style="white-space:nowrap">

        <?php if(!$removable) :?>
            <?= $values['side'] ?>
        <?php else:?>
         <input type="hidden" name="<?=$field_prefix?>[side_id]" value="<?=$values['side_id']; ?>" />

            <label class="inline">
                <input type="radio"
                       name="<?="side_group_name_$row_count"; ?>"
                       class="<?= $model_name ?>_previous_operation_side"
                       <?php if(empty($values['side_id'])): ?> checked <?php endif; ?>
                       value="" /> None
            </label>
            <?php foreach (Eye::model()->findAll(array('order' => 'display_order')) as $i => $eye) {?>
                <label class="inline">
                    <input
                        type="radio" name="<?="side_group_name_$row_count"; ?>"
                        class="<?= $model_name ?>_previous_operation_side"
                        value="<?php echo $eye->id?>"
                        <?php if($eye->id == $values['side_id']){ echo "checked"; }?>
                    />
                    <?php echo $eye->name ?>
                </label>
            <?php }?>
         <?php endif; ?>
    </td>
    <td>
        <?php if(!$removable) :?>
            <?=Helper::formatFuzzyDate($values['date']) ?>
        <?php else:?>

            <input type="hidden" name="<?= $field_prefix ?>[date]" value="<?=$values['date'] ?>" />

            <fieldset id="<?= $model_name ?>_fuzzy_date" class="row field-row fuzzy-date" style="padding:0">
                <?php $this->render('application.views.patient._fuzzy_date_fields', array('sel_day' => $sel_day, 'sel_month' => $sel_month, 'sel_year' => $sel_year)) ?>
            </fieldset>
        <?php endif; ?>
    </td>

    <?php if($removable && !$required) : ?>
        <td class="edit-column">
            <button class="button small warning remove">remove</button>
        </td>
    <?php elseif(!$required): ?>
        <td>read only <span class="has-tooltip fa fa-info-circle"
                            data-tooltip-content="This operation is recorded as an Operation Note event in OpenEyes and cannot be edited here"></span></td>
    <?php elseif($required): ?>
        <td>read only <span class="has-tooltip fa fa-info-circle"
                            data-tooltip-content="<?=$values['operation'];?> is mandatory to collect."></span></td>
    <?php endif; ?>

</tr>