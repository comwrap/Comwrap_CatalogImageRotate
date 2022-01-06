<?php
declare(strict_types=1);

namespace Comwrap\CatalogImageRotate\Controller\Adminhtml\Rotate;

use Comwrap\CatalogImageRotate\Model\RotateImage;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

class Upload extends Action implements HttpPostActionInterface
{
    /**
     * @var RotateImage
     */
    private $rotateImage;

    /**
     * Upload Constructor
     *
     * @param Context $context
     * @param RotateImage $rotateImage
     */
    public function __construct(
        Context $context,
        RotateImage $rotateImage
    ) {
        parent::__construct($context);
        $this->rotateImage = $rotateImage;
    }

    /**
     * Upload rotate image
     *
     * @return Json
     * @throws LocalizedException
     */
    public function execute()
    {
        $imageUrl = (string)$this->getRequest()->getParam('image_url', '');
        $imageOldUrl = (string)$this->getRequest()->getParam('image_old_url', '');
        $rotated = (int)$this->getRequest()->getParam('rotated', 0);
        $error = (string)$this->getRequest()->getParam('error', '');
        $previousAngle = (int)$this->getRequest()->getParam('angle', 0);
        if ($previousAngle == 360) {
            $previousAngle = 0;
        }
        $result = $this->rotateImage->catalogImageRotate(
            $imageUrl,
            $imageOldUrl,
            $rotated,
            $error,
            $previousAngle,
            true
        );

        /** @var Json $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $response->setHeader('Content-type', 'application/json', true);
        $response->setData($result->getData());
        return $response;
    }
}
