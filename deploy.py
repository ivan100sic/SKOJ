import os

wamp_path = 'D:\\wamp64\\www\\'
project_name = "skoj\\"
options = "/Q /Y";

os.system('xcopy www "{}{}" {}'.format(wamp_path, project_name, options));