<?php
App::uses('CakeTime', 'Utility');

class SalesreportsController extends AdminAppController
{
    public $components = array('HighCharts.HighCharts');

    public function index()
    {
        $cond = array();
        $date = "";
        $isSearch = false;
        $this->loadModel('Package');
        $this->set('packageId', $this->Package->find('list', array('fields' => array('id', 'name'))));
        if ($this->request->is('post')) {
            $isSearch = true;
            if ($this->request->data['Salesreport']['package_id'])
                $cond[] = array('PackagesPayment.package_id' => $this->request->data['Salesreport']['package_id']);
            if ($this->request->data['Salesreport']['date'])
                $date = $this->request->data['Salesreport']['date']['year'];
        }
        for ($i = 0; $i < 12; ++$i) {
            if (strlen($date) > 0) {
                $year = $date;
                $searchDate = $year . '-' . (12 - $i) . '-01';
                $month = CakeTime::format('m', $searchDate);
                $monthName = CakeTime::format('M Y', $searchDate);
            } else {
                $year = CakeTime::format("-$i months", '%Y', $this->siteTimezone);
                $month = CakeTime::format("-$i months", '%m', $this->siteTimezone);
                $monthName = CakeTime::format("-$i months", '%B %Y', $this->siteTimezone);
            }
            $salesCount = $this->Salesreport->find('count', array('joins' => array(array('table' => 'packages_payments', 'alias' => 'PackagesPayment', 'type' => 'Inner', 'conditions' => array('Salesreport.id=PackagesPayment.payment_id'))),
                'conditions' => array('Salesreport.status' => 'Approved', 'MONTH(Salesreport.date)' => $month, 'YEAR(Salesreport.date)' => $year, $cond)));
            $earningArr = $this->Salesreport->find('all', array('joins' => array(array('table' => 'packages_payments', 'alias' => 'PackagesPayment', 'type' => 'Inner', 'conditions' => array('Salesreport.id=PackagesPayment.payment_id'))),
                'fields' => array('SUM(PackagesPayment.amount) AS earning'),
                'conditions' => array('Salesreport.status' => 'Approved', 'MONTH(Salesreport.date)' => $month, 'YEAR(Salesreport.date)' => $year, $cond),
            ));
            if ($earningArr[0][0]['earning'] == null)
                $earning = 0;
            else
                $earning = $earningArr[0][0]['earning'];
            $graphMonth[] = $monthName;
            $months[]['MonthArr'] = array('monthName' => $monthName, 'salesCount' => $salesCount, 'earning' => $earning);
            $performanceChartData[] = (float)$earning;
        }
        $graphMonth = array_reverse($graphMonth);
        $months = array_reverse($months);
        $currMonth = CakeTime::format('m', $this->siteTimezone);
        $currYear = CakeTime::format('Y', $this->siteTimezone);
        $totalSalesCount = $this->Salesreport->find('count', array('joins' => array(array('table' => 'packages_payments', 'alias' => 'PackagesPayment', 'type' => 'Inner', 'conditions' => array('Salesreport.id=PackagesPayment.payment_id'))),
            'conditions' => array('Salesreport.status' => 'Approved')));
        $earningArr = $this->Salesreport->find('all', array('joins' => array(array('table' => 'packages_payments', 'alias' => 'PackagesPayment', 'type' => 'Inner', 'conditions' => array('Salesreport.id=PackagesPayment.payment_id'))),
            'fields' => array('SUM(PackagesPayment.amount) AS earning'),
            'conditions' => array('Salesreport.status' => 'Approved')));
        $earningArr1 = $this->Salesreport->find('all', array('joins' => array(array('table' => 'packages_payments', 'alias' => 'PackagesPayment', 'type' => 'Inner', 'conditions' => array('Salesreport.id=PackagesPayment.payment_id'))),
            'fields' => array('SUM(PackagesPayment.amount) AS earning'),
            'conditions' => array('Salesreport.status' => 'Approved', 'MONTH(Salesreport.date)' => $currMonth, 'YEAR(Salesreport.date)' => $currYear)));
        $monthSalesCount = $this->Salesreport->find('count', array('joins' => array(array('table' => 'packages_payments', 'alias' => 'PackagesPayment', 'type' => 'Inner', 'conditions' => array('Salesreport.id=PackagesPayment.payment_id'))),
            'conditions' => array('Salesreport.status' => 'Approved', 'MONTH(Salesreport.date)' => $currMonth, 'YEAR(Salesreport.date)' => $currYear)));
        if ($earningArr[0][0]['earning'] == null)
            $totalEearning = 0;
        else
            $totalEearning = $earningArr[0][0]['earning'];
        if ($earningArr1[0][0]['earning'] == null)
            $earningMonth = 0;
        else
            $earningMonth = $earningArr1[0][0]['earning'];
        $tooltipFormatFunction = "function() { return '<b>'+ this.series.name +'</b><br/>'+ this.x +': '+''+ this.y ;}";
        $chartName = "My Chartdl";
        $mychart = $this->HighCharts->create($chartName, 'line');
        $this->HighCharts->setChartParams(
            $chartName,
            array(
                'renderTo' => "mywrapperdl",  // div to display chart inside
                'title' => __('Sales Earning'),
                'titleAlign' => 'center',
                'creditsEnabled' => FALSE,
                'xAxisLabelsEnabled' => TRUE,
                'xAxisCategories' => $graphMonth,
                'yAxisTitleText' => '',
                'tooltipEnabled' => TRUE,
                'tooltipFormatter' => $tooltipFormatFunction,
                'enableAutoStep' => FALSE,
                'plotOptionsShowInLegend' => TRUE,
            )
        );
        $series = $this->HighCharts->addChartSeries();
        $series->addName(__('Sales Report'))->addData(array_reverse($performanceChartData));
        $mychart->addSeries($series);
        $this->set('totalSalesCount', $totalSalesCount);
        $this->set('totalEearning', $totalEearning);
        $this->set('earningMonth', $earningMonth);
        $this->set('monthSalesCount', $monthSalesCount);
        $this->set('salesReport', $months);
        $this->set('isSearch', $isSearch);
    }
}
