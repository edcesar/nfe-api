@echo OFF
set PATH=%PATH%;%~pd0..\vendor\bin

cd ..
cmd /C phpunit --coverage-html %~pd0tmp/coverage
cd %~pd0
cmd /c start %~pd0tmp/coverage/index.html
