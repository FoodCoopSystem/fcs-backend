<?php
namespace AppBundle\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;

class FeatureContext implements Context, KernelAwareContext
{
    use KernelDictionary;

    /**
     * @Given /^I am authenticated as "([^"]*)" and "([^"]*)"$/
     */
    public function iAmAuthenticatedAsAnd($username, $password)
    {
        $token = 'ZjY0MDdlYzU2ZmQzZGQ4ZjgyODU5Y2Q3ZTBjZjY5YjY5OGE1YWUxNmUyMjAxODgzYjI2YTJhMjMyNTFiZmU5MQ';
    }
}