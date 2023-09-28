<?php

namespace App\Security;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AppAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    /**
     * @param Request $request
     * @return Passport
     *
     * @throws \InvalidArgumentException Email required
     */
    public function authenticate(Request $request): Passport
    {
        try {
            $badges = $this->getBadges($request);
            $credentials = $this->getPasswordCredentials($request);
            $userBadge = $this->getUserBadge($request);
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException('No required argument.');
        }

        $request->getSession()
            ->set(Security::LAST_USERNAME, $userBadge->getUserIdentifier());

        return new Passport($userBadge, $credentials, $badges);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('admin'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    /**
     * @param Request $request
     * @return BadgeInterface[]
     *
     * @throws \InvalidArgumentException "CSRF token required"
     */
    public function getBadges(Request $request): array
    {
        $csrfToken = $request->request->get('_csrf_token');

        if (! is_string($csrfToken) || trim($csrfToken) === '') {
            throw new \InvalidArgumentException("CSRF token required");
        }

        return [
            new CsrfTokenBadge('authenticate', $csrfToken),
        ];
    }

    /**
     * @param Request $request
     * @return UserBadge
     *
     * @throws \InvalidArgumentException "Email required"
     */
    public function getUserBadge(Request $request): UserBadge
    {
        $email = $request->request->get('email', '');

        if(! is_string($email) || trim($email) === '') {
            throw new \InvalidArgumentException("Email required.");
        }

        return new UserBadge($email);
    }

    /**
     * @param Request $request
     * @return PasswordCredentials
     *
     * @throws \InvalidArgumentException "Password required"
     */
    public function getPasswordCredentials(Request $request): PasswordCredentials
    {
        $password = $request->request->get('password', '');

        if (! is_string($password) || trim($password) === '') {
            throw new \InvalidArgumentException("Password required");
        }
        return new PasswordCredentials($password);
    }
}
