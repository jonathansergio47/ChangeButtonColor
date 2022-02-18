<?php

namespace Js\ChangeButtonColor\Plugin\Css\PreProcessor\Adapter\Less;

use Magento\Framework\App\State;
use Magento\Framework\View\Asset\Source;
use Magento\Framework\Css\PreProcessor\File\Temporary;
use Magento\Framework\Phrase;
use Magento\Framework\View\Asset\ContentProcessorException;
use Magento\Framework\View\Asset\File;
use Js\ChangeButtonColor\Model\ResourceModel\Color\CollectionFactory as ColorFactory;

class Processor
{
    private $appState;
    private $assetSource;
    private $temporaryFile;
    private $colorFactory;

    public function __construct(
        State $appState,
        Source $assetSource,
        Temporary $temporaryFile,
        ColorFactory $colorFactory
    ) {
        $this->appState = $appState;
        $this->assetSource = $assetSource;
        $this->temporaryFile = $temporaryFile;
        $this->colorFactory = $colorFactory;
    }

    public function aroundProcessContent(\Magento\Framework\Css\PreProcessor\Adapter\Less\Processor $subject, callable $proceed, File $asset): string
    {
        $colorCollection = $this->colorFactory->create();
        $lastColor = $colorCollection->getLastItem();
        $color = $lastColor->getColor();
        $array = [];
        $array['js-button-color'] = $color;

        $path = $asset->getPath();
        try {
            $parser = new \Less_Parser(
                [
                    'relativeUrls' => false,
                    'compress' => $this->appState->getMode() !== State::MODE_DEVELOPER
                ]
            );

            $content = $this->assetSource->getContent($asset);

            if (trim($content) === '') {
                throw new ContentProcessorException(
                    new Phrase('Compilation from source: LESS file is empty: ' . $path)
                );
            }

            $tmpFilePath = $this->temporaryFile->createFile($path, $content);

            gc_disable();
            $parser->parseFile($tmpFilePath, '');
            $parser->ModifyVars($array);
            $content = $parser->getCss();
            gc_enable();

            if (trim($content) === '') {
                throw new ContentProcessorException(
                    new Phrase('Compilation from source: LESS file is empty: ' . $path)
                );
            } else {
                return $content;
            }
        } catch (\Exception $e) {
            throw new ContentProcessorException(new Phrase($e->getMessage()));
        }
        return $content;
    }
}
