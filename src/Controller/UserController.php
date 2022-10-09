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
    private $pageclass;

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    /**
     * @Route("/user/{name}/{pageid}", name="app_user_notifications")
     */
    public function notifications(Constants $constants, DataLoader $loader, string $name = '...', string $pageid = 'default'): Response
    {
        $this->pageid = $pageid;
        $this->pageclass = $pageid;
        $version = $constants::VERSION;
        #$version = $constants-> GetVersion();
        $view = $constants->getView($this->pageid);
        $userFirstName = $name;
        $userNotifications = ['...', '...'];

        $this->logger->info("Show: $this->pageid [$view]");

        #$loader = new DataLoader();
        $columns = $loader->getMetadata($view);
        $data = $loader->getData($view);

        #print_r($columns);

        return $this->render('user/notifications.html.twig', [
            'version' => $version,
            'view' => $view,
            'columns' => $columns,
            'data' => $data,
            'size' => count($data),
            'id' => $this->pageid,
            'class' => $this->pageclass,
            'user_first_name' => $userFirstName,
            'notifications' => $userNotifications,
        ]);
    }
}
