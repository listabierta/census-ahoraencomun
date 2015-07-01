<?php
namespace Listabierta\Bundle\MunicipalesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Listabierta\Bundle\MunicipalesBundle\Form\Type\Census\CensusStepConditionsType;
use Listabierta\Bundle\MunicipalesBundle\Form\Type\Census\CensusStep2Type;
use Listabierta\Bundle\MunicipalesBundle\Form\Type\Census\CensusStep3Type;
use Listabierta\Bundle\MunicipalesBundle\Form\Type\Census\CensusStepVerifyType;
use Listabierta\Bundle\MunicipalesBundle\Entity\CensusUser;

use Listabierta\Bundle\MunicipalesBundle\Form\Type\RecoverPasswordType;

use Listabierta\Bundle\MunicipalesBundle\Entity\PhoneVerified;

use Listabierta\Bundle\MunicipalesBundle\Entity\RecoveryAdmin;

use Symfony\Component\Form\FormError;

use Listabierta\Bundle\MunicipalesBundle\Form\ChangePasswordType;
use Listabierta\Bundle\MunicipalesBundle\Form\Model\ChangePassword;

/**
 * CensusController
 * 
 * @author Ángel Guzmán Maeso <shakaran@gmail.com>
 */
class CensusController extends Controller
{
	/**
	 * 
	 * @param Request $request
	 */
    public function indexAction(Request $request = NULL)
    {
        $this->stepConditionsAction($request);
    }
    
