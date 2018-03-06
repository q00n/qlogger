<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Laravel log viewer</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<style>
  #scrollspy-logs {
    position: relative;
    height: 100vh;
    overflow: auto;
  }
</style>
<body>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-2">
      @if (count($logs) > 1)
      <nav id="logs-navbar" class="navbar navbar-light bg-light flex-column">
          <a class="navbar-brand" href="#">Navbar</a>
          <nav class="nav nav-pills flex-column">
            @foreach($files as $key => $filename)
              <a class="nav-link" href="#item-{{ $key+1 }}">{{ $filename }}</a>
            @endforeach
          </nav>
      </nav>
      @endif
      <div class="form-group">
        <label for="dateSelect">Select date</label>
        <select class="form-control" id="dateSelect">
          <option value="">Any</option>
          @foreach($files as $file)
            <option value="{{ basename($file, '.log') }}">{{ basename($file, '.log') }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label for="dateSelect">Select level (type)</label>
        <select class="form-control" id="levelSelect">
          <option value="">Any</option>
          <option value="notice">NOTICE</option>
          <option value="info">INFO</option>
          <option value="success">SUCCESS</option>
          <option value="danger">DANGER</option>
        </select>
      </div>
      <button type="button" id="filter" class="btn btn-dark btn-block">Filter</button>
      <button type="button" id="erase" class="btn btn-warning btn-block">Erase</button>
      <button type="button" id="remove-by-date" class="btn btn-danger btn-block">Remove by date</button>
      <button type="button" id="remove-expired" class="btn btn-danger btn-block">Remove expired</button>
      <button type="button" id="remove-all" class="btn btn-danger btn-block">Remove all</button>
    </div>
    <div class="col-lg-10">
      <div data-spy="scroll" data-target="#logs-navbar" data-offset="0" id="scrollspy-logs">
        @foreach($logs as $key => $log)
          <h1 id="item-{{ $key+1 }}">{{ $log['filename'] }}</h1>
          <code>
            @foreach($log['lines'] as $key => $log)
              [{{ $log['timestamp'] }}] {{--   [{{ $log['id'] }}] --}} {{ strtoupper($log['level']) }}: {{ $log['mark'] }} {{ $log['caller']['file'] }}({{ $log['caller']['line'] }}): {{ json_encode($log['data']) }}</br>
            @endforeach
          </code>
        @endforeach
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script>
  $(document).ready( function() {
      $('#filter').click( function() {
        base_url = '/qlogger';
  
        if ($("#levelSelect").val())
          base_url += '/'+$("#levelSelect").val()
  
        if ($("#dateSelect").val())
          base_url += '/'+$("#dateSelect").val()
  
        location.href = base_url;
      });

      $('#erase').click( function() {
        if (!$("#levelSelect").val())
          return;

        base_url = '/qlogger/eraseLogsByType';
  
        if ($("#levelSelect").val())
          base_url += '/'+$("#levelSelect").val()
  
        if ($("#dateSelect").val())
          base_url += '/'+$("#dateSelect").val()
  
        location.href = base_url;
      });

      $('#remove-by-date').click( function() {
        if (!$("#dateSelect").val())
          return;

        base_url = '/qlogger/remove/'+$("#dateSelect").val();
    
        location.href = base_url;
      });

      $('#remove-expired').click( function() {
        base_url = '/qlogger/removeExpired';
    
        location.href = base_url;
      });

      $('#remove-all').click( function() {
        base_url = '/qlogger/remove';
    
        location.href = base_url;
      });
  });
</script>
</body>
</html>