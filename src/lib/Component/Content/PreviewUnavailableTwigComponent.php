<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\AdminUi\Component\Content;

use Ibexa\AdminUi\Siteaccess\SiteaccessResolverInterface;
use Ibexa\Contracts\AdminUi\Component\Renderable;
use Ibexa\Contracts\Core\Repository\LocationService;
use Ibexa\Contracts\Core\Repository\Values\Content\Content;
use Ibexa\Contracts\Core\Repository\Values\Content\Location;
use Twig\Environment;

class PreviewUnavailableTwigComponent implements Renderable
{
    /** @var \Twig\Environment */
    private $twig;

    /** @var \Ibexa\AdminUi\Siteaccess\NonAdminSiteaccessResolver */
    private $siteaccessResolver;

    /** @var \Ibexa\Contracts\Core\Repository\LocationService */
    private $locationService;

    /**
     * @param \Twig\Environment $twig
     * @param \Ibexa\AdminUi\Siteaccess\NonAdminSiteaccessResolver $siteaccessResolver
     * @param \Ibexa\Contracts\Core\Repository\LocationService $locationService
     */
    public function __construct(
        Environment $twig,
        SiteaccessResolverInterface $siteaccessResolver,
        LocationService $locationService
    ) {
        $this->twig = $twig;
        $this->siteaccessResolver = $siteaccessResolver;
        $this->locationService = $locationService;
    }

    /**
     * @param array $parameters
     *
     * @return string
     */
    public function render(array $parameters = []): string
    {
        /** @var \Ibexa\Contracts\Core\Repository\Values\Content\Location $location */
        $location = $parameters['location'];
        /** @var \Ibexa\Contracts\Core\Repository\Values\Content\Content $content */
        $content = $parameters['content'];
        /** @var \Ibexa\Contracts\Core\Repository\Values\Content\Language $language */
        $language = $parameters['language'];
        $versionNo = $content->getVersionInfo()->versionNo;

        // nonpublished content should use parent location instead because location doesn't exist yet
        if (!$content->contentInfo->published && null === $content->contentInfo->mainLocationId) {
            $parentLocations = $this->locationService->loadParentLocationsForDraftContent($content->getVersionInfo());
            $location = reset($parentLocations);
            $versionNo = null;
        }

        $siteaccesses = $this->siteaccessResolver->getSiteAccessesListForLocation(
            $location,
            $versionNo,
            $language->languageCode
        );

        if (empty($siteaccesses)) {
            return $this->twig->render(
                '@ibexadesign/ui/component/preview_unavailable.html.twig'
            );
        }

        return '';
    }
}

class_alias(PreviewUnavailableTwigComponent::class, 'EzSystems\EzPlatformAdminUi\Component\Content\PreviewUnavailableTwigComponent');
