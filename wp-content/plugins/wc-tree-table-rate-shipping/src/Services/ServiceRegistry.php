<?php
namespace Trs\Services;

use LogicException;


class ServiceRegistry
{
    public function install(IService $service)
    {
        $service->install($this);

        if (!$this->registered($service)) {
            throw new LogicException('Service did not register during install');
        }
    }

    public function register(IService $service)
    {
        $serviceId = $this->serviceId($service);

        if (isset($this->services[$serviceId])) {
            throw new LogicException("Service #{$serviceId} is already installed");
        }

        $this->services[$serviceId] = $service;
    }

    public function registered(IService $service)
    {
        return isset($this->services[$this->serviceId($service)]);
    }


    private $services = array();

    private function serviceId(IService $service)
    {
        return get_class($service);
    }
}