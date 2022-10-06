<?php
// src/Controller/UserController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{

    /**
     * @Route("/user/{name}") #, name="app_user_notifications"
     */

    public function notifications(string $name = '...'): Response
    {
        // получить информацию пользователя и уведомления каким-то образом
        $userFirstName = $name;
        $userNotifications = ['...', '...'];

        // путь шаблона - это относительн путь файла из `templates/`
        return $this->render('user/notifications.html.twig', [
            // этот массив определяет переменные, переданные шаблону, где ключ - это
            // имя переменной, а значение - значение переменной
            // (Twig рекомендует использование имен переменных snake_case : 'foo_bar' вместо 'fooBar')
            'user_first_name' => $userFirstName,
            'notifications' => $userNotifications,
        ]);
    }
}