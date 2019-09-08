<?php

namespace OsImportTags\Processor;



use OsImportTags\Exception\ManyPostWIthSameNameException;
use OsImportTags\Exception\PostNotExistException;
use OsImportTags\Exception\RowTagNumberOfItemException;
use OsImportTags\Exception\TagCelEmptyException;
use OsImportTags\Exception\TagUrlNotValidException;
use OsImportTags\Model\Post;
use OsImportTags\Validator\Validator;

class RowTagProcessor
{
    const SPLIT_TAG_ELEMENT = ',';

    /**
     * @var string:
     */
    private $url;

    /**
     * @var array
     */
    private $tags;

    private function __construct(string $url , array $tags)
    {
        $this->url = $url;
        $this->tags = $tags;
    }


    /**
     * @throws ManyPostWIthSameNameException
     * @throws PostNotExistException
     */
    public function process()
    {
        $post = Post::createFormURL($this->url);

        $post->assignTag($this->tags);
    }


    /**
     * @param array $data
     * @return RowTagProcessor
     * @throws RowTagNumberOfItemException
     * @throws TagUrlNotValidException
     * @throws TagCelEmptyException
     */
    public static function createFromArray(array $data) : RowTagProcessor
    {
        if(count($data) < 2) {
            throw new RowTagNumberOfItemException('the row has '.count($data).' cells has to have 2');
        }

        $url = $data[0];

        if(!Validator::isURL($url)) {
            throw new TagUrlNotValidException('the url '.$url.' not is a URL o not exist');
        }

        if(!isset($data[1])) {
            throw new TagCelEmptyException('The post with URL '.$url.' not have Tags');
        }

        $tags = $data[1];
        $tags = explode(self::SPLIT_TAG_ELEMENT,$tags);

        return new self($url, $tags);

    }



}