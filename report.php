<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>hackingcamping platform</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@toast-ui/editor@latest/dist/toastui-editor.min.css">
  <link rel="stylesheet" href="style.css"/
  >
  <style>
  </style>
</head>
<body>
<?php
session_start();
$s_id   = $_SESSION["user_id"]   ?? "";
$s_name = $_SESSION["user_name"] ?? "";
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="index.php">hackingcamp Platform</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item"><a class="nav-link" href="./board/index.php">board</a></li>
      <?php if(!$s_id){ ?>
          <li class="nav-item"><a class="nav-link" href="./login/login.php">login</a></li>
      <?php } else { ?>
          <li class="nav-item"><a class="nav-link" href="./login/logout.php">logout</a></li>
      <?php } ?>
      <li class="nav-item"><span class="navbar-text text-light ml-2"><?= htmlspecialchars($s_name, ENT_QUOTES, 'UTF-8') ?></span></li>
    </ul>
  </div>
</nav>

<div class="container py-4">
  <h1 class="mb-3">Vulnerability Report</h1>

  <form method="post" action="./report_proc.php"
        onsubmit="document.getElementById('poc').value = editor.getMarkdown();">

    <div class="form-group">
      <label for="report_title">Title</label>
      <input id="report_title" name="report_title" type="text" class="form-control" placeholder="Enter Title" maxlength="200"required>
    </div>

    <div class="form-group">
      <label for="endpoint">Endpoint/Parameter</label>
      <input id="endpoint" name="endpoint" type="text" class="form-control"
             placeholder="/api/?parameter= (or endpoint)"
             pattern="^\/.*" maxlength="255" required>
      <small class="text-muted">please write it in absolute path format (starting with /).</small>
    </div>

    <div class="form-group">
      <label for="url">URL</label>
      <input id="url" name="url" type="url" class="form-control"
             placeholder="https://target.example.com/path" maxlength="1024" required>
    </div>

    <div class="form-group">
      <label for="vuln_type">Vulnerability</label>
      <select id="vuln_type" name="vuln_type" class="form-control" required>
        <option value="" disabled selected>choose</option>
        <option value="xss">XSS</option>
        <option value="sqlli">SQLinjection</option>
        <option value="openredirection">Open Redirection</option>
        <option value="IDOR">IDOR</option>
        <option value="RCE">RCE</option>
        <option value="LFI">LFI</option>
      </select>
    </div>

    <div class="form-group">
      <label>POC(payload Markdown)</label>
      <div id="content"></div> 
      <textarea id="poc" name="poc" hidden></textarea>
    </div>

    <div class="mt-3 d-flex justify-content-end gap-2">
      <button class="btn btn-primary" type="submit">Submit</button>
      <a class="btn btn-outline-secondary" href="index.php">Cancel</a>
    </div>
  </form>
</div>
<script src="https://uicdn.toast.com/editor/latest/toastui-editor-all.min.js"></script>
<script>
  const editor = new toastui.Editor({
      el: document.querySelector('#content'),
      height: '500px',
      initialEditType: 'markdown',
      previewStyle: 'vertical',
      placeholder: 'Enter POC here (Markdown)'
    });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
