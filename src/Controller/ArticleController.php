<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Michelf\MarkdownInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage()
    {
        return $this->render('article/homepage.html.twig');
    }

    /**
     * @Route("/news/{slug}", name="article_show")
     */
    public function show($slug,  MarkdownInterface $markdown, AdapterInterface $cache)
    {
        // dump($markdown);die;
        dump($cache);die;

        $comments = [
            'I ate a normal rock once. It did NOT taste like bacon!',
            'Woohoo! I\'m going on an all-asteroid diet!',
            'I like bacon too! Buy some from my site! bakinsomebacon.com',
        ];
        // article content set with the multiline HEREDOC syntax
        $articleContent = <<<EOF
**Bacon ipsum dolor amet** jowl chuck sausage corned beef. **Shoulder burgdoggen sausage**, ball tip turkey venison hamburger ground round.[beef ribs](https://baconipsum.com/). Short ribs buffalo jerky <em>kielbasa</em> ham hock biltong pork chop bresaola. <p>Chuck porchetta jowl swine, tri-tip frankfurter pig corned beef bacon rump bresaola. Prosciutto pig jowl beef swine meatloaf cow biltong sausage ham hock boudin short loin doner. Drumstick shank ribeye venison picanha.</p>

**Spare ribs** burgdoggen porchetta jerky ribeye tenderloin prosciutto cow corned beef meatloaf beef sirloin jowl salami. Brisket t-bone boudin picanha pork belly cow bacon shankle ground round prosciutto tenderloin jerky short loin hamburger. Pancetta chicken rump landjaeger shankle kielbasa. Bacon brisket ribeye, pork belly biltong chuck turkey kevin ham pancetta buffalo. Fatback ribeye venison landjaeger turducken.

Pancetta sausage t-bone **andouille** salami pastrami turkey. Spare ribs ham hock doner, beef turkey venison landjaeger. Corned beef picanha t-bone salami strip steak. Pork chop ground round drumstick shoulder biltong andouille rump strip steak alcatra chuck meatloaf tongue flank. Strip steak drumstick beef ribs landjaeger tenderloin shank prosciutto filet mignon pork chop turducken meatloaf porchetta pork loin.

**Turducken landjaeger pork** loin short ribs hamburger meatball chuck pastrami shoulder chicken. Buffalo rump salami pork chop ham hock fatback landjaeger. Doner leberkas bacon venison beef ribs swine sirloin pastrami jowl biltong kielbasa alcatra picanha jerky. Chuck ham hock ribeye spare ribs short ribs pig, swine tenderloin hamburger bresaola turducken shank pork chop chicken.

**Tail short ribs rump swine short loin brisket prosciutto**, ham beef tenderloin. Burgdoggen ham spare ribs, leberkas chicken tri-tip tongue chuck rump capicola ribeye ham hock pork belly kielbasa. Chicken shankle cow burgdoggen salami ham brisket pork chop tenderloin prosciutto frankfurter pancetta cupim jerky picanha. **Sausage hamburger ground round prosciutto tri-tip pork loin pork belly**.
EOF;
        // Call the markdomn's transform method.
        $articleContent = $markdown->transform($articleContent);

        // The cache's service : both CacheItemPoolInterface and AdapterInterface have the same id : cache.app,
        // and do the same work
        // Symfony's cache service implements the PHP-standard cache interface, called PSR-6 
        // (une meilleure interopérabilité entre les bibliothèques).
 
        // Creates a CacheItem object in memory that can help us fetch and save to the cache.
            $item = $cache->getItem('markdown_'.md5($articleContent));
        
        // Check if this key is not already cached.
        if (!$item->isHit()) {
            // Put the item in to cache:
                // Step1:$item->set()
                $item->set($markdown->transform($articleContent));
                // Step 2: $cache->save($item) :
                $cache->save($item);
        }

        // Fetch the value from cache.
        $articleContent = $item->get();
        

        return $this->render('article/show.html.twig', [
            'title' => ucwords(str_replace('-', ' ', $slug)),
            'slug' => $slug,
            'articleContent' => $articleContent,
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("/news/{slug}/heart", name="article_toggle_heart", methods={"POST"})
     */
    public function toggleArticleHeart($slug, LoggerInterface $logger)
    {
        // TODO - actually heart/unheart the article!

        $logger->info('Article is being hearted!');

        return new JsonResponse(['hearts' => rand(5, 100)]);
    }
}
