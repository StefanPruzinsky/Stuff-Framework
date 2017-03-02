<?php

class ArrayHelper
{
    public function RemoveSpecificArrayFromSourceArray($sourceArray, $arrayToBeRemovedFromSourceArray)
    {
        $processedArray = null;

        if (is_object($sourceArray) && is_object($arrayToBeRemovedFromSourceArray))
        {
            foreach ($arrayToBeRemovedFromSourceArray as $item)
                if (array_search($item, (array)$sourceArray) !== null)
                    $sourceArray->offsetUnset(array_search($item, (array)$sourceArray));

            $processedArray = new ArrayObject(array_values((array)$sourceArray));
        }
        else
        {
            foreach ($arrayToBeRemovedFromSourceArray as $item)
                if (array_search($item, $sourceArray) !== null)
                    unset($sourceArray[array_search($item, $sourceArray)]);

            $processedArray = array_values($sourceArray);
        }

        return $processedArray;
    }
}