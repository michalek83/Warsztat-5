<?php

namespace CoderslabBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testNewuser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/newUser');
    }

    public function testModifyuser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/modifyUser');
    }

    public function testDeleteuser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/deleteUser');
    }

    public function testShowuserbyid()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/showUserById');
    }

    public function testShowallusers()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/showAllUsers');
    }

}
