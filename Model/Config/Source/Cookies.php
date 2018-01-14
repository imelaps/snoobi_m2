<?php

namespace Snoobi\Snoobi\Model\Config\Source;

class Cookies implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'on', 'label' => __('On')],
            ['value' => '1st', 'label' => __('1st')],
            ['value' => 'off', 'label' => __('Off')],
        ];
    }
}
