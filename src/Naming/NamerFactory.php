<?php

namespace Atom\Uploader\Naming;


use Atom\Uploader\Exception\NoSuchNamingException;

class NamerFactory
{
    private $namingMap;

    public function __construct() {
        $this->namingMap = [];
    }

    public function addNamer($strategy, INamer $namingService) {
        $this->namingMap[$strategy] = $namingService;
    }

    /**
     * @param $strategy string
     *
     * @throws NoSuchNamingException
     * @return INamer
     */
    public function getNamer($strategy) {
        if (!isset($this->namingMap[$strategy])) {
            throw new NoSuchNamingException(sprintf('The naming strategy "%s" does not exist', $strategy));
        }

        return $this->namingMap[$strategy];
    }
}