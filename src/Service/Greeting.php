<?php

namespace App\Service;

use phpDocumentor\Reflection\Types\String_;
use Psr\Log\LoggerInterface;

class Greeting
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function greet(string $name): string
    {
        $this->logger->info("Greeted $name");
        return "Hello $name";
    }
}