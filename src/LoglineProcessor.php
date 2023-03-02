<?php
namespace DigitalMx\jotr;

class LoglineProcessor
{
// used for adding file and line to log record
    /**
     * @param  array $record
     * @return array
     */
    public function __invoke( $record)
    {
      $info = $this->findFile();
      $record['file_info'] = $info['file'] . ':' . $info['line'];
      return $record;
    }

    public function findFile() {
      $debug = debug_backtrace();
      return [
        'file' => $debug[3] ? basename($debug[3]['file']) : '',
        'line' => $debug[3] ? $debug[3]['line'] : ''
      ];
    }
}
