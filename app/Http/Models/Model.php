<?php namespace App\Http\Models;

// use Laravel\Lumen\Routing\Controller as BaseController;

use Illuminate\Support\Collection;
use DB;

class Model
{
    // Event::listen(StatementPrepared::class, function ($event) {
    //     $event->statement->setFetchMode(...);
    // });

    /**
     * Pack
     *
     * @param iterable $batch
     *
     * @return Collection
     */
    protected static function pack(iterable $batch): Collection
    {
        return collect($batch);
    }

    protected static function query($query, $params = []) {
        $query = DB::raw($query);
        $result = DB::select($query, $params);

        return $result;
    }

}