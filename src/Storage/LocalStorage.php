<?php

namespace Atom\Uploader\Storage;

class LocalStorage implements IStorage
{
    public function writeStream($prefix, $path, $resource)
    {
        $location = $this->applyPathPrefix($prefix, $path);
        $this->ensureDirectory(dirname($location));
        $writeStream = @fopen($location, 'w+');

        if (!$writeStream) {
            return false;
        }

        stream_copy_to_stream($resource, $writeStream);

        return fclose($writeStream);
    }

    public function delete($prefix, $path)
    {
        $location = $this->applyPathPrefix($prefix, $path);

        return is_file($location) && @unlink($location);
    }

    private function ensureDirectory($dir)
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    private function applyPathPrefix($prefix, $path)
    {
        $path = ltrim($path, '\\/');
        $prefix = rtrim($prefix, '\\/');

        if ($prefix) {
            $path = $prefix . '/' . $path;
        }

        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);

        return str_replace(':\\\\', '://', $path);
    }

    public function resolveFileInfo($prefix, $path)
    {
        if (empty($path)) {
            return null;
        }

        $location = $this->applyPathPrefix($prefix, $path);

        if (!file_exists($location)) {
            return null;
        }

        return new \SplFileInfo($location);
    }
}