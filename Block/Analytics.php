<?php

namespace Snoobi\Snoobi\Block;

use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Snoobi\Snoobi\Helper\Data as SnoobiHelper;

class Analytics extends Template
{
    /**
     * @var SnoobiHelper
     */
    protected $snoobiHelper;

    /**
     * @var OrderCollectionFactory
     */
    protected $salesOrderCollection;

    /**
     * Analytics constructor.
     * @param SnoobiHelper $snoobiHelper
     * @param OrderCollectionFactory $salesOrderCollection
     * @param Template\Context $context
     * @param array $data
     */
    public function  __construct(
        SnoobiHelper $snoobiHelper,
        OrderCollectionFactory $salesOrderCollection,
        Template\Context $context,
        array $data = []
    ) {
        $this->snoobiHelper = $snoobiHelper;
        $this->salesOrderCollection = $salesOrderCollection;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function getAccount()
    {
        return $this->_scopeConfig->getValue(SnoobiHelper::XML_PATH_ACCOUNT, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed|string
     */
    public function getAnchors()
    {
        $anchors = $this->_scopeConfig->getValue(SnoobiHelper::XML_PATH_ANCHORS, ScopeInterface::SCOPE_STORE);
        return ($anchors ? $anchors : 'on');
    }

    /**
     * @return mixed|string
     */
    public function getCookies()
    {
        $cookies = $this->_scopeConfig->getValue(SnoobiHelper::XML_PATH_COOKIES, ScopeInterface::SCOPE_STORE);
        return ($cookies ? $cookies : 'on');
    }

    /**
     * @return string|void
     */
    public function getOrdersTrackingCode()
    {
        $orderIds = $this->getOrderIds();
        if (empty($orderIds) || !is_array($orderIds)) {
            return;
        }

        $collection = $this->salesOrderCollection->create();
        $collection->addFieldToFilter('entity_id', ['in' => $orderIds]);

        $result = [];
        $j = 0;
        $result[] = 'var snoobiTrans = new SnoobiTrans();';
        /** @var $order \Magento\Sales\Model\Order */
        foreach ($collection as $order) {
            // Order data
            $result[] = ((!$j)?'var ':'') . 'snoobiOrder = snoobiTrans.order("' . $order->getIncrementId() . '");';
            // Billing address fields
            if ($billingAddress = $order->getBillingAddress()) {
                if ($email = $billingAddress->getEmail()) {
                    $result[] = 'snoobiOrder.billingEmail = "' . $email . '";';
                }
                if ($firstname = $billingAddress->getFirstname()) {
                    $result[] = 'snoobiOrder.billingFirstname = "' . $firstname . '";';
                }
                if ($lastname = $billingAddress->getLastname()) {
                    $result[] = 'snoobiOrder.billingLastname = "' . $lastname . '";';
                }
                if ($company = $billingAddress->getCompany()) {
                    $result[] = 'snoobiOrder.billingCompany = "' . $company . '";';
                }
                if ($street = $billingAddress->getStreet()) {
                    $result[] = 'snoobiOrder.billingStreet = "' . implode(', ', $street) . '";';
                }
                if ($zip = $billingAddress->getPostcode()) {
                    $result[] = 'snoobiOrder.billingZip = "' . $zip . '";';
                }
                if ($city = $billingAddress->getCity()) {
                    $result[] = 'snoobiOrder.billingCity = "' . $city . '";';
                }
                if ($state = $billingAddress->getRegion()) {
                    $result[] = 'snoobiOrder.billingRegion = "' . $state . '";';
                }
                if ($country = $billingAddress->getCountryId()) {
                    $result[] = 'snoobiOrder.billingCountry = "' . $country . '";';
                }
                if ($phone = $billingAddress->getTelephone()) {
                    $result[] = 'snoobiOrder.billingPhone = "' . $phone . '";';
                }
            }

            // Shipping address fields
            if ($shippingAddress = $order->getShippingAddress()) {
                if ($email = $shippingAddress->getEmail()) {
                    $result[] = 'snoobiOrder.shippingEmail = "' . $email . '";';
                }
                if ($firstname = $shippingAddress->getFirstname()) {
                    $result[] = 'snoobiOrder.shippingFirstname = "' . $firstname . '";';
                }
                if ($lastname = $shippingAddress->getLastname()) {
                    $result[] = 'snoobiOrder.shippingLastname = "' . $lastname . '";';
                }
                if ($company = $shippingAddress->getCompany()) {
                    $result[] = 'snoobiOrder.shippingCompany = "' . $company . '";';
                }
                if ($street = $shippingAddress->getStreet()) {
                    $result[] = 'snoobiOrder.shippingStreet = "' . implode(', ', $street) . '";';
                }
                if ($zip = $shippingAddress->getPostcode()) {
                    $result[] = 'snoobiOrder.shippingZip = "' . $zip . '";';
                }
                if ($city = $shippingAddress->getCity()) {
                    $result[] = 'snoobiOrder.shippingCity = "' . $city . '";';
                }
                if ($state = $shippingAddress->getRegion()) {
                    $result[] = 'snoobiOrder.shippingRegion = "' . $state . '";';
                }
                if ($country = $shippingAddress->getCountryId()) {
                    $result[] = 'snoobiOrder.shippingCountry = "' . $country . '";';
                }
                if ($phone = $shippingAddress->getTelephone()) {
                    $result[] = 'snoobiOrder.shippingPhone = "' . $phone . '";';
                }
            }

            $result[] = 'snoobiOrder.total = "' . $order->getBaseGrandTotal() . '";';
            $result[] = 'snoobiOrder.currency = "' . $order->getBaseCurrencyCode() . '";';
            $result[] = 'snoobiOrder.shippingCosts = "' . $order->getBaseShippingAmount() . '";';
            $result[] = 'snoobiOrder.tax = "' . $order->getBaseTaxAmount() . '";';
            $result[] = 'snoobiOrder.coupons = "' . $order->getCouponCode() . '";';
            $result[] = 'snoobiOrder.turnover = "' . ( $order->getBaseGrandTotal() - $order->getBaseTaxAmount() ) . '";';
            $result[] = 'snoobiOrder.shippingMethod = "' . $order->getShippingDescription() . '";';
            $result[] = 'snoobiOrder.discount = "' . $order->getDiscountAmount() . '";';
            $result[] = 'snoobiOrder.discountPct = "' . ($order->getDiscountAmount()/$order->getBaseGrandTotal())*100 . '";';

            // Get payment method
            $payment = $order->getPayment();
            if ($payment && $method = $payment->getMethodInstance()) {
                $result[] = 'snoobiOrder.paymentMethod = "' . $method->getTitle() . '";';
            }

            $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();

            // Order items
            $i = 0;
            foreach ($order->getAllVisibleItems() as $item) {
                $result[] = ((!$i)?'var ':'') .'orderedItem = snoobiOrder.item("' . $item->getId() . '");';

                $result[] = 'orderedItem.name = "' . $this->escapeJsQuote($item->getName()) . '";';
                $result[] = 'orderedItem.sku = "' . $item->getSku() . '";';
                $result[] = 'orderedItem.price = "' . $item->getBasePrice() . '";';
                $result[] = 'orderedItem.amount = "' . $item->getQtyOrdered() . '";';

                $result[] = 'orderedItem.discount = "'.$item->getDiscountAmount().'";';
                $result[] = 'orderedItem.discountPct = "'.(($item->getDiscountAmount()/$item->getBasePrice())*100).'";';

		$_cats=$item->getProduct()->getCategoryIds();
		$category = $_objectManager->create('Magento\Catalog\Model\Category') ->load($_cats[0]);
                $result[] = 'orderedItem.category = "'.$category->getName().'";';

                $i++;
            }

            $j++;
        }

        return implode("\n", $result);
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->snoobiHelper->isSnoobiAnalyticsAvailable()) {
            return '';
        }

        return parent::_toHtml();
    }
}
