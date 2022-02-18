<?php

namespace Js\ChangeButtonColor\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Js\ChangeButtonColor\Helper\ButtonChangeColor as ButtonChangeColorHelper;

class ButtonChangeColor extends Command
{
    const COLOR = 'final_color';
    const STORE_VIEW_ID = 'store_view_id';

    private $buttonChangeColorHelper;
    private $appState;

    public function __construct(
        ButtonChangeColorHelper $buttonChangeColorHelper,
        \Magento\Framework\App\State $appState
    ) {
        parent::__construct();
        $this->buttonChangeColorHelper = $buttonChangeColorHelper;
        $this->appState = $appState;
    }

    protected function configure()
    {
        $this->setName('color:change')
            ->setDescription('Change button color for store views')
            ->addArgument(self::COLOR)
            ->addArgument(self::STORE_VIEW_ID);

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $color = $input->getArgument(self::COLOR);
        $storeViewId = $input->getArgument(self::STORE_VIEW_ID);

        if ($this->appState->getMode() != \Magento\Framework\App\State::MODE_DEVELOPER) {
            $output->writeln("Deploy mode is not developer");
        } else {
            if ($color == null || $storeViewId == null) {
                $output->writeln("Color:change Command needs two arguments");
            } else {
                $this->buttonChangeColorHelper->checkCommandArguments($color, $storeViewId);
            }
        }
        return $this;
    }
}
