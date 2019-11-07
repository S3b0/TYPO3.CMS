<?php
declare(strict_types = 1);

namespace TYPO3\CMS\Extbase\Tests\Unit\Pagination;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ArrayPaginatorTest extends UnitTestCase
{
    /**
     * @var array
     */
    protected $fixture = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->fixture = range(1, 14, 1);
    }

    /**
     * A short integration test to check that the fixtures are as expected
     *
     * @test
     */
    public function integration()
    {
        self::assertCount(14, $this->fixture);
    }

    /**
     * @test
     */
    public function checkPaginatorWithDefaultConfiguration()
    {
        $paginator = new ArrayPaginator($this->fixture);

        self::assertSame(2, $paginator->getNumberOfPages());
        self::assertSame(0, $paginator->getKeyOfFirstPaginatedItem());
        self::assertSame(9, $paginator->getKeyOfLastPaginatedItem());
        self::assertCount(10, $paginator->getPaginatedItems());
    }

    /**
     * @test
     */
    public function paginatorRespectsItemsPerPageConfiguration()
    {
        $paginator = new ArrayPaginator(
            $this->fixture,
            3,
            1
        );

        self::assertSame(5, $paginator->getNumberOfPages());
        self::assertSame(0, $paginator->getKeyOfFirstPaginatedItem());
        self::assertSame(2, $paginator->getKeyOfLastPaginatedItem());
        self::assertCount(3, $paginator->getPaginatedItems());
    }

    /**
     * @test
     */
    public function paginatorRespectsItemsPerPageConfigurationAndCurrentPage()
    {
        $paginator = new ArrayPaginator(
            $this->fixture,
            3,
            3
        );

        self::assertSame(5, $paginator->getNumberOfPages());
        self::assertSame(6, $paginator->getKeyOfFirstPaginatedItem());
        self::assertSame(8, $paginator->getKeyOfLastPaginatedItem());
        self::assertCount(3, $paginator->getPaginatedItems());
    }

    /**
     * @test
     */
    public function paginatorProperlyCalculatesLastPage()
    {
        $paginator = new ArrayPaginator(
            $this->fixture,
            3,
            5
        );

        self::assertSame(5, $paginator->getNumberOfPages());
        self::assertSame(12, $paginator->getKeyOfFirstPaginatedItem());
        self::assertSame(13, $paginator->getKeyOfLastPaginatedItem());
        self::assertCount(2, $paginator->getPaginatedItems());
    }

    /**
     * @test
     */
    public function withCurrentPageNumberThrowsInvalidArgumentExceptionIfCurrentPageIsLowerThanOne()
    {
        static::expectExceptionCode(1573047338);

        $paginator = new ArrayPaginator(
            $this->fixture,
            3
        );
        $paginator->withCurrentPageNumber(0);
    }

    /**
     * @test
     */
    public function paginatorProperlyCalulatesOnlyOnePage()
    {
        $paginator = new ArrayPaginator(
            $this->fixture,
            50,
            1
        );

        self::assertSame(1, $paginator->getNumberOfPages());
        self::assertSame(0, $paginator->getKeyOfFirstPaginatedItem());
        self::assertSame(13, $paginator->getKeyOfLastPaginatedItem());
        self::assertCount(14, $paginator->getPaginatedItems());
    }
}
