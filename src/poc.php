<?php
// poc.php
session_start();
require "db.php";

$s_id   = $_SESSION["user_id"]   ?? "";
$s_name = $_SESSION["user_name"] ?? "";

$rid = (int)($_GET['id'] ?? 0);
if ($rid <= 0) { echo "Invalid report id"; exit; }

$sql = "SELECT report_id, user_name, report_title, endpoint, url, vuln_type, poc, report_date
        FROM report WHERE report_id = ?";
$stmt = $db_conn->prepare($sql);
$stmt->bind_param("i", $rid);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
if (!$row) { echo "Not found"; exit; }

$ymd  = date('Ymd', strtotime($row['report_date']));
$code = "HVE-20{$ymd}-".str_pad((string)$row['report_id'], 6, '0', STR_PAD_LEFT);


$parameter = '';
$parts = parse_url($row['url']);
if (!empty($parts['query'])) {
  parse_str($parts['query'], $query_params);
  $parameter = http_build_query($query_params);
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
  <title>hackingcamp Platform</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@toast-ui/editor@3.2.2/dist/toastui-editor.min.css">
  <style>
    :root{
      --wrap-max: 960px;
      --side-pad: clamp(12px, 3.2vw, 28px);
      --k-col: clamp(120px, 22vw, 180px);
    }
    html { font-size: clamp(14px, 1.2vw + 10px, 16px); }
    body { background:#f8f9fa; line-height:1.6; letter-spacing:.01em; }

    .report-wrap{
      width: min(100% - 2*var(--side-pad), var(--wrap-max));
      margin: 12px auto 24px;
      padding: var(--side-pad);
      background:#fff;
      border:1px solid #e5e5e5;
      border-radius:12px;
      box-shadow:0 2px 12px rgba(0,0,0,.04);
    }
    .top-code{
      font-weight:700;
      font-size:clamp(16px,1.6vw,18px);
      color:#333;
      margin-bottom:12px;
      word-break:break-all;
      overflow-wrap:anywhere;
    }

    .kv{
      display: grid;
      grid-template-columns: var(--k-col) 1fr;
      border-top: 1px solid #eee;
    }
    .kv:first-child{ border-top: none; }

    .kv .k,.kv .v{
      padding: 12px 16px;
    }
    .kv .k{
      background: #fafafa;
      color: #555;
      font-weight: 600;
      border-right: 1px solid #eee;
      white-space: nowrap;
    }

    .kv .v{
      color: #111;
      word-break: break-word;
      overflow-wrap: anywhere;
    }

    .kv .v a{
      word-break: break-all;
      overflow-wrap: anywhere;
    }

    h2{
      text-align: center;
      font-weight: 700;
      font-size: clamp(20px, 2.4vw, 28px);
      margin: 10px 0 18px;
    }
    .btns {
      width: min(100% - 2*var(--side-pad), var(--wrap-max));
      margin: 24px auto 0;
      padding: 0 var(--side-pad);
      box-sizing: border-box;
      display: flex;
      gap: 10px;
    }
    .section-title {
      font-size: clamp(18px, 2vw, 24px);
      font-weight: 600;
      margin-top: 40px;
      margin-bottom: 16px;
      padding-bottom: 8px;
      border-bottom: 2px solid #e5e5e5;
    }
    #poc-viewer {
      padding: 16px;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      background-color: #fdfdfd;
      min-height: 200px;
      width: 100%;
      box-sizing: border-box;
    }
    @media (max-width: 640px){
      .kv{ grid-template-columns: 1fr; }
      .kv .k{
        border-right: none;
        border-bottom: 1px solid #eee;
      }
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php">hackingcamp Platform</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="./board/index.php">board</a></li>
        <?php if(!$s_id){ ?>
            <li class="nav-item"><a class="nav-link" href="./login/login.php">login</a></li>
        <?php } else { ?>
            <li class="nav-item">
                <a class="nav-link" href="./login/logout.php">logout</a>
            </li>
        <?php } ?>
        <li class="nav-item"><a class="nav-link" href="./report.php">report</a></li>
        <li class="nav-item"><span class="navbar-text text-light ml-2"><?= htmlspecialchars($s_name) ?></span></li>
    </ul>
</div>
</nav>
<div class="btns">
  <button id="btnPdf" class="btn btn-primary">download</button>
  <a href="./index.php" class="btn btn-outline-secondary">Back</a>
</div>

<div id="report-print" class="report-wrap">
  <div class="top-code"><?= htmlspecialchars($code) ?></div>

  <h2 style="margin-top:4px;"><?= htmlspecialchars($row['report_title']) ?></h2>

  <div class="kv"><div class="k">Hacker of Honor</div><div class="v"><?= htmlspecialchars($row['user_name']) ?></div></div>
  <div class="kv"><div class="k">Report Date</div><div class="v"><?= htmlspecialchars($row['report_date']) ?></div></div>
  <div class="kv"><div class="k">Endpoint</div><div class="v"><?= htmlspecialchars($row['endpoint']) ?></div></div>
  <div class="kv"><div class="k">URL</div><div class="v"><a href="<?= htmlspecialchars($row['url']) ?>" target="_blank"><?= htmlspecialchars($row['url']) ?></a></div></div>
  <div class="kv"><div class="k">Vuln Type</div><div class="v"><?= htmlspecialchars(strtoupper($row['vuln_type'])) ?></div></div>

  <div class="section-title">PoC</div>
  <div id="poc-viewer"></div>
  <div id="poc-data" style="display:none;"><?= htmlspecialchars($row['poc']) ?></div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://uicdn.toast.com/editor/latest/toastui-editor-all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
  window.onload = function() {

    try {
      const mdPoc = document.getElementById('poc-data').textContent || '';

      new toastui.Editor.factory({
        el: document.querySelector('#poc-viewer'),
        viewer: true,
        initialValue: mdPoc
      });
    } catch (e) {
      console.error("Toast UI 뷰어 초기화에 실패했습니다:", e);
      document.querySelector('#poc-viewer').innerText = "PoC 내용을 불러오는 데 실패했습니다.";
    }

    document.getElementById('btnPdf').addEventListener('click', function(){
      const el = document.getElementById('report-print');
      const filename = <?= json_encode($code . '.pdf') ?>;
      const opt = {
        margin:       10,
        filename:     filename,
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true },
        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
      };

      html2pdf().set(opt).from(el).save().catch(err => {
        console.error("PDF 생성 중 오류가 발생했습니다:", err);
      });
    });
  };
</script>

</body>
</html>