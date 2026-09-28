<?php

namespace App\Service;

use App\Document\RestaurantActivity;
use Doctrine\ODM\MongoDB\DocumentManager;

class RestaurantActivityService
{
    public function __construct(
        private DocumentManager $documentManager
    ) {
    }

    public function menuViewed(
        int $menuId,
        ?int $userId = null,
        array $metadata = []
    ): void {
        $activity = new RestaurantActivity();

        $activity
            ->setType('menu_view')
            ->setMenuId($menuId)
            ->setUserId($userId)
            ->setMetadata($metadata);

        $this->documentManager->persist($activity);
        $this->documentManager->flush();
    }

    public function platViewed(
        int $platId,
        ?int $userId = null,
        array $metadata = []
    ): void {
        $activity = new RestaurantActivity();

        $activity
            ->setType('plat_view')
            ->setPlatId($platId)
            ->setUserId($userId)
            ->setMetadata($metadata);

        $this->documentManager->persist($activity);
        $this->documentManager->flush();
    }

public function getDailyMenuViews(int $days = 7): array
{
    $startDate = new \DateTimeImmutable(
        sprintf('-%d days', $days - 1)
    );

    $startDate = $startDate->setTime(0, 0, 0);

    $result = $this->documentManager
        ->createAggregationBuilder(RestaurantActivity::class)
        ->match()
            ->field('type')
            ->equals('menu_view')
            ->field('date')
            ->gte($startDate)
        ->group()
            ->field('_id')
            ->expression([
                '$dateToString' => [
                    'format' => '%Y-%m-%d',
                    'date' => '$date',
                ],
            ])
            ->field('count')
            ->sum(1)
        ->sort(['_id' => 1])
        ->getAggregation()
        ->execute()
        ->toArray();

    $statistics = [];

    foreach ($result as $row) {
        $statistics[$row['_id']] = $row['count'];
    }

    // Crée également les jours sans consultation
    $dailyViews = [];

    for ($i = 0; $i < $days; $i++) {
        $date = $startDate->modify("+{$i} days");
        $key = $date->format('Y-m-d');

        $dailyViews[] = [
            'date' => $key,
            'count' => $statistics[$key] ?? 0,
        ];
    }

    return $dailyViews;
}



    public function getStatistics(): array
    {
        $byType = $this->documentManager
            ->createAggregationBuilder(RestaurantActivity::class)
            ->group()
                ->field('_id')
                ->expression('$type')
                ->field('count')
                ->sum(1)
            ->sort(['count' => -1])
            ->getAggregation()
            ->execute()
            ->toArray();

        $topMenus = $this->documentManager
            ->createAggregationBuilder(RestaurantActivity::class)
            ->match()
                ->field('type')
                ->equals('menu_view')
            ->group()
                ->field('_id')
                ->expression('$menuId')
                ->field('count')
                ->sum(1)
            ->sort(['count' => -1])
            ->limit(10)
            ->getAggregation()
            ->execute()
            ->toArray();

        return [
            'byType' => $byType,
            'topMenus' => $topMenus,
        ];
    }
}