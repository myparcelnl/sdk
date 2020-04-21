<?php

namespace Gett\MyParcel\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Gett\MyParcel\Entity\MyparcelOrderLabel;

class MyparcelOrderLabelListener
{
    public function preUpdate(MyparcelOrderLabel $label, LifecycleEventArgs $event) {

    }

    public function prePersist(MyparcelOrderLabel $label, LifecycleEventArgs $event) {
        var_dump($event);
    }

}