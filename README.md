# SERVER HEALTH MONITOR
- A real-time server monitoring dashboard built with Bash, PHP, and Docker.
- Displays live CPU, memory, disk, network, and process metrics —
- Auto-refreshing every 10 seconds.

![Dashboard Screenshot](screenshot.png)

## WHAT IT DOES?
- Collects system metrics every 10 seconds via a Bash script
- Stores data as structured JSON in /data/metrics.json
- Renders a live web dashboard with color-coded health indicators
- Runs entirely in Docker — deploys on any Linux server in minutes

## TECH STACK
| Layer | Technology |
| Metrics collection | Bash scripting, Linux /proc filesystem |
| Data format | JSON |
| Dashboard | PHP 8.2 |
| Web server | Apache (via Docker) |
| Containerization | Docker + docker-compose |
| Networking monitor | ping, ss (socket statistics) |

## METRICS MONITORED
- CPU usage % and load average
- Memory: used, free, total (MB) and percentage
- Disk: used/total and percentage
- Network: RX/TX bytes, ping latency to 8.8.8.8
- Open listening TCP ports
- Running process count

## QUICK START
git clone https://github.com/Ahmar25/server-health-monitor
cd server-health-monitor
docker compose up --build

Open http://localhost:8080

## DEPLOY TO A CLOUD SERVER
ssh user@your-server-ip
git clone https://github.com/Ahmar25/server-health-monitor
cd server-health-monitor
docker compose up -d --build

Tested on: AWS EC2 (t2.micro free tier), Oracle Cloud Always Free

## PROJECT STRUCTURE
server-health-monitor/
├── collector.sh
├── dashboard.php
├── Dockerfile
├── docker-compose.yml
└── data/
    └── metrics.json

## SKILLS RECOMMENDED
- Linux system administration and Bash scripting
- Docker containerization and docker-compose orchestration
- PHP backend development
- Network monitoring (ping, port scanning with ss)
- Infrastructure observability concepts
- Cloud deployment readiness (AWS/GCP/Azure compatible)

-----------------------------------------------
Built by Ahmar Hayat | Cloud & DevOps Engineer
LinkedIn: https://linkedin.com/in/ahmarhayat
