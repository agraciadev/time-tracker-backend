<?php

namespace App\Infrastructure\Listener;

use App\Infrastructure\Attribute\HtmlConfig;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class HtmlListener
{

    private array $args = [];

    public function onKernelController(ControllerEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $controllers = $event->getController();

        if (!is_array($controllers)) {
            return;
        }

        $this->handleAnnotation($controllers);
    }

    private function handleAnnotation(iterable $controllers): void
    {
        list($controller, $method) = $controllers;

        try {
            $controller = new ReflectionClass($controller);
        } catch (ReflectionException $e) {
            throw new RuntimeException('Failed to read annotation!');
        }

        $this->handleClassAnnotation($controller);
        $this->handleMethodAnnotation($controller, $method);
    }

    private function getParamsFromAttributes(
        array $attributes
    ) {
        foreach ($attributes as $attribute) {
            if ($attribute->getName() == HtmlConfig::class) {
                $this->args = array_merge($this->args, $attribute->getArguments());
            }
        }
    }

    private function handleClassAnnotation(ReflectionClass $controller): void
    {

        $this->getParamsFromAttributes($controller->getAttributes());

        while ($controller->getParentClass()) {
            $controller = $controller->getParentClass();
            $this->getParamsFromAttributes($controller->getAttributes());
        }
    }

    private function handleMethodAnnotation(ReflectionClass $controller, string $method): void
    {
        $this->getParamsFromAttributes($controller->getMethod($method)->getAttributes());
    }

    /**
     * @return HtmlConfig
     * @throws ReflectionException
     */
    public function getClass(): HtmlConfig
    {
        $controller = new ReflectionClass(HtmlConfig::class);
        return $controller->newInstanceArgs($this->args);
    }
}
