<?php

namespace CoderslabBundle\Controller;

use CoderslabBundle\Entity\Address;
use CoderslabBundle\Entity\User;
use CoderslabBundle\Form\UserModifyType;
use CoderslabBundle\Repository\UserRepository;
use CoderslabBundle\Form\UserType;
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
		$form = $this->createForm( UserType::class, $user );

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
		$form = $this->createForm( UserType::class, $user );
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
	public function modifyUserGetAction( $id, Request $request )
	{
		$userRepository = $this->getDoctrine()->getRepository( 'CoderslabBundle:User' );
		$userToModify = $userRepository->find( $id );
		$addressRepository = $this->getDoctrine()->getRepository( 'CoderslabBundle:Address' );
		$userAddress = $addressRepository->find( $userToModify->getAddress() );
		$phoneRepository = $this->getDoctrine()->getRepository( 'CoderslabBundle:Phone' );
		$userPhone = $phoneRepository->findByUser( $id );
		$userPhone = $userPhone[ 0 ];

		return $this->render( 'CoderslabBundle:User:show_user_by_id.html.twig', array(
			'user' => $userToModify,
			'userAddress' => $userAddress,
			'userPhone' => $userPhone
		) );
	}

	/**
	 * @Route("/{id}/modify", requirements={"id": "\d+"})
	 * @Method("POST")
	 */
	public function modifyUserPostAction( Request $request, $id )
	{
		$em = $this->getDoctrine()->getEntityManager();

		$name = $request->request->get( 'name' );
		$surname = $request->request->get( 'surname' );
		$description = $request->request->get( 'description' );
		$city = $request->request->get( 'city' );
		$street = $request->request->get( 'street' );
		$houseNumber = $request->request->get( 'houseNumber' );
		$flatNumber = $request->request->get( 'flatNumber' );
		$phoneNumber = $request->request->get( 'phoneNumber' );
		$phoneType = $request->request->get( 'phoneType' );

		$userRepository = $this->getDoctrine()->getRepository( 'CoderslabBundle:User' );
		$userToModify = $userRepository->find( $id );
		$addressRepository = $this->getDoctrine()->getRepository( 'CoderslabBundle:Address' );
		$userAddress = $addressRepository->find( $userToModify->getAddress() );

		if ( !$userToModify->getAddress() ) {      //Dopisywanie nowego adresu
			$address = new Address();
			$address->setCity( $city );
			$address->setStreet( $street );
			$address->setHouseNumber( $houseNumber );
			$address->setFlatNumber( $flatNumber );
			$em->persist( $address );
			$userToModify->setAddress( $address );
			$em->persist( $userToModify );
		} else {                                    //Modyfikacja istniejącego adresu
			if ( $city ) {
				$userAddress->setCity( $city );
			}
			if ( $street ) {
				$userAddress->setStreet( $street );
			}
			if ( $houseNumber ) {
				$userAddress->setHouseNumber( $houseNumber );
			}
			if ( $flatNumber ) {
				$userAddress->setFlatNumber( $flatNumber );
			}
			$em->persist( $userAddress );
		}

		if ( $name ) {
			$userToModify->setName( $name );
		}
		if ( $surname ) {
			$userToModify->setSurname( $surname );
		}
		if ( $description ) {
			$userToModify->setDescription( $description );
		}

		$em->flush();

		return $this->redirectToRoute( 'modifyUser', array( 'id' => $id ) );

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
		$userToModify = $userRepository->find( $id );
		$addressRepository = $this->getDoctrine()->getRepository( 'CoderslabBundle:Address' );
		if ( $userToModify->getAddress() ) {
			$userAddress = $addressRepository->find( $userToModify->getAddress() );
		} else {
			$userAddress = null;
		}
		$phoneRepository = $this->getDoctrine()->getRepository( 'CoderslabBundle:Phone' );
		$userPhone = $phoneRepository->findByUser( $id );
		if ( count( $userPhone ) == 0 ) {
			$userPhone = null;
		} else {
			$userPhone = $userPhone[ 0 ];
		}
		$emailRepository = $this->getDoctrine()->getRepository( 'CoderslabBundle:Email' );
		$userEmail = $emailRepository->findByUser($id);
		if ( count( $userEmail ) == 0 ) {
			$userEmail = null;
		} else {
			$userEmail = $userEmail[ 0 ];
		}

		return $this->render( 'CoderslabBundle:User:show_user_by_id.html.twig', array(
			'user' => $userToModify,
			'userAddress' => $userAddress,
			'userPhone' => $userPhone,
			'userEmail' => $userEmail
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
