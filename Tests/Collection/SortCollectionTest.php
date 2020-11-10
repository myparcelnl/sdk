<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\tests\Collection;

use MyParcelNL\Sdk\src\Helper\MyParcelCollection;
use MyParcelNL\Sdk\src\Model\Consignment\PostNLConsignment;
use PHPUnit\Framework\TestCase;

class SortCollectionTest extends TestCase
{
    /**
     * @return void
     * @throws \Exception
     */
    public function testInSameOrder(): void
    {
        $consignment1 = (new PostNLConsignment())->setConsignmentId(1);
        $consignment2 = (new PostNLConsignment())->setConsignmentId(2);

        $sorted = new MyParcelCollection([$consignment1, $consignment2]);
        $new = new MyParcelCollection([$consignment1, $consignment2]);
        $new = $new->sortByCollection($sorted);

        $this->assertEquals(new MyParcelCollection([$consignment1, $consignment2]), $new);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testWithMoreItems(): void
    {
        $consignment1 = (new PostNLConsignment())->setConsignmentId(1);
        $consignment2 = (new PostNLConsignment())->setConsignmentId(2);
        $consignment3 = (new PostNLConsignment())->setConsignmentId(3);

        $sorted = new MyParcelCollection([$consignment1, $consignment2]);
        $new = new MyParcelCollection([$consignment1, $consignment2, $consignment3]);
        $new = $new->sortByCollection($sorted);

        $this->assertEquals(new MyParcelCollection([$consignment1, $consignment2, $consignment3]), $new);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testWithEmptyCollection(): void
    {
        $consignment1 = (new PostNLConsignment())->setConsignmentId(1);
        $consignment2 = (new PostNLConsignment())->setConsignmentId(2);

        $sorted = new MyParcelCollection([$consignment1, $consignment2]);
        $new = new MyParcelCollection([]);
        $new = $new->sortByCollection($sorted);

        $this->assertEquals(new MyParcelCollection([]), $new);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testWithReplacedItems(): void
    {
        $consignment1 = (new PostNLConsignment())->setConsignmentId(1);
        $consignment2 = (new PostNLConsignment())->setConsignmentId(2);

        $sorted = new MyParcelCollection([$consignment1, $consignment2]);
        $new = new MyParcelCollection([$consignment2, $consignment1]);

        $new = $new->sortByCollection($sorted);

        $this->assertEquals($sorted, $new);
    }
}
