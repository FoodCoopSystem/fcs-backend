<?php

namespace AppBundle\Controller;
use FOS\RestBundle\Util\Codes;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController
{
    use RestTrait;

    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @return \FOS\RestBundle\View\View|null
     */
    public function getProfileAction()
    {
        return $this->renderRestView($this->user, Codes::HTTP_OK, [], ['user_profile']);
    }
}
