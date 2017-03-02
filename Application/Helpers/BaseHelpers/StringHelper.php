<?php

class StringHelper
{
    public function Split($stringToSplit, $splittingChar)
    {
        $splitArray = new ArrayObject();
        $currentWord = '';

        foreach(str_split($stringToSplit) as $character)
        {
            if ($character == $splittingChar)
            {
                if ($currentWord != '')
                {
                    $splitArray->append($currentWord);
                    $currentWord = '';
                }
            }
            else
                $currentWord .= $character;
        }

        if ($currentWord != '')
            $splitArray->append($currentWord);

        return $splitArray;
    }

    public function TrimCharsFromBeginAndEnd($stringToTrim, $charsToTrim)
    {
        if (strlen($stringToTrim) < $charsToTrim)
            throw new Exception('Length of string is less then the length of trimming pattern.', E_USER_WARNING);

        $processedString = $stringToTrim;

        $startOfString = substr($stringToTrim, 0, strlen($charsToTrim));
        $endOfString = substr($stringToTrim, strlen($stringToTrim) - strlen($charsToTrim));

        if ($startOfString == $charsToTrim)
            $processedString = substr($stringToTrim, strlen($charsToTrim));

        if ($endOfString == $charsToTrim)
            $processedString = substr($processedString, 0, strlen($processedString) - strlen($charsToTrim));

        return $processedString;
    }

    public function ConvertToCamelCase($separatingCharacter, $stringToConvert)
    {
        $convertedString = implode(
            array_map(
                function($word)
                {
                    return ucfirst($word);
                },
                (array)$this->Split($stringToConvert, $separatingCharacter)
            )
        );

        return $convertedString;
    }

    public function GetSubstringUsingStartAndUniqueEndFragments($text, $startFragment, $endFragment)
    {
        $positionOfSectionStart = strpos(
                $text,
                $startFragment
            ) + strlen($startFragment);

        $finalSubstring = substr(
            $text,
            $positionOfSectionStart,
            strpos(
                $text,
                $endFragment
            ) - $positionOfSectionStart
        );

        return $finalSubstring;
    }

    public function GetSubstringUsingStartAndNonUniqueEndFragments($text, $startFragment, $endFragment)
    {
        $positionOfSectionStart = strpos(
                $text,
                $startFragment
            ) + strlen($startFragment);

        $i = $positionOfSectionStart;
        $finalSubstring = '';

        while (substr($text, $i, strlen($endFragment)) != $endFragment)
        {
            $finalSubstring .= $text[$i];
            $i++;
        }

        return $finalSubstring;
    }

    public function CutStringAfterLastSpecificChar($stringToCut, $separatingCharacter)
    {
        $cutString = substr($stringToCut, strripos($stringToCut, $separatingCharacter) + 1);

        return $cutString;
    }

    public function DeleteStringSectionAfterLastSpecificChar($stringToCut, $separatingCharacter)
    {
        $cutString = substr($stringToCut, 0, strripos($stringToCut, $separatingCharacter));

        return $cutString;
    }

    public function RemoveRedundantConsecutiveChars($stringToEdit, $potentiallyRedundantCharacter)
    {
        for ($i = 0; $i < strlen($stringToEdit); $i++)
        {
            if (isset($stringToEdit[$i - 1]) && ($potentiallyRedundantCharacter == $stringToEdit[$i] && $stringToEdit[$i] == $stringToEdit[$i - 1]))
            {
                $stringToEdit = $this->RemoveCharFromString($stringToEdit, $i - 1);
                $i--;
            }
        }

        return $stringToEdit;
    }

    public function RemoveCharFromString($stringToEdit, $indexOfSpecificChar)
    {
        $editedString = substr_replace($stringToEdit, '', $indexOfSpecificChar, 1);

        return $editedString;
    }

    public function AddApostrophesIfIsString($variableToCheck)
    {
        $finalString = '';

        if (is_string($variableToCheck))
            $finalString = '\''.$variableToCheck.'\'';

        return $finalString != null ? $finalString : $variableToCheck;
    }
}