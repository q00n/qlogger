<?php

use Q00n\QLogger\LogReader;

Route::get('qlogger/removeExpired', function (LogReader $logReader) {
    $logReader->removeExpired();
    return redirect()->back();
});

Route::get('qlogger/remove/{date?}', function ($date = null, LogReader $logReader) {
    $logReader->removeLog($date);
    return redirect()->back();
})->where(['date' => '(0[1-9]|[12]\d|3[01])-(0[1-9]|1[0-2])-([12]\d{3})']);

// Route::get('qlogger/eraseLogById/{id}/{date?}', function (string $id, string $date = null, LogReader $logReader) {
//     $logReader->eraseLogByFieldValues('id', [$id], $date);
// })->where(['date' => '(0[1-9]|[12]\d|3[01])-(0[1-9]|1[0-2])-([12]\d{3})']);

Route::get('qlogger/eraseLogsByType/{type}/{date?}', function (string $type, string $date = null, LogReader $logReader) {
    $logReader->eraseLogByFieldValues('level', [$type], $date);
    return redirect()->back();
})->where(['date' => '(0[1-9]|[12]\d|3[01])-(0[1-9]|1[0-2])-([12]\d{3})']);

Route::get('qlogger/{date?}', function (string $date = null, LogReader $logReader) {
    return view('qlogger::logs', ['files' => $logReader->getLogFileList(), 'logs' => $logReader->fetchLogs($date)]);
})->where(['date' => '(0[1-9]|[12]\d|3[01])-(0[1-9]|1[0-2])-([12]\d{3})']);

Route::get('qlogger/{type}/{date?}', function (string $type, string $date = null, LogReader $logReader) {
    return view('qlogger::logs', ['files' => $logReader->getLogFileList(), 'logs' => $logReader->fetchLogs($date, [$type])]);
})->where(['date' => '(0[1-9]|[12]\d|3[01])-(0[1-9]|1[0-2])-([12]\d{3})']);