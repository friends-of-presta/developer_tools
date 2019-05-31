<?php

namespace FOP\DeveloperTools\Employee;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Exception;

class LoggedEmployeeDataCollector extends DataCollector
{

    public function __construct(Repository $employeeRepository)
    {
        $this->data = [
            'employees' => $employeeRepository->getAll(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, Exception $exception = null)
    {
        $this->data['route'] = $request->get('_route');
        $this->data['route_params'] = $request->get('_route_params');
        $this->data['route_params']['_switch_user'] = '_exit';
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        $this->data = [];
    }

    public function getName()
    {
        return 'fop.logged_employee_collector';
    }

    public function getRoute()
    {
        return [
            'route' => $this->data['route'],
            'route_params' => $this->data['route_params'],
        ];
    }

    public function getEmployees()
    {
        return $this->data['employees'];
    }
}
