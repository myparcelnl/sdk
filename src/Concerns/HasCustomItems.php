<?php
/**
 * Created by PhpStorm.
 * User: richardperdaan
 * Date: 05-10-18
 * Time: 10:10
 */

namespace MyParcelNL\sdk\Concerns;

use MyParcelNL\Sdk\src\Model\MyParcelConsignment;
use MyParcelNL\Sdk\src\Model\MyParcelCustomsItem;

trait HasCustomItems {
    /**
     * Add items for international shipments
     *
     * @param $consignmentData
     * @param MyParcelConsignment $consignment
     *
     * @throws \Exception
     */
    public function setCustomItems( $consignmentData, $consignment ) {
        foreach ( $consignmentData['custom_items'] as $customItem ) {
            $item = ( new MyParcelCustomsItem() )
                ->setDescription( $customItem['description'] )
                ->setAmount( $customItem['amount'] )
                ->setWeight( $customItem['weight'] )
                ->setItemValue( $customItem['item_value'] )
                ->setClassification( $customItem['classification'] )
                ->setCountry( $customItem['country'] );

            $consignment->addItem( $item );
        }
    }
}