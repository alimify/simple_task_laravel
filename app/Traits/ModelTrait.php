<?php

namespace App\Traits;

use Error;
use Illuminate\Support\Facades\DB;

trait ModelTrait
{

    public static function indexInstance($data = [])
    {
        $show = $data['show']??null;
        $res = $show == 'all' ? self::withTrashed() : 
                                self::withoutTrashed();

        $order = $data['order']??null;
        if($order && is_array($order)){
            foreach ($order as $key => $value) {
               $res->orderBy($key, $value);
            }
        }

        return $res->get();
    }

    public static function batchUpdate($data = [], $identifier = 'id')
    {

        $collection = collect($data);
        if($collection->count() === 0 || $identifier == ''){
            return false;
        }

        $table = (new self)->getTable();
        $fillable = (new self)->getFillable();
        
        $chunks = $collection->chunk(5000);

        foreach ($chunks as $chunk) {

            $firstChunk = $chunk[0]??null;
            if($firstChunk){
                $fields = array_intersect( $fillable, array_keys($firstChunk) );

                foreach ($fields as $field) {
                    if($field == $identifier)
                        continue;

                    $identifiers = [];
                    $cases = [];
                    $params = [];
        
                    foreach ($chunk as $value) {
                        $idnt = $value[$identifier]??null;
                        if(!$idnt){
                            throw new Error("No identifier found!");
                        }
                        $identifiers[] = $idnt;
                        $cases[] = "WHEN {$idnt} then ?";
                        $params[] = $value[$field];
                    }

                    $identifiersIn = implode(",", $identifiers);
                    $cases = implode(" ", $cases);

                    if(!empty($identifiers)){
                        DB::update("UPDATE {$table} SET `{$field}` = CASE `{$identifier}` {$cases} END 
                                                    WHERE `{$identifier}` 
                                                    IN ({$identifiersIn})", $params);
                    }

                }
            }
        }

        return true;
    }


    public static function destroyInstance($id,$state)
    {
        $modelInstance = self::withTrashed()->find($id);
        switch ($state) {
            case 'restore':
                $result = $modelInstance->deleted_at ? $modelInstance->restore() : false;
                break;
            case 'force':
                $result = $modelInstance->deleted_at ? $modelInstance->forceDelete() : false;
            default:
                $result = $modelInstance->deleted_at ? false : $modelInstance->delete();
                break;
        }

        return $result;
    }
}