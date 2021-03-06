<?php
/**
* This class has been generated by TheliaStudio
* For more information, see https://github.com/thelia-modules/TheliaStudio
*/

namespace BeanstreamModule\Controller\Base;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Thelia\Controller\Admin\AbstractCrudController;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Tools\URL;
use BeanstreamModule\Event\BeanstreamPaymentEvent;
use BeanstreamModule\Event\BeanstreamPaymentEvents;
use BeanstreamModule\Model\BeanstreamPaymentQuery;

/**
 * Class BeanstreamPaymentController
 * @package BeanstreamModule\Controller\Base
 * @author TheliaStudio
 */
class BeanstreamPaymentController extends AbstractCrudController
{
    public function __construct()
    {
        parent::__construct(
            "beanstream_payment",
            "id",
            "order",
            AdminResources::MODULE,
            BeanstreamPaymentEvents::CREATE,
            BeanstreamPaymentEvents::UPDATE,
            BeanstreamPaymentEvents::DELETE,
            null,
            null,
            "BeanstreamModule"
        );
    }

    /**
     * Return the creation form for this object
     */
    protected function getCreationForm()
    {
        return $this->createForm("beanstream_payment.create");
    }

    /**
     * Return the update form for this object
     */
    protected function getUpdateForm($data = array())
    {
        if (!is_array($data)) {
            $data = array();
        }

        return $this->createForm("beanstream_payment.update", "form", $data);
    }

    /**
     * Hydrate the update form for this object, before passing it to the update template
     *
     * @param mixed $object
     */
    protected function hydrateObjectForm($object)
    {
        $data = array(
            "id" => $object->getId(),
            "order_id" => $object->getOrderId(),
            "message_id" => $object->getMessageId(),
            "message" => $object->getMessage(),
        );

        return $this->getUpdateForm($data);
    }

    /**
     * Creates the creation event with the provided form data
     *
     * @param mixed $formData
     * @return \Thelia\Core\Event\ActionEvent
     */
    protected function getCreationEvent($formData)
    {
        $event = new BeanstreamPaymentEvent();

        $event->setOrderId($formData["order_id"]);
        $event->setMessageId($formData["message_id"]);
        $event->setMessage($formData["message"]);

        return $event;
    }

    /**
     * Creates the update event with the provided form data
     *
     * @param mixed $formData
     * @return \Thelia\Core\Event\ActionEvent
     */
    protected function getUpdateEvent($formData)
    {
        $event = new BeanstreamPaymentEvent();

        $event->setId($formData["id"]);
        $event->setOrderId($formData["order_id"]);
        $event->setMessageId($formData["message_id"]);
        $event->setMessage($formData["message"]);

        return $event;
    }

    /**
     * Creates the delete event with the provided form data
     */
    protected function getDeleteEvent()
    {
        $event = new BeanstreamPaymentEvent();

        $event->setId($this->getRequest()->request->get("beanstream_payment_id"));

        return $event;
    }

    /**
     * Return true if the event contains the object, e.g. the action has updated the object in the event.
     *
     * @param mixed $event
     */
    protected function eventContainsObject($event)
    {
        return null !== $this->getObjectFromEvent($event);
    }

    /**
     * Get the created object from an event.
     *
     * @param mixed $event
     */
    protected function getObjectFromEvent($event)
    {
        return $event->getBeanstreamPayment();
    }

    /**
     * Load an existing object from the database
     */
    protected function getExistingObject()
    {
        return BeanstreamPaymentQuery::create()
            ->findPk($this->getRequest()->query->get("beanstream_payment_id"))
        ;
    }

    /**
     * Returns the object label form the object event (name, title, etc.)
     *
     * @param mixed $object
     */
    protected function getObjectLabel($object)
    {
        return '';
    }

    /**
     * Returns the object ID from the object
     *
     * @param mixed $object
     */
    protected function getObjectId($object)
    {
        return $object->getId();
    }

    /**
     * Render the main list template
     *
     * @param mixed $currentOrder , if any, null otherwise.
     */
    protected function renderListTemplate($currentOrder)
    {
        $this->getParser()
            ->assign("order", $currentOrder)
        ;

        return $this->render("beanstream-payments");
    }

    /**
     * Render the edition template
     */
    protected function renderEditionTemplate()
    {
        $this->getParserContext()
            ->set(
                "beanstream_payment_id",
                $this->getRequest()->query->get("beanstream_payment_id")
            )
        ;

        return $this->render("beanstream-payment-edit");
    }

    /**
     * Must return a RedirectResponse instance
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToEditionTemplate()
    {
        $id = $this->getRequest()->query->get("beanstream_payment_id");

        return new RedirectResponse(
            URL::getInstance()->absoluteUrl(
                "/admin/module/BeanstreamModule/beanstream_payment/edit",
                [
                    "beanstream_payment_id" => $id,
                ]
            )
        );
    }

    /**
     * Must return a RedirectResponse instance
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToListTemplate()
    {
        return new RedirectResponse(
            URL::getInstance()->absoluteUrl("/admin/module/BeanstreamModule/beanstream_payment")
        );
    }
}
