<?php
$data = json_decode(file_get_contents('/data/metrics.json'), true);
if (!$data) { $data = ['timestamp' => 'Loading...', 'cpu' => ['used_pct' => 0],
  'memory' => ['used_pct' => 0, 'used_mb' => 0, 'total_mb' => 0],
  'disk' => ['used_pct' => 0, 'used' => '0', 'total' => '0'],
  'network' => ['ping_ms' => '-', 'open_ports' => '-', 'rx_bytes' => 0, 'tx_bytes' => 0],
  'processes' => ['count' => 0]]; }

function bar($pct, $warn=70, $danger=90) {
  $pct = (int)$pct;
  $color = $pct >= $danger ? '#E24B4A' : ($pct >= $warn ? '#EF9F27' : '#1D9E75');
  return "<div style='background:#e5e7eb;border-radius:4px;height:8px;width:100%'>
    <div style='background:$color;width:{$pct}%;height:8px;border-radius:4px;transition:width .5s'></div>
  </div><small style='color:#6b7280'>{$pct}%</small>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="refresh" content="10">
<title>Server Health Monitor</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    background: #f3f4f6; color: #111827; padding: 24px; }
  h1 { font-size: 20px; font-weight: 600; margin-bottom: 4px; }
  .ts { font-size: 12px; color: #6b7280; margin-bottom: 24px; }
  .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; }
  .card { background: #fff; border-radius: 10px; padding: 18px 20px;
    border: 1px solid #e5e7eb; }
  .card-title { font-size: 11px; font-weight: 600; color: #6b7280;
    text-transform: uppercase; letter-spacing: .05em; margin-bottom: 10px; }
  .big { font-size: 28px; font-weight: 700; margin-bottom: 6px; }
  .label { font-size: 13px; color: #374151; margin-bottom: 4px; }
  .tag { display: inline-block; background: #f3f4f6; border-radius: 4px;
    padding: 2px 8px; font-size: 11px; color: #374151; margin: 2px; }
  .green { color: #1D9E75; } .amber { color: #EF9F27; } .red { color: #E24B4A; }
  .footer { margin-top: 20px; font-size: 11px; color: #9ca3af; text-align: center; }
</style>
</head>
<body>
<h1>🖥 Server Health Monitor</h1>
<p class="ts">Last updated: <?= htmlspecialchars($data['timestamp']) ?> — auto-refreshes every 10s</p>

<div class="grid">

  <div class="card">
    <div class="card-title">CPU Usage</div>
    <?php $cpu = (int)$data['cpu']['used_pct'];
      $c = $cpu >= 90 ? 'red' : ($cpu >= 70 ? 'amber' : 'green'); ?>
    <div class="big <?= $c ?>"><?= $cpu ?>%</div>
    <?= bar($cpu) ?>
    <p class="label" style="margin-top:10px">Load avg: <?= htmlspecialchars($data['cpu']['load_avg']) ?></p>
  </div>

  <div class="card">
    <div class="card-title">Memory</div>
    <?php $mem = (int)$data['memory']['used_pct'];
      $c = $mem >= 90 ? 'red' : ($mem >= 70 ? 'amber' : 'green'); ?>
    <div class="big <?= $c ?>"><?= $mem ?>%</div>
    <?= bar($mem) ?>
    <p class="label" style="margin-top:10px">
      <?= $data['memory']['used_mb'] ?> MB used
      / <?= $data['memory']['total_mb'] ?> MB total</p>
  </div>

  <div class="card">
    <div class="card-title">Disk</div>
    <?php $disk = (int)$data['disk']['used_pct'];
      $c = $disk >= 90 ? 'red' : ($disk >= 70 ? 'amber' : 'green'); ?>
    <div class="big <?= $c ?>"><?= $disk ?>%</div>
    <?= bar($disk) ?>
    <p class="label" style="margin-top:10px">
      <?= $data['disk']['used'] ?> used / <?= $data['disk']['total'] ?> total</p>
  </div>

  <div class="card">
    <div class="card-title">Network</div>
    <div class="big green"><?= htmlspecialchars($data['network']['ping_ms']) ?> ms</div>
    <p class="label">Ping to 8.8.8.8</p>
    <p class="label" style="margin-top:8px">
      RX: <?= number_format((int)$data['network']['rx_bytes']/1024/1024, 1) ?> MB<br>
      TX: <?= number_format((int)$data['network']['tx_bytes']/1024/1024, 1) ?> MB</p>
  </div>

  <div class="card">
    <div class="card-title">Open Ports</div>
    <?php
    $ports = explode(',', $data['network']['open_ports'] ?? '');
    foreach ($ports as $p) {
      $p = trim($p);
      if ($p) echo "<span class='tag'>:$p</span>";
    } ?>
  </div>

  <div class="card">
    <div class="card-title">Processes</div>
    <div class="big"><?= (int)$data['processes']['count'] - 1 ?></div>
    <p class="label">running processes</p>
  </div>

</div>
<p class="footer">Server Health Monitor — Built with Bash + PHP + Docker</p>
</body>
</html>