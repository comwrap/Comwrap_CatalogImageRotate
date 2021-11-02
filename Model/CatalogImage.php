<?php
declare(strict_types=1);

namespace Comwrap\CatalogImageRotate\Model;

use Comwrap\CatalogImageRotate\Api\CatalogImageInterface;
use Magento\Framework\Model\AbstractModel;

class CatalogImage extends AbstractModel implements CatalogImageInterface
{
    /**
     * Get URL
     *
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->getData(self::URL);
    }

    /**
     * Set URL
     *
     * @param string|null $url
     * @return CatalogImageInterface
     */
    public function setUrl(?string $url): CatalogImageInterface
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * Get File
     *
     * @return string|null
     */
    public function getFile(): ?string
    {
        return $this->getData(self::FILE);
    }

    /**
     * Set File
     *
     * @param string|null $file
     * @return CatalogImageInterface
     */
    public function setFile(?string $file): CatalogImageInterface
    {
        return $this->setData(self::FILE, $file);
    }

    /**
     * Get Angle
     *
     * @return int|null
     */
    public function getAngle(): ?int
    {
        return $this->getData(self::ANGLE);
    }

    /**
     * Set Angle
     *
     * @param int|null $angle
     * @return CatalogImageInterface
     */
    public function setAngle(?int $angle): CatalogImageInterface
    {
        return $this->setData(self::ANGLE, $angle);
    }
}
