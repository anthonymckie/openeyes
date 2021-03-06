<?php

    /**
     * Class OphDrPrescription_ItemTest
     * @property OphDrPrescription_Item $item
     */
class OphDrPrescription_ItemTest extends CDbTestCase
{
    private $items = array();
    protected $fixtures = array(
        'ophdrprescription_items' => OphDrPrescription_Item::class,
        'ophdrprescription_item_tapers' => OphDrPrescription_ItemTaper::class,
        'drug_routes' => DrugRoute::class,
        'drug_frequencys' => DrugFrequency::class,
        'drug_durations' => DrugDuration::class,
    );

    public function setUp()
    {
        parent::setUp();
        $this->items[] = $this->ophdrprescription_items('prescription_item1');
        $this->items[] = $this->ophdrprescription_items('prescription_item2');
        $this->items[] = $this->ophdrprescription_items('prescription_item4');
        $this->items[] = $this->ophdrprescription_items('prescription_item6');
    }

    public function getLineUsage()
    {
        return array(
            'Single taper' => array(
                'lines' => 9,
                'index' => 0,
            ),
            'No taper' => array(
                'lines' => 6,
                'index' => 1,
            ),
            'Multiple tapers' => array(
                'lines' => 34,
                'index' => 2,
            ),
            'Simple duration' => array(
                'lines' => 5,
                'index' => 3,
            )
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->item, $this->tapered_item);
    }

    /**
     * @covers OphDrPrescription_Item::getAttrLength()
     * @covers OphDrPrescription_Item::fpTenLinesUsed()
     */
    public function testGetAttrLength()
    {
        $settings = new SettingMetadata();
        $max_lines = $settings->getSetting('prescription_form_format') === 'WP10'
            ? OphDrPrescription_Item::MAX_WPTEN_LINE_CHARS : OphDrPrescription_Item::MAX_FPTEN_LINE_CHARS;
        foreach ($this->items as $item) {
            $drug_label = $item->drug->label;
            $dose = 'Dose: ' . (is_numeric($item->dose) ? "{$item->dose} {$item->drug->dose_unit}" : $item->dose)
                . ', ' . $item->route->name . ($item->route_option ? ' (' . $item->route_option->name . ')' : null);
            $frequency = "Frequency: {$item->frequency->long_name} for {$item->duration->name}";

            $item->fpTenLinesUsed();
            $actual = $item->getAttrLength('item_drug');
            $this->assertEquals(ceil(strlen($drug_label) / $max_lines), $actual, "Drug label has $actual lines, expected 1.");

            $actual = $item->getAttrLength('item_dose');
            $this->assertEquals(ceil(strlen($dose) / $max_lines), $actual, "Dose has $actual lines, expected 1.");

            $actual = $item->getAttrLength('item_frequency');
            $this->assertEquals(ceil(strlen($frequency) / $max_lines), $actual, "Frequency has $actual lines, expected 1.");

            foreach ($item->tapers as $index => $taper) {
                $taper_dose = 'Dose: ' . (is_numeric($taper->dose) ? ($taper->dose . ' ' . $item->drug->dose_unit) : $taper->dose)
                    . ', ' . $item->route->name . ($item->route_option ? ' (' . $item->route_option->name . ')' : null);
                $taper_frequency = "Frequency: {$taper->frequency->long_name} for {$taper->duration->name}";
                $actual = $item->getAttrLength("taper{$index}_label");
                $this->assertEquals(1, $actual, "Taper $index label has $actual lines, expected 1.");
                $actual = $item->getAttrLength("taper{$index}_dose");
                $this->assertEquals(ceil(strlen($taper_dose) / $max_lines), $actual, "Taper $index dose has $actual lines, expected 1.");
                $actual = $item->getAttrLength("taper{$index}_frequency");
                $this->assertEquals(
                    ceil(strlen($taper_frequency) / $max_lines),
                    $actual,
                    "Taper $index frequency has $actual lines, expected 1."
                );
            }
        }
    }

    /**
     * @covers OphDrPrescription_Item::fpTenLinesUsed()
     * @dataProvider getLineUsage
     * @param $lines
     * @param $index
     */
    public function testFpTenLinesUsed($lines, $index)
    {
        $actual = $this->items[$index]->fpTenLinesUsed();
        $this->assertEquals($lines, $actual, "Item has $actual lines, expected {$lines}.");
    }

    /**
     * @covers OphDrPrescription_Item::fpTenDose
     */
    public function testFpTenDose()
    {
        foreach ($this->items as $item) {
            $expected = strtoupper('Dose: ' . (is_numeric($item->dose) ? "{$item->dose} {$item->drug->dose_unit}" : $item->dose)
                . ', ' . $item->route->name . ($item->route_option ? ' (' . $item->route_option->name . ')' : null));

            $actual = $item->fpTenDose();

            $this->assertEquals($expected, $actual);
        }
    }

    /**
     * @covers OphDrPrescription_Item::fpTenFrequency
     */
    public function testFpTenFrequency()
    {
        foreach ($this->items as $index => $item) {
            if ($index === 3) {
                $duration = strtoupper($item->duration->name);
                $expected = strtoupper("FREQUENCY: {$item->frequency->long_name} {$duration}");
            } else {
                $expected = strtoupper("FREQUENCY: {$item->frequency->long_name} FOR {$item->duration->name}");
            }
            $actual = $item->fpTenFrequency();

            $this->assertEquals($expected, $actual);
        }
    }
}
