<?php
declare(strict_types=1);

namespace Comwrap\CatalogImageRotate\Api;

interface ConfigInterface
{
    const XML_ENABLED = 'catalog/general_settings/enabled';
    const XML_ANGLE = 'catalog/general_settings/angle';

    /**
     * Is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Get angle
     *
     * @return string|null
     */
    public function getAngle();
}
