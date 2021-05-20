<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace EzSystems\EzPlatformAdminUi\Tab;

use Symfony\Bridge\Twig\Extension\HttpKernelRuntime;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Base class for Tabs based on a route.
 */
abstract class AbstractRouteBasedTab extends AbstractTab
{
    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    /** @var HttpKernelRuntime */
    private $httpKernelRuntime;

    /**
     * @param Environment $twig
     * @param TranslatorInterface $translator
     * @param UrlGeneratorInterface $urlGenerator
     * @param HttpKernelRuntime $httpKernelRuntime
     */
    public function __construct(
        Environment $twig,
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator,
        HttpKernelRuntime $httpKernelRuntime
    ) {
        parent::__construct($twig, $translator);

        $this->urlGenerator = $urlGenerator;
        $this->httpKernelRuntime = $httpKernelRuntime;
    }

    public function renderView(array $parameters): string
    {
        $route = $this->urlGenerator->generate(
            $this->getRouteName($parameters),
            $this->getRouteParameters($parameters)
        );

        return $this->httpKernelRuntime->renderFragment($route);
    }

    /**
     * Returns route name used to generate path to the resource.
     *
     * @param array $parameters
     *
     * @return string
     */
    abstract public function getRouteName(array $parameters): string;

    /**
     * Returns parameters array required to generate path using the router.
     *
     * @param array $parameters
     *
     * @return array
     */
    abstract public function getRouteParameters(array $parameters): array;
}
