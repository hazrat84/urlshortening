<!DOCTYPE html>
<html lang="en">
<head>
  <title>Url Shortener</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Welcome to ShortURL!</h2>
  <p>If you don't like long url so try using this service to convert your long url into short one.</p>

  @if($url_length == '')
    <div class="alert alert-danger" role="alert">
      A URL was not entered, please try again below:
    </div>
  @endif

  @if($url != '')
  <p>The Following Url: <br />
    <b>{{ $url }}</b><br />
    has a length of {{ $url_length }} characters and resulted in the following TinyURL which has a length of {{ $shorturl_length }} characters:
  </p>
  @elseif($urlempty != '')
  <p>Enter a valid url to shorten</p>
  @endif

  @if( $short_url != '')
  <p>The short url is the following (will open in new tab): <br />
      <a href="{{ url($short_url) }}">{{ $short_url }}</a>
  </p>
  @endif

  @if(isset($notfound))
  <div class="alert alert-danger" role="alert">
    <strong>Oh snap!</strong> This short url does not exist.
  </div>
  @endif

  <p>@if($InvalidUrl != '') {{ $InvalidUrl }}@endif</p>
  <form class="form-inline" method="POST" action="">
  {{ csrf_field() }}
    <div class="form-group">
      <label for="url">Enter a long URL to make short:</label>
      <input type="text" class="form-control input-md" id="url" name="url">
    </div>
    
    <button type="submit" name="submit" class="btn btn-default">Make ShortUrl!</button>
  </form>
</div>

</body>
</html>
