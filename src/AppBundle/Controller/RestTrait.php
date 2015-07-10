<?php

namespace AppBundle\Controller;

use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;

trait RestTrait
{
    protected function renderRestView($data = null, $statusCode = null, array $headers = array(), $groups = array())
    {
        $view = View::create($data, $statusCode, $headers);

        if(!empty($groups)) {
            $context = SerializationContext::create();
            $context->setGroups($groups);
            $view->setSerializationContext($context);
        }

        return $view;
    }
}
