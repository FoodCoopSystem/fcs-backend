<?php

namespace AppBundle\Behat\Context;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\WebApiExtension\Context\WebApiContext as BaseWebApiContext;
use Codifico\ParameterBagExtension\Context\ParameterBagDictionary;

class WebApiContext extends BaseWebApiContext
{
    use ParameterBagDictionary;

    public function iSendARequest($method, $url)
    {
        $token = $this->getParameterBag()->get('token');
        $this->addHeader('Authorization', 'Bearer '.$token);

        parent::iSendARequest($method, $url);
    }

    public function iSendARequestWithValues($method, $url, TableNode $post)
    {
        parent::iSendARequestWithValues($method, $url, $post);
    }

    public function iSendARequestWithBody($method, $url, PyStringNode $string)
    {
        parent::iSendARequestWithBody($method, $url, $string);
    }

    public function iSendARequestWithFormData($method, $url, PyStringNode $body)
    {
        parent::iSendARequestWithFormData($method, $url, $body);
    }
}
