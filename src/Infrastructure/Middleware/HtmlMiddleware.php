<?php

namespace App\Infrastructure\Middleware;

use App\Infrastructure\Listener\HtmlListener;
use App\Infrastructure\Stamp\ResponseStamp;
use ReflectionException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class HtmlMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Environment $twig,
        private HtmlListener $htmlListener
    )
    {
    }

    /**
     * @throws SyntaxError
     * @throws ReflectionException
     * @throws RuntimeError
     * @throws LoaderError
     * @throws ExceptionInterface
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $status = Response::HTTP_OK;

        // Check the template exist before executing the handler
        $template = ($this->htmlListener->getClass())->pageTemplate();

        if (empty($template)) {
            throw new \RuntimeException('Missing template. Did you forget to add the attribute CoreHtml or the property page_template to the controller method?');
        }

        $loadedTemplate = $this->twig->load($template);

        // Execute the handler
        $envelope = $stack->next()->handle($envelope, $stack);

        // Check the handler is executed (we have the HandleStamp in the envelope)
        /** @var HandledStamp $stamp */
        $stamp = $envelope->last(HandledStamp::class);

        if (!$stamp instanceof HandledStamp) {
            throw new \RuntimeException(sprintf('This middleware only works after "%s" stamp.', HandledStamp::class));
        }

        // Render the template white the data and add the ResponseStamp to the Envelope
        $data = $stamp->getResult();

        $body = $loadedTemplate->render($data);

        return $envelope->with(new ResponseStamp(new Response($body, $status)));
    }
}
