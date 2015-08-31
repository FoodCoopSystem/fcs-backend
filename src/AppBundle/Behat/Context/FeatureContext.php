<?php
namespace AppBundle\Behat\Context;

use AppBundle\Entity\AccessToken;
use AppBundle\Entity\User;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Codifico\ParameterBagExtension\Context\ParameterBagDictionary;

class FeatureContext implements Context, KernelAwareContext
{
    use KernelDictionary;
    use ParameterBagDictionary;

    /**
     * @Given /^I am authenticated as "([^"]*)"$/
     */
    public function iAmAuthenticatedAsAnd($username)
    {
        $accessToken = $this->getAccessToken($username);

        $this->getParameterBag()->set('token', $accessToken->getToken());
    }

    /**
     * @return \FOS\OAuthServerBundle\Model\ClientInterface
     */
    private function getOAuthClient()
    {
        $clientManager = $this->getContainer()->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setRedirectUris(['http://example.com']);
        $client->setAllowedGrantTypes(array('token', 'authorization_code', 'password'));
        $clientManager->updateClient($client);

        return $client;
    }

    private function getUser($username)
    {
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findOneBy(['username' => $username])
        ;

        if (!$user) {
            $user = new User($username, 'password');
        }

        $roles = $user->getRoles();
        foreach (['ROLE_USER', 'ROLE_API'] as $role) {
            if (!in_array($role, $roles)) {
                $roles[] = $role;
            }
        }
        $user->setRoles($roles);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($user);
        $manager->flush();

        return $user;
    }

    /**
     * @param $username
     *
     * @return AccessToken
     */
    private function getAccessToken($username)
    {
        $token = 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA';
        $client = $this->getOAuthClient();
        $user = $this->getUser($username);
        $expireAt = strtotime('+1 year');

        $accessToken = $this->getDoctrine()
            ->getRepository('AppBundle:AccessToken')
            ->findOneBy(['token' => $token])
        ;

        if (!$accessToken) {
            $accessToken = new AccessToken();
        }

        $accessToken->setToken($token);
        $accessToken->setClient($client);
        $accessToken->setUser($user);
        $accessToken->setExpiresAt($expireAt);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($accessToken);
        $manager->flush();

        return $accessToken;
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private function getDoctrine()
    {
        return $this->getContainer()->get('doctrine');
    }
}
