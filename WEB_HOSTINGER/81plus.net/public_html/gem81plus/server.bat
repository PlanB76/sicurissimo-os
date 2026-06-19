@echo off
echo.
echo  GEM81+ — Server locale
echo  Apri il browser su: http://localhost:8080
echo  Premi Ctrl+C per fermare il server
echo.
cd /d "%~dp0"
python -m http.server 8080
pause
