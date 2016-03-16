<?php

/**
 * Created by PhpStorm.
 * User: peter
 * Date: 19/02/16
 * Time: 16:09
 */
class PcrRiskReport extends Report implements ReportInterface
{
    protected $graphConfig = array(
        'chart' => array('renderTo' => '', 'type' => 'spline'),
        'title' => array('text' => 'PCR Rate (risk adjusted)'),
        'xAxis' => array(
            'title' => array('text' => 'No. Operations')
        ),
        'yAxis' => array(
            'title' => array('text' => 'PCR Rate'),
            'plotLines' => array(array(
                'value' => 0,
                'color' => 'black',
                'dashStyle' => 'shortdash',
                'width' => 1,
                'label' => array('text' => 'Average')
            )),
            'max' => 30
        ),
        'tooltip' => array(
            'headerFormat' => '<b>PCR Risk</b><br>',
            'pointFormat' => '<i>Operations</i>: {point.x} <br /> <i>PCR Avg</i>: {point.y:.2f}'
        ),
        'legend' => array(
            'align' => 'right',
            'verticalAlign' => 'top',
            'layout' => 'vertical',
            'x' => 0,
            'y' => 50,
            'floating' => true,
            'borderWidth' => 1
        ),
        'plotOptions' => array('spline' => array('marker' => array(
            'enabled' => false,
            'states' => array('hover' => array('enabled' => false))
        )))
    );

    /**
     * @param $surgeon
     * @param $dateFrom
     * @param $dateTo
     * @return CDbDataReader|mixed
     */
    protected function queryData($surgeon, $dateFrom, $dateTo)
    {
        $this->command->select('ophtroperationnote_cataract_complications.name as complication, pcr_risk as risk')
            ->from('et_ophtroperationnote_cataract')
            ->join('event', 'et_ophtroperationnote_cataract.event_id = event.id')
            ->join('et_ophtroperationnote_surgeon', 'et_ophtroperationnote_surgeon.event_id = event.id')
            ->leftJoin('ophtroperationnote_cataract_complication', 'et_ophtroperationnote_cataract.id = ophtroperationnote_cataract_complication.cataract_id')
            ->leftJoin('ophtroperationnote_cataract_complications', 'ophtroperationnote_cataract_complications.id = ophtroperationnote_cataract_complication.complication_id')
            ->where('surgeon_id = :surgeon', array('surgeon' => $surgeon));

        if ($dateFrom) {
            $this->command->andWhere('event.event_date > :dateFrom', array('dateFrom' => $dateFrom));
        }

        if ($dateTo) {
            $this->command->andWhere('event.event_date < :dateTo', array('dateTo' => $dateTo));
        }

        return $this->command->queryAll();
    }

    /**
     * @return array
     */
    public function dataSet()
    {
        $data = $this->queryData($this->surgeon, $this->from, $this->to);

        $total = 0;
        $pcrCases = 0;
        $pcrRiskTotal = 0;
        $adjustedPcrRate = 0;

        foreach($data as $case){
            $total++;
            if(isset($case['complication']) && $case['complication'] === 'PC rupture with vitreous loss' || $case['complication'] === 'PC rupture no vitreous loss'){
                $pcrCases++;
            }
            if(isset($case['risk'])){
                $pcrRiskTotal += $case['risk'];
            }
        }

        if($total !== 0 && (int)$pcrRiskTotal !== 0){
            $adjustedPcrRate = (($pcrCases / $total) / ($pcrRiskTotal / $total)) * $this->average();
        }

        return array(array($total, $adjustedPcrRate));

    }

    /**
     * @return string
     */
    public function seriesJson()
    {
        $this->series = array(
            array(
                'name' => 'Current Surgeon',
                'type' => 'scatter',
                'data' => $this->dataSet()
            ),
            array(
                'name' => 'Upper 98%',
                'data' => $this->upper98(),
                'color' => 'red',
            ),
            array(
                'name' => 'Upper 95%',
                'data' => $this->upper95(),
                'color' => 'green',
            )
        );

        return json_encode($this->series);
    }

    /**
     * @return string
     */
    public function graphConfig()
    {
        $this->graphConfig['yAxis']['plotLines'][0]['value'] = $this->average();
        $this->graphConfig['chart']['renderTo'] = $this->graphId();

        return json_encode(array_merge_recursive($this->globalGraphConfig, $this->graphConfig));
    }

    /**
     * @return array
     */
    protected function upper98()
    {
        return array(
            array(100, 15.67042149),
            array(200, 8.80658139),
            array(300, 6.73926036),
            array(400, 5.73078072),
            array(500, 5.12580471),
            array(600, 4.71852291),
            array(700, 4.42342034),
            array(800, 4.1984406),
            array(900, 4.02041122),
            array(1000, 3.87547218),
        );
    }

    /**
     * @return array
     */
    protected function upper95()
    {
        return array(
            array(100, 7.58459394),
            array(200, 5.14002939),
            array(300, 4.31373999),
            array(400, 3.88317448),
            array(500, 3.61334183),
            array(600, 3.4258317),
            array(700, 3.28661537),
            array(800, 3.1783895),
            array(900, 3.09136168),
            array(1000, 3.01954389),
        );
    }

    /**
     * @return float
     */
    protected function average()
    {
        return 1.95;
    }
}