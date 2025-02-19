<?php

namespace ImDong\FlarumExtVisibleToOpOnly\Listener;


use Flarum\Post\Event\Posted;
use ImDong\FlarumExtVisibleToOpOnly\Common\Defined;

class AddPostSendOpAuthListener
{

    public function handle(Posted $event) {
        $post = $event->post;

        $discussionTags = $post->discussion->tags;
        $canVisibleToOpPermissionsViewButton = false;
        foreach ($discussionTags as $tag) {
            if ($event->actor->hasPermission("tag{$tag->id}.discussion.".Defined::$extPrefix.".viewButton")) {
                $canVisibleToOpPermissionsViewButton = true;
            }
        }
        if (!$canVisibleToOpPermissionsViewButton) {
//            $post->afterSave(function ($post) {
//                $content = $post->content;
//                $post->content = str_replace(['[op]', '[/op]', '[OP]', '[/OP]', '<OP>', '</OP>'], '', $content);
//                $post->save();
//            });
            $content = $post->content;
            $post->content = str_replace(['[op]', '[/op]', '[OP]', '[/OP]', '<OP>', '</OP>'], '', $content);
            $post->save();
        }
    }

}
