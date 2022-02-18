<?php

namespace Js\ChangeButtonColor\Model\ResourceModel\Color;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';

    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init(
            'Js\ChangeButtonColor\Model\Color',
            'Js\ChangeButtonColor\Model\ResourceModel\Color'
        );
    }
}
