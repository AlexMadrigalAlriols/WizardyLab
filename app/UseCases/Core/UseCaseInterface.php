<?php

namespace App\UseCases\Core;

interface UseCaseInterface
{
    public function action();
    public function handle(): UseCaseResponse;
}
