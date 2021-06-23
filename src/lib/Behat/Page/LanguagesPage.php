<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\AdminUi\Behat\Page;

use Behat\Mink\Session;
use Ibexa\Behat\Browser\Locator\VisibleCSSLocator;
use Ibexa\Behat\Browser\Page\Page;
use Ibexa\Behat\Browser\Routing\Router;
use Ibexa\AdminUi\Behat\Component\Dialog;
use Ibexa\AdminUi\Behat\Component\Table\TableBuilder;
use PHPUnit\Framework\Assert;

class LanguagesPage extends Page
{
    /** @var \Ibexa\AdminUi\Behat\Component\Table\Table */
    private $table;

    /** @var \Ibexa\AdminUi\Behat\Component\Dialog */
    private $dialog;

    public function __construct(Session $session, Router $router, TableBuilder $tableBuilder, Dialog $dialog)
    {
        parent::__construct($session, $router);
        $this->table = $tableBuilder->newTable()->build();
        $this->dialog = $dialog;
    }

    public function editLanguage(string $languageName): void
    {
        $this->table->getTableRow(['Name' => $languageName])->edit();
    }

    public function create(): void
    {
        $this->getHTMLPage()->find($this->getLocator('createButton'))->click();
    }

    public function deleteLanguage(string $languageName): void
    {
        $this->table->getTableRow(['Name' => $languageName])->select();
        $this->getHTMLPage()->find($this->getLocator('deleteButton'))->click();
        $this->dialog->verifyIsLoaded();
        $this->dialog->confirm();
    }

    public function isLanguageOnTheList(string $languageName)
    {
        return $this->table->hasElement(['Name' => $languageName]);
    }

    public function verifyIsLoaded(): void
    {
        Assert::assertEquals(
            'Languages',
            $this->getHTMLPage()->find($this->getLocator('pageTitle'))->getText()
        );
        Assert::assertEquals(
            'Languages',
            $this->getHTMLPage()->find($this->getLocator('listHeader'))->getText()
        );
    }

    public function getName(): string
    {
        return 'Languages';
    }

    protected function getRoute(): string
    {
        return 'language/list';
    }

    protected function specifyLocators(): array
    {
        return [
            new VisibleCSSLocator('pageTitle', '.ez-header h1'),
            new VisibleCSSLocator('listHeader', '.ez-table-header .ez-table-header__headline, header .ez-table__headline, header h5'),
            new VisibleCSSLocator('createButton', '.ez-icon-create'),
            new VisibleCSSLocator('deleteButton', '.ez-icon-trash,button[data-original-title^="Delete"]'),
        ];
    }
}
