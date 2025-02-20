<?php

namespace ImDong\FlarumExtVisibleToOpOnly\Attributes;

use Flarum\Api\Serializer\BasicUserSerializer;
use Flarum\Post\Post;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\User;
use ImDong\FlarumExtVisibleToOpOnly\Common\Defined;
use Symfony\Contracts\Translation\TranslatorInterface;

class AddUserAttributes
{
    /**
     * @var SettingsRepositoryInterface|mixed
     */
    private $settings;

    /**
     * @var mixed|TranslatorInterface
     */
    private $translator;

    private $post;

    public function __construct()
    {
        $this->settings = resolve(SettingsRepositoryInterface::class);
        $this->translator = resolve(TranslatorInterface::class);
    }


    public function __invoke(BasicUserSerializer $serializer, User $user) {

        $attributes = [];

        $actor = $serializer->getActor();

        $path = explode('/', $serializer->getRequest()->getRequestTarget());
        $uri = explode('-', array_pop($path));
        $id = array_shift($uri);

        $attributes['canVisibleToOpPermissionsViewButton'] = false;
        $post = Post::query()->where(["discussion_id"=>$id])->first();
        if ($post) {
            $discussion = $post->discussion;
            // 是否有使用仅楼主可见功能的权限
            $canViewButton = $actor->can(Defined::$extPrefix . '.viewButton', $discussion);

            $discussionTags = $post->discussion->tags;
            foreach ($discussionTags as $tag) {
                if ($actor->hasPermission("tag{$tag->id}.discussion.".Defined::$extPrefix.".viewButton")) {
                    $attributes['canVisibleToOpPermissionsViewButton'] = true;
                }
            }

//            $attributes['canVisibleToOpPermissionsViewButton'] = $canViewButton;
        }

        return $attributes;
    }
}
