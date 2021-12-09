<?php
declare(strict_types=1);

namespace Comwrap\CatalogImageRotate\Model;

use Comwrap\CatalogImageRotate\Api\CatalogImageInterface;
use Comwrap\CatalogImageRotate\Api\CatalogImageInterfaceFactory;
use Comwrap\CatalogImageRotate\Api\ConfigInterface;
use Magento\Catalog\Model\Product\Media\Config;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\File\Uploader;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Image\AdapterFactory;
use Magento\MediaStorage\Model\File\Uploader as FileUploader;

class RotateImage
{
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var AdapterFactory
     */
    private $imageFactory;
    /**
     * @var Config
     */
    private $productMediaConfig;
    /**
     * @var ConfigInterface
     */
    private $config;
    /**
     * @var File
     */
    private $file;
    /**
     * @var CatalogImageInterfaceFactory
     */
    private $catalogImageFactory;

    /**
     * Rotate image constructor
     *
     * @param Filesystem $filesystem
     * @param AdapterFactory $imageFactory
     * @param Config $productMediaConfig
     * @param ConfigInterface $config
     * @param File $file
     * @param CatalogImageInterfaceFactory $catalogImageFactory
     */
    public function __construct(
        Filesystem $filesystem,
        AdapterFactory $imageFactory,
        Config $productMediaConfig,
        ConfigInterface $config,
        File $file,
        CatalogImageInterfaceFactory $catalogImageFactory
    ) {
        $this->filesystem = $filesystem;
        $this->imageFactory = $imageFactory;
        $this->productMediaConfig = $productMediaConfig;
        $this->config = $config;
        $this->file = $file;
        $this->catalogImageFactory = $catalogImageFactory;
    }

    /**
     * Catalog image rotate
     *
     * @param string|null $imageUrl
     * @param int|null $rotated
     * @param string|null $error
     * @param int|null $previousAngle
     * @param bool $tmp
     * @return CatalogImageInterface
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @throws LocalizedException
     */
    public function catalogImageRotate(
        ?string $imageUrl,
        ?int $rotated,
        ?string $error,
        ?int $previousAngle,
        $tmp = false
    ) {
        $catalogImage = $this->catalogImageFactory->create();
        try {
            if ($imageUrl !== '') {
                $baseMediaPath = $this->productMediaConfig->getBaseMediaPath();
                $baseTmpPath = $this->productMediaConfig->getBaseTmpMediaPath();
                $tmpPath = 0;
                $mediaPath = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
                $absolutePath = $mediaPath->getAbsolutePath($baseMediaPath). '/' .$imageUrl;
                if ($rotated || $error !== '') {
                    if (strpos($imageUrl, '.tmp') !== false) {
                        $imageUrl = str_replace('.tmp', '', $imageUrl);
                        $tmpPath = 1;
                    }
                    if ($tmpPath) {
                        $absolutePath = $mediaPath->getAbsolutePath($baseTmpPath). '/' .$imageUrl;
                    }
                }
                if ($this->file->isExists($absolutePath)) {
                    $angle = $this->config->getAngle() + $previousAngle;
                    $fileName = $this->getUniqueFileName($imageUrl);
                    if ($tmpPath) {
                        $fileName = $this->getUniqueFileName($imageUrl, true);
                    }
                    $dispersionPath = Uploader::getDispersionPath($fileName);
                    $filePath = $dispersionPath . '/' . $fileName;
                    $imageRotateDestination = $mediaPath->getAbsolutePath($baseTmpPath) . '/' . $filePath;
                    $imageRotate = $this->imageFactory->create();
                    $imageRotate->open($absolutePath);
                    $imageRotate->rotate($angle);
                    if (!$tmpPath && $this->file->isExists($imageRotateDestination)) {
                        $this->file->deleteFile($imageRotateDestination);
                    }
                    $imageRotate->save($imageRotateDestination);
                    $rotatedURL = $this->productMediaConfig->getTmpMediaUrl($filePath);
                    if ($tmpPath && $tmp) {
                        $filePath = $filePath . '.tmp';
                    }
                    $catalogImage->setUrl($rotatedURL)
                        ->setFile($filePath)
                        ->setAngle($angle);
                } else {
                    throw new LocalizedException(__('Image not exists.'));
                }
            } else {
                throw new LocalizedException(__('Image not exists.'));
            }
        } catch (\Exception $e) {
            throw new LocalizedException(__('Something went wrong while rotate the file.'));
        }
        return $catalogImage;
    }

    /**
     * Get Unique file name
     *
     * @param string|null $file
     * @param bool $forTmp
     * @return string
     */
    private function getUniqueFileName($file, $forTmp = false)
    {
        $mediaPath = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $destinationFile = $forTmp
            ? $mediaPath->getAbsolutePath($this->productMediaConfig->getTmpMediaPath($file))
            : $mediaPath->getAbsolutePath($this->productMediaConfig->getMediaPath($file));
        // phpcs:disable Magento2.Functions.DiscouragedFunction
        $destFile = FileUploader::getNewFileName($destinationFile);
        return $destFile;
    }
}
