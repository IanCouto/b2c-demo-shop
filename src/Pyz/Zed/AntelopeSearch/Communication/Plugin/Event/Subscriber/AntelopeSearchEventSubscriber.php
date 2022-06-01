<?php

namespace Pyz\Zed\AntelopeSearch\Communication\Plugin\Event\Subscriber;

use Pyz\Zed\Antelope\Dependency\AntelopeEvents;
use Pyz\Zed\AntelopeSearch\Communication\Plugin\Event\Listener\AntelopeSearchListener;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\AntelopeSearch\Business\AntelopeSearchFacadeInterface getFacade()
 */
class AntelopeSearchEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(AntelopeEvents::ENTITY_PYZ_ANTELOPE_CREATE, new AntelopeSearchListener());
        $eventCollection->addListenerQueued(AntelopeEvents::ENTITY_PYZ_ANTELOPE_UPDATE, new AntelopeSearchListener());
        $eventCollection->addListenerQueued(AntelopeEvents::ENTITY_PYZ_ANTELOPE_DELETE, new AntelopeSearchListener());

        return $eventCollection;
    }
}