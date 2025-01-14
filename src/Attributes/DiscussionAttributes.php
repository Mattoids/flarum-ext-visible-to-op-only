<?php

namespace ImDong\FlarumExtVisibleToOpOnly\Attributes;

use Flarum\Api\Serializer\BasicDiscussionSerializer;
use Flarum\Api\Serializer\BasicPostSerializer;
use Flarum\Discussion\Discussion;
use Flarum\Post\Post;
use Flarum\Settings\SettingsRepositoryInterface;
use ImDong\FlarumExtVisibleToOpOnly\Common\Defined;
use Symfony\Contracts\Translation\TranslatorInterface;

class DiscussionAttributes
{
    /**
     * @var SettingsRepositoryInterface|mixed
     */
    private $settings;

    /**
     * @var mixed|TranslatorInterface
     */
    private $translator;

    public function __construct()
    {
        $this->settings = resolve(SettingsRepositoryInterface::class);
        $this->translator = resolve(TranslatorInterface::class);
    }

    public function __invoke(BasicDiscussionSerializer $serializer, Discussion $discussion): array
    {
        $actor = $serializer->getActor();
        $canViewButton = $actor->can(Defined::$extPrefix . '.viewButton', $discussion);
        $attributes['canVisibleToOpPermissionsViewButton'] = $canViewButton;
        return $attributes;
    }
}
