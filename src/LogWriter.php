<?php

namespace Q00n\QLogger;

use Request;
use RuntimeException;

class LogWriter
{
    private $fileHandle;

    public function __construct()
    {
        $logDirectory = config('qlogging.logs_path');
        if ( ! file_exists($logDirectory)) {
            mkdir($logDirectory, 0777, true);
        }

        $logFilePath = $logDirectory.DIRECTORY_SEPARATOR.date('d-m-Y').'.'.'log';
        if(file_exists($logFilePath) && !is_writable($logFilePath)) {
            throw new RuntimeException('The file could not be written to. Check that appropriate permissions have been set.');
        }

        $this->fileHandle = fopen($logFilePath, 'a');
        if ( ! $this->fileHandle) {
            throw new RuntimeException('The file could not be opened. Check permissions.');
        }
    }

    public function __destruct()
    {
        if ($this->fileHandle) {
            fclose($this->fileHandle);
        }
    }

    public function notice($data, $mark = null)
    {
        $this->log(__FUNCTION__, $data, $mark);
    }

    public function info($data, $mark = null)
    {
        $this->log(__FUNCTION__, $data, $mark);
    }

    public function danger($data, $mark = null)
    {
        $this->log(__FUNCTION__, $data, $mark);
    }

    public function success($data, $mark = null)
    {
        $this->log(__FUNCTION__, $data, $mark);
    }

    public function save($data, $mark = null)
    {
        $this->notice($data, $mark);
    }

    public function request($key = null)
    {
        $data = Request::all($key);
        
        $this->notice($data, 'Request data');
    }

    public function input($key = null)
    {
        $data = Request::input($key);
        
        $this->notice($data, 'Input data');
    }

    public function json($key = null)
    {
        $data = $key ? Request::json($key) : Request::json()->all();
        
        $this->notice($data, 'JSON data');
    }

    public function post($key = null)
    {
        $data = $key ? (isset($_POST[$key]) ? $_POST[$key] : []) : $_POST;
        
        $this->notice($data, 'POST data');
    }

    public function get($key = null)
    {
        $data = $key ? (isset($_GET[$key]) ? $_GET[$key] : []) : $_GET;
        
        $this->notice($data, 'GET data');
    }

    public function php()
    {
        $data = Request::getContent();
        
        $this->notice($data, 'PHP data');
    }

    public function server($key = null)
    {
        $data = $key ? $_SERVER[$key] : $_SERVER;
        
        $this->notice($data, 'SERVER data');
    }

    public function cookies($key = null)
    {
        $data = Request::cookie($key);
        
        $this->notice($data, 'COOKIES data');
    }

    public function headers($key = null)
    {
        $data = Request::header($key);
        
        $this->notice($data, 'HEADERS data');
    }

    protected function formatMessage($level, $data, $mark, $caller)
    {
        $timestamp = date('H:i:s');

        return json_encode([
            // 'id' => md5($timestamp.json_encode($data).rand()),
            'timestamp' => $timestamp,
            'level' => $level,
            'mark' => $mark,
            'caller' => ['file' => $caller['file'], 'line' => $caller['line']],
            'data' => $data
        ]).PHP_EOL;
    }

    public function log($level, $data, $mark)
    {
        $debug_backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 4);
        $caller = $debug_backtrace[3]['type'] == '::' ? $debug_backtrace[3] : $debug_backtrace[2];

        $message = $this->formatMessage($level, $data, $mark, $caller);

        $this->write($message);
    }

    public function write($message)
    {
        if ($this->fileHandle !== null) {
            if (fwrite($this->fileHandle, $message) === false) {
                throw new RuntimeException('The file could not be written to. Check that appropriate permissions have been set.');
            }
        }
    }
}
