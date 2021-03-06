<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Cms\Controller\Adminhtml\Block;

use ALevel\Event\Api\Data\CustomerInterfaceFactory;
use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;


class Edit extends \Magento\Backend\App\Action implements HttpGetActionInterface
{
    /**
     * @var CustomerInterfaceFactory
     */
    private $customerInterfaceFactory;


    /**
     * @param CustomerInterfaceFactory $customerInterfaceFactory
     */
    public function __construct(
        Action\Context $context,
        CustomerInterfaceFactory $customerInterfaceFactory
    ) {
        parent::__construct($context);
        $this->customerInterfaceFactory = $customerInterfaceFactory;
    }

    /**
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('id');
        $model = $this->customerInterfaceFactory->create();

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This raw no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
                return $resultRedirect->setPath('*/*/');
            }
        }

        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit User') : __('New User'),
            $id ? __('Edit User') : __('New User')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('User'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getTitle() : __('New User'));
        return $resultPage;
    }
}
