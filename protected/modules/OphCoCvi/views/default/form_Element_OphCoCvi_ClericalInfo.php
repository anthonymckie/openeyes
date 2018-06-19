<?php
/**
 * OpenEyes
 *
 * (C) OpenEyes Foundation, 2016
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2016, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/agpl-3.0.html The GNU Affero General Public License V3.0
 */

?>
<?php
if ($this->checkClericalEditAccess()) {
    $model = OEModule\OphCoCvi\models\Element_OphCoCvi_ClericalInfo::model();
?>
    <div class="element-fields full-width">
      <div class="data-group">
        <table class="cols-full">
          <colgroup>
            <col class="cols-6">
            <col class="cols-3">
          </colgroup>
          <tbody>
          <?php foreach ($this->getPatientFactors() as $factor) { ?>
            <tr class="col-gap">
              <td>
                <label> <?php echo $factor->name ?> </label>
                  <?php
                  $field_base_name = CHtml::modelName($element) . "[patient_factors][{$factor->id}]";
                  $factor_field_name = "{$field_base_name}[is_factor]";
                  $answer = $element->getPatientFactorAnswer($factor);
                  $value = $answer ? $answer->is_factor : null;
                  if (!is_null($value)) {
                      $value = (integer) $value;
                  }
                  $comments = $answer ? $answer->comments : null;
                  ?>
              </td>
                <?php if ($factor->require_comments) {
                  $comment_button_id = CHtml::modelName($element) . '_patient_factors_' . $factor->id . '_comment_button'; ?>
                  <td>
                    <div class="cols-full ">
                      <button
                          id="<?= $comment_button_id ?>"
                          class="button js-add-comments" style="display: <?php if ($comments): ?>none<?php endif; ?>"
                          data-comment-container="#<?= CHtml::modelName($element) . '_patient_factors_' . $factor->id . '_comments'; ?>">
                        <i class="oe-i comments small-icon"></i>
                      </button>
                        <?php echo CHtml::textArea("{$field_base_name}[comments]", $comments, array(
                            'class' => 'js-comment-field js-comment-container',
                            'rows' => 2,
                            'style' => 'display:' . (($comments) ? '' : 'none'),
                            'placeholder' => 'Comments',
                            'data-comment-button' => '#' . $comment_button_id)); ?>
                    </div>
                  </td>
                <?php } else { ?>
                  <td></td>
                <?php } ?>
              <td>
                <label class="inline highlight">
                    <?php echo CHtml::radioButton($factor_field_name, ($value === 1), array('id' => $factor_field_name . '_1', 'value' => 1)) ?>
                  Yes
                </label>
                <label class="inline highlight">
                    <?php echo CHtml::radioButton($factor_field_name, ($value === 0), array('id' => $factor_field_name . '_0', 'value' => 0)) ?>
                  No
                </label>
                <label class="inline highlight">
                    <?php echo CHtml::radioButton($factor_field_name, ($value === 2), array('id' => $factor_field_name . '_2', 'value' => 2)) ?>
                  Unknown
                </label>
              </td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="flex-layout flex-top col-gap">
        <div class="cols-6 data-group">
          <table class="cols-full last-left">
            <colgroup>
              <col class="cols-6">
              <col class="cols-6">
            </colgroup>
            <tbody>
            <tr>
              <td>
                  <?php echo $element->getAttributeLabel('employment_status_id')?>
              </td>
              <td>
                  <?php echo $form->dropDownList(
                      $element,
                      'employment_status_id',
                      CHtml::listData(
                          OEModule\OphCoCvi\models\OphCoCvi_ClericalInfo_EmploymentStatus::model()->findAll(
                              '`active` = ?',
                              array(1),
                              array('order' => 'display_order asc')), 'id', 'name'),
                      array('empty' => '- Please select -', 'nowrapper' => true),
                      false,
                      array()) ?>
              </td>
            </tr>
            <tr>
              <td>
                  <?php echo $element->getAttributeLabel('preferred_info_fmt_id')?>
              </td>
              <td>
                  <?php echo $form->dropDownList($element, 'preferred_info_fmt_id', CHtml::listData(OEModule\OphCoCvi\models\OphCoCvi_ClericalInfo_PreferredInfoFmt::model()->findAll(
                      array('order' => 'display_order asc')),
                      'id',
                      'name'
                  ),
                      array('empty' => '- Please select -', 'nowrapper' => true),
                      false,
                      array()) ?>
                  <?php
                  $preferredInfoFormatEmail = OEModule\OphCoCvi\models\OphCoCvi_ClericalInfo_PreferredInfoFmt::model()->findAll('`require_email` = ?', array(1));
                  ?>
                <div class="cols-6">
                    <?php echo $form->textField($element, 'info_email', array('size' => '20'), false, array('label' => 6, 'field' => 6)) ?>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                  <?php echo $element->getAttributeLabel('contact_urgency_id')?>
              </td>
              <td>
                  <?php echo $form->dropDownList($element,
                      'contact_urgency_id',
                      CHtml::listData(OEModule\OphCoCvi\models\OphCoCvi_ClericalInfo_ContactUrgency::model()->findAll(
                          array('order' => 'display_order asc')
                      ),
                          'id',
                          'name'
                      ),
                      array(
                          'empty' => '- Please select -',
                          'nowrapper' => true,
                          'class' => 'cols-full'
                      ),
                      false,
                      array('label' => 6, 'field' => 6)) ?>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
        <div class="cols-6 data-group">
          <table class="cols-full last-left">
            <colgroup>
              <col class="cols-6">
              <col class="cols-6">
            </colgroup>
            <tbody>
              <tr>
                <td>
                    <?php echo $element->getAttributeLabel('preferred_language_id')?>
                </td>
                <td>
                    <?php echo $form->dropDownList($element, 'preferred_language_id',
                        CHtml::listData(Language::model()->findAll(array('order' => 'name asc')), 'id', 'name') + array('0'=>'Other'), array('nowrapper'=> true), false, array()) ?>
                    <?php echo $form->textField($element, 'preferred_language_text', array('size' => '20',), false, array('label' => 6, 'field' => 6)) ?>
                </td>
              </tr>
            <tr>
              <td>
                  <?php echo $element->getAttributeLabel('social_service_comments')?>
              </td>
              <td>
                  <?php echo $form->textArea($element, 'social_service_comments', array('nowrapper' => true), false, array()) ?>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

<?php } else {
    $this->renderPartial('view_Element_OphCoCvi_ClericalInfo', array('element' => $element));
}

