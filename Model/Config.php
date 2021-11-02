<?php
declare(strict_types=1);

namespace Comwrap\CatalogImageRotate\Model;

use Comwrap\CatalogImageRotate\Api\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config implements ConfigInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Config constructor
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_ENABLED, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get Angle
     *
     * @return string|null
     */
    public function getAngle()
    {
        return $this->scopeConfig->getValue(self::XML_ANGLE, ScopeInterface::SCOPE_STORE);
    }
}
