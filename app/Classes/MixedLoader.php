<?php

namespace App\Classes;

use Illuminate\Contracts\Translation\Loader;

class MixedLoader implements Loader
{
    
    /**
     * db loader
     *
     * @var Loader
     */
    protected $primaryLoader;
    
    /**
     * file loader
     *
     * @var Loader
     */
    protected $secondaryLoader;
    
    /**
     * All of the namespace hints.
     *
     * @var array
     */
    protected $hints = [];
    
    /**
     *  Create a new mixed loader instance.
     *
     * @param Loader $primaryLoader
     * @param Loader $secondaryLoader
     */
    public function __construct(Loader $primaryLoader, Loader $secondaryLoader)
    {
        $this->primaryLoader = $primaryLoader;
        $this->secondaryLoader = $secondaryLoader;
    }
    
    /**
     * Load the messages for the given locale.
     *
     * @param  string $locale
     * @param  string $group
     * @param  string $namespace
     *
     * @return array
     */
    public function load($locale, $group, $namespace = null)
    {
        return array_replace_recursive(
            $this->secondaryLoader->load($locale, $group, $namespace),
            $this->primaryLoader->load($locale, $group, $namespace)
        );
    }
    
    /**
     *  Add a new namespace to the loader.
     *
     * @param  string $namespace
     * @param  string $hint
     *
     * @return void
     */
    public function addNamespace($namespace, $hint)
    {
        $this->hints[$namespace] = $hint;
        $this->secondaryLoader->addNamespace($namespace, $hint);
    }
    
    /**
     * Add a new JSON path to the loader.
     *
     * @param  string $path
     *
     * @return void
     */
    public function addJsonPath($path)
    {
        // TODO: Implement addJsonPath() method.
    }
    
    /**
     * Get an array of all the registered namespaces.
     *
     * @return array
     */
    public function namespaces()
    {
        // TODO: Implement namespaces() method.
    }
}
