<?php

namespace Somecode\Framework\Tests;

class AreaWeb
{
    public function __construct(
        private readonly Telegram $telegram,
        private readonly YouTube $youTube,
    ) {
    }

    public function getTelegram(): Telegram
    {
        return $this->telegram;
    }

    public function getYouTube(): YouTube
    {
        return $this->youTube;
    }
}
