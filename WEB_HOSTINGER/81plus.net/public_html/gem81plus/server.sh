#!/bin/bash
echo ""
echo " GEM81+ — Server locale"
echo " Apri il browser su: http://localhost:8080"
echo " Premi Ctrl+C per fermare il server"
echo ""
cd "$(dirname "$0")"
python3 -m http.server 8080
