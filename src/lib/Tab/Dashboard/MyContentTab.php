<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\AdminUi\Tab\Dashboard;

use Ibexa\Contracts\AdminUi\Tab\OrderedTabInterface;
use Ibexa\Core\Pagination\Pagerfanta\LocationSearchAdapter;
use Pagerfanta\Pagerfanta;

class MyContentTab extends AbstractContentTab implements OrderedTabInterface
{
    public function getIdentifier(): string
    {
        return 'my-content';
    }

    public function getName(): string
    {
        return /** @Desc("Content") */
            $this->translator->trans('tab.name.my_content', [], 'dashboard');
    }

    public function getOrder(): int
    {
        return 200;
    }

    public function renderView(array $parameters): string
    {
        /** @todo Handle pagination */
        $page = 1;
        $limit = 10;

        $pager = new Pagerfanta(
            new LocationSearchAdapter(
                $this->contentLocationSubtreeQueryType->getQuery(['owned' => true]),
                $this->searchService
            )
        );
        $pager->setMaxPerPage($limit);
        $pager->setCurrentPage($page);

        return $this->twig->render('@ibexadesign/ui/dashboard/tab/my_content.html.twig', [
            'data' => $this->pagerLocationToDataMapper->map($pager),
        ]);
    }
}

class_alias(MyContentTab::class, 'EzSystems\EzPlatformAdminUi\Tab\Dashboard\MyContentTab');
