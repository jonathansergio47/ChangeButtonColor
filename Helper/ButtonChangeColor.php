<?php

namespace Js\ChangeButtonColor\Helper;

use Exception;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\App\Config\ReinitableConfigInterface;
use Magento\Framework\Indexer\IndexerRegistry;
use Magento\Theme\Model\Data\Design\Config as DesignConfig;
use Js\ChangeButtonColor\Model\Color;

class ButtonChangeColor
{
    const COLOR_LENGTH = 6;
    const THEME_ID = 18;                    // ESTE ID DO TEMA PRECISA SER ALTERADO CONFORME O PROJETO
    const HASHTAG = '#';

    protected $storeRepository;
    protected $configInterface;
    protected $reinitableConfig;
    protected $indexerRegistry;
    protected $colorModel;

    public function __construct(
        StoreRepositoryInterface $storeRepository,
        ConfigInterface $configInterface,
        ReinitableConfigInterface $reinitableConfig,
        IndexerRegistry $indexerRegistry,
        Color $colorModel
    ) {
        $this->storeRepository = $storeRepository;
        $this->configInterface = $configInterface;
        $this->reinitableConfig = $reinitableConfig;
        $this->indexerRegistry = $indexerRegistry;
        $this->colorModel = $colorModel;
    }

    public function checkCommandArguments($color, $storeViewId)
    {
        $isColorValid = $this->validateColorFormat($color);
        $isSetStoreView = $this->isSetStoreView($storeViewId);

        if ($isColorValid && $isSetStoreView) {
            $this->changeButtonColor($color, $storeViewId);
        }
    }

    /**
     * @throws Exception
     */
    public function changeButtonColor($color, $storeViewId)
    {
        $this->configInterface->saveConfig('design/theme/theme_id', self::THEME_ID, 'stores', $storeViewId);
        $this->reinitableConfig->reinit();
        $this->indexerRegistry->get(DesignConfig::DESIGN_CONFIG_GRID_INDEXER_ID)->reindexAll();
        $this->colorModel->setColor(self::HASHTAG.$color);
        $this->colorModel->save();
        echo "Deploying static content and cleaning cache\n";
        exec('bin/magento setup:static-content:deploy --theme Js/theme -f '.
            '&& rm -rf var/view_preprocessed/ var/cache/ var/page_cache/ var/tmp/ var/generation/ pub/static/frontend/');
        echo "Button color was changed\n";
    }

    public function isSetStoreView($storeViewId): bool
    {
        $stores = $this->storeRepository->getList();
        $storeExists = false;
        foreach ($stores as $store) {
            if ($storeViewId == $store->getId()) {
                $storeExists = true;
            }
        }
        echo (!$storeExists) ? 'Store View Id argument is not valid for this website' : '';
        return $storeExists;
    }

    public function validateColorFormat($color): bool
    {
        if (strlen($color) == self::COLOR_LENGTH) {
            return true;
        }
        echo 'Color argument must have only 6 characters';
        return false;
    }
}
