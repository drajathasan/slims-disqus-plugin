<?php
/**
 * Plugin Name: Disqus
 * Plugin URI: -
 * Description: Disqus widget
 * Version: 1.0.0
 * Author: Drajat Hasan
 * Author URI: https://github.com/drajathasan/
 */

// get plugin instance
$plugin = \SLiMS\Plugins::getInstance();

// registering menus or hook
$plugin->registerMenu('system', 'Disqus Configuration', __DIR__ . '/index.php');
$plugin->register("comment_init", function(&$_list_comment){
    $url = config('disqus_url')[0]??'';

    if (empty($url))
    {
        $_list_comment = <<<HTML
            <div class="alert alert-danger" role="alert">Disqus URL is not set!</div>
            <style>
                a[href="index.php?p=member"][class="btn btn-outline-primary"] { 
                    display: none !important; 
                }
            </style>
        HTML;
    }
    else
    {
        $url = rtrim($url, '/');
        $_list_comment = <<<HTML
        <div id="disqus_thread"></div>
        <style>
            a[href="index.php?p=member"][class="btn btn-outline-primary"] { 
                display: none !important; 
            }
        </style>
        <script>
            /**
            *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
            *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables    */
            /*
            var disqus_config = function () {
            this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
            this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
            };
            */
            (function() { // DON'T EDIT BELOW THIS LINE
            var d = document, s = d.createElement('script');
            s.src = '{$url}/embed.js';
            s.setAttribute('data-timestamp', +new Date());
            (d.head || d.body).appendChild(s);
            })();
        </script>
        <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
        HTML;
    }
});