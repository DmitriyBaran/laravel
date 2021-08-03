<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Carbon\Carbon;

class DiggingDeeperController extends Controller
{
    public function collections() {

        $result = [];

        $eloquentCollections = BlogPost::withTrashed()->get();

//        dd(__METHOD__, $eloquentCollections, $eloquentCollections->toArray());

        $collection = collect($eloquentCollections->toArray());


        $result['where']['data'] = $collection
            ->where('category_id', 10)
            ->values()
            ->keyBy('id');

        $result['where']['count'] = $result['where']['data']->count();
        $result['where']['isEmpty'] = $result['where']['data']->isEmpty();
        $result['where']['isNotEmpty'] = $result['where']['data']->isNotEmpty();

        $result['where_first'] = $collection
            ->firstWhere('created_at', '>', '2021-07-01 18:34:13');

        $collection->transform(function (array $item) {
            $newItem = new \stdClass();
            $newItem->item_id = $item['id'];
            $newItem->item_name = $item['title'];
            $newItem->exists = is_null($item['deleted_at']);
            $newItem->created_at = Carbon::parse($item['created_at']);

            return $newItem;
        });

//        $newItem = new \StdClass();
//        $newItem->id = 999;
//
//        $newItem2 = new \StdClass();
//        $newItem2->id = 888;
//
//        $collection->prepend($newItem);
//        $collection->push($newItem2);


//        $newItemFirst = $collection->prepend($newItem)->first();
//        $newItemLast = $collection->push($newItem2)->last();
//        $pulledItem = $collection->pull(10);

//        dd(compact('collection', 'newItemFirst', 'newItemLast', 'pulledItem'));

        $filtred  = $collection->filter(function ($item) {
            $byDay = $item->created_at->isSaturday();
            $byDate = $item->created_at->day == 26;

            $result = $byDate && $byDay;

            return $result;
        });

        dd(compact($filtred));
    }
}
