<?php

namespace AppBundle\Controller;
use FOS\RestBundle\Util\Codes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/profile", service="controller.profile")
 */
class UserController
{
    use RestTrait;

    /**
     * @var UserInterface
     */
    private $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @Route("", name="user_profile")
     * @Method("GET")
     * @return UserInterface
     */
    public function getProfileAction()
    {
        return $this->renderRestView($this->user, Codes::HTTP_OK, [], ['user_profile']);
    }
}
