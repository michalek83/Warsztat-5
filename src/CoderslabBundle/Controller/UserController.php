<?php

namespace CoderslabBundle\Controller;

use CoderslabBundle\Entity\User;
use CoderslabBundle\Form\UserModifyType;
use CoderslabBundle\Repository\UserRepository;
use CoderslabBundle\Form\UserNewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class UserController extends Controller
{
	/**
	 * @Route("/new", name="newUser")
	 * @Method({"GET"})
	 */
	public function newUserGetAction()
	{
		$user = new User();
		$form = $this->createForm( UserNewType::class, $user );

		return $this->render( 'CoderslabBundle:User:new_user.html.twig', array(
			'form' => $form->createView()
		) );
	}

	/**
	 * @Route("/new")
	 * @Method("POST")
	 */
	public function newUserPostAction( Request $request )
	{
		$user = new User();
		$form = $this->createForm( UserNewType::class, $user );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			$user = $form->getData();
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist( $user );
			$em->flush();

			$userRepository = $this->getDoctrine()->getRepository( 'CoderslabBundle:User' );
			$newUser = $userRepository->findOneBy( [ 'name' => $user->getName(),
				'surname' => $user->getSurname(),
				'description' => $user->getDescription() ] );

			return $this->redirectToRoute( 'showUserById', array( 'id' => $newUser->getId() ) );
		}

		return $this->redirectToRoute( 'newUser' );
	}

	/**
	 * @Route("/{id}/modify", name="modifyUser", requirements={"id": "\d+"})
	 * @Method("GET")
	 */
	public function modifyUserGetAction( $id )
	{
		$user = new User();
		$form = $this->createForm( UserModifyType::class, $user );

		$userRepository = $this->getDoctrine()->getRepository( 'CoderslabBundle:User' );
		$userToModify = $userRepository->findBy( [ 'id' => $id ] );

		return $this->render( 'CoderslabBundle:User:modify_user.html.twig', array(
			'form' => $form->createView(),
			'user' => $userToModify
		) );
	}

	/**
	 * @Route("/{id}/modify", requirements={"id": "\d+"})
	 * @Method("POST")
	 */
	public function modifyUserPostAction( $id )
	{
		$user = new User();
		$form = $this->createForm( UserModifyType::class, $user );

//		$userRepository = $this->getDoctrine()->getRepository( 'CoderslabBundle:User' );
//		$userToModify = $userRepository->findBy( ['id' => $id] );

		if ( $form->isSubmitted() && $form->isValid() ) {
			$user = $form->getData();
			$em = $this->getDoctrine()->getEntityManager();
			var_dump( $em );
//			$em->flush();

//			$userRepository = $this->getDoctrine()->getRepository( 'CoderslabBundle:User' );
//			$newUser = $userRepository->findOneBy( ['name' => $user->getName(),
//				'surname' => $user->getSurname(),
//				'description' => $user->getDescription()] );

			return $this->redirectToRoute( 'showUserById', array( 'id' => $newUser->getId() ) );
		}

		return $this->redirectToRoute( 'modifyUser' );

	}

	/**
	 * @Route("/{id}/delete", name="deleteUser", requirements={"id": "\d+"})
	 * @Method("GET")
	 */
	public function deleteUserAction( $id, Request $request )
	{
		$em = $this->getDoctrine()->getEntityManager();
		$user = $em->getRepository( 'CoderslabBundle:User' )->find( $id );
		$em->remove( $user );
		$em->flush();

		$session = $request->getSession();
		$deleteConfirmation = "Contact " . $user->getName() . " " . $user->getSurname() . " was deleted.";
		$session->set( 'deleteConfirmation', $deleteConfirmation );

		return $this->redirectToRoute( 'showAllUsers' );
	}

	/**
	 * @Route("/{id}", name="showUserById", requirements={"id": "\d+"})
	 * @Method("GET")
	 */
	public function showUserByIdAction( $id )
	{
		$userRepository = $this->getDoctrine()->getRepository( 'CoderslabBundle:User' );
		$user = $userRepository->findBy( [ 'id' => $id ] );

		return $this->render( 'CoderslabBundle:User:show_user_by_id.html.twig', array(
			'user' => $user
		) );
	}

	/**
	 * @Route("/", name="showAllUsers")
	 * @Method("GET")
	 */
	public function showAllUsersAction( Request $request )
	{
		$UsersRepository = $this->getDoctrine()->getRepository( 'CoderslabBundle:User' );
		$allUsers = $UsersRepository->findBy( [], [ 'surname' => 'ASC', 'name' => 'ASC' ] );

		$session = $request->getSession();
		$deleteConfirmation = $session->get( 'deleteConfirmation', null );
		$session->set( 'deleteConfirmation', null );

		return $this->render( 'CoderslabBundle:User:show_all_users.html.twig', array(
			'allUsers' => $allUsers,
			'deleteConfirmation' => $deleteConfirmation
		) );
	}

}
