<?php

namespace App\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class CoreController extends AbstractController
{
    protected Params $params;

    public function __construct(
        protected RequestStack $request
    )
    {
        $this->params = $this->parseData($request->getCurrentRequest());
    }

    protected function parseData(Request $request): Params
    {
        $content = $request->getContent();
        $params = [];

        if (!empty($content)) {
            $params = json_decode($content, true);
        }

        return new Params(
            array_merge(
                $request->query->all(),
                $request->request->all(),
                $params
            )
        );
    }
}
