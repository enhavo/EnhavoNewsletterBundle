<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Strategy\Type;

use Enhavo\Bundle\NewsletterBundle\Event\SubscriberEvent;
use Enhavo\Bundle\NewsletterBundle\Model\SubscriberInterface;
use Enhavo\Bundle\NewsletterBundle\Storage\Storage;
use Enhavo\Bundle\NewsletterBundle\Strategy\StrategyTypeInterface;
use Enhavo\Component\Type\AbstractType;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class StrategyType extends AbstractType implements StrategyTypeInterface
{
    /** @var TranslatorInterface */
    private $translator;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var Storage */
    private $storage;

    /**
     * StrategyType constructor.
     */
    public function __construct(TranslatorInterface $translator, EventDispatcherInterface $eventDispatcher)
    {
        $this->translator = $translator;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function addSubscriber(SubscriberInterface $subscriber, array $options)
    {
        return null;
    }

    public function activateSubscriber(SubscriberInterface $subscriber, array $options)
    {
    }

    public function removeSubscriber(SubscriberInterface $subscriber, array $options)
    {
        $this->getStorage()->removeSubscriber($subscriber);

        return 'subscriber.form.message.remove';
    }

    public function exists(SubscriberInterface $subscriber, array $options): bool
    {
        return false;
    }

    public function handleExists(SubscriberInterface $subscriber, array $options)
    {
        return null;
    }

    public function getActivationTemplate(array $options): ?string
    {
        return null;
    }

    public function getUnsubscribeTemplate(array $options): ?string
    {
        return null;
    }

    public function setStorage(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function getStorage(): Storage
    {
        return $this->storage;
    }

    public function preAddSubscriber(SubscriberInterface $subscriber)
    {
        $this->eventDispatcher->dispatch(new SubscriberEvent(SubscriberEvent::EVENT_PRE_ADD_SUBSCRIBER, $subscriber), SubscriberEvent::EVENT_PRE_ADD_SUBSCRIBER);
    }

    public function postAddSubscriber(SubscriberInterface $subscriber)
    {
        $this->eventDispatcher->dispatch(new SubscriberEvent(SubscriberEvent::EVENT_POST_ADD_SUBSCRIBER, $subscriber), SubscriberEvent::EVENT_POST_ADD_SUBSCRIBER);
    }

    public function preActivateSubscriber(SubscriberInterface $subscriber)
    {
        $this->eventDispatcher->dispatch(new SubscriberEvent(SubscriberEvent::EVENT_PRE_ACTIVATE_SUBSCRIBER, $subscriber), SubscriberEvent::EVENT_PRE_ACTIVATE_SUBSCRIBER);
    }

    public function postActivateSubscriber(SubscriberInterface $subscriber)
    {
        $this->eventDispatcher->dispatch(new SubscriberEvent(SubscriberEvent::EVENT_POST_ACTIVATE_SUBSCRIBER, $subscriber), SubscriberEvent::EVENT_POST_ACTIVATE_SUBSCRIBER);
    }

    public function trans($id, array $parameters = [], $domain = null, $locale = null)
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'check_exists' => true,
            'notify' => true,
            'admin_subject' => 'subscriber.mail.admin.subject',
            'subject' => 'subscriber.mail.notify.subject',
            'translation_domain' => 'EnhavoNewsletterBundle',
            'content_type' => 'text/html',
        ]);
        $resolver->setRequired([
            'from',
            'admin_email',
            'sender_name',
        ]);
    }
}
