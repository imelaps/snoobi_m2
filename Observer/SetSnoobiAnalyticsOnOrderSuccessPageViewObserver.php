<?php

namespace Snoobi\Snoobi\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\LayoutInterface;

class SetSnoobiAnalyticsOnOrderSuccessPageViewObserver implements ObserverInterface
{
    protected $layout;

    public function __construct(
        LayoutInterface $layout
    ) {
        $this->layout = $layout;
    }

    public function execute(EventObserver $observer)
    {
        $orderIds = $observer->getEvent()->getOrderIds();
        if (empty($orderIds) || !is_array($orderIds)) {
            return;
        }
        $block = $this->layout->getBlock('snoobi_analytics');
        if ($block) {
            $block->setOrderIds($orderIds);
        }
    }
}
