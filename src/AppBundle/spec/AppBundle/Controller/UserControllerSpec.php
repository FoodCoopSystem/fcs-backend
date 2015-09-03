<?php

namespace spec\AppBundle\Controller;

use AppBundle\Controller\UserController;
use FOS\RestBundle\Util\Codes;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @mixin UserController
 *
 * @method beConstructedWith(UserInterface $user)
 */
class UserControllerSpec extends ObjectBehavior
{
    function let(UserInterface $user)
    {
        $this->beConstructedWith($user);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Controller\UserController');
    }

    function it_generate_user_profile(UserInterface $user)
    {
        $this->getProfileAction()->shouldBeRestViewWith([
            'data' => $user,
            'statusCode' => Codes::HTTP_OK,
            'serializationGroups' => ['user_profile'],
            'headers' => [
                'cache-control' => ['no-cache'],
                'date' => ["@string@.isDateTime()"],
            ]
        ]);
    }
}
