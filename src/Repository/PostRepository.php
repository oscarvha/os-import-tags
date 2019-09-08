<?php


namespace OsImportTags\Repository;


use OsImportTags\Exception\ManyPostWIthSameNameException;
use OsImportTags\Exception\PostNotExistException;

class PostRepository
{
    /**
     * @param string $title
     * @return int
     * @throws PostNotExistException
     * @throws ManyPostWIthSameNameException
     */
    public function getPostIdByTitle(string $title) : int
    {
       $posts =  get_posts([
            'title' => $title,
        ]);

       if(empty($posts)) {
           throw new PostNotExistException('Not Exist Post with title '.$title.' not exist');
       }


       if(count($posts) > 1) {
           throw new ManyPostWIthSameNameException('Have '.count($posts).' with the title '.$title);
       }


      return $posts[0]->ID;

    }

    public function addTagsToPost(int $postId , array $tags)
    {
        wp_set_post_tags($postId,$tags);
    }

}