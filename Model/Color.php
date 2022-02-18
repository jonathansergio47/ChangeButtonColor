<?php

namespace Js\ChangeButtonColor\Model;

use Js\ChangeButtonColor\Api\Data\ColorInterface;

class Color extends \Magento\Framework\Model\AbstractModel implements ColorInterface
{
    const CACHE_TAG = 'spire_box';
    protected $_cacheTag = 'spire_box';
    protected $_eventPrefix = 'spire_box';

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
    }

    protected function _construct()
    {
        $this->_init('Js\ChangeButtonColor\Model\ResourceModel\Color');
    }

    public function getId()
    {
        return $this->getData(self::ID);
    }

    public function setId($id): Color
    {
        return $this->setData(self::ID, $id);
    }

    public function getColor()
    {
        return $this->getData(self::COLOR);
    }

    public function setColor($color): Color
    {
        return $this->setData(self::COLOR, $color);
    }
}