    /**
     * Step for show the conditions for register
     * 
     * @author Ángel Guzmán Maeso <shakaran@gmail.com>
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function stepConditionsAction(Request $request = NULL)
    {
    	$session = $this->getRequest()->getSession();
    	 
    	$form = $this->createForm(new CensusStepConditionsType(), NULL, array(
    			'action' => $this->generateUrl('census_step1'),
    			'method' => 'POST',
    		)
    	);
    	 
    	$form->handleRequest($request);
    
    	$ok = TRUE;
    	if ($form->isValid())
    	{
    		$conditions = $form['conditions']->getData();
    		
    		if(empty($conditions) || $conditions != 'yes')
    		{
    			$form->addError(new FormError('Debes aceptar las condiciones de alta para continuar'));
    			$ok = FALSE;
    		}
    
    		if($ok)
    		{
    			$session->clear();
    			$session->set('conditions', $conditions);
    	   
    			return $this->step2LocationAction($request);
    		}
    	}
    	 
    	return $this->render('MunicipalesBundle:Census:step_conditions.html.twig', array(
    			'form' => $form->createView(),
    	));
    }
 

    /**
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function step2LocationAction(Request $request = NULL)
    {
    	$session = $this->getRequest()->getSession();
    	$entity_manager = $this->getDoctrine()->getManager();
    
    	$conditions = $session->get('conditions');
    
    	// Check conditions in step 1 for avoid bad usage
    	if(empty($conditions) || $conditions!= 'yes')
    	{
    		return $this->redirect($this->generateUrl('census_step1'), 301);
    	}
    
    	$form = $this->createForm(new CensusStep2Type(), NULL, array(
    			'action' => $this->generateUrl('census_step2'),
    			'method' => 'POST',
    	)
    	);
    
    	$form->handleRequest($request);
    
    	$ok = TRUE;
    	if ($form->isValid())
    	{
    		if($ok)
    		{
    			return $this->step3RegisterAction($request);
    		}
    	}
    
    	return $this->render('MunicipalesBundle:Census:step2_location.html.twig', array(
    			'form' => $form->createView(),
    	));
    }
    
    /**
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function step3RegisterAction(Request $request = NULL)
    {
    	$session = $this->getRequest()->getSession();
    	$entity_manager = $this->getDoctrine()->getManager();

    	$form = $this->createForm(new CensusStep3Type(), NULL, array(
    			'action' => $this->generateUrl('census_step3'),
			    'method' => 'POST',
    			)
    	);
    	
    	$form->handleRequest($request);

    	$ok = TRUE;

    	if ($form->isValid())
    	{
    		$name     = $form['name']->getData();
    		$lastname = $form['lastname']->getData();
    		$dni      = $form['dni']->getData();
    		$email    = $form['email']->getData();
    		$phone    = $form['phone']->getData();

    		$extra    = $form->getExtraData();
    		
    		$province_geolocation = $extra['province_geolocation'];
    		$province_latitude    = $extra['province_latitude'];
    		$province_longitude   = $extra['province_longitude'];

    		$census_user_repository = $entity_manager->getRepository('Listabierta\Bundle\MunicipalesBundle\Entity\CensusUser');
    		
    		$census_user_dni = $census_user_repository->findOneBy(array('dni' => $dni));
    			
    		if(!empty($census_user_dni))
    		{
    			$form->addError(new FormError('Ya existe un usuario de censo registrado con el dni ' . $dni));
    			$ok = FALSE;
    		}
    		
    		$census_user_email = $census_user_repository->findOneBy(array('email' => $email));
    		 
    		if(!empty($census_user_email))
    		{
    			$form->addError(new FormError('Ya existe un usuario de censo registrado con el email ' . $email));
    			$ok = FALSE;
    		}
    		
    		$census_user_phone = $census_user_repository->findOneBy(array('phone' => $phone));

    		if(!empty($census_user_phone))
    		{
    			$form->addError(new FormError('Ya existe un usuario de censo registrado con el teléfono ' . $phone));
    			$ok = FALSE;
    		}

    		if($ok)
    		{
    			$session->set('geolocation_allowed', TRUE);

    			$entity_manager = $this->getDoctrine()->getManager();
    			
    			// Store info in database AdminCandidacy
    			$census_user = new CensusUser();
    			$census_user->setName($name);
    			$census_user->setLastname($lastname);
    			$census_user->setDni($dni);
    			$census_user->setEmail($email);
    			$census_user->setPhone($phone);
    			$census_user->setProvinceGeolocation($province_geolocation);
    			$census_user->setProvinceLatitude($province_latitude);
    			$census_user->setProvinceLongitude($province_longitude);

    			$entity_manager->persist($census_user);
    			$entity_manager->flush();
    			
    			$current_time = time();
    			$token = sha1($census_user->getId() + rand(0, 5000) + $current_time);
    			 
    			$census_user->setToken($token);
    			
    			$entity_manager->persist($census_user);
    			$entity_manager->flush();
    			
    			// Store email and phone in database as pending PhoneVerified without timestamp
    			$phone_verified = new PhoneVerified();
    			$phone_verified->setPhone($phone);
    			$phone_verified->setEmail($email);
    			$phone_verified->setTimestamp(0);
    			$phone_verified->setMode(PhoneVerified::MODE_CENSUS_USER);

    			$entity_manager->persist($phone_verified);
    			$entity_manager->flush();

    			$session->set('census_user_id', $census_user->getId());
	    		$session->set('name', $name);
	    		$session->set('lastname', $lastname);
	    		$session->set('dni', $dni);
	    		$session->set('email', $email);
	    		$session->set('phone', $phone);
	    		$session->set('token', $token);
	    		$session->set('province_geolocation', $province_geolocation);
	    		$session->set('province_latitude', $province_latitude);
	    		$session->set('province_longitude', $province_longitude);
	    		
				return $this->step4VerifyAction($request);
    		}
    	}
    	
    	return $this->render('MunicipalesBundle:Census:step3_register.html.twig', array(
    			'form' => $form->createView(),
    			'enable_geolocation' => TRUE,
    	));
    }
    
    /**
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function step4VerifyAction(Request $request = NULL)
    {
    	$session = $this->getRequest()->getSession();
    
    	$form = $this->createForm(new CensusStepVerifyType(), NULL, array(
    			'action' => $this->generateUrl('census_step4'),
    			'method' => 'POST',
    		)
    	);
    
    	$form->handleRequest($request);
    
    	$ok = TRUE;
    	if ($form->isValid())
    	{
    		$phone = $session->get('phone', NULL);
    		
    		if(empty($phone))
    		{
    			$form->addError(new FormError('El número de móvil no esta presente. ¿Sesión caducada?'));
    			$ok = FALSE;
    		}
    		
    		$email = $session->get('email', NULL);
    		if(empty($email))
    		{
    			$form->addError(new FormError('El email no esta presente. ¿Sesión caducada?'));
    			$ok = FALSE;
    		}

    		if($ok)
    		{
    			$entity_manager = $this->getDoctrine()->getManager();
    			$phone_verified_repository = $entity_manager->getRepository('Listabierta\Bundle\MunicipalesBundle\Entity\PhoneVerified');
    			
    			$phone_status = $phone_verified_repository->findOneBy(array('phone' => $phone, 'email' => $email));
    			
    			if(empty($phone_status) || $phone_status->getTimestamp() == 0)
    			{
	    			$form->addError(new FormError('El número de móvil <b>' . $phone . '</b> aún no ha sido verificado'));
	    			$ok = FALSE;
    			}
    		}
    			
    		if($ok)
    		{
    			return $this->step5FinishAction($request);
    		}
    	}
    
    	return $this->render('MunicipalesBundle:Census:step4_verify.html.twig', array(
    			'form' => $form->createView(),
    			'errors' => $form->getErrors(),
    	));
    }
    
    /**
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function step5FinishAction(Request $request = NULL)
    {
    	$session = $this->getRequest()->getSession();
    	
    	$census_mail = $session->get('email', NULL);
    	
    	if(!empty($census_mail))
    	{
	    	// Send mail with census confirmation
	    	$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
	    	
	    	$message = \Swift_Message::newInstance()
	    	->setSubject('Confirmación de inscripción en el censo')
	    	->setFrom('confirmacion@' . rtrim($host, '.'), 'Confirmacion Censo')
	    	->setTo($census_mail)
	    	->setBody(
	    			$this->renderView(
	    					'MunicipalesBundle:Mail:census_confirmation.html.twig',
	    					array(
	    					)
	    			), 'text/html'
	    	);
	    		
	    	$this->get('mailer')->send($message);
	    	
	    	
	    	/*
	    	$census_token = $session->get('token', NULL);
	    	
	    	if(!empty($census_token))
	    	{
		    	// Send token mail
		    	$message = \Swift_Message::newInstance()
		    	->setSubject('Enlace para comenzar la votación')
		    	->setFrom('info@' . rtrim($host, '.'), 'Info Censo')
		    	->setTo($census_mail)
		    	->setBody(
		    			$this->renderView(
		    					'MunicipalesBundle:Mail:token_link.html.twig',
		    					array(
		    							'token' => $census_token,
		    					)
		    			), 'text/html'
		    	);
		    	
		    	$this->get('mailer')->send($message);
	    	}
	    	*/
    	}
    	
    	return $this->render('MunicipalesBundle:Census:step5_finish.html.twig', array());
    }
}
