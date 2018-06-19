<?php foreach (OEModule\OphCoCvi\models\OphCoCvi_ClinicalInfo_Disorder_Section::model()
             ->findAll('`active` = ?', array(1)) as $disorder_section) {
    $comments = OEModule\OphCoCvi\models\Element_OphCoCvi_ClinicalInfo_Disorder_Section_Comments::model()
        ->getDisorderSectionComments($disorder_section->id, $element->id); ?>
        <div class="element full priority eye-divider">
          <header class="element-header">
            <h3><?php echo $disorder_section->name; ?></h3>
          </header>
              <div class="element-data element-eyes">
                    <?php foreach(['left' => 'right', 'right' => 'left'] as $page_side => $eye_side){ ?>
                      <div class="element-eye <?= $eye_side; ?>-eye column">
                          <?php $this->renderPartial('view_Element_OphCoCvi_ClinicalInfo_Disorder_Assignment_Disorders_Side', array(
                              'side' => $eye_side,
                              'element' => $element,
                              'disorder_section' => $disorder_section,
                          )) ?>
                      </div>
                    <?php } ?>
                </div>
        </div>
        <?php
        if ($disorder_section->comments_allowed == 1) {
            if ($comments != '') { ?>
                <fieldset class="row">
                    <legend class="cols-4 column">
                        <?php echo $disorder_section->comments_label; ?>
                    </legend>
                    <div class="cols-8 column">
                        <?php echo $comments; ?>
                    </div>
                </fieldset>
            <?php }
        }
} ?>
