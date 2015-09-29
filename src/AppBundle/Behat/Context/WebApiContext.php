<?php

namespace AppBundle\Behat\Context;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\WebApiExtension\Context\WebApiContext as BaseWebApiContext;
use Codifico\ParameterBagExtension\Context\ParameterBagDictionary;
use PHPUnit_Framework_Assert as Assertions;

class WebApiContext extends BaseWebApiContext
{
    function __construct()
    {
        $this->loader = new \Twig_Loader_Array([]);
        $this->twig = new \Twig_Environment($this->loader);
    }

    use ParameterBagDictionary;

    public function iSendARequest($method, $url)
    {
        $token = $this->getParameterBag()->get('token');
        $this->addHeader('Authorization', 'Bearer '.$token);

        $url = $this->parameterize($url);
        parent::iSendARequest($method, $url);

        $this->getParameterBag()->set('response', $this->response);
    }


    public function iSendARequestWithValues($method, $url, TableNode $post)
    {
        $token = $this->getParameterBag()->get('token');
        $this->addHeader('Authorization', 'Bearer '.$token);

        parent::iSendARequestWithValues($method, $url, $post);

        $this->getParameterBag()->set('response', $this->response);
    }

    public function iSendARequestWithBody($method, $url, PyStringNode $string)
    {
        $name = md5($string);
        $this->loader->setTemplate($name, (string)$string);

        $token = $this->getParameterBag()->get('token');
        $this->addHeader('Authorization', 'Bearer '.$token);

        $body = $this->twig->render($name, $this->getParameterBag()->getAll());
        $string = new PyStringNode(explode("\n", $body), $string->getLine());

        $url = $this->parameterize($url);
        parent::iSendARequestWithBody($method, $url, $string);

        $this->getParameterBag()->set('response', $this->response);
    }

    public function iSendARequestWithFormData($method, $url, PyStringNode $body)
    {
        $token = $this->getParameterBag()->get('token');
        $this->addHeader('Authorization', 'Bearer '.$token);

        parent::iSendARequestWithFormData($method, $url, $body);

        $this->getParameterBag()->set('response', $this->response);
    }

    /**
     * Prints last response body.
     *
     * @Then print pretty response
     */
    public function printPrettyResponse()
    {
        $request = $this->request;
        $response = $this->response;

        echo sprintf(
            "%s %s => %d:\n%s",
            $request->getMethod(),
            $request->getUrl(),
            $response->getStatusCode(),
            json_encode(json_decode($response->getBody()), JSON_PRETTY_PRINT)
        );
    }

    /**
     * @Given /^the response should be empty$/
     */
    public function theResponseShouldBeEmpty()
    {
        Assertions::assertSame('', (string)$this->response->getBody());
    }

    private function parameterize($string)
    {
        $name = md5($string);
        $this->loader->setTemplate($name, $string);
        $content = $this->twig->render($name, $this->getParameterBag()->getAll());

        return $content;
    }

    /**
     * @Given /^debug request$/
     */
    public function debugRequest()
    {
        $this->request;
        echo "Headers:\n";
        foreach ($this->request->getHeaders() as $name => $values) {
            echo $name . ": " . implode(", ", $values) . "\n";
        }

        echo "Url: " . $this->request->getUrl() . "\n";
    }
}
