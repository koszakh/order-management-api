<?php

namespace App\States;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class OrderState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->allowTransition(Created::class, Assigned::class)
            ->allowTransition(Assigned::class, Completed::class)
        ;
    }
}