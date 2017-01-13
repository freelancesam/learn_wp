<?php
namespace Trs\Core\Model;


class Customer
{
    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    private $id;
}