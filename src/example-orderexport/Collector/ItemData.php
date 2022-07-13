<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc.
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\OrderExport\Collector;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use SwiftOtter\OrderExport\Action\GetOrderExportItems;
use SwiftOtter\OrderExport\Api\DataCollectorInterface;
use SwiftOtter\OrderExport\Model\HeaderData;

class ItemData implements DataCollectorInterface
{
    /**
     * @var GetOrderExportItems
     */
    private $getOrderExportItems;

    public function __construct(
        GetOrderExportItems $getOrderExportItems
    ) {
        $this->getOrderExportItems = $getOrderExportItems;
    }

    public function collect(OrderInterface $order, HeaderData $headerData): array
    {
        $items = [];

        foreach ($this->getOrderExportItems->execute($order) as $item) {
            $items[] = $this->transform($item);
        }

        return [
            'items' => $items
        ];
    }

    private function transform(OrderItemInterface $orderItem): array
    {
        return [
            'sku' => $orderItem->getSku(),
            'qty' => $orderItem->getQtyOrdered(),
            'item_price' => $orderItem->getBasePrice(),
            'item_cost' => $orderItem->getBaseCost(),
            'total' => $orderItem->getBaseRowTotal()
        ];
    }
}
