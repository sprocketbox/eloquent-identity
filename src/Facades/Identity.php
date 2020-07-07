<?php

namespace Sprocketbox\Eloquent\Identity\Facades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use Sprocketbox\Eloquent\Identity\IdentityManager;
use Sprocketbox\Eloquent\Identity\ModelIdentity;

/**
 * Identity Facade
 *
 * @method static IdentityManager getInstance()
 * @method static bool hasIdentity(ModelIdentity $identity)
 * @method static Model|null getIdentity(ModelIdentity $identity)
 * @method static IdentityManager storeIdentity(ModelIdentity $identity, Model $model)
 * @method static IdentityManager removeIdentity(ModelIdentity $identity)
 * @method static array allIdentities()
 *
 * @package Sprocketbox\Eloquent\Identity\Facades
 */
class Identity extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'eloquent.identity';
    }
}