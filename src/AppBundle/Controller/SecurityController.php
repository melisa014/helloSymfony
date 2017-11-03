<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Code;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class SecurityController extends Controller
{
    /**
     * @Route("login", name="login")
     * 
     * @param Request $request
     * 
     * @return Response
     */
    public function loginAction(Request $request): Response
    {
        $data = [
            'error' => null,
            'csrf_token' => null,
        ];
        
        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')
                ->getToken('authenticate')
                ->getValue()
            : null;
               
        $data['csrf_token'] = $csrfToken;
        
        if (!empty($request->get('submit'))) {
            $formData = $request->get('form');
//            $checkSmsStatus = $this->isSmsCodeConsist($formData);
            
//            if (!empty($checkSmsStatus['error'])) {
//                $data['error'] = $checkSmsStatus['error'];
//                
//                return $this->renderLogin($data);
//            }
            
            $mobileNumber = $formData['mobileNumber'];
            $user = $this->getDoctrine()
                    ->getManager()
                    ->getRepository(User::class)
                    ->findOneBy(['mobileNumber' => $mobileNumber]);
            
            if (empty($user)) {
                $data['error'] = 'Номер телефона должен начинаться с +7 и должен быть записан без пробелов (например, +79991234567).'
                        . ' Возможно, Вы ещё не зарегистрированы.';

                return $this->renderLogin($data);
            }

            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')
                    ->setToken($token);

            $this->get('session')
                    ->set('_security_main', serialize($token));

            $this->get('event_dispatcher')
                    ->dispatch('security.interactive_login', new InteractiveLoginEvent($request, $token));

            return $this->redirectToRoute('homepage');
        }

        return $this->renderLogin($data); 
    }

    /**
     * @param array $data
     *
     * @return Response
     */
    protected function renderLogin(array $data): Response
    {
        $form = $this->createFormBuilder($data)
            ->add('mobileNumber', TextType::class, [
                'label' => 'Мобильный телефон',
                'attr'=> [
                    'placeholder' => '+7**********',
                    'class' => 'info-input',
                ],
            ])
            ->add('code', TextType::class, [
                'label' => 'Код из СМС',
                'attr'=> [
                    'placeholder' => '9876',
                    'class' => 'info-input',
                ],
                'mapped' => false,
            ])
            ->getForm();
        
        return $this->render('FOSUserBundle/security/login.html.twig', [
            'form' => $form->createView(),
            'error' => $data['error'],
            'csrf_token' => $data['csrf_token'],
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
     * @return Response
     * 
     * @Route("/logout")
     */
    public function logoutAction(): Response
    {
        $this->get('security.token_storage')
                ->setToken(null);
        $this->get('session')
                ->invalidate();
        
        return $this->redirectToRoute('homepage');
    }
}
