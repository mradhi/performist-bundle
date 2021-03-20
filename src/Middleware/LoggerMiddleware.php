<?php
/*
 * This file is part of the PerformistBundle package.
 *
 * (c) Radhi GUENNICHI <hello@guennichi.com> (https://www.guennichi.com)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Guennichi\PerformistBundle\Middleware;


use Closure;
use Guennichi\Performist\MiddlewareInterface;
use Psr\Log\LoggerInterface;

class LoggerMiddleware implements MiddlewareInterface
{
    private ?LoggerInterface $logger;

    public function __construct(?LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function handle($action, Closure $next)
    {
        if (null === $this->logger) {
            // Ignore, there is no logger service.
            return $next($action);
        }

        $context = ['class' => get_class($action)];
        $this->logger->info('Received message {class}', $context);
        $result = $next($action);
        $this->logger->info('Message {class} handled successfully', $context);

        return $result;
    }
}
