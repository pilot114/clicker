<?php

// ITS OLD FILE

namespace Catter;
use MongoClient;

class Monger
{
    private $dbh;
    function __construct()
    {
        $m = new MongoClient('mongodb://db:27017/');
        $this->dbh = $m->catter;
    }
    public function addBatch(array $hashes, $name)
    {
        $docs = [];
        foreach ($hashes as $i => $hash) {
            $this->dbh->images->update([
                '_id' => $hash,
            ], [
                '$set' => [
                    'like' => false,
                    'name' => $name
                ]
            ], ['upsert' => true]);
        }
    }
    public function like(array $docs)
    {
        foreach ($docs as $doc) {
            $this->dbh->images->update(
                [
                    '_id' => $doc['_id']
                ],
                [
                    '$set' => [
                        'like' => true,
                    ],
                ]
            );
        }
    }
    public function getByName($name)
    {
        return $this->dbh->images->find(['name' => $name]);
    }
    public function getByLike()
    {
        return $this->dbh->images->find(['like' => true]);
    }
    public function getSetList()
    {
        $ops = [
            [
                '$group' => [
                    '_id'    => '$name',
                    'count'  => ['$sum' => 1]
                ]
            ]
        ];
        return $this->dbh->images->aggregate($ops)['result'];
    }
    public function callApi($command, $hash)
    {
        if($command == 'like') {
            $this->dbh->images->update(['_id'=>$hash], ['$set' => ['like'=>true]]);
        } elseif($command == 'dislike') {
            $this->dbh->images->update(['_id'=>$hash], ['$set' => ['like'=>false]]);
        } elseif($command == 'remove') {
            $this->dbh->images->remove(['_id'=>$hash]);
        }
    }
}