<?php echo "<?php\n"?>
/**
 * OpenEyes.
 *
 * (C) OpenEyes Foundation, 2019
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @link http://www.openeyes.org.uk
 *
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2019, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/agpl-3.0.html The GNU Affero General Public License V3.0
 */
<?php echo "?>\n"?>

<?php echo "<?php\n"?>$this->beginContent('//patient/event_container');<?php echo "?>\n"?>

<div id="delete_event">
    <h3>Delete event</h3>
    <div class="alert-box alert with-icon">
        <strong>WARNING: This will permanently delete the event and remove it from view.<br><br>THIS ACTION CANNOT BE UNDONE.</strong>
    </div>
    <?php echo '<?php'?> $this->displayErrors($errors)<?php echo "?>\n"?>
    <div style="width:300px; margin-bottom: 0.6em;">
        <p>Reason for deletion:</p>
        <?php echo '<?php'?> echo CHtml::textArea('delete_reason','')<?php echo "?>\n"?>
    </div>
    <p>
        <strong>Are you sure you want to proceed?</strong>
    </p>
    <?php echo "<?php\n"?>
        echo CHtml::form(array('Default/delete/'.$this->event->id), 'post', array('id' => 'deleteForm'));
        echo CHtml::hiddenField('event_id', $this->event->id);
    <?php echo "?>\n"?>
    <button type="submit" class="warning" id="et_deleteevent" name="et_deleteevent">
        Delete event
    </button>
    <button type="submit" class="secondary" id="et_canceldelete" name="et_canceldelete">
        Cancel
    </button>
  <i class="spinner" title="Loading..." style="display: none;"></i>
    <?php echo '<?php'?> echo CHtml::endForm()<?php echo "?>\n"?>
</div>

<?php echo '<?php'?> $this->endContent()<?php echo '?>'?>
