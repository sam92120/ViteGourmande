<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait; // permet de rediriger l'utilisateur vers la page qu'il voulait visiter avant de se connecter

    public const LOGIN_ROUTE = 'app_login'; // route de la page de connexion

    public function __construct(
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    /**
     * @param Request $request
     * @return Passport
     */
    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', ''); // valider l'email
        $password = $request->request->get('password', ''); // valider le mot de passe
        $csrfToken = $request->request->get('_csrf_token', ''); // valider le token CSRF

        $request->getSession()->set(
            SecurityRequestAttributes::LAST_USERNAME, // permet de pré-remplir le champ email avec l'email saisi par l'utilisateur
            $email
        );

        return new Passport(
            new UserBadge($email), // valider l'utilisateur
            
            new PasswordCredentials($password), // valider le mot de passe et flaguer l'utilisateur comme connecté
            
            [
                new CsrfTokenBadge('authenticate', $csrfToken), // valider le token CSRF
                new RememberMeBadge(), // valider le cookie "remember me"
            ]
        );
        
    }

    public function onAuthenticationSuccess(
        Request $request, // la requête HTTP
        TokenInterface $token, // l'objet qui contient les informations de l'utilisateur connecté
        string $firewallName // le nom du pare-feu de sécurité
    ): ?Response {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
         }

        $user = $token->getUser(); // récupérer l'utilisateur connecté

        if (method_exists($user, 'isActive') && !$user->isActive()) {
            throw new DisabledException('Votre compte est désactivé.'); // l'utilisateur est désactivé
        }

        $roles = $user->getRoles();

        if (in_array('ROLE_ADMIN', $roles, true)) {
            return new RedirectResponse($this->urlGenerator->generate('admin')); // l'utilisateur est un administrateur
        }

        if (in_array('ROLE_EMPLOYEE', $roles, true)) {
            return new RedirectResponse($this->urlGenerator->generate('employee'));
        }

        return new RedirectResponse($this->urlGenerator->generate('app_accueil'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE); // retourne l'URL de la page de connexion
    }
}