<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\NotificationRepository;
final class NotificationsController extends AbstractController
{
    #[Route('/notifications', name: 'app_notifications')]
   public function notifications(NotificationRepository  $notificationRepository): Response
{
    $notifications = $notificationRepository->findBy(
        ['user' => $this->getUser()],
        ['createdAt' => 'DESC']
    );

    return $this->render('notifications/index.html.twig', [
        'notifications' => $notifications,
    ]);

}

}