<?php
declare(strict_types=1);

namespace Comwrap\CatalogImageRotate\Observer;

use Comwrap\CatalogImageRotate\Api\ConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ChangeTemplateObserver implements ObserverInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * Override gallery file
     *
     * @param ConfigInterface $config
     */
    public function __construct(
        ConfigInterface $config
    ) {
        $this->config = $config;
    }

    /**
     * Override template
     *
     * @param mixed $observer
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @return void
     */
    public function execute(Observer $observer)
    {
        if ($this->config->isEnabled()) {
            $observer->getBlock()->setTemplate('Comwrap_CatalogImageRotate::helper/gallery.phtml');
        }
    }
}
