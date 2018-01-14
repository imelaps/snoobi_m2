<?php

namespace Snoobi\Snoobi\Model\Config\Source;

class Anchors implements \Magento\Framework\Option\ArrayInterface
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
            ['value' => 'off', 'label' => __('Off')],
        ];
    }
}
