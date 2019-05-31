<?php

namespace FOP\DeveloperTools\Grid;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

final class GridDataCollector extends DataCollector
{
    /**
     * @var Reporter the Grid data reporter.
     */
    private $reporter;

    public function __construct(Reporter $reporter)
    {
        $this->reporter = $reporter;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, Exception $exception = null)
    {
        $report = $this->reporter->generateReport();

        $this->data = [
            'grids' => $this->improveReport($report),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'fop.grids_collector';
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        $this->data = [];
    }

    public function getGrids()
    {
        return $this->data['grids'];
    }

    /**
     * @param array $report
     * @return array
     */
    private function improveReport(array &$report)
    {
        foreach ($report as &$reportEntry) {
            $reportEntry['data']['records'] = $this->cloneVar($reportEntry['data']['records']);
            foreach ($reportEntry['columns'] as $columnIndex => $column) {
                $reportEntry['columns'][$columnIndex]['options'] = $this->cloneVar($reportEntry['columns'][$columnIndex]['options']);
            }
        }

        return $report;
    }
}
