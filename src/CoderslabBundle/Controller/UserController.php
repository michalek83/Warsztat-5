<?php

namespace CoderslabBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class UserController extends Controller
{
    /**
     * @Route("/new", name="newUser")
	 * @Method({"GET"})
     */
    public function newUserGetAction()
    {
        return $this->render('CoderslabBundle:User:new_user_get.html.twig', array(
            // ...
        ));
    }

	/**
	 * @Route("/new", name="newUser")
	 * @Method("POST")
	 */
	public function newUserPostAction()
	{
		return $this->render('CoderslabBundle:User:new_user_post.html.twig', array(
			// ...
		));
	}

    /**
     * @Route("/{id}/modify", name="modifyUser", requirements={"id": "\d+"})
	 * @Method("GET")
     */
    public function modifyUserGetAction()
    {
        return $this->render('CoderslabBundle:User:modify_user.html.twig', array(
            // ...
        ));
    }

	/**
	 * @Route("/{id}/modify", name="modifyUser", requirements={"id": "\d+"})
	 * @Method("POST")
	 */
	public function modifyUserPostAction()
	{
		return $this->render('CoderslabBundle:User:modify_user.html.twig', array(
			// ...
		));
	}

    /**
     * @Route("/{id}/delete", name="deleteUser", requirements={"id": "\d+"})
	 * @Method("GET")
     */
    public function deleteUserAction()
    {
        return $this->render('CoderslabBundle:User:delete_user.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/{id}", name="showUserById", requirements={"id": "\d+"})
	 * @Method("GET")
     */
    public function showUserByIdAction()
    {
        return $this->render('CoderslabBundle:User:show_user_by_id.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/", name="showAllUsers")
	 * @Method("GET")
     */
    public function showAllUsersAction()
    {
        return $this->render('CoderslabBundle:User:show_all_users.html.twig', array(
            // ...
        ));
    }

}
