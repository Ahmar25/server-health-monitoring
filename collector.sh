#!/bin/bash

OUTPUT="/data/metrics.json"

while true; do
  # Timestamp
  TIMESTAMP=$(date -u +"%Y-%m-%dT%H:%M:%SZ")

  # CPU usage (idle % subtracted from 100)
  CPU_IDLE=$(top -bn1 | grep "Cpu(s)" | awk '{print $8}' | tr -d '%')
  CPU_USED=$(echo "100 - $CPU_IDLE" | bc 2>/dev/null || echo "0")

  # Memory
  MEM_TOTAL=$(free -m | awk 'NR==2{print $2}')
  MEM_USED=$(free -m | awk 'NR==2{print $3}')
  MEM_FREE=$(free -m | awk 'NR==2{print $4}')
  MEM_PCT=$(echo "scale=1; $MEM_USED * 100 / $MEM_TOTAL" | bc 2>/dev/null || echo "0")

  # Disk usage on root partition
  DISK_TOTAL=$(df -h / | awk 'NR==2{print $2}')
  DISK_USED=$(df -h / | awk 'NR==2{print $3}')
  DISK_PCT=$(df / | awk 'NR==2{print $5}' | tr -d '%')

  # Load average
  LOAD=$(uptime | awk -F'load average:' '{print $2}' | xargs)

  # Network — bytes in/out on eth0
  NET_RX=$(cat /proc/net/dev | grep eth0 | awk '{print $2}' 2>/dev/null || echo "0")
  NET_TX=$(cat /proc/net/dev | grep eth0 | awk '{print $10}' 2>/dev/null || echo "0")

  # Ping latency to Google DNS
  PING=$(ping -c 1 -W 2 8.8.8.8 2>/dev/null | \
    grep 'time=' | awk -F'time=' '{print $2}' | awk '{print $1}' || echo "timeout")

  # Open TCP ports (listening)
  PORTS=$(ss -tlnp 2>/dev/null | awk 'NR>1{print $4}' | \
    awk -F: '{print $NF}' | sort -n | uniq | tr '\n' ',' | sed 's/,$//')

  # Running process count
  PROC_COUNT=$(ps aux | wc -l)

  # Write JSON
  cat > "$OUTPUT" << EOF
{
  "timestamp": "$TIMESTAMP",
  "cpu": {
    "used_pct": "$CPU_USED",
    "load_avg": "$LOAD"
  },
  "memory": {
    "total_mb": "$MEM_TOTAL",
    "used_mb": "$MEM_USED",
    "free_mb": "$MEM_FREE",
    "used_pct": "$MEM_PCT"
  },
  "disk": {
    "total": "$DISK_TOTAL",
    "used": "$DISK_USED",
    "used_pct": "$DISK_PCT"
  },
  "network": {
    "rx_bytes": "$NET_RX",
    "tx_bytes": "$NET_TX",
    "ping_ms": "$PING",
    "open_ports": "$PORTS"
  },
  "processes": {
    "count": "$PROC_COUNT"
  }
}
EOF

  sleep 10
done