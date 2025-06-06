<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Factory;

use Enhavo\Bundle\NewsletterBundle\Model\LocalSubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\ResourceBundle\Factory\FactoryInterface;

interface LocalSubscriberFactoryInterface extends FactoryInterface
{
    public function createFrom(SubscriberInterface $subscriber): LocalSubscriberInterface;

    public function createWithGroupId($groupId): LocalSubscriberInterface;
}
