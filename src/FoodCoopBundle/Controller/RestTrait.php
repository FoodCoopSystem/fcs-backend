<?php

namespace FoodCoopBundle\Controller;

use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use Symfony\Component\Form\FormInterface;

trait RestTrait
{
    protected function renderRestView($data = null, $statusCode = null, array $headers = array(), $groups = array())
    {
        if ($data instanceof FormInterface) {
            return $data;
        }

        $view = View::create($data, $statusCode, $headers);

        if(!empty($groups)) {
            $context = SerializationContext::create();
            $context->setGroups($groups);
            $view->setSerializationContext($context);
        }

        return $view;
    }
}
