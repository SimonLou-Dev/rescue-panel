<?php


namespace App\Blade;


use Psr\SimpleCache\CacheInterface;

class ViteAssetLoader
{
    /**
     * @var bool
     */
    private $isDev;
    /**
     * @var string
     */
    private $manifest;


    /**
     * @var array|null
     */
    private ?array $manifestData = null;

    /**
     * @var CacheInterface
     */
    private CacheInterface $cache;



    public function __construct(bool $isDev, string $manifest, CacheInterface $cache)
    {
        $this->isDev = $isDev;
        $this->manifest = $manifest;
        $this->cache = $cache;
    }

    public function asset(string $url, array $deps){
        if($this->isDev){
            return $this->assetDev($url, $deps);
        }else{
            return $this->assetProd($url);
        }
    }

    public function assetDev(string $url, array $array){
        $base = env('ASSETS_URL').'/assets';
        $html = '';
        $html .= <<<HTML
            <script type="module" src="{$base}/@vite/client"></script>
        HTML;

        if(in_array('react', $array)){
            $html .= <<<HTML
                <script type="module">
                    import RefreshRuntime from "{$base}/@react-refresh"
                    RefreshRuntime.injectIntoGlobalHook(window)
                    window.\$RefreshReg\$ = () => {}
                    window.\$RefreshSig\$ = () => (type) => type
                    window.__vite_plugin_react_preamble_installed__ = true
                </script>

                HTML;


        }

        $html .= <<<HTML
                <script src="{$base}{$url}" type="module" defer></script>
            HTML;
        return $html;
    }

    public function assetProd(string $url){
        if(!$this->manifestData){
            $manifest = $this->cache->get('vite_manifest',null);
            if($manifest === null){
                $manifest = json_decode(file_get_contents($this->manifest), true);
                $this->cache->set('vite_manifest', $manifest);
            }
            $this->manifestData = $manifest;
        }

        $url = trim($url, '/');
        $file = $this->manifestData[$url]['file'] ?: null;
        $cssFiles= $this->manifestData[$url]['css'] ?: [];
        if($file == null){
            return '';
        }

        $html = <<<HTML
            <script src="/assets/{$file}" type="module" defer></script>
        HTML;

        foreach ($cssFiles as $css){
            $html .= <<<HTML
            <link rel="stylesheet" href="/assets/{$css}">
            HTML;

        }

        return $html;
    }





}
