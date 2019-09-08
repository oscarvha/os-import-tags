<?php


namespace OsImportTags\Model;


use OsImportTags\Exception\ManyPostWIthSameNameException;
use OsImportTags\Exception\PostNotExistException;
use OsImportTags\Repository\PostRepository;
use OsImportTags\Util\Scrapping;

class Post
{
    /**
     * @var int
     */
    private $id;


    /**
     * Post constructor.
     * @param int $id
     */
    private function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @param string $url
     * @return Post
     * @throws PostNotExistException
     * @throws ManyPostWIthSameNameException
     */
    static function createFormURL(string $url) : self
    {
        $scrappingClient = new Scrapping($url);

        $h1 = $scrappingClient->getH1();

        $postRepository = new PostRepository();

        $id = $postRepository->getPostIdByTitle($h1);

        return new self($id);

    }

    public function assignTag(array $tags)
    {
        $postRepository = new PostRepository();

        $postRepository->addTagsToPost($this->id, $tags);
    }

}