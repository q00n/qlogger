<?php

namespace Q00n\QLogger;

use DateTime;

class LogReader
{
    protected $logDirectory;

    protected $logExtension = '.log';

    public function __construct()
    {
        $this->logDirectory = config('qlogging.logs_path');
    }

    private function getFullPath(string $filename)
    {
        return $this->logDirectory . DIRECTORY_SEPARATOR . $filename;
    }

    public function getLogFileList(string $date = null)
    {
        if (!file_exists($this->logDirectory))
            return [];

        if (!empty($date) && file_exists($this->getFullPath($date.$this->logExtension)))
            return [$date.$this->logExtension];

        return array_slice(scandir($this->logDirectory), 2);
    }

    public function fetchLogs(string $date = null, array $types = [])
    {
        $filenames = $this->getLogFileList($date);

        $logs = array_map(function ($filename) use ($types) {
            $lines = $this->fetchLogLines($filename, $types);

            $lines = $this->decodeLines($lines);

            if (!empty($types))
                $lines = $this->filterLinesByFieldValues($lines, 'level', $types);

            return ['filename' => $filename, 'lines' => $lines];
        }, $filenames);

        return $logs;
    }

    private function fetchLogLines(string $filename)
    {
        $logFilePath = $this->getFullPath($filename);

        return file($logFilePath);
    }

    private function filterLinesByFieldValues(array $lines, string $field, array $values, bool $except = false)
    {
        return array_filter($lines, function ($line) use ($field, $values, $except) {
            return $except !== in_array($line[$field], $values);
        });
    }

    public function removeLog($date = null)
    {
        $filenames = $this->getLogFileList($date);

        if (empty($filenames))
            return FALSE;

        foreach ($filenames as $filename) {
            unlink($this->getFullPath($filename));
        }

        return TRUE;
    }

    public function removeExpired()
    {
        $filenames = $this->getLogFileList();

        if (empty($filenames))
            return FALSE;

        $expireDays = config('qlogging.logs_expire_days');

        $currentDate = new DateTime();

        foreach ($filenames as $filename) {
            $logCreateDate = new DateTime(basename($filename, $this->logExtension));

            if ($currentDate->diff($logCreateDate)->days > $expireDays)
                $this->removeLog(basename($filename, $this->logExtension));
        }

        return TRUE;
    }

    public function eraseLogByFieldValues(string $field, array $values, string $date = null)
    {
        $filenames = $this->getLogFileList($date);

        if (empty($filenames))
            return FALSE;

        foreach ($filenames as $filename) {
            $logFilePath = $this->getFullPath($filename);

            $lines = $this->fetchLogLines($filename);

            $decodedLines = $this->decodeLines($lines);

            $filteredLines = $this->filterLinesByFieldValues($decodedLines, $field, $values, TRUE);

            $encodedLines = $this->encodeLines($filteredLines);

            file_put_contents($logFilePath, implode($encodedLines));
        }

        return TRUE;
    }
    
    private function decodeLines($lines)
    {
        return array_map(function ($line) {
            return json_decode($line, TRUE);
        }, $lines);
    }

    private function encodeLines($lines)
    {
        return array_map(function ($line) {
            return json_encode($line).PHP_EOL;
        }, $lines);
    }
}
