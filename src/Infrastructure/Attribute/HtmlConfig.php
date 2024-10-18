<?php

namespace App\Infrastructure\Attribute;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class HtmlConfig
{

    public function __construct(
        private string $page_template = "",
        private bool $secure = false,
        private ?string $error_template = null,
        private string $redirectWithoutLogin = "account_login",
        private string $redirectWithLogin = "account",
        private bool $saveRoute = false,
        private bool $showWithLogin = true,
        private bool $showPrevious = false
    ) {
    }

    public function secure(): bool
    {
        return $this->secure;
    }

    public function errorTemplate(): ?string
    {
        return $this->error_template;
    }

    public function pageTemplate(): string
    {
        return $this->page_template;
    }

    public function redirectWithoutLogin(): string
    {
        return $this->redirectWithoutLogin;
    }

    public function redirectWithLogin(): string
    {
        return $this->redirectWithLogin;
    }

    public function saveRoute(): bool
    {
        return $this->saveRoute;
    }

    public function showWithLogin(): bool
    {
        return $this->showWithLogin;
    }

    public function showPrevious(): bool
    {
        return $this->showPrevious;
    }
}
