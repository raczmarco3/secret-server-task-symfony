<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;

Class Secret
{
    #[Assert\NotBlank]
    #[Assert\Type(\String::class)]
    protected $secret;

    #[Assert\NotBlank]
    #[Assert\Type(\Integer::class)]
    protected $expireAfterView;

    #[Assert\NotBlank]
    #[Assert\Type(\Integer::class)]
    protected $expireAfter;

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function setSecret(string $secret): void
    {
        $this->secret = $secret;
    }

    public function getExpireAfterView(): int
    {
        return $this->expireAfterView;
    }

    public function setExpireAfterView(int $expireAfterView): void
    {
        $this->expireAfterView = $expireAfterView;
    }

    public function getExpireAfter(): int
    {
        return $this->expireAfter;
    }

    public function setExpireAfter(int $expireAfter): void
    {
        $this->expireAfter = $expireAfter;
    }

}