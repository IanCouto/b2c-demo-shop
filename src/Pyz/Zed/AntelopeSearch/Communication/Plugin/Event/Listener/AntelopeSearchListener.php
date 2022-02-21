<?php

namespace Pyz\Zed\AntelopeSearch\Communication\Plugin\Event\Listener;

use Pyz\Zed\Antelope\Dependency\AntelopeEvents;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\AntelopeSearch\Business\AntelopeSearchFacadeInterface getFacade()
 */
class AntelopeSearchListener extends AbstractPlugin implements EventHandlerInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     * @param string $eventName
     *
     * @return void
     */
    public function handle(TransferInterface $transfer, $eventName): void
    {
        /** @var \Generated\Shared\Transfer\EventEntityTransfer $transfer */
        if ($eventName === AntelopeEvents::ENTITY_PYZ_ANTELOPE_CREATE) {
            $this->getFacade()->publish($transfer->getId());
        }
    }
}