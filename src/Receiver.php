<?php

namespace MobileMaster\LaravelFileInput;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class Receiver
{
    private $maxFileAge = 600; //600 secondes

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getPath()
    {
        $path = storage_path().'/fileinput';

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        return $path;
    }

    public function receiveSingle($name, Closure $handler)
    {
        if ($this->request->file($name)) {
            return $handler($this->request->file($name));
        }

        return false;
    }

    private function appendData($filePathPartial, UploadedFile $file)
    {
        if (!$out = @fopen($filePathPartial, 'ab')) {
            throw new FileInputException('Failed to open output stream.', 102);
        }

        if (!$in = @fopen($file->getPathname(), 'rb')) {
            throw new FileInputException('Failed to open input stream', 101);
        }
        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);
    }

    public function removeOldData($filePath)
    {
        if (file_exists($filePath) && filemtime($filePath) < time() - $this->maxFileAge) {
            @unlink($filePath);
        }
    }

    public function receive($name, Closure $handler)
    {
        $response = [];
        $response['jsonrpc'] = '2.0';

        $result = $this->receiveSingle($name, $handler);

        $response['result'] = $result;

        return $response;
    }
}
