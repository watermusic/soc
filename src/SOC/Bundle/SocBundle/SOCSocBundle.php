<?php

namespace SOC\Bundle\SocBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SOCSocBundle extends Bundle
{

    public function getParent()
    {
        return 'FOSUserBundle';
    }

}
