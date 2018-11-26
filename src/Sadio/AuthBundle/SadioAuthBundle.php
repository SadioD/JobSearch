<?php

namespace Sadio\AuthBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SadioAuthBundle extends Bundle
{
    // Permet de faire en sorte que AuthBundle hérite de FOSUserBundle
    public function getParent() {
        return 'FOSUserBundle';
    }
}
