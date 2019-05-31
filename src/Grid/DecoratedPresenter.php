<?php

namespace FOP\DeveloperTools\Grid;

use PrestaShop\PrestaShop\Core\Grid\GridInterface;
use PrestaShop\PrestaShop\Core\Grid\Presenter\GridPresenterInterface;

final class DecoratedPresenter implements GridPresenterInterface
{
    /**
     * @var GridPresenterInterface the Core presenter
     */
    private $corePresenter;

    /**
     * @var Reporter the Grid Data Collector reporter
     */
    private $reporter;

    public function __construct(GridPresenterInterface $corePresenter, Reporter $reporter)
    {
        $this->corePresenter = $corePresenter;
        $this->reporter = $reporter;
    }

    /**
     * {@inheritdoc}
     */
    public function present(GridInterface $grid)
    {
        $this->reporter->add($grid);

        return $this->corePresenter->present($grid);
    }
}
