<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 Forbidden!</title>
    <style type="text/css" media="all">
      * {
        padding: 0;
        margin: 0;
      }
      body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        justify-content: center;
        align-items: center;
      }
      h1 {
        font-size: 2rem;
        font-weight: bold;
      }
      p {
        font-size: 1.2rem;
      }
      a {
        text-decoration: none;
        color: #0e7eff; /* default theme */
      }
    </style>
  </head>
  <body>
    <h1>403</h1>
    <p>Your Are Forbidden!</p>
    <a id="back">back to home</a>
    <script type="text/javascript" charset="utf-8">
      document.querySelector("#back").setAttribute("href", window.location.origin);
    </script>
  </body>
</html>