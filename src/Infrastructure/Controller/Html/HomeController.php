<?php

namespace App\Infrastructure\Controller\Html;

use App\Application\Query\Home\HomeCommand;
use App\Infrastructure\Attribute\HtmlConfig;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends HtmlController
{
    /**
     * @throws ExceptionInterface
     */
    #[Route('/', name: 'home')]
    #[HtmlConfig(page_template: '@current/pages/home.html.twig', secure: false)]
    public function home(): Response
    {
        return $this->handle(new HomeCommand());
    }
}
