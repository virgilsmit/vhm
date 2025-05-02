#!/usr/bin/env bash
#
# deploy_to_vps.sh
# Automatische deploy van code + database-migrations naar je Contabo VPS.
#

set -euo pipefail

### CONFIGURATIE — pas aan naar jouw situatie ###
VPS_USER="vsm"
VPS_HOST="161.97.112.158"
SSH_KEY="$HOME/.ssh/vhm_deploy"
SSH_PORT=2200
REMOTE_WP="/home/vhm/public_html"
MIGRATIONS_DIR="plugins/hardloop-manager/migrations"

### 1) Rsync wp-content (code, themes, plugins, uploads, migrations) ###
echo ">> Rsync wp-content to VPS..."
rsync -avz \
  -e "ssh -i $SSH_KEY -p $SSH_PORT" \
  plugins/ \
  themes/ \
  uploads/ \
  $MIGRATIONS_DIR/ \
  "$VPS_USER@$VPS_HOST:$REMOTE_WP/wp-content/"

### 2) SSH in en draai WP-CLI commando’s + migrations ###
echo ">> Running WP-CLI tasks on VPS..."
ssh -i "$SSH_KEY" -p $SSH_PORT "$VPS_USER@$VPS_HOST" bash << EOF
set -euo pipefail

cd $REMOTE_WP

echo " - Activate plugin (if not active)…"
wp plugin activate hardloop-manager --allow-root || true

echo " - Flush cache & rewrite rules…"
wp cache flush --allow-root
wp rewrite flush --hard --allow-root

echo " - Applying SQL migrations…"
for f in plugins/hardloop-manager/migrations/*.sql; do
  if [ -f "\$f" ]; then
    echo "   > \$f"
    wp db query "\$(cat \"\$f\")" --allow-root
  fi
done

echo " - Applying PHP migrations…"
for f in plugins/hardloop-manager/migrations/*.php; do
  if [ -f "\$f" ]; then
    echo "   > \$f"
    wp eval-file "\$f" --allow-root
  fi
done

echo "✅ Deploy + migrations complete."
EOF
