#!/usr/bin/env bash
set -euo pipefail

# 1) Exporteer je 'loper'-CPT via WP-CLI in Docker (als www-data)
docker-compose exec -u www-data wordpress \
  wp export \
    --post_type=loper \
    --filename_format=hardlopers.xml \
    --allow-root

# 2) Stream het bestand uit de container naar je host
docker cp $(docker-compose ps -q wordpress):/var/www/html/hardlopers.xml ./hardlopers.xml

# 3) Scp het XML-bestand naar de VPS
scp -P 2200 -i ~/.ssh/vhm_deploy hardlopers.xml vsm@161.97.112.158:/home/vhm/public_html/

# 4) Op de VPS: import en opruimen
ssh -i ~/.ssh/vhm_deploy -p 2200 vsm@161.97.112.158 << 'EOF'
  cd /home/vhm/public_html
  wp plugin is-installed wordpress-importer --allow-root || wp plugin install wordpress-importer --activate --allow-root
  wp import hardlopers.xml --authors=create --allow-root
  rm hardlopers.xml
EOF

echo "✅ Loper-data van localhost is nu gesynct naar je VPS."
