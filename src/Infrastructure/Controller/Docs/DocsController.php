<?php

namespace App\Infrastructure\Controller\Docs;

use App\Infrastructure\ExceptionsApi\HandlerApiExceptions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DocsController extends AbstractController
{

    #[Route('/docs/{type}', name: 'core_docs', methods: ['GET'])]
    public function exception($type, HandlerApiExceptions $handlerApiExceptions): Response
    {
        return $this->render("@Docs/base.docs.twig", [
            "exception" => $handlerApiExceptions->byType($type)
        ]);
    }
}