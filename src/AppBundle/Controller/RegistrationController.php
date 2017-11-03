<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\House;
use AppBundle\Entity\Flat;
use AppBundle\Entity\Code;
use DateTime;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\RegistrationType;
use AppBundle\Form\MeetingType;

/**
 * @Route("/register", name="register")
 */
class RegistrationController extends BaseController
{
    /**
     * @Route("/")
     * 
     * @param Request $request
     * 
     * @return Response
     */
    public function registerAction(Request $request): Response
    {
        /** @var $userManager UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');
        
        $data['error'] = '';

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $getResponseUserEvent = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $getResponseUserEvent);

        if (null !== ($getResponseUserEvent->getResponse())) {
            return $getResponseUserEvent->getResponse();
        }

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $requestParams = $request->request->all();
                $formData = $requestParams['app_user_registration'];
                
                $checkSmsStatus = $this->isSmsCodeConsist($formData);
                if (!empty($checkSmsStatus['success'])) {
                    $event = new FormEvent($form, $request);
                    $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                    $userManager->updateUser($user);
                    
                    $houseAddress = $formData['address'];
                    $house = $this->getDoctrine()
                            ->getManager()
                            ->getRepository(House::class)
                            ->findOneBy(['address' => $houseAddress]);
                    if (empty($house)) {
                        $house = new House();
                    }
                    $house->addUser($user);
                    $house->setAddress($houseAddress);

                    $em = $this->getDoctrine()
                            ->getManager();
                    $em->persist($house);
                    $em->flush();

                    if (null === ($response = $event->getResponse())) {
                        $response = $this->redirectToRoute('meeting');
                    }

                    $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                    return $response;
                }
                else {
                    $data['error'] = $checkSmsStatus['error'];
                    
                    return $this->render('@FOSUser/Registration/register.html.twig', [
                        'form' => $form->createView(),
                        'error' => $data['error'],
                    ]);
                }
            }

            $formFailureEvent = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $formFailureEvent);

            if (null !== ($response = $formFailureEvent->getResponse())) {
                return $response;
            }
        }

        return $this->render('@FOSUser/Registration/register.html.twig', [
            'form' => $form->createView(),
            'error' => $data['error'],
        ]);
    }
    
    /**
     * @Route("/generateSmsCode", name="code")
     * 
     * @param Request $request
     * 
     * @return Response
     */
    public function generateSmsCode(Request $request): Response
    {
        $mobileNumber = $request->get('mobileNumber');
        
        $rand = rand(1000, 9999);
        
        $code = new Code();
        $code->setMobileNumber($mobileNumber);
        $code->setCreatedAt(new DateTime());
        $code->setValue($rand);
        
        $em = $this->getDoctrine()
                        ->getManager();
        $em->persist($code);
        $em->flush();
        
//        file_get_contents('https://smsc.ru/?login=login&psw=psw&phones='.$mobileNumber.'&mes=Code: '.$rand);
        
        return new JsonResponse([
            'status' => 'success',
        ]);
    }
    
    /**
     * @param array $formData
     * 
     * @return array $data содержит ошибку 'error' или запись о том, что ошибок нет 'success'
     */
    public function isSmsCodeConsist(array $formData): array
    {
        // Достаём код из БД по известному нам мобильному номеру
        $codeFromDataBase = $this->getDoctrine()
                        ->getManager()
                        ->getRepository(Code::class)
                        ->findOneBy([
                            'mobileNumber' => $formData['mobileNumber'],
                            'value' => $formData['code'],
                            'isLogin' => null,
                        ]);
        
        // Если такого кода в базе нет, возвращаем ошибку
        if (empty($codeFromDataBase)) {
            $data['error'] = 'Неверно введён SMS-код';
            
            return $data;
        }
        
        // Проверка, не просрочен ли код
        $createCodeTime = $codeFromDataBase->getCreatedAt();
        $checkTime = (new DateTime())->modify('-5 minutes'); // время действия кода - 5 минут
        
        if ($checkTime > $createCodeTime) { 
            $data['error'] = 'Данный SMS-код уже недействителен. Запросите новый код.';
            
            return $data;
        }
        
        // Если же ошибок не обнаружено
        $codeFromDataBase->setIsLogin(1);
        
        $em = $this->getDoctrine()
                ->getManager();
        $em->persist($codeFromDataBase);
        $em->flush();
        
        $data['success'] = 'Пользователь идентифицирован';
                
        return $data;
    }
    
    /**
     * @param Request $request
     * 
     * @return Response
     * 
     * @Route("/2_meeting", name="meeting")
     */
    public function meetingAction(Request $request): Response
    {
        $form = $this->createForm(MeetingType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $requestParams = $request->request->all();
            $formData = $requestParams['app_meeting_registration'];
                    
            $user = $this->getUser();
            $user->setEmail($formData['email']);
            
            $house = $user->getHouse();
            $house->setFirstFlatNumber($formData['firstFlatNumber']);
            $house->setLastFlatNumber($formData['lastFlatNumber']);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($house);
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('fns_registration');
        }

        return $this->render('registration/meeting.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/3_fns_registration", name="fns_registration")
     * 
     * @return Response
     */
    public function fnsRegistrationAction(): Response
    {
        return $this->render('registration/fns_registration.html.twig');
    }
}
