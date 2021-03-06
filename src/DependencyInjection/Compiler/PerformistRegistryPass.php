<?php
/*
 * This file is part of the PerformistBundle package.
 *
 * (c) Radhi GUENNICHI <radhi@guennichi.com> (https://www.guennichi.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Guennichi\PerformistBundle\DependencyInjection\Compiler;


use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionUnionType;
use RuntimeException;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class PerformistRegistryPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    public function process(ContainerBuilder $container)
    {
        $registryDefinition = $container->getDefinition('guennichi.performist.registry');

        foreach ($container->findTaggedServiceIds('guennichi_performist.handler') as $handlerId => $tags) {
            $className = $this->getServiceClass($container, $handlerId);
            $reflectionClass = $container->getReflectionClass($className);

            if (null === $reflectionClass) {
                throw new RuntimeException(sprintf('Invalid service "%s": class "%s" does not exist.', $handlerId, $className));
            }

            $actionClass = $this->guessAction($reflectionClass, $handlerId);

            $registryDefinition->addMethodCall('add', [$actionClass, new Reference($handlerId)]);
        }
    }

    private function guessAction(ReflectionClass $handlerClass, string $serviceId): string
    {
        try {
            $method = $handlerClass->getMethod('__invoke');
        } catch (ReflectionException $e) {
            throw new RuntimeException(sprintf('Invalid handler service "%s": class "%s" must have an "__invoke()" method.', $serviceId, $handlerClass->getName()));
        }

        if (0 === $method->getNumberOfRequiredParameters()) {
            throw new RuntimeException(sprintf('Invalid handler service "%s": method "%s::__invoke()" requires at least one argument, first one being the message it handles.', $serviceId, $handlerClass->getName()));
        }

        $parameters = $method->getParameters();
        if (!$type = $parameters[0]->getType()) {
            throw new RuntimeException(sprintf('Invalid handler service "%s": argument "$%s" of method "%s::__invoke()" must have a type-hint corresponding to the action class it handles.', $serviceId, $parameters[0]->getName(), $handlerClass->getName()));
        }

        if ($type instanceof ReflectionUnionType) {
            throw new RuntimeException(sprintf('Invalid handler service "%s": type-hint of argument "$%s" in method "%s::__invoke()" is not supported, "%s" given.', $serviceId, $parameters[0]->getName(), $handlerClass->getName(), $type instanceof ReflectionNamedType ? $type->getName() : (string)$type));
        }

        if ($type->isBuiltin()) {
            throw new RuntimeException(sprintf('Invalid handler service "%s": type-hint of argument "$%s" in method "%s::__invoke()" must be a class , "%s" given.', $serviceId, $parameters[0]->getName(), $handlerClass->getName(), $type instanceof ReflectionNamedType ? $type->getName() : (string)$type));
        }

        if ($type instanceof ReflectionNamedType) {
            return $type->getName();
        }

        throw new RuntimeException(sprintf('Invalid handler service "%s": class "%s" the "__invoke()" method contains unsupported argument type, "%s" given.', $serviceId, $handlerClass->getName(), (string)$type));
    }

    private function getServiceClass(ContainerBuilder $container, string $serviceId): string
    {
        while (true) {
            $definition = $container->findDefinition($serviceId);

            if (!$definition->getClass() && $definition instanceof ChildDefinition) {
                $serviceId = $definition->getParent();

                continue;
            }

            return $definition->getClass();
        }
    }
}
