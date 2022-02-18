<?php

namespace Js\ChangeButtonColor\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\Context;

class Color extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected $_idFieldName = 'id';

    /**
     * Construct.
     *
     * @param Context       $context
     * @param string|null   $resourcePrefix
     */
    public function __construct(
        Context $context,
        string $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
    }

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('js_button_color', 'color_id');
    }
}
