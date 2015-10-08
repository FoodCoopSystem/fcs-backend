<?php

namespace FoodCoopBundle\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Codifico\ParameterBagExtension\Context\ParameterBagDictionary;
use Coduo\PHPMatcher\Factory\SimpleFactory;

class MatcherContext implements  Context
{
    use ParameterBagDictionary;

    /**
     * @Then the JSON should match pattern:
     *
     * @param PyStringNode $string
     * @throws \Exception
     */
    public function theJsonShouldMatchPattern(PyStringNode $string)
    {
        $expected = $string->getRaw();
        $current = (string)$this->getParameterBag()->get('response')->getBody();

        $matcher = (new SimpleFactory())->createMatcher();

        if (!$matcher->match($current, $expected)) {
            throw new \Exception($matcher->getError());
        }
    }
}