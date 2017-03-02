<?php

class FileHelper
{
    public function LoadAndGetProcessedContent($file, $outputData)
    {
        $processedContent = '';
        extract((array)$outputData);

        ob_start();

        eval('?>'.file_get_contents($file));
        $processedContent = ob_get_contents();

        ob_end_clean();

        return $processedContent;
    }

    //Old layout system method
    /*public function LoadAndProcessContent($file, $outputData, $processingMethod)
    {
        extract((array)$outputData);

        ob_start($processingMethod);

        require($file);

        ob_end_flush();
    }

    public function LoadAndGetProcessedContent($file, $outputData)
    {
        $fileContent = '';
        extract((array)$outputData);

        ob_start();

        require($file);
        $fileContent = ob_get_contents();

        ob_end_clean();

        return $fileContent;
    }*/

    public function EditContent($fileName, $processingMethod)
    {
        $fileContent = file_get_contents($fileName);

        $resourcePointer = fopen($fileName, 'w');

        $editedFile = call_user_func_array($processingMethod, array($fileContent));

        fwrite($resourcePointer, $editedFile);
        fclose($resourcePointer);
    }
}