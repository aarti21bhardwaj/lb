<?php
namespace App\Auth;

use Adldap\Adldap;
use Cake\Auth\FormAuthenticate;
use Cake\Controller\ComponentRegistry;
use Cake\Network\Request;
use Cake\Network\Response;
use ActiveDirectoryAuthenticate\Auth\AdldapAuthenticate;

class WindowsAdAuthenticate extends AdldapAuthenticate
{

    /**
     * Connect to Active Directory on behalf of a user and return that user's data.
     *
     * @param string $username The username (samaccountname).
     * @param string $password The password.
     * @return mixed False on failure. An array of user data on success.
     */
    public function findAdUser($username, $password)
    {
        try {
            $this->ad->connect('default');
            if ($this->provider->auth()->attempt($username, $password, true)) {
                return $this->_findUser($username);
            }

            return false;
        } catch (\Adldap\Exceptions\Auth\BindException $e) {
            throw new \RuntimeException('Failed to bind to LDAP server. Check Auth configuration settings.');
        }
    }
}
