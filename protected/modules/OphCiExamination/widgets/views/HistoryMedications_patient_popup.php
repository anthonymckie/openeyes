<?php
/**
 * OpenEyes
 *
 * (C) OpenEyes Foundation, 2017
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later
 * version. OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details. You should have received a copy of the GNU General Public License along with OpenEyes in a file titled
 * COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2017, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */
?>
<link rel="stylesheet" type="text/css" href="<?= $this->getCssPublishedPath('medications.css') ?>">
<script type="text/javascript" src="<?= $this->getJsPublishedPath('HistoryMedications.js') ?>"></script>
<?php $el_id =  CHtml::modelName($element) . '_popup'; ?>

<?php if ($element && ($current || $stopped)) { ?>
    <div class="row" id="<?= $el_id ?>">
        <div class="large-2 column label">
            Medications
        </div>
        <div class="large-10 column data">
            <i>Current:</i> <?php if ($stopped) {?><a href="#" class="kind-toggle show" data-kind="stopped"><i class="fa fa-icon fa-history" aria-hidden="true"></i></a><?php } ?>
            <?php if ($current) {?>
                <table class="plain valign-top">
                <?php foreach ($current as $entry) { ?>
                    <tr>
                        <td><?= $entry->getDatesDisplay() ?></td>
                        <td><span class="laterality <?= $entry->getLateralityDisplay() ?>"><?= $entry->getLateralityDisplay() ?></span><strong><?= $entry->getMedicationDisplay() ?></strong>
                            <?php if ($entry->prescription_item) { ?>
                                <a href="<?= $this->getPrescriptionLink($entry) ?>"><span class="has-tooltip fa fa-eye" data-tooltip-content="View prescription"></span></a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </table>
            <?php } else { ?>
                No current medications.
            <?php } ?>
            <?php if ($stopped) { ?>
                <div class="row stopped-kind" style="display: none;">
                    <i>Stopped:</i> <a href="#" class="kind-toggle remove" data-kind="stopped"><i class="fa fa-icon fa-times" aria-hidden="true"></i></a>
                    <table class="plain valign-top">
                    <?php foreach ($stopped as $entry) { ?>
                        <tr>
                            <td><?= $entry->getDatesDisplay() ?></td>
                            <td><span class="laterality <?= $entry->getLateralityDisplay() ?>"><?= $entry->getLateralityDisplay() ?></span><strong><?= $entry->getMedicationDisplay() ?></strong>
                                <?php if ($entry->prescription_item) { ?>
                                    <a href="<?= $this->getPrescriptionLink($entry) ?>"><span class="has-tooltip fa fa-eye" data-tooltip-content="View prescription"></span></a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </table>
                </div>
            <?php } ?>

        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            new OpenEyes.OphCiExamination.HistoryMedicationsViewController({
                element: $('#<?= $el_id ?>')
            });
        });
    </script>
<?php } ?>
