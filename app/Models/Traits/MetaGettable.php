<?php

namespace App\Models\Traits;

/**
 * Class MetaGettable
 * @package App\Models\Traits
 */
trait MetaGettable
{
    
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->name;
    }
    
    /**
     * @return string
     */
    public function getContent()
    {
        return empty($this->content) ? $this->short_content : $this->content;
    }
    
    /**
     * @return string
     */
    public function getH1()
    {
        return empty($this->h1) ? $this->getTitle() : $this->h1;
    }
    
    /**
     * @return string
     */
    public function getMetaTitle()
    {
        return empty($this->meta_title) ? $this->getTitle() : $this->meta_title;
    }
    
    /**
     * @return string
     */
    public function getMetaDescription()
    {
        return str_limit(
            empty($this->meta_description) ? strip_tags($this->getContent()) : $this->meta_description,
            config('seo.share.meta_description_length')
        );
    }
    
    /**
     * @return string
     */
    public function getMetaKeywords()
    {
        return empty($this->meta_keywords) ? $this->getTitle() : $this->meta_keywords;
    }
    
    /**
     * @return string
     */
    public function getMetaImage()
    {
        $image = config('seo.share.'.snake_case($this->slug).'.image');
        
        $image = empty($image)
            ? (empty($this->image) ? config('seo.share.default_image') : $this->image)
            : $image;
        
        return $image ? url($image) : $image;
    }
    
    /**
     * @return array
     */
    public function getBreadcrumbs()
    {
        return [];
    }
}