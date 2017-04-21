import os

wamp_path = 'P:\\Program Files\\wamp\\www\\'
project_name = "skoj\\"
options = "/Q /Y";

os.system('xcopy www "{}{}" {}'.format(wamp_path, project_name, options));