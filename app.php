<?php


use Inverse\Termin\Container;

require __DIR__.'/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$container = new Container();

$siteParser = $container->getSiteParser();

$sites = $siteParser->parse(getenv('SITES'));

$scraper = $container->getScraper();
$logger = $container->getLogger();
$notifier = $container->getNotifier();

foreach ($sites as $name => $url) {
    $result = $scraper->scrapeSite($name, $url);

    if ($result->isFound()) {
        $logger->info(
            sprintf('Found availability for %s @ %s', $name, $result->getDate()->format('c'))
        );

        $notifier->notify($name, $url, $result->getDate());
        continue;
    }

    $logger->info('No availability found for: '.$name);
}


