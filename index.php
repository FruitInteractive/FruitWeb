<?php
$requestUri = $_SERVER['REQUEST_URI'];

if (strpos($requestUri, 'index.php') !== false) {
    $newUrl = str_replace('index.php', '', $requestUri);
    header("Location: $newUrl", true, 301);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fruit Interactive</title>
<link rel="icon" type="image/png" href="assets/images/Transparent.png">
<style>
  *{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, Helvetica, sans-serif;
  }

  body{
    background:#050505;
    color:white;
  }

  #loader{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:#050505;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    transition:opacity .8s ease;
    z-index:9999;
  }

  #loader.fade{
    opacity:0;
    pointer-events:none;
  }

  .spinner{
    width:90px;
    height:90px;
    border:6px solid transparent;
    border-top:6px solid #ffe354;
    border-left:6px solid #ffe354;
    border-right:6px solid #ffe354;
    border-radius:50%;
    animation:spin 1s linear infinite;
  }

  @keyframes spin{
    0%{transform:rotate(0deg);}
    100%{transform:rotate(360deg);}
  }

  #loadingText{
    margin-top:25px;
    font-size:18px;
    color:#bfbfbf;
  }

  #site{
    opacity:0;
    transition:opacity 1s ease;
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    text-align:center;
    padding:40px;
  }

  #site.show{
    opacity:1;
  }

  .remodel h1{
    font-size:52px;
    margin-bottom:20px;
  }

  .remodel p{
    color:#9e9e9e;
    font-size:18px;
    max-width:600px;
    margin:auto;
    line-height:1.6;
  }
</style>
</head>
<body>

<div id="loader">
  <div class="spinner"></div>
  <p id="loadingText">Refreshing.</p>
</div>

<div id="site">
  <section class="remodel">
    <h1>Website Under Work</h1>
    <p>
      Carl Cardnelle is currently upgrading the website.
      New features and improvements are coming soon.
    </p>
  </section>
</div>

<script>
  const text = document.getElementById("loadingText");
  let dots = 1;

  const dotInterval = setInterval(() => {
    dots++;
    if(dots > 3) dots = 1;
    text.textContent = "Refreshing" + ".".repeat(dots);
  }, 500);

  setTimeout(() => {
    clearInterval(dotInterval);
    const loader = document.getElementById("loader");
    const site = document.getElementById("site");
    
    loader.classList.add("fade");

    setTimeout(()=>{
      site.classList.add("show");
    },600);

  },1000);
</script>

</body>
</html>
