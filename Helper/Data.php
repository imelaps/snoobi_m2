<?php

namespace Snoobi\Snoobi\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_ACTIVE = 'snoobi/analytics/active';
    const XML_PATH_ACCOUNT = 'snoobi/analytics/account';
    const XML_PATH_ANCHORS = 'snoobi/analytics/anchors';
    const XML_PATH_COOKIES = 'snoobi/analytics/cookies';

    /**
     * @param null $store
     * @return bool
     */
    public function isSnoobiAnalyticsAvailable($store = null)
    {
        $accountId = $this->scopeConfig->getValue(self::XML_PATH_ACCOUNT, ScopeInterface::SCOPE_STORE, $store);
        return $accountId && $this->scopeConfig->isSetFlag(self::XML_PATH_ACTIVE, ScopeInterface::SCOPE_STORE, $store);
    }
}
