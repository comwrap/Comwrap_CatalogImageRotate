<?php
declare(strict_types=1);

namespace Comwrap\CatalogImageRotate\Api;

interface CatalogImageInterface
{
    public const URL = 'url';
    public const FILE = 'file';
    public const ANGLE = 'angle';

    /**
     * Get Url
     *
     * @return string|null
     */
    public function getUrl(): ?string;

    /**
     * Set Url
     *
     * @param string|null $url
     * @return $this
     */
    public function setUrl(?string $url): CatalogImageInterface;

    /**
     * Get File
     *
     * @return string|null
     */
    public function getFile(): ?string;

    /**
     * Set File
     *
     * @param string|null $file
     * @return $this
     */
    public function setFile(?string $file): CatalogImageInterface;

    /**
     * Get Angle
     *
     * @return int|null
     */
    public function getAngle(): ?int;

    /**
     * Set Angle
     *
     * @param int|null $angle
     * @return $this
     */
    public function setAngle(?int $angle): CatalogImageInterface;
}
