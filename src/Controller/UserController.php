<?php
// src/Controller/UserController.php
namespace App\Controller;

use App\Constants;
use App\Services\DataLoader;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class UserController extends AbstractController
{
    protected $logger;
    private $pageid;

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    /**
     * @Route("/user/{name}/{pageid}", name="app_user_notifications")
     */
    public function notifications(string $name = '...', string $pageid = 'default'): Response
    {
        $this->pageid = $pageid;
        $version = Constants\VERSION;
        $view = Constants\getView($this->pageid);
        $userFirstName = $name;
        $userNotifications = ['...', '...'];

        $this->logger->info("Show: $this->pageid [$view]");

        $loader = new DataLoader();
        $data = $loader->getData($view);

        dd($data);

        return $this->render('user/notifications.html.twig', [
            'version' => $version,
            'view' => $view,
            'user_first_name' => $userFirstName,
            'notifications' => $userNotifications,
        ]);
    }
}
