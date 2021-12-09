<?php
declare(strict_types=1);

namespace Comwrap\CatalogImageRotate\Api;

interface ConfigInterface
{
    public const XML_ENABLED = 'catalog/general_settings/enabled';
    public const XML_ANGLE = 'catalog/general_settings/angle';

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
