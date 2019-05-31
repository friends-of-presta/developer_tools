<?php

namespace FOP\DeveloperTools\Grid;

use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollectionInterface;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnInterface;
use PrestaShop\PrestaShop\Core\Grid\Data\GridDataInterface;
use PrestaShop\PrestaShop\Core\Grid\Definition\GridDefinition;
use PrestaShop\PrestaShop\Core\Grid\GridInterface;
use ReflectionClass;

final class Reporter
{
    /**
     * @var array the list of rendered grid
     */
    private $grids = [];

    public function add(GridInterface $presentedGrid)
    {
        $this->grids[] = $presentedGrid;
    }

    public function generateReport()
    {
        $report = [];
        /** @var GridInterface $grid */
        foreach ($this->grids as $grid) {
            /** @var GridDefinition $definition */
            $definition = $grid->getDefinition();
            $report[$definition->getId()] = [
                'name' => $definition->getName(),
                'columns' => $this->generateGridColumns($definition->getColumns()),
                'data' => $this->generateGridData($grid->getData()),
            ];
        }

        return $report;
    }

    private function generateGridData(GridDataInterface $gridData)
    {
        return [
            'records' => $gridData->getRecords(),
            'query' => $gridData->getQuery(),
            'records_total' => $gridData->getRecordsTotal(),
        ];
    }

    private function generateGridColumns(ColumnCollectionInterface $columnCollection)
    {
        $columns = [];

        /** @var ColumnInterface $column */
        foreach ($columnCollection as $column) {
            $reflection = new ReflectionClass($column);
            $columns[] = [
                'id' => $column->getId(),
                'name' => $column->getName(),
                'type' => $column->getType(),
                'options' => $column->getOptions(),
                'column_path' => $reflection->getFileName(),
            ];
        }

        return $columns;
    }
}
